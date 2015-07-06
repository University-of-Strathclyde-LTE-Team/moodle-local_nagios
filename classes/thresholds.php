<?php

namespace local_nagios;

class thresholds {

    public $warning;
    public $critical;

    public function __construct(threshold $warning = null, threshold $critical = null) {
        $this->warning = $warning;
        $this->critical = $critical;
    }

    public function check($value) {
        if (!empty($this->critical) && $this->critical->check($value)) {
            return service::NAGIOS_STATUS_CRITICAL;
        }
        if (!empty($this->warning) && $this->warning->check($value)) {
            return service::NAGIOS_STATUS_WARNING;
        }
        return service::NAGIOS_STATUS_OK;
    }
}