<?php

class AuditTrail extends Eloquent {
    protected $table = 'audit_trail';
    
    public function user() {
        return $this->belongsTo('User', 'audit_by');
    }
}