<?php

if ($ADMIN->fulltree && $ADMIN->locate('localplugins')) {
    $ADMIN->add('localplugins', new admin_externalpage('local_nagios', get_string('pluginname', 'local_nagios'), new moodle_url('/local/nagios/admin.php')));
} else {
	if ($ADMIN->locate('localplugins') && !$ADMIN->locate('local_nagios')) {
		$ADMIN->add('localplugins', new admin_externalpage('local_nagios', get_string('pluginname', 'local_nagios'), new moodle_url('/local/nagios/admin.php')));
	}
}