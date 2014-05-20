<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/5/14
 * Time: 5:01 PM
 */
class Partnerinvoicerows extends Eloquent {

    protected $table = 'partnerinvoicerows';

    public $timestamps = true;

    public function partnerinvoice()
    {
        return $this->belongsTo('Partnerinvoice');
    }

}
