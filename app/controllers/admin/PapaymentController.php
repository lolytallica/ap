<?php namespace Controllers\Admin;

use AdminController;
use Input;
use Lang;
use Mainvoice;
use Mapayment;
use Mainvoicestatus;
use Mainvoicerows;
use Merchantagreement;
use Merchant;
use Transaction;
use Transactionorder;
use Voucherevent;
use Map;
use Redirect;
use Sentry;
use Str;
use Validator;
use View;
use DB;
use DOMPDF;
use PDF;
use Cache;


class PapaymentController extends AdminController {
    /**
     * The invoice id
     * @var int
     */
    protected $id;

    public $agreements = array();

    public function __construct()
    {
        $this->beforeFilter('hasAccess:view_ma_payments');


        if(Sentry::check())
        {
            $user = Sentry::getUser();

            if( ($user->merchant_id >0) || ($user->merchantagreement_id >0) )
            {
                if($user->merchant_id >0)
                {
                    $ma = Merchantagreement::where('merchant_id','=',$user->merchant_id)->get();

                }
                else
                    if($user->merchantagreement_id >0)
                    {
                        $ma = Merchantagreement::where('id','=',$user->merchantagreement_id)->get();
                    }


                foreach($ma as $ma_ids)
                {
                    $this->agreements[$ma_ids->id] = $ma_ids->id;

                }
            }
        }
    }


    /**
     * Show a list of all the invoices.
     *
     * @return View
     */
    public function getIndex()
    {

        if(count($this->agreements))
        {
            $payments = Mapayment::join('mainvoice','mainvoice.id','=','mapayment.mainvoice_id')
                ->whereIn('mainvoice.merchantagreement_id', $this->agreements)
                ->orderBy('mainvoice_id', 'DESC')->get();

        }
        else
        {
            $payments = Mapayment::orderBy('mainvoice_id', 'DESC')->get();
        }





        // Show the page
        return View::make('backend/mapayment/index', compact('payments'));
    }

    public function getConversionrate($invoiceId)
    {
        /*
         * Conversion rate is stored each day from partnerinvoice
         * @param: bankaccount_id, date, currency_from, currency_to
         * */
        $invoice = Mainvoice::find($invoiceId);


        $conversionrate = DB::table('conversionrate')
            ->where('created_at','>=', $invoice->date_from)
            ->where('created_at','<=', $invoice->date_to)
            ->where('bankaccount_id','=', $invoice->merchantagreement()->bankaccount_id)
            ->where('currency_from','=', $invoice->merchantagreement()->paramval('processcurrency')->map_value)
            ->where('currency_to','=', $invoice->merchantagreement()->paramval('payoutcurrency')->map_value)
            ->get();


        return $conversionrate;

    }

    /**
     * invoice Payment amounts pending
     * @param  int  $invoiceId
     * @return View
     */
    public function getPaymentamount($invoiceId = null)
    {
        // Check if the invoice exists
        if (is_null($invoice = Mainvoice::find($invoiceId)))
        {
            // Redirect to the invoices page
            return Redirect::to('admin/mapayment')->with('error', Lang::get('admin/invoices/message.does_not_exist'));
        }

        $invoice = Mainvoice::find($invoiceId);

        $amount_paid = Mapayment::where('mainvoice_id','=',$invoiceId)->sum('amount_payout');

        ///Check amount pending

        return View::make('backend/mapayment/pay', compact('invoice','conversionrate','amount_paid'));
    }

    /**
     * invoice Payment.
     *
     * @param  int  $invoiceId
     * @return View
     */
    public function getPay($invoiceId = null)
    {
        // Check if the invoice exists
        if (is_null($invoice = Mainvoice::find($invoiceId)))
        {
            // Redirect to the invoices page
            return Redirect::to('admin/mapayment')->with('error', Lang::get('admin/invoices/message.does_not_exist'));
        }

        $invoice = Mainvoice::find($invoiceId);

        $conversionrate = $this->getConversionrate($invoiceId);

        $amount_paid = Mapayment::where('mainvoice_id','=',$invoiceId)->sum('amount_payout');
        $amount_processed_paid = Mapayment::where('mainvoice_id','=',$invoiceId)->sum('amount_processed');

        ///Check amount pending

        return View::make('backend/mapayment/pay', compact('invoice','conversionrate','amount_paid','amount_processed_paid'));
    }


    public function postPay($invoiceId = null)
    {
        // Check if the invoice exists
        if (is_null($invoice = Mainvoice::find($invoiceId)))
        {
            // Redirect to the blogs management page
            return Redirect::to('admin/mainvoice')->with('error', Lang::get('admin/invoices/message.does_not_exist'));
        }

        // Declare the rules for the form validation
        $rules = array(
            'amount_processed' => 'required|numeric|min:'.$invoice->merchantagreement()->paramval('min_payout')->map_value.'|max:'.$invoice->rowval('payable')->amount,
            'conversionrate'   => 'required',

        );

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $payment = New Mapayment();

        $payment->total_processed  = e(Input::get('payable_hid'));
        $payment->amount_processed = e(Input::get('amount_processed'));
        $payment->amount_payout    = e(Input::get('payout_hid'));
        $payment->held             = $payment->total_processed - $payment->amount_processed; //e(Input::get('paymentheld_hid'));

        $payment->conversionrate   = e(Input::get('conversionrate'));
        $payment->user_id          = Sentry::getUser()->id;
        $payment->comments         = e(Input::get('comments'));
        $payment->mainvoice_id     = $invoiceId;

        /**
         * additional costs
         * */



        // Was the payment Saved?
        if($payment->save() )
        {


          //Update balance
            $new_balance = $invoice->balance_out - $payment->amount_processed;

            $invoice->balance_out = $new_balance;

            $invoice->save();

            //Update Invoice Status

            $sum_paid = Mapayment::where('mainvoice_id','=', $invoiceId)->sum('amount_processed');

            $status=4;

            if(($sum_paid - e(Input::get('payable_hid'))) == 0)
            {
                $status = '3'; //Paid
                Cache::forget('numinvoicespaid');
                Cache::rememberForever('numpayments', function()
                {
                    return Mapayment::count();
                });
            }
            else
                if(($sum_paid - e(Input::get('payable_hid'))) < 0 && strtotime($invoice->payout_date) == strtotime(date("Y-m-d 00:00:00")) )
                {
                $status = '2'; //tobepaid
                }
            else
                if(($sum_paid - e(Input::get('payable_hid'))) < 0 && strtotime($invoice->payout_date) > strtotime(date("Y-m-d 00:00:00")) )
                {
                    $status = '4'; //Partially paid
                }


            //Update invoice status

            $updatestatus = DB::table('mainvoicestatus')->where('mainvoice_id','=',$invoiceId)->update(array('invoicestatus_id' => $status));

            ////UPDATE ALL merchant agreement invoices balance/status where id > $invoiceId

            $upcominginvoices = Mainvoice:: where('id','>=',$invoiceId)->where('merchantagreement_id','=', $invoice->merchantagreement_id)->orderBy('id','asc')->get();
            ///Update balance out of all next invoices
            foreach($upcominginvoices as $upinv)
            {
              //  echo $upinv->id.': BI:'.$upinv->balance_in.' BO: '.$upinv->balance_out.'<br>';
                $nextinv = Mainvoice::where('id','>',($upinv->id))->where('merchantagreement_id','=',$upinv->merchantagreement_id)->orderBy('id','asc')->first();
                if(@$nextinv->id)
                {
                    $nextinv->balance_out -= $payment->amount_processed;
                  //  $nextinv->balance_in   = $upinv->balance_out;
                    $nextinv->save();
                }
            }

            ///Update balance in  of all next invoices
            foreach($upcominginvoices as $upinv)
            {
                //  echo $upinv->id.': BI:'.$upinv->balance_in.' BO: '.$upinv->balance_out.'<br>';
                $nextinv = Mainvoice::where('id','>',($upinv->id))->where('merchantagreement_id','=',$upinv->merchantagreement_id)->orderBy('id','asc')->first();
                if(@$nextinv->id)
                {
                   $nextinv->balance_in   = $upinv->balance_out;
                   $nextinv->save();
                }
            }

            // Redirect to the new invoice page
            return Redirect::to("admin/mainvoice/$invoiceId/show")->with('success', Lang::get('admin/invoices/message.update.success'));


            /*
         * Update Cache
         * */
           update_cache();
            ///////////////////////////////////////////////////End update cache
        }



        // Redirect to the invoice edit page
       return Redirect::to("admin/mainvoice/$invoiceId/show")->with('error', Lang::get('admin/invoices/message.update.error'));
    }
//


    public function getDelete($invoiceId)
    {
        // Check if the invoice exists
        if (is_null($invoice = Mainvoice::find($invoiceId)))
        {
            // Redirect to the invoice management page
            return Redirect::to('admin/mainvoice')->with('error', Lang::get('admin/invoices/message.not_found'));
        }

        // Delete the invoice
        $invoice->delete();

        // Redirect to the invoice management page
        return Redirect::to('admin/mainvoice')->with('success', Lang::get('admin/invoices/message.delete.success'));
    }

}
