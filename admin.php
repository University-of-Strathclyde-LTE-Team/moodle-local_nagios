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
require_once($CFG->libdir.'/adminlib.php');

require_login(SITEID);
require_capability('moodle/site:config', context_system::instance());
admin_externalpage_setup('local_nagios');

$PAGE->set_context(context_system::instance());
$PAGE->set_url('/local/nagios/admin.php');
$PAGE->set_pagelayout('standard');
$PAGE->set_pagetype('admin');
$PAGE->set_heading("Nagios services");
$PAGE->navbar->add("Nagios services");

$action = optional_param('action', 'servicelist', PARAM_TEXT);
$out = $PAGE->get_renderer('local_nagios');

if ($action == 'servicelist') {
    $servicelist = \local_nagios\service::service_list();


    echo $OUTPUT->header();
    echo $OUTPUT->heading("Nagios services");
    echo $out->render_servicelist($servicelist);
    echo $OUTPUT->box(markdown_to_html(get_string('servicelist_help', 'local_nagios')));
    echo $OUTPUT->footer();
} else {
    die("Unknown action");
}
