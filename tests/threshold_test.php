<?php

use local_nagios\threshold;

class threshold_testcase extends advanced_testcase {

    public function testDefaults() {
        $threshold1 = new threshold();
        $this->assertEquals(null, $threshold1->start);
        $this->assertFalse($threshold1->startinfinity);
        $this->assertEquals(null, $threshold1->end);
        $this->assertFalse($threshold1->endinfinity);
        $this->assertEquals(threshold::OUTSIDE, $threshold1->alerton);

        $threshold2 = new threshold(1, 10);
        $this->assertEquals(1, $threshold2->start);
        $this->assertEquals(10, $threshold2->end);

        $threshold3 = new threshold(-INF, 10);
        $this->assertTrue($threshold3->startinfinity);
        $this->assertEquals(10, $threshold3->end);
    }

    public function testCheck() {
        $threshold1 = new threshold(0, 10);
        $this->assertTrue($threshold1->check(50));
        $this->assertFalse($threshold1->check(5));

        $threshold2 = new threshold(-INF, 500, threshold::INSIDE);
        $this->assertTrue($threshold2->check(0));
    }

    public function testParseRange() {
        $t1 = threshold::from_string('10');
        $this->assertEquals(10, $t1->end);
        $this->assertFalse($t1->startinfinity);

        $t2 = threshold::from_string('~:10');
        $this->assertEquals(10, $t2->end);
        $this->assertTrue($t2->startinfinity);

        $t3 = threshold::from_string('@~:10');
        $this->assertEquals(10, $t3->end);
        $this->assertTrue($t3->startinfinity);
        $this->assertEquals(threshold::INSIDE, $t3->alerton);

    }

}