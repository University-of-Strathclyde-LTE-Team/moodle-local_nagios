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

namespace local_nagios;

use Behat\Behat\Exception\Exception;
/**
 * Base class for built-in services.
 *
 * Also contains static function for discovering services.
 *
 * @author Michael Aherne
 *
 */
class service {

    const SERVICELIST_FUNCTION = 'nagios_services';
    const STATUS_FUNCTION = 'nagios_status';

    const NAGIOS_STATUS_OK = 0;
    const NAGIOS_STATUS_WARNING = 1;
    const NAGIOS_STATUS_CRITICAL = 2;
    const NAGIOS_STATUS_UNKNOWN = 3;

    public static $default_state_result = array(
        'key'  => '',
        'data' => array(
        'status' => \local_nagios\service::NAGIOS_STATUS_UNKNOWN,
        'type'    => 'state', // Can be a 'state' for OK, Warning, Critical, Unknown) or can be 'perf', which does
                              // Cause an alert, but can be processed later by custom programs
        'text'   => '',
        ),
    );

    public static function service_list() {
        global $CFG;

        require_once($CFG->dirroot.'/local/nagios/lib.php');
        $result = array('local_nagios' => local_nagios_nagios_services());

        $pluginswithservices = array('local_nagios');
        foreach (\core_component::get_plugin_types() as $plugintype => $fulldir) {
            foreach (get_plugin_list_with_function($plugintype, self::SERVICELIST_FUNCTION) as $plugin => $function) {
                if ($plugin == 'local_nagios') {
                    continue;
                }
                try {
                    $result[$plugin] = $function();
                } catch (Exception $e) {
                    debugging("Unable to get Nagios services from $plugin");
                }
            }
        }

        return $result;
    }

}