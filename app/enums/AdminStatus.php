<?php

namespace Enums;

class AdminStatus {
    const APPROVED = 1;
    const REJECTED = 2;

    public static function toArray() {
        return [
            AdminStatus::APPROVED => trans("app.forms.approved"),
            AdminStatus::REJECTED => trans("app.forms.rejected"),
        ];
    }
}
?>