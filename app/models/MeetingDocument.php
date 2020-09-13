<?php

class MeetingDocument extends Eloquent {
    protected $table = 'meeting_document';
    
    public function files() {
        return $this->belongsTo('Files', 'file_id');
    }
}