<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 3/6/14
 * Time: 10:23 AM
 */
class Mainvoicestatus extends Eloquent {

    protected $table = 'mainvoicestatus';

    protected $fillable = array('mainvoice_id', 'invoicestatus_id');


    public $timestamps = true;

    public function mainvoice()
    {
        return $this->belongsTo('Mainvoice')->where('id','=',$this->mainvoice_id);
    }

    public function statusinvoices(){

        $statusinvoices = Mainvoice::where('id','=',$this->mainvoice_id)->get();

        return $statusinvoices;
    }

}
