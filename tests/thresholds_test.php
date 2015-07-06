<?php

use local_nagios\threshold;
use local_nagios\thresholds;
use local_nagios\service;
class thresholds_testcase extends advanced_testcase {

    public function testCheck() {
        $t1 = new thresholds();
        $t1->warning = new threshold(0, 10);
        $t1->critical = new threshold(0, 20);
        $this->assertEquals(service::NAGIOS_STATUS_CRITICAL, $t1->check(30));
        $this->assertEquals(service::NAGIOS_STATUS_WARNING, $t1->check(15));
        $this->assertEquals(service::NAGIOS_STATUS_OK, $t1->check(5));
    }

}