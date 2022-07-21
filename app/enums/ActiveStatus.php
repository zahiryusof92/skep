<?php

namespace Enums;

class ActiveStatus {
    const INACTIVE = 0;
    const ACTIVE = 1;

    public static function toArray() {
        return [
            ActiveStatus::ACTIVE => trans("app.forms.active"),
            ActiveStatus::INACTIVE => trans("app.forms.inactive"),
        ];
    }
}
?>