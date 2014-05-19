<?php

class Resultfields extends Eloquent {

    protected $table = 'result_fields';

    protected $fillable = array('fieldname', 'fielddescription', 'fieldclass', 'user_id');

    public $timestamps = true;

    public function reportsearch()
    {
        return $this->belongsToMany('Reportsearch', 'report_search_fields');
    }

}
