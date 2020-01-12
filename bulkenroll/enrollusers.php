<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * @package    bulkenroll
 * @copyright  2015 Paul LaRiviere (plariv@augurynet.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

//defined('MOODLE_INTERNAL') || die();


/**
 * Groupenroller class
 *
 * This groupenroller class does the actual enrollment of an employee as part of the
 * bulkenroll process. It should only use the 'manual' enrollment instance. It extends the 
 * abstract class enrol_plugin in order to take advantage of the constructor and 
 * capability checking methods that are typical of all enrollment plugins.
 *
 *
 */
class groupenroll extends enrol_plugin {
    protected $lasternoller = null;
    protected $lasternollercourseid = 0;

    /**
     * Does this plugin assign protected roles are can they be manually removed?
     * @return bool - false means anybody may tweak roles, it does not use itemid and component when assigning roles
     */
    public function roles_protected() {
        return false;
    }

    /**
     * Does this plugin allow manual unenrolment of all users?
     * All plugins allowing this must implement 'enrol/xxx:unenrol' capability
     *
     * @param stdClass $instance course enrol instance
     * @return bool - true means user with 'enrol/xxx:unenrol' may unenrol others freely, false means nobody may touch user_enrolments
     */
    public function allow_unenrol(stdClass $instance) {
        return true;
    }

    /**
     * Does this plugin allow manual unenrolment of a specific user?
     * All plugins allowing this must implement 'enrol/xxx:unenrol' capability
     *
     * This is useful especially for synchronisation plugins that
     * do suspend instead of full unenrolment.
     *
     * @param stdClass $instance course enrol instance
     * @param stdClass $ue record from user_enrolments table, specifies user
     *
     * @return bool - true means user with 'enrol/xxx:unenrol' may unenrol this user, false means nobody may touch this user enrolment
     */
    public function allow_unenrol_user(stdClass $instance, stdClass $ue) {
        return true;
    }

    /**
     * Does this plugin allow manual changes in user_enrolments table?
     *
     * All plugins allowing this must implement 'enrol/xxx:manage' capability
     *
     * @param stdClass $instance course enrol instance
     * @return bool - true means it is possible to change enrol period and status in user_enrolments table
     */
    public function allow_manage(stdClass $instance) {
        return true;
    }

    /**
     * Is it possible to delete enrol instance via standard UI?
     *
     * @param object $instance
     * @return bool
     */
    public function instance_deleteable($instance) {
        return true;
    }

    /**
     * Gets an array of the user enrolment actions.
     *
     * @param course_enrolment_manager $manager
     * @param stdClass $ue A user enrolment object
     * @return array An array of user_enrolment_actions
     */
    public function get_user_enrolment_actions(course_enrolment_manager $manager, $ue) 
    {
        $actions = array();
        $context = $manager->get_context();
        $instance = $ue->enrolmentinstance;
        $params = $manager->get_moodlepage()->url->params();
        $params['ue'] = $ue->id;
        if ($this->allow_unenrol_user($instance, $ue) && has_capability("enrol/flatfile:unenrol", $context)) {
            $url = new moodle_url('/enrol/unenroluser.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/delete', ''), get_string('unenrol', 'enrol'), $url, array('class'=>'unenrollink', 'rel'=>$ue->id));
        }
        if ($this->allow_manage($instance) && has_capability("enrol/flatfile:manage", $context)) {
            $url = new moodle_url('/enrol/editenrolment.php', $params);
            $actions[] = new user_enrolment_action(new pix_icon('t/edit', ''), get_string('edit'), $url, array('class'=>'editenrollink', 'rel'=>$ue->id));
        }
        return $actions;
    }

    /**
     * Enrol user into course via the injected enrol instance.
     *
     * @param stdClass $instance  An enrollment instance
     * @param int $userid  The user to be enrolled
     * @param int $roleid optional role id
     * @param int $timestart 0 means unknown
     * @param int $timeend 0 means forever
     * @param int $status default to ENROL_USER_ACTIVE for new enrolments, no change by default in updates
     * @param bool $recovergrades restore grade history
     * @return void
     */
    public function enrol_user(stdClass $instance, $userid, $roleid = null, $timestart = 0, $timeend = 0, $status = null, $recovergrades = null) {
        parent::enrol_user($instance, $userid, null, $timestart, $timeend, $status, $recovergrades);
        if ($roleid) {
            $context = context_course::instance($instance->courseid, MUST_EXIST);
            role_assign($roleid, $userid, $context->id, 'enrol_'.$this->get_name(), $instance->id);
        }
    }


    /**
     * Process user enrolment line.
     *
     * @param string $action  Enrollment action: add or del(ete)
     * @param int $roleid  Typically as student
     * @param stdClass $user  User object from the database table
     * @param stdClass $course  Course object from the database table
     * @param int $timestart
     * @param int $timeend
     */
    protected function process_records( $action, $roleid, $user, $course, $timestart, $timeend ) {
        global $CFG, $DB, $SESSION;

        $context = context_course::instance($course->id);

        if ($action === 'add') 
        {
          $instance = $DB->get_record('enrol', array('courseid' => $course->id, 'enrol' => 'manual'));
          if (empty($instance)) {
            // Only add an enrol instance for the course if it is non-existent, but it should be there.
            $enrolid = $this->add_instance($course);
            $instance = $DB->get_record('enrol', array('id' => $enrolid));
          }

          $notify = false;
          if ($ue = $DB->get_record('user_enrolments', array('enrolid'=>$instance->id, 'userid'=>$user->id))) 
          {
            // Update only.
            $this->update_user_enrol($instance, 
                                     $user->id, 
                                     ENROL_USER_ACTIVE, 
                                     $roleid, 
                                     $timestart, 
                                     $timeend);
            if (!$DB->record_exists('role_assignments', array('contextid'=>$context->id, 
                                                              'roleid'=>$roleid, 
                                                              'userid'=>$user->id, 
                                                              'component'=>'enrol_flatfile', 
                                                              'itemid'=>$instance->id))) 
            {
              role_assign($roleid, $user->id, $context->id, 'enrol_flatfile', $instance->id);
            }
          } else 
            {
              // Enrol the user with this plugin instance.
              $this->enrol_user($instance, $user->id, $roleid, $timestart, $timeend);
              $notify = true;
            }

            return;

        } else if ($action === 'del') {
            // Clear the buffer just in case there were some future enrolments.
            $DB->delete_records('enrol_flatfile', array('userid'=>$user->id, 'courseid'=>$course->id, 'roleid'=>$roleid));

            $action = $this->get_config('unenrolaction');
            if ($action == ENROL_EXT_REMOVED_KEEP) {
                $trace->output("del action is ignored", 1);
                return;
            }

            // Loops through all enrolment methods, try to unenrol if roleid somehow matches.
            $instances = $DB->get_records('enrol', array('courseid' => $course->id));
            $unenrolled = false;
            foreach ($instances as $instance) {
                if (!$ue = $DB->get_record('user_enrolments', array('enrolid'=>$instance->id, 'userid'=>$user->id))) {
                    continue;
                }
                if ($instance->enrol === 'flatfile') {
                    $plugin = $this;
                } else {
                    if (!enrol_is_enabled($instance->enrol)) {
                        continue;
                    }
                    if (!$plugin = enrol_get_plugin($instance->enrol)) {
                        continue;
                    }
                    if (!$plugin->allow_unenrol_user($instance, $ue)) {
                        continue;
                    }
                }

                // For some reason the del action includes a role name, this complicates everything.
                $componentroles = array();
                $manualroles = array();
                $ras = $DB->get_records('role_assignments', array('userid'=>$user->id, 'contextid'=>$context->id));
                foreach ($ras as $ra) {
                    if ($ra->component === '') {
                        $manualroles[$ra->roleid] = $ra->roleid;
                    } else if ($ra->component === 'enrol_'.$instance->enrol and $ra->itemid == $instance->id) {
                        $componentroles[$ra->roleid] = $ra->roleid;
                    }
                }

                if ($componentroles and !isset($componentroles[$roleid])) {
                    // Do not unenrol using this method, user has some other protected role!
                    continue;

                } else if (empty($ras)) {
                    // If user does not have any roles then let's just suspend as many methods as possible.

                } else if (!$plugin->roles_protected()) {
                    if (!$componentroles and $manualroles and !isset($manualroles[$roleid])) {
                        // Most likely we want to keep users enrolled because they have some other course roles.
                        continue;
                    }
                }

                if ($action == ENROL_EXT_REMOVED_UNENROL) {
                    $unenrolled = true;
                    if (!$plugin->roles_protected()) {
                        role_unassign_all(array('contextid'=>$context->id, 'userid'=>$user->id, 'roleid'=>$roleid, 'component'=>'', 'itemid'=>0), true);
                    }
                    $plugin->unenrol_user($instance, $user->id);
                    $trace->output("User $user->id was unenrolled from course $course->id (enrol_$instance->enrol)", 1);

                } else if ($action == ENROL_EXT_REMOVED_SUSPENDNOROLES) {
                    if ($plugin->allow_manage($instance)) {
                        if ($ue->status == ENROL_USER_ACTIVE) {
                            $unenrolled = true;
                            $plugin->update_user_enrol($instance, $user->id, ENROL_USER_SUSPENDED);
                            if (!$plugin->roles_protected()) {
                                role_unassign_all(array('contextid'=>$context->id, 'userid'=>$user->id, 'component'=>'enrol_'.$instance->enrol, 'itemid'=>$instance->id), true);
                                role_unassign_all(array('contextid'=>$context->id, 'userid'=>$user->id, 'roleid'=>$roleid, 'component'=>'', 'itemid'=>0), true);
                            }
                            $trace->output("User $user->id enrolment was suspended in course $course->id (enrol_$instance->enrol)", 1);
                        }
                    }
                }
            }

            if (!$unenrolled) {
                if (0 == $DB->count_records('role_assignments', array('userid'=>$user->id, 'contextid'=>$context->id))) {
                    role_unassign_all(array('contextid'=>$context->id, 'userid'=>$user->id, 'component'=>'', 'itemid'=>0), true);
                }
                $trace->output("User $user->id (with role $roleid) not unenrolled from course $course->id", 1);
            }

            return;
        }
    }

    /**
     * Returns the user who is responsible for bulk enrollments in given course.
     *
     * Usually it is the first editing teacher - the person with "highest authority"
     * as defined by sort_by_roleassignment_authority() having 'enrol/flatfile:manage'
     * or 'moodle/role:assign' capability.
     *
     * @param int $courseid enrolment instance id
     * @return stdClass user record
     */
    protected function get_enroller($courseid) {
        if ($this->lasternollercourseid == $courseid and $this->lasternoller) {
            return $this->lasternoller;
        }

        $context = context_course::instance($courseid);

        $users = get_enrolled_users($context, 'enrol/flatfile:manage');
        if (!$users) {
            $users = get_enrolled_users($context, 'moodle/role:assign');
        }

        if ($users) {
            $users = sort_by_roleassignment_authority($users, $context);
            $this->lasternoller = reset($users);
            unset($users);
        } else {
            $this->lasternoller = get_admin();
        }

        $this->lasternollercourseid == $courseid;

        return $this->lasternoller;
    }

    /**
     * Returns a mapping of ims roles to role ids.
     *
     * @param progress_trace $trace
     * @return array imsrolename=>roleid
     */
    protected function get_role_map(progress_trace $trace) {
        global $DB;

        // Get all roles.
        $rolemap = array();
        $roles = $DB->get_records('role', null, '', 'id, name, shortname');
        foreach ($roles as $id=>$role) {
            $alias = $this->get_config('map_'.$id, $role->shortname, '');
            $alias = trim(core_text::strtolower($alias));
            if ($alias === '') {
                // Either not configured yet or somebody wants to skip these intentionally.
                continue;
            }
            if (isset($rolemap[$alias])) {
                $trace->output("Duplicate role alias $alias detected!");
            } else {
                $rolemap[$alias] = $id;
            }
        }

        return $rolemap;
    }

    /**
     * Restore instance and map settings.
     *
     * @param restore_enrolments_structure_step $step
     * @param stdClass $data
     * @param stdClass $course
     * @param int $oldid
     */
    public function restore_instance(restore_enrolments_structure_step $step, stdClass $data, $course, $oldid) {
        global $DB;

        if ($instance = $DB->get_record('enrol', array('courseid'=>$course->id, 'enrol'=>$this->get_name()))) {
            $instanceid = $instance->id;
        } else {
            $instanceid = $this->add_instance($course);
        }
        $step->set_mapping('enrol', $oldid, $instanceid);
    }

    /**
     * Restore user enrolment.
     *
     * @param restore_enrolments_structure_step $step
     * @param stdClass $data
     * @param stdClass $instance
     * @param int $oldinstancestatus
     * @param int $userid
     */
    public function restore_user_enrolment(restore_enrolments_structure_step $step, $data, $instance, $userid, $oldinstancestatus) {
        $this->enrol_user($instance, $userid, null, $data->timestart, $data->timeend, $data->status);
    }

    /**
     * Restore role assignment.
     *
     * @param stdClass $instance
     * @param int $roleid
     * @param int $userid
     * @param int $contextid
     */
    public function restore_role_assignment($instance, $roleid, $userid, $contextid) {
        role_assign($roleid, $userid, $contextid, 'enrol_'.$instance->enrol, $instance->id);
    }
}
