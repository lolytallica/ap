<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/5/14
 * Time: 4:51 PM
 */
class Partnerinvoice extends Eloquent {

    protected $table = 'partnerinvoice';

    public $timestamps = true;

    public function partnerinvoicerows()
    {
        return $this->hasMany('Partnerinvoicerows');
    }

}
