<?php

namespace local_nagios\nagios;

use local_nagios\invalid_service_exception;
use local_nagios\status_result;
use local_nagios\thresholds;
use local_nagios\service;

class adhoc_task_service extends service {

    public function check_status(thresholds $thresholds, $params = array()) {
        global $DB;
        $count = $DB->count_records('task_adhoc');
        $result = new status_result();
        $result->status = $thresholds->check($count);
        $result->text = "$count ad-hoc tasks in queue";

        return $result;
    }

}