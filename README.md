Repository: custom_mods
-----------------------
> Refer to the wiki attached to this GitHub repository for more extensive and
> detailed documentation regarding the West Bay LMS and the customized plugins
> and modules that are maintained here.

This repository holds the source code for the customized Moodle plugins or
modules that have been developed for West Bay Residential Services.

Here's a list of the customized components, and where they have been deployed:

 Component | Description | Deployed to host... 
 --- | --- | --- 
customsql | supports ad hoc SQL queries that produce admin reports out of the moodle database | fedora01
googleoauth2 | support for single sign on authentication to LMS and other systems | devwblms
bulkenroll | supports enrolling multiple students in a single admin operation | fedora01
certificate | plugin generating certificates of completion for courses | fedora01
getcertificate | event handler monitoring for completed quizzes | fedora01
recertpol | plugin supporting recertification policy administration | devwblms, dev2wblms
staff | plugin providing bulk enrollment capability for new LMS | devwblms, dev2wblms
dynamic | theme for updated LMS in Digital Ocean | devwblms, dev2wblms

Note: LMS hosts
-----------
There are three hosts referred to in the above table. They are:
* _fedora01_ - West Bay data center, intranet access only in the office
* _devwblms_ - a Digital Ocean droplet, used for development purposes only
* _dev2wblms_ - a Digital Ocean droplet, supporting the production instance of
  **Moodle v2.5** for continuing staff training


