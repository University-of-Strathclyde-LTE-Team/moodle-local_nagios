<?php

// services for self

$services = array(
    'scheduled_task' => array(
        'classname' => 'local_nagios\nagios\scheduled_task_service',
        'params' => array(
            'task' => false
        )
    ),
    'adhoc_task' => array(
        'classname' => 'local_nagios\nagios\adhoc_task_service'
    ),
    'event_queue' => array(
        'classname' => 'local_nagios\nagios\event_queue_service'
    )
);