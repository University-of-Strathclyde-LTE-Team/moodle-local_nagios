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

use \local_nagios\service;

class local_nagios_service_testcase extends advanced_testcase {

    public function testServiceList() {
        $list = service::service_list();
        $this->assertArrayHasKey('local_nagios', $list);
        $coreservices = $list['local_nagios'];
        $this->assertArrayHasKey('scheduled_task', $coreservices);
        $this->assertEquals('local_nagios\nagios\scheduled_task_service',
            $coreservices['scheduled_task']['classname']);
    }

    public function testGetServices() {
        $services = service::get_services('local_nagios');
        $this->assertNotNull($services);
        $this->assertArrayHasKey('scheduled_task', $services);

        $services = service::get_services('non_existant');
        $this->assertNull($services);

    }

    public function testGetService() {
        $service = service::get_service('local_nagios', 'scheduled_task');
        $this->assertInstanceOf('local_nagios\service', $service);

        $this->setExpectedException('local_nagios\invalid_service_exception');
        $service = service::get_service('local_nagios', 'non_existent');
    }

}
