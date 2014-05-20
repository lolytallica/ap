<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/24/14
 * Time: 12:09 PM
 */
class Partners extends Eloquent {

    protected $table = 'partners';

    public $timestamps = true;

    public function partneragreement()
    {
        return $this->belongsTo('Partneragreement');
    }



}
