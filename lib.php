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

function local_nagios_nagios_services() {
    return array(
        'cron' => array(
            'name' => 'Cron job',
            'description' => 'Checks that the cron job is running properly by checking the last time it was run.'
        )
    );
}

function local_nagios_nagios_status($service, $params = null) {
    global $DB;

    $warning = 10 * 60;
    $critical = 60 * 60;

    if (!empty($params)) {
        if (isset($params['warning'])) {
            $warning = $params['warning'];
        }
        if (isset($params['critical'])) {
            $critical = $params['critical'];
        }
    }

    $result = \local_nagios\service::$default_state_result;

    switch ($service) {
        case 'cron':
            $lastcron = $DB->get_field_sql('SELECT MAX(lastcron) FROM {modules}');
            if (!$lastcron) {
                $result['data']['text'] = "Cron has never run";
                return $result;
            }
            $timeelapsed = time() - $lastcron;
            if ($timeelapsed < $warning) {
                $result['data']['status'] = \local_nagios\service::NAGIOS_STATUS_OK;
            } else if ($timeelapsed < $critical) {
                $result['data']['status'] = \local_nagios\service::NAGIOS_STATUS_WARNING;
            } else {
                $result['data']['status'] = \local_nagios\service::NAGIOS_STATUS_CRITICAL;
            }
            $result['data']['text'] = "Cron last ran at " . date(DATE_RSS, $lastcron) . ", $timeelapsed seconds ago";
            return $result;
            break;
        default:
            debugging("Invalid core service");
    }

}