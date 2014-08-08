<?php

// This file is part of local_nagios
//
// local_nagios is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// local_nagios is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with local_nagios.  If not, see <http://www.gnu.org/licenses/>.
//
// Author: Michael Aherne
// Copyright 2014 University of Strathclyde

$string['pluginname'] = 'Nagios monitoring';

$string['servicelist_help'] = 'To monitor one of these services, create a new command in your Nagios configuration, e.g.

    define command {
        command_name    check_moodle_myplugin_my_service
        command_line    /usr/lib/nagios/plugins/check_moodle -p local_myplugin -s my_service -w 10000 -c 20000
    }

with the following parameters:

* -p: the frankenstyle name of the plugin, shown in the first column
* -s: the name of the service, shown in the second column
* -w: the warning threshold
* -c: the critical threshold

The last column describes the variable quantity that will be compared against the warning and critical
thresholds to determine the service status.

This command can then be used in Nagios service definitions.';