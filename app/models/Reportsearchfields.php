<?php


class Reportsearchfields extends Eloquent {

    protected $table = 'report_search_fields';

    protected $fillable = array('reportsearch_id', 'searchfields_id', 'reporttype_id');

    public $timestamps = true;



}
