<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 3/7/14
 * Time: 11:33 AM
 */
class Invoicestatus extends Eloquent {

    protected $table = 'invoicestatus';

    public $timestamps = true;


    public function statusinvoices(){

        $statusinvoices = Mainvoice::with('mainvoicestatus')->where('mainvoicestatus.invoicestatus_id','=',$this->id)->get();

        return $statusinvoices;
    }


}
