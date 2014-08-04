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

require_once('../../config.php');

require_login(SITEID);
require_capability('moodle/site:config', context_system::instance());

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/nagios/admin.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_pagetype('admin');
$PAGE->set_heading("Nagios services");
$PAGE->navbar->add("Nagios services");

$action = optional_param('action', 'servicelist', PARAM_TEXT);

if ($action == 'servicelist') {
    $servicelist = \local_nagios\service::service_list();
    $table = new html_table();
    $table->head = array('Plugin', 'Service name', 'Description', '');
    foreach ($servicelist as $plugin => $pluginservices) {
        foreach ($pluginservices as $name => $pluginservice) {
            $row = new html_table_row(array($plugin, $name, $pluginservice['description']));
            $row->cells[] = new html_table_cell('view');
            $table->data[] = $row;
        }
    }

    echo $OUTPUT->header();
    echo $OUTPUT->heading("Nagios services");
    echo html_writer::table($table);
    echo $OUTPUT->footer();
} else {
    die("Unknown action");
}
