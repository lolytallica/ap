<?php

class Event extends Eloquent {

    protected $table = 'event';

    public $timestamps = true;

    public function voucher()
    {
        return $this->hasMany('voucher');
    }


}
