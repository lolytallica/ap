<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 5/8/14
 * Time: 11:33 AM
 */


class Searchfields extends Eloquent {

    protected $table = 'search_fields';

    protected $fillable = array('fieldname', 'fieldtype', 'fieldclass', 'user_id');

    public $timestamps = true;

    public function reportsearch()
    {
        return $this->belongsToMany('Reportsearch', 'report_search_fields', 'reporttype_id');
    }

    public function scopeByname($query, $fieldname)
    {
        return $query->where('fieldname','=', $fieldname);
    }
}
