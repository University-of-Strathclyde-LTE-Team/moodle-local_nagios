<?php

use local_nagios\thresholds;
use local_nagios\threshold;
use local_nagios\service;

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
// require_once($CFG->libdir.'/pluginlib.php');
require_once($CFG->libdir.'/clilib.php');

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

$thresholds = new thresholds();

if ($options['warning']) {
    $thresholds->warning = threshold::from_string($options['warning']);
}
if ($options['critical']) {
    $thresholds->critical = threshold::from_string($options['critical']);
}

if (empty($thresholds->critical) && empty($thresholds->warning)) {
    echo "No valid thresholds given";
    exit(service::NAGIOS_STATUS_UNKNOWN);
}

try {
    $service = service::get_service($plugin, $service);

    if (empty($service)) {
        echo "Unable to get service $service from $plugin";
        exit(3);
    }

    $params = cli_get_params($service->get_param_defs());

    $status = $service->check_status($thresholds, $params[0]);

    if (is_null($status)) {
        throw new Exception("Service check returned no status");
    }

    echo $status->text;
    exit($status->status);
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
    exit(service::NAGIOS_STATUS_UNKNOWN);
}

function print_help() {
    echo "Runs a nagios status.";
    exit(3);
}