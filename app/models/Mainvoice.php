<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/5/14
 * Time: 4:50 PM
 */
class Mainvoice extends Eloquent {

    protected $table = 'mainvoice';

    protected $fillable = array('merchantagreement_id', 'amount', 'date_from','date_to', 'description', 'user_id', 'transactions_number', 'transactions_amount', 'redemptions_number', 'redemptions_amount', 'refunds_number', 'refunds_amount', 'chargebacks_number', 'chargebacks_amount', 'conversion_rate', 'payout_date', 'transactionid_from', 'transactionid_to', 'balance_in', 'balance_out', 'prepayment_amount', 'processcurrency');

    public $timestamps = true;

    /*
     * Invoice details*/

    public function mainvoicerows()
    {
        return $this->hasMany('Mainvoicerows', 'mainvoice_id');
    }

    /////////////////////////////////////////////////////////////////////////////////////
    public function mainvoicestatus()
    {
        return $this->hasOne('Mainvoicestatus', 'mainvoice_id');//->where('mainvoice_id','=', $this->id);
    }
    /////////////////////////////////////////////////////////////////////////////////////


    public function ScopeBystatus($query, $statusID)
    {
        $invoicesbystatus = array();

        $invoices = Mainvoicestatus::where('invoicestatus_id','=',$statusID)->select('mainvoice_id')->get();

        foreach($invoices as $inv)
        {
            $invoicesbystatus[$inv->mainvoice_id] = $inv->mainvoice_id;
        }

        return $query->whereIn('id',$invoicesbystatus);
    }

    public function ScopeNotstatus($query, $statusID)
    {
        $invoicesbystatus = array();

        $invoices = Mainvoicestatus::where('invoicestatus_id','<>',$statusID)->select('mainvoice_id')->get();

        foreach($invoices as $inv)
        {
            $invoicesbystatus[$inv->mainvoice_id] = $inv->mainvoice_id;
        }

        return $query->whereIn('id',$invoicesbystatus);
    }

    public function ScopeBymerchantagreement($query, $merchantagreementID)
    {
        return $query->where('merchantagreement_id',$merchantagreementID);
    }

    public function ScopeBymerchant($query, $merchantID)
    {

        $ma = Merchantagreement::where('merchant_id','=',$merchantID)->get();

            foreach($ma as $ma_ids)
            {
                $agreements[$ma_ids->id] = $ma_ids->id;
            }

        return $query->whereIn('merchantagreement_id',$agreements);
    }

    public function rowval($row)
    {
        return DB::table('mainvoicerows')
            ->where('mainvoice_id','=',$this->id)
            ->where('description','=',$row)
            ->first();
    }


    /*
     * mecrhants / merchantagreements*/

    public function merchant()
    {

        $maId = $this->merchantagreement()->merchant_id;

        return Merchant::where('id','=',$maId)->first();
    }

    public function merchantagreements()
    {
        return $this->belongsTo('Merchantagreement')->where('merchantagreement.id', '=', $this->merchantagreement_id);
    }

    public function merchants()
    {
        return $this->belongsTo('Merchant');
    }

    public function invoicestatus($invoiceID=null)
    {
        if(!is_null($invoiceID))
        {
        $status = DB::table('invoicestatus')->join('mainvoicestatus','mainvoicestatus.invoicestatus_id','=','invoicestatus.id')->where('mainvoicestatus.mainvoice_id','=',$invoiceID)->orderBy('mainvoicestatus.created_at','desc')->first();
        }
        else
        {
        $status = DB::table('invoicestatus')->join('mainvoicestatus','mainvoicestatus.invoicestatus_id','=','invoicestatus.id')->where('mainvoicestatus.mainvoice_id','=',$this->id)->orderBy('mainvoicestatus.created_at','desc')->first();
        }
        return $status;
    }


    public function merchantagreement()
    {
        return Merchantagreement::where('id','=',$this->merchantagreement_id)->first();

    }


    /*
     * payments
     * */

    public function payments()
    {
        return Mapayment::where('mainvoice_id','=',$this->id)->get();

    }

    public function paid()
    {
        return Mapayment::where('mainvoice_id','=',$this->id)->sum('amount_processed');

    }

    public function mapayment()
    {
        return $this->hasmany('Mapayment');
    }

    /*
     * Scopes*/

    public function scopeMerchantagreement($query,$merchantagreementId) {
        $query->where('merchantagreement_id', '=', $merchantagreementId);
    }

}
