<?php

class VendorProject extends Eloquent {

    protected $table = 'vendor_projects';

    const PENDING = 0;
    const INPROGRESS = 1;
    const COMPLETE = 2;

    public function vendor() {
        return $this->belongsTo('Vendors', 'vendor_id');
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id');
    }

    public function category() {
        return $this->belongsTo('ProjectCategory', 'project_category_id');
    }

    public function status() {
        $label = '-';

        if ($this->status == self::PENDING) {
            $label = '<span class="label label-danger">' . trans('app.directory.vendors.project.pending') . '</span>';
        } else if ($this->status == self::INPROGRESS) {
            $label = '<span class="label label-warning">' . trans('app.directory.vendors.project.inprogress') . '</span>';
        } else if ($this->status == self::COMPLETE) {
            $label = '<span class="label label-success">' . trans('app.directory.vendors.project.complete') . '</span>';
        }

        return $label;
    }

}
