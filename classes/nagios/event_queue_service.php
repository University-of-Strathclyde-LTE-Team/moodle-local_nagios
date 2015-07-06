<?php

namespace local_nagios\nagios;

use local_nagios\service;
use local_nagios\status_result;
use local_nagios\thresholds;

/**
 * Check the number of event handlers in the old events API event queue.
 *
 * Buggy event handler code can cause the event queue to become blocked, so
 * this service is intended to check that the size of the queue stays within
 * reasonable limits.
 *
 * @author Michael Aherne
 *
 */
class event_queue_service extends service {

    public function check_status(thresholds $thresholds, $params = array()) {
        global $DB;
        $count = $DB->count_records('events_queue_handlers');
        $result = new status_result();
        $result->status = $thresholds->check($count);
        $result->text = "$count events in queue";

        return $result;
    }

}