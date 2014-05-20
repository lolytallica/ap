<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/24/14
 * Time: 12:08 PM
 */
class Partneragreement extends Eloquent {

    protected $table = 'partneragreement';

    public $timestamps = true;

    public function partners()
    {
        return $this->hasMany('Partners');
    }



}
