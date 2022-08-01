<?php

namespace Enums;

class ManagementStatus {
    const JMB = 'jmb';
    const MC = 'mc';
    const AGENT = 'agent';
    const OTHERS = 'others';

    public static function toArray() {
        return [
            ManagementStatus::JMB => trans("app.forms.jmb"),
            ManagementStatus::MC => trans("app.forms.mc"),
            ManagementStatus::AGENT => trans("app.forms.agent"),
            ManagementStatus::OTHERS => trans("app.forms.others"),
        ];
    }
}
?>