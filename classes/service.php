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

/**
 * Base class for built-in services.
 *
 * Also contains static function for discovering services.
 *
 * @author Michael Aherne
 *
 */
abstract class service {

    const SERVICELIST_FUNCTION = 'nagios_services';
    const STATUS_FUNCTION = 'nagios_status';

    const NAGIOS_STATUS_OK = 0;
    const NAGIOS_STATUS_WARNING = 1;
    const NAGIOS_STATUS_CRITICAL = 2;
    const NAGIOS_STATUS_UNKNOWN = 3;

    public $definition; // the definition from the db/local_nagios.php file

    /**
     * Check the status of the service.
     *
     * @param thresholds $thresholds the thresholds for the check
     * @param array $params an array of parameters
     * @return status_result the results of the check
     */
    abstract public function check_status(thresholds $thresholds, $params = array());

    public function get_param_defs() {
        if (!isset($this->definition) || !isset($this->definition['params'])) {
            return array();
        } else {
            return $this->definition['params'];
        }
    }

    /**
     * Get a list of services available in this Moodle installation.
     *
     * @return array plugin name => array('name' => '\class\implementing\service')
     */
    public static function service_list() {
        global $CFG;

        $result = array();

        foreach (\core_component::get_plugin_types() as $plugintype => $fulldir) {
            foreach (\core_component::get_plugin_list($plugintype) as $name => $dir) {
                $frankenstyle = $plugintype. '_' . $name;
                if ($services = self::get_services($frankenstyle)) {
                    $result[$frankenstyle] = $services;
                }
            }
        }

        return $result;
    }

    public static function get_services($plugin) {
        $services = array();

        $pluginmanager = \core_plugin_manager::instance();
        $plugininfo = $pluginmanager->get_plugin_info($plugin);

        if (is_null($plugininfo)) {
            return;
        }

        $defsfile = $plugininfo->rootdir . '/db/local_nagios.php';

        if (!file_exists($defsfile)) {
            return;
        }

        include($defsfile);

        return $services;
    }

    public static function get_service($plugin, $service) {
        $services = self::get_services($plugin);

        if (is_null($services) || empty($services) || !isset($services[$service])) {
            throw new invalid_service_exception("Service $service not found in $plugin");
        }

        if (!isset($services[$service]['classname'])) {
            throw new invalid_service_exception("classname parameter not found");
        }

        if (!class_exists($services[$service]['classname'])) {
            throw new invalid_service_exception("Unable to find service class {$services[$service]['classname']}");
        }

        $result = new $services[$service]['classname']();
        $result->definition = $services[$service];

        return $result;
    }

}