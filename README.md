custom\_mods
===========

Customizations of Moodle modules or plugins

Implementation Note:
In the WBLMS deployment of this custom\_mods repo, be sure to install a symlink as follows:

ln -s /var/www/moodle/config.php ./config.php

Otherwise, Moodle won't be able to locate the config.php file in any of the certificate functions.
