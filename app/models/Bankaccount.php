<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 3/27/14
 * Time: 12:58 PM
 */
class Bankaccount extends Eloquent {

    protected $table = 'bankaccount';

    protected $fillable = array('description', 'currency', 'accountnumber');

    public $timestamps = true;

    public function merchantagreement()
    {
        return $this->belongsTo('Merchantagreement');
    }

    public function merchant()
    {
        return $this->belongsTo('Merchant');
    }


}
