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

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2014072301;               // If version == 0 then module will not be installed
$plugin->requires  = 2010031900;      // Requires this Moodle version
$plugin->maturity  = MATURITY_ALPHA;
$plugin->cron      = 0;               // Period for cron to check this module (secs)
$plugin->component = 'local_nagios'; // To check on upgrade, that module sits in correct place