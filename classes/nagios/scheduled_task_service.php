<?php

namespace local_nagios\nagios;

use local_nagios\invalid_service_exception;
use local_nagios\status_result;
use local_nagios\thresholds;
use local_nagios\service;

class scheduled_task_service extends \local_nagios\service {

    public function check_status(thresholds $thresholds, $params = array()) {
        if (!isset($params['task'])) {
            throw new invalid_service_exception("Task parameter required");
        }

        if (! $task = \core\task\manager::get_scheduled_task($params['task'])) {
            throw new invalid_service_exception("Task not found");
        }

        $result = new status_result();

        $lastrun = $task->get_last_run_time();

        if (!$lastrun) {
            $result->text = 'Task has never run';
            $result->status = service::NAGIOS_STATUS_UNKNOWN;
        } else {
            $timeelapsed = time() - $lastrun;
            $result->status = $thresholds->check($timeelapsed);
            $result->text = "Last ran at " . date(DATE_RSS, $lastrun) . ", $timeelapsed seconds ago";
        }

        return $result;
    }

}