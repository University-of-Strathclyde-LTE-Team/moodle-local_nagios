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

define('CLI_SCRIPT', 1);
require_once(__DIR__.'/../../../config.php');
require_once($CFG->libdir.'/pluginlib.php');
require_once($CFG->libdir.'/clilib.php');

// TODO: Add some security!!!!

// Get cli options.
list($options, $unrecognized) = cli_get_params(
        array(
                'plugin'           => false,
                'service'          => false,
                'warning'          => false,
                'critical'         => false,
                'help'             => false
        ),
        array(
                'h' => 'help',
                'p' => 'plugin',
                's' => 'service',
                'w' => 'warning',
                'c' => 'critical'
        )
);

if ($options['help']) {
    print_help();
}

if (empty($options['plugin']) || empty($options['service'])) {
    print_help();
}

$plugin = $options['plugin'];
$service = $options['service'];
$warning = $options['warning'];
$critical = $options['critical'];

$params = array();
if ($options['warning']) {
    $params['warning'] = $options['warning'];
}
if ($options['critical']) {
    $params['critical'] = $options['critical'];
}

$pluginmanager = plugin_manager::instance();
$plugininfo = $pluginmanager->get_plugin_info($plugin);

require_once($plugininfo->rootdir . '/lib.php');

$checkfunction = $plugin . '_nagios_status';

if (!function_exists($checkfunction)) {
    echo "Invalid plugin";
    exit(3);
}

$status = $checkfunction($service, $params);

echo $status['data']['text'];

exit($status['data']['status']);

// TODO: Print the performance info if available

function print_help() {
    echo "Runs a nagios status.";
    exit(3);
}