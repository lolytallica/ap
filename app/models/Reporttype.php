<?php


class Reporttype extends Eloquent {

    protected $table = 'search_types';

    protected $fillable = array('searchtype', 'description');

    public $timestamps = true;

    public function searchfields()
    {
        return $this->belongsToMany('Searchfields', 'report_search_fields');
    }

    public function resultfields()
    {
        return $this->belongsToMany('Resultfields', 'report_result_fields');
    }

    public function reportsearch()
    {
        return $this->belongsToMany('Reportsearch', 'report_search_fields');
    }



}
