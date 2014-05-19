<?php

class Voucherevent extends Eloquent {

    protected $table = 'voucher_event';

    public $timestamps = true;

    public function event()
    {
        return $this->hasMany('Event');
    }

    public function voucher()
    {
        return $this->hasMany('Voucher');
    }

    public function transaction()
    {
        return $this->hasMany('Transaction');
    }


}
