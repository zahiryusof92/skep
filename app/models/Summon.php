<?php

class Summon extends Eloquent {

    protected $table = 'summon';

    const DRAFT = 0;
    const PENDING = 1;
    const APPROVED = 2;
    const REJECTED = 3;
    const CANCELED = 99;
    const LETTER_OF_REMINDER = 1;
    const LETTER_OF_DEMAND = 2;
    const ONE_MONTH = 1;
    const TWO_MONTH = 2;
    const THREE_MONTH = 3;
    const FOUR_MONTH = 4;
    const FIVE_MONTH = 5;
    const SIX_MONTH = 6;
    const MORE_THAN_SIX_MONTH = 7;
    const SUMMON = 'summon';

    public static function durationOverdue() {
        $result = '';

        $result[self::TWO_MONTH] = trans('app.summon.two_month');
        $result[self::THREE_MONTH] = trans('app.summon.three_month');
        $result[self::FOUR_MONTH] = trans('app.summon.four_month');
        $result[self::FIVE_MONTH] = trans('app.summon.five_month');
        $result[self::SIX_MONTH] = trans('app.summon.six_month');
        $result[self::MORE_THAN_SIX_MONTH] = trans('app.summon.more_than_six_month');

        return $result;
    }

    public static function durationOverdueLOD() {
        $result = '';

        $result[self::ONE_MONTH] = trans('app.summon.one_month');
        $result[self::MORE_THAN_SIX_MONTH] = trans('app.summon.more_than_six_month');

        return $result;
    }

    public function durationTitle() {
        $title = '';

        if ($this->duration_overdue) {
            if ($this->type == Summon::LETTER_OF_DEMAND) {
                $duration = self::durationOverdueLOD();
            } else {
                $duration = self::durationOverdue();
            }
            $title = $duration[$this->duration_overdue];
        }

        return $title;
    }

    public function status() {
        $status = '<span class="label label-pill label-secondary" style="font-size:12px;">' . trans('app.summon.draft') . '</span>';

        if ($this->status == self::PENDING) {
            $status = '<span class="label label-pill label-warning" style="font-size:12px;">' . trans('app.summon.pending') . '</span>';
        } else if ($this->status == self::APPROVED) {
            $status = '<span class="label label-pill label-success" style="font-size:12px;">' . trans('app.summon.approved') . '</span>';
        } else if ($this->status == self::REJECTED) {
            $status = '<span class="label label-pill label-danger" style="font-size:12px;">' . trans('app.summon.rejected') . '</span>';
        } else if ($this->status == self::CANCELED) {
            $status = '<span class="label label-pill label-default" style="font-size:12px;">' . trans('app.summon.canceled') . '</span>';
        }

        return $status;
    }

    public function category() {
        return $this->belongsTo('Category', 'category_id');
    }

    public function lawyer() {
        return $this->belongsTo('User', 'lawyer_id');
    }
    
    public function user() {
        return $this->belongsTo('User', 'action_by');
    }

}
