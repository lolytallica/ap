<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/5/14
 * Time: 4:50 PM
 */
class Mapayment extends Eloquent {

    protected $table = 'mapayment';

    protected $fillable = array('mainvoice_id', 'total_processed', 'amount_processed', 'amount_payout', 'user_id','painvoice_id', 'held', 'conversionrate', 'comments' );

    public $timestamps = true;

    public function mapaymentrows()
    {
       return $this->hasMany('Mapaymentrows', 'mapayment_id');
    }

    public function mainvoice()
    {
        return $this->belongsTo('mainvoice', 'mainvoice_id');
    }

    ////////////////////////SCOPES
    public function ScopeBymerchant($query, $merchantID)
    {

        $invoiceids = array();

        $invoices = Mainvoice::join('merchantagreement','mainvoice.merchantagreement_id','=','merchantagreement.id')->where('merchantagreement.merchant_id','=',$merchantID)->get();

        foreach($invoices as $mainvoices)
        {
            $invoiceids[$mainvoices->id] = $mainvoices->id;
        }

        return $query->whereIn('mainvoice_id',$invoiceids);
    }


    public function ScopeBymerchantagreement($query, $merchantagreementID)
    {
        $invoiceids = array();

        $invoices = Mainvoice::where('merchantagreement_id','=',$merchantagreementID)->get();

        foreach($invoices as $mainvoices)
        {
            $invoiceids[$mainvoices->id] = $mainvoices->id;
        }

        return $query->whereIn('mainvoice_id',$invoiceids);
    }


    ///////////


////////////////////////////////////////////////////
    public function paymentinvoice()
    {
        return Mainvoice::where('id','=',$this->mainvoice_id)->first();
    }

    public function payer()
    {
        $user = Sentry::getUserProvider()->findById($this->user_id);

        return $user;
    }

}
