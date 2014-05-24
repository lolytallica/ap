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


<<<<<<< HEAD
=======




    /*@todo: remove from here */
    public function ve()
    {
    $ve_sql = 'SELECT voucher_event.id AS voucherevent_id, voucher_event.merchant_id, merchant.merchant, voucher_event.firstname, voucher_event.lastname, voucher_event.email, voucher_event.merchantusername, voucher_event.merchantprofile, voucher_event.event_id, event.event, voucher_event.merchantredemptionid AS DF_traceid, voucher.transactionid, voucher.orderid, voucher_event.voucher_id AS voucherid, voucher.amount, voucher.currency, voucher_event.datetimecreated, voucher.datetimecreated AS purchased, (extract(epoch from voucher_event.datetimecreated )- extract(epoch from voucher.datetimecreated )) AS timetaken
                FROM "voucher_event"  INNER JOIN "event" ON voucher_event.event_id=event.id INNER JOIN merchant ON voucher_event.merchant_id=merchant.id INNER JOIN voucher ON voucher_event.voucher_id=voucher.id
                WHERE voucher_event.event_id > 302 and voucher_event.datetimecreated >= current_date';

            $ve_query = DB::select(DB::raw($ve_sql));

        return $ve_query;

    }


>>>>>>> origin/develop
}
