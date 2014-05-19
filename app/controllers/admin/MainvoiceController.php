<?php namespace Controllers\Admin;

use AdminController;
use Input;
use Lang;
use Cache;
use Mainvoice;
use Invoicestatus;
use Mainvoicerows;
use Mainvoicestatus;
use Mapayment;
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
use Session;


class MainvoiceController extends AdminController {
    /**
     * The invoice id
     * @var int
     */
    protected $id;

    /**
     * An array to store rows
     * @var array
     */
    protected $invoiceRows = array();

    /**
     * Concversion rate
     *
     */
    protected $convRate;

    /**
     * Invoice Total amount
     * @var int
     */
    protected $invoiceAmount;

    public $agreements = array();


    public function __construct()
    {
        $this->beforeFilter('hasAccess:view_ma_invoices');

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
    public function getIndex($invoicestatus = null)
    {

        $this->generate();
        // Grab all the Merchants invoices

        if(count($this->agreements))
        {

            $invoices = Mainvoice::notstatus(1)->whereIn('mainvoice.merchantagreement_id', $this->agreements)->get();

        }
        else
        {
             $invoices = Mainvoice::with(array('mainvoicestatus', 'mainvoicerows', 'merchantagreements'))->get();
        }


        /*if(!(is_null($invoicestatus)))
        {

            $invoices = Mainvoice::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mainvoice.id')->where('mainvoicestatus.invoicestatus_id','=',$invoicestatus)->get();

        }*/

        $allstatuses = Invoicestatus::orderBy('id')->get();


        // Show the page
        return View::make('backend/mainvoice/index', compact('invoices','allstatuses'));
    }

    public function getStatusinvoices($statusId)
    {
        $allstatuses = Invoicestatus::orderBy('id')->get();
        $invoicestatus = Invoicestatus::where('id','=',$statusId)->first();




       // $invoices = Mainvoice::bystatus(6)->get();



           if(count($this->agreements))
            {
                $invoices =  Mainvoice::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mainvoice.id')
                    ->join('merchantagreement','mainvoice.merchantagreement_id','=','merchantagreement.id')
                    ->join('merchant','merchant.id','=','merchantagreement.merchant_id')
                    ->where('mainvoicestatus.invoicestatus_id','=',$statusId)
                    ->whereIn('mainvoice.merchantagreement_id', $this->agreements)
                    ->select('mainvoice.id','mainvoice.invoiceid', 'mainvoice.description','merchantagreement.name','merchant.merchant','mainvoice.date_from','mainvoice.date_to','mainvoice.created_at','mainvoice.processcurrency','mainvoice.payoutcurrency', 'mainvoice.amount')
                    ->get();

                //$invoices = Mainvoice::bystatus(6)->whereIn('mainvoice.merchantagreement_id', $this->agreements)->get();

            }
            else{
                $invoices =  Mainvoice::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mainvoice.id')
                    ->join('merchantagreement','mainvoice.merchantagreement_id','=','merchantagreement.id')
                    ->join('merchant','merchant.id','=','merchantagreement.merchant_id')
                    ->where('mainvoicestatus.invoicestatus_id','=',$statusId)
                    ->select('mainvoice.id', 'mainvoice.invoiceid','mainvoice.description','merchantagreement.name','merchant.merchant','mainvoice.date_from','mainvoice.date_to','mainvoice.created_at','mainvoice.processcurrency','mainvoice.payoutcurrency', 'mainvoice.amount')
                    ->get();

                //$invoices = Mainvoice::bystatus(6)->get();
            }

        if(count($invoices))
        {
            return View::make('backend/mainvoice/status')->with('invoices',$invoices)->with('status',$invoicestatus->status)->with('allstatuses',$allstatuses);
        }
        else
        {
            return redirect::to('admin');
        }
    }

    /**
     * Show Invoice with rows & details
     *
     * @return View
     */

    public function approve($invoiceId)
    {
        if(count($this->agreements))
        {
            $invoice = mainvoice::whereIn('merchantagreement_id',$this->agreements)->find($invoiceId);
        }
        else{
            $invoice = Mainvoice::find($invoiceId);
        }

        if($invoice)
        {

        if($invoice->invoicestatus()->status == 'draft')
        {
            Session::flash('message_approved', 'Invoice has been approved!');
            Session::flash('message-approved-class', 'alert-success');

            Mainvoicestatus::where('mainvoice_id','=',$invoiceId)->update(array('invoicestatus_id' => '8'));

            return $this->getShow($invoiceId);
        }
        else
        {
            Session::flash('message_approved', 'Only draft invoices can be approved!');
            Session::flash('message-approved-class', 'alert-danger');

            return $this->getShow($invoiceId);

        }
        }
        else{
            return Redirect::to("admin/mainvoice/")->with('error', 'Access denied!');
        }


    }

    public function draft($invoiceId)
    {

        if(count($this->agreements))
        {
            $invoice = mainvoice::whereIn('merchantagreement_id',$this->agreements)->find($invoiceId);
        }
        else{
            $invoice = Mainvoice::find($invoiceId);
        }

        if($invoice)
        {
        $haspayments = (Mapayment::where('mainvoice_id','=',$invoiceId)->count()>0? Mapayment::where('mainvoice_id','=',$invoiceId)->count() : 0);

        if($invoice->invoicestatus()->status == 'approved' && $haspayments==0)
        {
            Session::flash('message_approved', 'Invoice has been reset to Draft!');
            Session::flash('message-approved-class', 'alert-success');

            Mainvoicestatus::where('mainvoice_id','=',$invoiceId)->update(array('invoicestatus_id' => '1'));

            return $this->getShow($invoiceId);
        }
        else
        {
            Session::flash('message_approved', 'Only approved invoices with no payments can be reset to Draft!');
            Session::flash('message-approved-class', 'alert-danger');

            return $this->getShow($invoiceId);

        }
        }
        else{
            return Redirect::to("admin/mainvoice/")->with('error', 'Access denied!');
        }


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



    public function getShow($invoiceId)
    {

        if(count($this->agreements))
        {
        $invoice = Mainvoice::whereIn('merchantagreement_id',$this->agreements)->notstatus(1)->find($invoiceId);
        }
        else{
        $invoice = Mainvoice::find($invoiceId);
        }

        if($invoice)
        {
        $specificationrows = $invoice->mainvoicerows()->where('type','=','specification')->get();

        $costrows = $invoice->mainvoicerows()->where('type','=','costs')->get();

        $reportrows = $invoice->mainvoicerows()->where('type','=','report')->get();

        /*
         * @todo: get from seperate table holdbackreserve
         * */
        $holdbackrows = ''; //$invoice->mainvoicerows()->where('type','=','holdback')->get();

        $customrows = $invoice->mainvoicerows()->where('type','=','custom')->get();


        $heldrows = $invoice->mainvoicerows()->where('type','=','held')->get();

        $incomerows = $invoice->mainvoicerows()->where('type','=','income')->get();

        $conversionrate = $this->getConversionrate($invoiceId);

        $payments = Mapayment::where('mainvoice_id','=',$invoiceId)->get();

        //has any payment

            $haspayments = (Mapayment::where('mainvoice_id','=',$invoiceId)->count()>0? Mapayment::where('mainvoice_id','=',$invoiceId)->count() : 0);

          //  echo $haspayments; exit;


        return View::make('backend/mainvoice/show', compact('invoice','specificationrows', 'costrows', 'reportrows', 'holdbackrows', 'customrows', 'heldrows', 'incomerows','merchantagreement','conversionrate', 'payments'))->with('haspayments', $haspayments);
        }
        else{
            return Redirect::to("admin/mainvoice/")->with('error', 'Access denied!');
        }

    }

    public function getPDF($invoiceId)
    {
        if(count($this->agreements))
        {
            $invoice = mainvoice::whereIn('merchantagreement_id',$this->agreements)->notstatus(1)->find($invoiceId);
        }
        else{
            $invoice = Mainvoice::find($invoiceId);
        }

        if($invoice)
        {
        $specificationrows = $invoice->mainvoicerows()->where('type','=','specification')->get();

        $costrows = $invoice->mainvoicerows()->where('type','=','costs')->get();

        $reportrows = $invoice->mainvoicerows()->where('type','=','report')->get();

        $holdbackrows = $invoice->mainvoicerows()->where('type','=','holdback')->get();

        $customrows = $invoice->mainvoicerows()->where('type','=','custom')->get();

        $heldrows = $invoice->mainvoicerows()->where('type','=','held')->get();

        $incomerows = $invoice->mainvoicerows()->where('type','=','income')->get();

        $payments = Mapayment::where('mainvoice_id','=',$invoiceId)->get();

        $view = View::make('backend/mainvoice/invoicepdf', compact('invoice','specificationrows', 'costrows', 'reportrows', 'holdbackrows', 'customrows', 'heldrows', 'incomerows','payments'));

        //  return $view; exit;

        $dompdf = new DOMPDF();
        $dompdf->load_html($view);
        $dompdf->render();

        // output to the broswer
        $dompdf->stream('Invoice_'.$invoiceId.'.pdf',array('Attachment'=>0));

        // download the file.
        // $dompdf->stream("my.pdf");

        }
        else{
            return Redirect::to("admin/mainvoice/")->with('error', 'Access denied!');
        }


    }

    /*
     * @todo: test xls download*/
    public function getExcel()
    {
        Excel::loadView('xlstest', array('data'))
            ->setTitle('MarpTest')
            ->sheet('Marp Sheel1')
            ->export('xls');
    }

    /**
     * Manually Create Inovoice.
     * @todo: create whole invoice manually?
     * @return View
     */
    public function getCreate()
    {
        // Show the page
        return View::make('backend/mainvoice/create');
    }

    /**
     * Automatically generate merchant invoice
     * @param  json invoice data from voucher, shop
     * @return View
     */


    public function generate()  /*@todo add generate($invoiceid)*/
    {
        /**
         * Grab all redemptions per merchant since last invoice (date to + merchant agreement report length) and date_to < today
         * create new invoice in Mainvoice table if not created, draft status
         * updates existing draft invoice if created
         * @return view
         */


        $merchantagreements = Merchantagreement::get();

        foreach($merchantagreements as $ma)
        {

            $merchantagreementID = $ma->id;

            $merchantID = $ma->merchant_id;

            $maps = New Map();

            //Dispaly merchants payout currency
            $map = $maps::join('ma_map','map.id','=', 'ma_map.map_id')
                ->join('map_status','ma_map.status_id','=', 'map_status.id')
                ->where('ma_map.merchantagreement_id', '=', $merchantagreementID)
                ->where('map.parameter','=','payoutcurrency')
                ->first();

            $payoutcurrency = $map->map_value;


            $description = 'Generated automatically from ARP';

            //Dates
            $date_from   = $this->getDateFrom($merchantagreementID);
            $date_to     = $this->getDateTo($merchantagreementID);



            /**
             * if date_to < today
             * proceed to generate invoice
             * */

            $payout_date = date("Y-m-d", strtotime($this->getPayoutDate($merchantagreementID)));

            if(strtotime(date("Y-m-d 00:00:00")) > strtotime($date_to))
            {
                //Just for new generated invoices, not updated if already exists

                //Calculate amounts to be stored
                /*@todo: get all this data from shop and voucher*/


                $transaction = $this->getTransactionRange($merchantID, $date_from, $date_to);

                $processcurrency     = $transaction['processcurrency'];

                $numtransactions     = $transaction['numtransactions'];
                $transactions_amount = $transaction['amounttransactions'];
                $transactions_avg    = $transaction['avgtransactions'];

                $transactionid_from = $transaction['transactionid_from'];
                $transactionid_to   = $transaction['transactionid_to'];

                $redemptions = $this->getRedemptions($merchantID, $date_from, $date_to);

                $numredemptions    = $redemptions['numredemptions'];
                $amountredemptions = $redemptions['amountredemptions'];

                $refunds = $this->getRefunds($merchantID, $date_from, $date_to);

                $numrefunds    = $refunds['numrefunds'];
                $amountrefunds = $refunds['amountrefunds'];



                $chargebacks = $this->getChargebacks($merchantID, $date_from, $date_to);

                $numchb    = ($chargebacks['numchargebacks'] ? $chargebacks['numchargebacks']:0);
                $amountchb = ($chargebacks['amountchargebacks']? $chargebacks['amountchargebacks']:0);

                //FROM EVENTS 490 To 499
                $numevents = $this->getnumVE($merchantID, $date_from, $date_to);
                $amountevents = $this->getamountVE($merchantID, $date_from, $date_to);

                //Specification rows
                $invrows = array();
                $deducted_amount = 0;

                $invrows['processed_amount']     = $amountredemptions;

                //   $invrows['refunded_amount']      = $amountrefunds;
                //   $invrows['chargebacked_amount']  = $amountchb;

                foreach($amountevents as $nv)
                {
                    $invrows[$nv->event] = $nv->sumamount;
                    $deducted_amount += $invrows[$nv->event];
                }

                $invrows['deducted_amount'] = $deducted_amount;



                $payable_amount = $amountredemptions + $deducted_amount;

                //Total specification
                $invrows['payable_sum'] = $payable_amount;




                //Cost Rows
                $costrows = array();

                //Fixed costs

                $costrows['rate_processed_amount'] = (@$ma->paramval('percentage')->map_value) ? (($amountredemptions * $ma->paramval('percentage')->map_value)/100) : 0;
                $costrows['cost_per_refund']       = (@$ma->paramval('refund_cost')->map_value) ? ($numrefunds * $ma->paramval('refund_cost')->map_value) : 0;
                $costrows['cost_per_chargeback']   = (@$ma->paramval('chb_cost')->map_value) ? ($numchb * $ma->paramval('chb_cost')->map_value) : 0;
                $costrows['cost_per_transaction']  = (@$ma->paramval('transaction_cost')->map_value ) ? ($numtransactions * $ma->paramval('transaction_cost')->map_value) : 0;

                $costrows['sum_fixed_costs'] = $costrows['rate_processed_amount'] + $costrows['cost_per_refund'] + $costrows['cost_per_chargeback'] + $costrows['cost_per_transaction'];



                //Holdback reserve
                /**
                 * @todo: separate table
                 */

                $holdbackrows = array();

                /**check if invoice already created
                 * Update if created,
                 * Insert if not */

                $check = Mainvoice::where('merchantagreement_id','=',$merchantagreementID)
                    ->where('date_from','=',$date_from )
                    ->where('date_to','=',$date_to )
                    ->first();

                if($check)  //UPDATE EXISTING INVOICE
                {
                    //Custom Costs Rows
                    $customrows = array();

                    $cust = Mainvoicerows::where('type','=','custom')
                        ->where('mainvoice_id','=',$check->id)
                        ->where('description','<>','sum_custom_costs')
                        ->get();

                    $held = Mainvoicerows::where('type','=','held')
                        ->where('mainvoice_id','=',$check->id)
                        ->sum('amount');

                    $costrows['sum_fixed_costs'] = $costrows['rate_processed_amount'] + $costrows['cost_per_refund'] + $costrows['cost_per_chargeback'] + $costrows['cost_per_transaction'];
                    $costrows['sum_costs'] = $costrows['sum_fixed_costs'];

                    $custom_costs = 0;

                    foreach($cust as $custrow)
                    {
                        $customrows[$custrow->description] = $custrow->amount;
                        $custom_costs += $custrow->amount;
                    }

                    $customrows['sum_custom_costs'] = $custom_costs;
                    $costrows['sum_costs'] += $custom_costs;

                    //partial payments
                    $sumpayments = Mapayment::where('mainvoice_id','=',$check->id)->sum('amount_processed');

                    //Report Rows
                    $reprows = array();
                    $payable = $invrows['payable_sum']  - $costrows['sum_costs'] - $held;
                    $reprows['payable'] = $payable;

                    //Invoice Balance
                    $balance_in  = $this->getLastbalance($merchantagreementID);

                    $balance_out = $balance_in + $held + $payable - $sumpayments;

                    $inv = New Mainvoice;

                    $invoice = $inv->where('id', '=',$check->id )->update(
                        array('merchantagreement_id'  => $merchantagreementID,
                            'transactions_number'   => $numtransactions,
                            'transactions_amount'   => $transactions_amount,
                            'transactions_avg'      => $transactions_avg,
                            'transactionid_from'    => $transactionid_from,
                            'transactionid_to'      => $transactionid_to,
                            'redemptions_number'    => $numredemptions,
                            'redemptions_amount'    => $amountredemptions,
                            'chargebacks_number'    => $numchb,
                            'chargebacks_amount'    => $amountchb,
                            'refunds_number'        => $numrefunds,
                            'refunds_amount'        => $amountrefunds,
                            //'conversion_rate'       => $conv_rate,
                            'amount'                => $payable_amount,
                            'balance_out'           => $balance_out,
                            'balance_in'            => $balance_in,
                            'processcurrency'       => $processcurrency,
                            'payoutcurrency'        => $payoutcurrency,


                        )
                    );

                    //update existing rows, insert new ones

                    ///Specifications
                    foreach(array_keys($invrows) as $row)
                    {
                        $checkrow = Mainvoicerows::where('mainvoice_id','=',$check->id)
                            ->where('description','=',$row)
                            ->where('type','=','specification')
                            ->first();

                        if($checkrow) //update
                        {
                            $uprow = DB::table('mainvoicerows')
                                ->where('mainvoice_id','=',$check->id)
                                ->where('description','=',$row)
                                ->where('type','=','specification')
                                ->update(array(
                                    'amount'  => $invrows[$row],
                                    'user_id' => Sentry::getUser()->id
                                ));
                        }
                        else  //insert
                        {
                            $insrow = Mainvoicerows::insert(array(
                                'mainvoice_id' => $check->id,
                                'user_id'      => Sentry::getUser()->id,
                                'description'  => $row,
                                'amount'       => $invrows[$row],
                                'type'         => 'specification'
                            ));

                        }

                    }//end foreach

                    ///Costs
                    foreach(array_keys($costrows) as $row)
                    {
                        $checkrow = Mainvoicerows::where('mainvoice_id','=',$check->id)
                            ->where('description','=',$row)
                            ->where('type','=','costs')
                            ->first();

                        if($checkrow) //update
                        {
                            $uprow = DB::table('mainvoicerows')->where('mainvoice_id','=',$check->id)
                                ->where('description','=',$row)
                                ->where('type','=','costs')
                                ->update(array(
                                    'amount'  => $costrows[$row],
                                    'user_id' => Sentry::getUser()->id
                                ));
                        }
                        else  //insert
                        {
                            $insrow = Mainvoicerows::insert(array(
                                'mainvoice_id' => $check->id,
                                'user_id'      => Sentry::getUser()->id,
                                'description'  => $row,
                                'amount'       => $costrows[$row],
                                'type'         => 'costs'
                            ));

                        }

                    }//end foreach

                    ///Report
                    foreach(array_keys($reprows) as $row)
                    {
                        $checkrow = Mainvoicerows::where('mainvoice_id','=',$check->id)
                            ->where('description','=',$row)
                            ->where('type','=','report')
                            ->first();

                        if($checkrow) //update
                        {
                            $uprow = DB::table('mainvoicerows')->where('mainvoice_id','=',$check->id)
                                ->where('description','=',$row)
                                ->update(array(
                                    'amount'  => $reprows[$row],
                                    'user_id' => Sentry::getUser()->id,
                                    'type'    => 'report'
                                ));
                        }
                        else  //insert
                        {
                            $insrow = Mainvoicerows::insert(array(
                                'mainvoice_id' => $check->id,
                                'user_id'      => Sentry::getUser()->id,
                                'description'  => $row,
                                'amount'       => $reprows[$row],
                                'type'         => 'report'
                            ));

                        }

                    }//end foreach




                    ///
                }
                else //CREATE NEW INVOICE
                {
                    $costrows['sum_fixed_costs'] = $costrows['rate_processed_amount'] + $costrows['cost_per_refund'] + $costrows['cost_per_chargeback'] + $costrows['cost_per_transaction'];
                    $costrows['sum_costs'] = $costrows['rate_processed_amount'] + $costrows['cost_per_refund'] + $costrows['cost_per_chargeback'] + $costrows['cost_per_transaction'];

                    //Report Rows
                    $reprows = array();
                    $payable = $invrows['payable_sum']  - $costrows['sum_costs'];
                    $reprows['payable'] = $payable;
                    //Invoice Balance
                    $balance_in  = $this->getLastbalance($merchantagreementID);

                    $balance_out = $balance_in + $payable;

                    $invoice = Mainvoice::insertGetId(
                        array('merchantagreement_id' => $merchantagreementID,
                            'date_from'              => $date_from,
                            'date_to'                => $date_to,
                            'transactions_number'    => $numtransactions,
                            'transactions_amount'    => $transactions_amount,
                            'transactions_avg'       => $transactions_avg,
                            'transactionid_from'     => $transactionid_from,
                            'transactionid_to'       => $transactionid_to,
                            'redemptions_number'     => $numredemptions,
                            'redemptions_amount'     => $amountredemptions,
                            'chargebacks_number'     => $numchb,
                            'chargebacks_amount'     => $amountchb,
                            'refunds_number'         => $numrefunds,
                            'refunds_amount'         => $amountrefunds,
                            'description'            => $description,
                            'payout_date'            => $payout_date,
                            'processcurrency'        => $processcurrency,
                            'payoutcurrency'         => $payoutcurrency,
                            'amount'                 => $payable_amount,
                            'balance_in'             => $balance_in,
                            'balance_out'            => $balance_out
                        )
                    );


                    ///Insert status
                    $insert_status = DB::table('mainvoicestatus')->insert(array('mainvoice_id' => $invoice , 'invoicestatus_id' => '1'));

                    /**Create Invoice Rows */

                    foreach(array_keys($invrows) as $row)
                    {
                        //Specifications
                        $insrows = Mainvoicerows::insert(array(
                            'amount'       => $invrows[$row],
                            'description'  => $row,
                            'user_id'      => Sentry::getUser()->id,
                            'mainvoice_id' => $invoice,
                            'type'         => 'specification'
                        ));

                    }

                    foreach(array_keys($costrows) as $row)
                    {
                        //Costs
                        $insrows = Mainvoicerows::insert(array(
                            'amount'       => $costrows[$row],
                            'description'  => $row,
                            'user_id'      => Sentry::getUser()->id,
                            'mainvoice_id' => $invoice,
                            'type'         => 'costs'
                        ));

                    }

                    foreach(array_keys($reprows) as $row)
                    {
                        //Specifications
                        $insrows = Mainvoicerows::insert(array(
                            'amount'       => $reprows[$row],
                            'description'  => $row,
                            'user_id'      => Sentry::getUser()->id,
                            'mainvoice_id' => $invoice,
                            'type'         => 'report'
                        ));

                    }


                    //Holdback reserve
                    /**
                     * @todo: separate table
                     */

                    /*============== Invoice Secuencial ID */

                    $secid_sql = "UPDATE mainvoice SET invoiceid = (mainvoice_sec.row_number + 999) FROM (SELECT id, merchantagreement_id, row_number() OVER (PARTITION BY merchantagreement_id ORDER BY id) from mainvoice) AS mainvoice_sec WHERE mainvoice.id = mainvoice_sec.id AND mainvoice.merchantagreement_id = '".$merchantagreementID."' ";

                    $secuencialID = DB::update(DB::raw($secid_sql));


                }

                if(!$invoice)
                {

                    echo 'ERROR <hr>';
                }


            } //end date_to < today

        } //end foreach merchantagreement

        ///Update status
        //to be paid

        /*================= Update Invoice Status ===========*/

        $this->updateInvoicestatus();




    }


    public function updateInvoicestatus()
    {
        $tobepaid = Mainvoicestatus::join('mainvoice','mainvoicestatus.mainvoice_id','=','mainvoice.id')
            ->where('mainvoice.payout_date','=',date("Y-m-d 00:00:00"))
            ->where('mainvoicestatus.invoicestatus_id','<>','3')         //paid
            ->where('mainvoicestatus.invoicestatus_id','<>','6')         //overdue
            ->where('mainvoicestatus.invoicestatus_id','<>','5')         //archieved
            ->update(array('invoicestatus_id' => '2'));


        //overdue
        $overdue = Mainvoicestatus::join('mainvoice','mainvoice.id','=','mainvoicestatus.mainvoice_id')
            ->where('mainvoice.payout_date','<',date("Y-m-d 00:00:00"))
            ->where('mainvoicestatus.invoicestatus_id','<>','3')           //paid
            ->where('mainvoicestatus.invoicestatus_id','<>','6')           //overdue
            ->where('mainvoicestatus.invoicestatus_id','<>','5')           //archieved
            ->update(array('invoicestatus_id' => '6'));


        //min payout not met
        $minpayout = Mainvoice::join('mainvoicestatus','mainvoice.id','=','mainvoicestatus.mainvoice_id')
            ->where('mainvoicestatus.invoicestatus_id','<>','3')           //paid
            ->where('mainvoicestatus.invoicestatus_id','<>','2')           //paid
            ->select('mainvoice.id')
            ->get();

        foreach($minpayout as $inv_mp)
        {
            $invoice_mp = Mainvoice::find($inv_mp->id);

            if($invoice_mp->rowval('payable')->amount < $invoice_mp->merchantagreement()->paramval('min_payout')->map_value)
            {
                $update_mp = Mainvoicestatus::where('mainvoice_id','=',$inv_mp->id)->update(array('invoicestatus_id' => '7')); //min payout not met : status 7
            }
        }
    }

    /**
     * Get date of last paid invoice invoice_status: 3 (paid)
     *
     * @return date
     */
    public function lastinvoiceDate($merchantagreementID)
    {
        $lastinvoice = Mainvoice::join('mainvoicestatus','mainvoice.id','=','mainvoicestatus.mainvoice_id')
            ->where('merchantagreement_id','=',$merchantagreementID)
            ->where('date_to','<',date('Y-m-d'))
            ->orderBy('mainvoice.date_to','desc')->first();

        if($lastinvoice)
            return $lastinvoice->date_to;
        else
            return null;
    }

    /**
     * Calculates date to based
     *
     * @return date
     */
    public function getDateFrom($merchantagreementID)
    {
        $lastInvoiceDate = $this->lastinvoiceDate($merchantagreementID);

        if($lastInvoiceDate)
        {
            //@todo calculate based on merchantagreement parameters

            $datefrom = date('Y-m-d', strtotime($lastInvoiceDate. ' + 1 days'));
        }
        else
        {
            //$datefrom = date("Y-m-d");
            $datefrom = '2014-03-01 00:00:00';


        }

        return $datefrom;

    }

    /**
     * Calculates date to based on merchant agreement report length parameter : id = 2
     *
     * @return date
     */

    public function getDateTo($merchantagreementID)
    {
        $mapID = 2; //Report Legnth

        $days  = DB::table('ma_map')->join('merchantagreement','ma_map.merchantagreement_id','=','merchantagreement.id')->where('merchantagreement.id','=',$merchantagreementID)->where('map_id','=',$mapID)->get();

        foreach($days as $d)
        {
            $numdays = $d->map_value;

        }
        $dateFrom = $this->getDateFrom($merchantagreementID);

        $dateTo = date('Y-m-d 23:59:59', strtotime($dateFrom. ' + '.$numdays.' days'));

        return $dateTo;
    }

    /**
     * Calculates Invoice Payout date based on merchant agreement report Payout Delay id = 11
     *
     * @return date
     */
    public function getPayoutDate($merchantagreementID)
    {
        $mapID = 11; //payout delay

        $days  = DB::table('ma_map')->join('merchantagreement','ma_map.merchantagreement_id','=','merchantagreement.id')->where('merchantagreement.id','=',$merchantagreementID)->where('map_id','=',$mapID)->get();

        foreach($days as $d)
            $numdays = $d->map_value;

        $dateTo = $this->getDateTo($merchantagreementID);

        $payoutDate = date('Y-m-d', strtotime($dateTo. ' + '.$numdays.' days'));


        return $payoutDate;
    }


    /*
     * @todo: merchant_id must be merchantagreement_id for transactions, reddemptions, refunds && chb
     * */
    public function getTransactionRange($merchantID, $date_from, $date_to)
    {
        /*@todo: Replace with Json data from Shop */


        $transactionid_from = Transactionorder::join('transaction','transaction.id','=','order.transaction_id')
            ->where('transaction.merchant_id','=',$merchantID)
            ->where('order.datetimecreated','>=',$date_from)
            ->where('order.datetimecreated','<=',$date_to)
            ->min('order.id');

        $transactionid_to =Transactionorder::join('transaction','transaction.id','=','order.transaction_id')
            ->where('transaction.merchant_id','=',$merchantID)
            ->where('order.datetimecreated','>=',$date_from)
            ->where('order.datetimecreated','<=',$date_to)
            ->max('order.id');

        $numtransactions = Transactionorder::join('transaction','transaction.id','=','order.transaction_id')
            ->where('transaction.merchant_id','=',$merchantID)
            ->where('order.datetimecreated','>=',$date_from)
            ->where('order.datetimecreated','<=',$date_to)
            ->count();

        $amounttransactions = Transactionorder::join('transaction','transaction.id','=','order.transaction_id')
            ->where('transaction.merchant_id','=',$merchantID)
            ->where('order.datetimecreated','>=',$date_from)
            ->where('order.datetimecreated','<=',$date_to)
            ->sum('order.amount');

        $avgtransactions = Transactionorder::join('transaction','transaction.id','=','order.transaction_id')
            ->where('transaction.merchant_id','=',$merchantID)
            ->where('order.datetimecreated','>=',$date_from)
            ->where('order.datetimecreated','<=',$date_to)
            ->avg('order.amount');

        $processcurrency = Voucherevent::where('merchant_id','=', $merchantID)
            ->where('datetimecreated','>=',$date_from)
            ->where('datetimecreated','<=',$date_to)
            ->select('currency')->first();



        return array(
            'transactionid_from' => $transactionid_from,
            'transactionid_to'   => $transactionid_to,
            'numtransactions'    => $numtransactions,
            'amounttransactions' => $amounttransactions,
            'avgtransactions'    => $avgtransactions,
            'processcurrency'    => $processcurrency['currency']
        );

    }


    public function getRedemptions($merchantID, $date_from, $date_to)
    {
        /*@todo: Replace with data from voucher */
        $numredemptions = Voucherevent::where('merchant_id','=',$merchantID)
            ->where('event_id','=', 403)
            ->where('datetimecreated','>=',$date_from)
            ->where('datetimecreated','<=',$date_to)
            ->count();

        $amountredemptions = Voucherevent::where('merchant_id','=',$merchantID)
            ->where('event_id','=', 403)
            ->where('datetimecreated','>=',$date_from)
            ->where('datetimecreated','<=',$date_to)
            ->sum('amount');

        return array(
            'numredemptions'    => $numredemptions,
            'amountredemptions' => $amountredemptions
        );

    }


    public function getRefunds($merchantID, $date_from, $date_to)
    {
        /*@todo: Replace with data from voucher */
        $numrefunds = Voucherevent::where('merchant_id', '=', $merchantID)
            ->where('event_id','=', 490)
            ->where('datetimecreated','>=', $date_from)
            ->where('datetimecreated','<=', $date_to)
            ->count();

        $amountrefunds = Voucherevent::where('merchant_id', '=', $merchantID)
            ->where('event_id','=', 490)
            ->where('datetimecreated','>=', $date_from)
            ->where('datetimecreated','<=', $date_to)
            ->sum('amount');

        return array(
            'numrefunds'    => $numrefunds,
            'amountrefunds' => $amountrefunds
        );

    }

    public function getChargebacks($merchantID, $date_from, $date_to)
    {
        /*@todo: Get chargebacks from voucher */

        $numchargebacks = Voucherevent::where('merchant_id', '=', $merchantID)
            ->whereIn('event_id', array(491, 492, 494, 495))
            ->where('datetimecreated','>=', $date_from)
            ->where('datetimecreated','<=', $date_to)
            ->count();

        $amountchargebacks =  Voucherevent::where('merchant_id', '=', $merchantID)
            ->whereIn('event_id', array(491, 492, 494, 495))
            ->where('datetimecreated','>=', $date_from)
            ->where('datetimecreated','<=', $date_to)
            ->sum('amount');


        return array(
            'numchargebacks'    => $numchargebacks,
            'amountchargebacks' => $amountchargebacks
        );

    }

    public function getnumVE($merchantID, $date_from, $date_to)
    {

        $numVe = DB::table('event')->join('voucher_event','event.id','=','voucher_event.event_id')
            ->select(DB::raw('count(event.id) as numevents, event.event'))
            ->where('merchant_id', '=', $merchantID)
            ->where('datetimecreated','>=', $date_from)
            ->where('datetimecreated','<=', $date_to)
            ->where('event_id','>=', 490)
            ->where('event_id','<=', 499)
            ->groupBy('event_id')
            ->groupBy('event')
            ->get();

        return $numVe;
    }
    public function getamountVE($merchantID, $date_from, $date_to)
    {

        $amountVe = DB::table('event')->join('voucher_event','event.id','=','voucher_event.event_id')
            ->select(DB::raw('sum(voucher_event.amount) as sumamount, event.event'))
            ->where('merchant_id', '=', $merchantID)
            ->where('datetimecreated','>=', $date_from)
            ->where('datetimecreated','<=', $date_to)
            ->where('event_id','>=', 490)
            ->where('event_id','<=', 499)
            ->groupBy('event_id')
            ->groupBy('event')
            ->get();

        return $amountVe;
    }




    public function getLastbalance($merchantagreement)
    {

        $lastbalance = Mainvoice::where('merchantagreement_id','=',$merchantagreement)
            ->where('mainvoice.date_to','<', $this->getDateFrom($merchantagreement))
            ->orderBy('id','DESC')->first();

        if($lastbalance)
        {
            return $lastbalance->balance_out;
        }
        else
        {
            return 0;
        }
    }



    /**
     * Invoice create form processing.
     *
     * @return Redirect
     */
    /* public function postCreate()
     {
         // Declare the rules for the form validation
         $rules = array(
             'title'   => 'required|min:3',
             'description' => 'required|min:3',
         );

         // Create a new validator instance from our validation rules
         $validator = Validator::make(Input::all(), $rules);

         // If validation fails, we will exit the operation now.
         if ($validator->fails())
         {
             // Ooops.. something went wrong
             return Redirect::back()->withInput()->withErrors($validator);
         }

         // Create a new Invoice
         $invoice = new Mainvoice;

         // Update invoice data
         $invoice->description    = e(Input::get('description'));
         $invoice->from           = (Input::get('from'));
         $invoice->to             = e(Input::get('to'));
         $invoice->date           = e(Input::get('date-title'));
         $invoice->amount         = e(Input::get('meta-amount'));

         $invoice->user_id        = Sentry::getId();

         // Was the invoice created?
         if($invoice->save())
         {
             // Redirect to the new invoice page
             return Redirect::to("admin/mainvoice/$invoice->id/edit")->with('success', Lang::get('admin/invoices/message.create.success'));
         }

         // Redirect to the invoice create page
         return Redirect::to('admin/mainvoice/create')->with('error', Lang::get('admin/invoices/message.create.error'));
     }*/

    /**
     * invoice update.
     *
     * @param  int  $postId
     * @return View
     */
    public function getEdit($invoiceId = null)
    {


        if(count($this->agreements))
        {
            $invoice = mainvoice::whereIn('merchantagreement_id',$this->agreements)->find($invoiceId);
        }
        else{
            $invoice = Mainvoice::find($invoiceId);
        }

        //if($invoice && Sentry::getUser()->hasAccess('manage_ma_invoices'))
        if($invoice)
        {
        if($invoice->invoicestatus($invoice->id)->status == 'paid')
        {
            return Redirect::to("admin/mainvoice/$invoiceId/show")->with('warning', 'Paid invoices can not be edited');
        }

        //has any payment
        $haspayments = Mapayment::where('mainvoice_id','=',$invoiceId)->count();


        $specificationrows = $invoice->mainvoicerows()->where('type','=','specification')->get();

        $costrows = $invoice->mainvoicerows()->where('type','=','costs')->get();

        $reportrows = $invoice->mainvoicerows()->where('type','=','report')->get();

        $holdbackrows = ''; //$invoice->mainvoicerows()->where('type','=','holdback')->get();

        $customrows = $invoice->mainvoicerows()->where('type','=','custom')->get();

        $heldrows = $invoice->mainvoicerows()->where('type','=','held')->get();

        $incomerows = $invoice->mainvoicerows()->where('type','=','income')->get();

        $payments = Mapayment::where('mainvoice_id','=',$invoiceId)->get();

        $conversionrate = $this->getConversionrate($invoiceId);


        return View::make('backend/mainvoice/edit', compact('invoice','specificationrows', 'costrows', 'reportrows', 'holdbackrows', 'customrows', 'heldrows', 'incomerows', 'payments','conversionrate','haspayments'));
        }
        else
        {
            return Redirect::to("admin/mainvoice/")->with('error', 'Access denied!');
        }
    }

    /**
     * invoice update form processing page.
     *
     * @param  int  $postId
     * @return Redirect
     */
    public function postEdit($invoiceId = null)
    {
        // Check if the invoice exists
        if (is_null($invoice = Mainvoice::find($invoiceId)))
        {
            // Redirect to the blogs management page
            return Redirect::to('admin/mainvoice')->with('error', Lang::get('admin/invoices/message.does_not_exist'));
        }

        // Declare the rules for the form validation
        $rules = array(
            'payout_date' => 'required',

        );

        // Create a new validator instance from our validation rules
        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails())
        {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $invoice->payout_date = e(Input::get('payout_date'));
        $invoice->user_id     = Sentry::getUser()->id;

        /**
         * additional costs
         * */

        $description = $_POST['description'];
        $amount      = $_POST['amount'];
        $comments    = $_POST['comments'];

        $saverows = 0;
        $total_costs = $invoice->rowval('sum_costs')->amount;


        foreach(array_keys($description) as $key)
        {
            $invoicerows = New Mainvoicerows();
            $invoicerows->description    = $description[$key]; //(Input::get('description'));
            $invoicerows->amount         = $amount[$key]; //e(Input::get('amount'));
            $invoicerows->custom_reason  = $comments[$key]; //e(Input::get('comments'));
            $invoicerows->user_id        = Sentry::getUser()->id;
            $invoicerows->type           = 'custom';
            $invoicerows->mainvoice_id   = $invoiceId;

            //save new row
            if($invoicerows->description!='' && $invoicerows->amount!='' )
            {
                if(!$invoicerows->save()) $saverows++;
            }

        }

        /**
         * additional income
         * */

        $incomeDescription = $_POST['incomeDescription'];
        $incomeAmount      = $_POST['incomeAmount'];
        $incomeComments    = $_POST['incomeComments'];

        $saveincome = 0;
        $total_income = 0;

        foreach(array_keys($incomeDescription) as $key)
        {
            $incomerows = New Mainvoicerows();
            $incomerows->description    = $incomeDescription[$key];
            $incomerows->amount         = $incomeAmount[$key];
            $incomerows->custom_reason  = $incomeComments[$key];
            $incomerows->user_id        = Sentry::getUser()->id;
            $incomerows->type           = 'income';
            $incomerows->mainvoice_id   = $invoiceId;

            //save new row
            if($incomerows->description!='' && $incomerows->amount!='' )
            {
                if(!$incomerows->save()) $saveincome++;
            }

        }

        /**
         * additional held
         * */
        $heldreason = $_POST['held_reason'];
        $heldamount = $_POST['held_amount'];
        $heldcomments = $_POST['held_comments'];

        $saveheldrows = 0;
        $totalheld = 0;

        foreach(array_keys($heldreason) as $key)
        {
            $heldrows = New Mainvoicerows();
            $heldrows->description    = $heldreason[$key];
            $heldrows->amount         = $heldamount[$key];
            $heldrows->custom_reason  = $heldcomments[$key];
            $heldrows->user_id        = Sentry::getUser()->id;
            $heldrows->type           = 'held';
            $heldrows->mainvoice_id   = $invoiceId;

            //save new row
            if($heldrows->description!='' && $heldamount!='' )
            {
                if(!$heldrows->save())
                {
                    $saveheldrows++;
                }
            }
        }


        //all costs
        $fixedcosts = Mainvoicerows::where('type','=','costs')
            ->where('mainvoice_id','=', $invoiceId)
            ->where('description','<>','sum_costs')
            ->where('description','<>','sum_fixed_costs')
            ->where('description','<>','sum_custom_costs')
            ->sum('amount');

        $custcosts = Mainvoicerows::where('type','=','custom')
            ->where('description','<>','sum_custom_costs')
            ->where('mainvoice_id','=', $invoiceId)
            ->sum('amount');

        $allcosts = Mainvoicerows::where(function ($query) {
            $query->where('type', '=', 'costs')
                ->orWhere('type', '=', 'custom');
        })  ->where('mainvoice_id','=', $invoiceId)
            ->where('description','<>','sum_costs')
            ->where('description','<>','sum_income')
            ->where('description','<>','sum_fixed_costs')
            ->where('description','<>','sum_custom_costs')
            ->sum('amount');

        $allheld = Mainvoicerows::where('type','=','held')
            ->where('mainvoice_id','=',$invoiceId)
            ->sum('amount');

        $allincome = Mainvoicerows::where('type','=','income')
            ->where('description','<>','sum_income')
            ->where('mainvoice_id','=',$invoiceId)
            ->sum('amount');


        ///UPDATE SUM AMOUNTS
        $updateamounts = Mainvoicerows::where('mainvoice_id', '=', $invoiceId)
            ->where('description','=','sum_custom_costs')
            ->where('type','=','custom')
            ->update(array('amount' => $custcosts));

        if(!$updateamounts && $custcosts)
        {
            $insertamounts = Mainvoicerows::insert(array(
                'mainvoice_id' => $invoiceId,
                'description'  => 'sum_custom_costs',
                'type'         => 'custom',
                'amount'       => $custcosts,
                'user_id'      => Sentry::getUser()->id,
                'mainvoice_id' => $invoiceId

            ));
        }


        $updateallamounts = Mainvoicerows::where('mainvoice_id', '=', $invoiceId)
            ->where('description','=','sum_costs')
            ->where('type','=','costs')
            ->update(array('amount' => $allcosts));

        $updateallincome = Mainvoicerows::where('mainvoice_id', '=', $invoiceId)
            ->where('description','=','sum_income')
            ->where('type','=','income')
            ->update(array('amount' => $allincome));

        if(!$updateallincome && $allincome)
        {
            $insertallincome = Mainvoicerows::insert(array(
                'mainvoice_id' => $invoiceId,
                'description'  => 'sum_income',
                'type'         => 'income',
                'user_id'      => Sentry::getUser()->id,
                'amount'       => $allincome,
                'mainvoice_id' => $invoiceId

            ));
        }

        $sumpayments = Mapayment::where('mainvoice_id','=',$invoiceId)->sum('amount_processed');

        $new_payable = $invoice->redemptions_amount - ($invoice->refunds_amount + $invoice->chargebacks_amount) - $allcosts - $allheld + $allincome;

        //Update Payable Amount
        $update_payable = Mainvoicerows::where('mainvoice_id','=',$invoiceId)
            ->where('description','=','payable')
            ->where('type','=','report')
            ->update(array('amount' => $new_payable));




        //Update balance
        $invoice->balance_out = $invoice->balance_in + $allheld + $new_payable - $sumpayments;

        //Update ALL MA FOLLOWING INVOICES BALANCES

        // Was the invoice updated?
        if($invoice->save() && $saverows==0 && $saveheldrows==0 && $saveincome==0)
        {
            //success
            $upcominginvoices = Mainvoice:: where('id','>=',$invoiceId)->where('merchantagreement_id','=', $invoice->merchantagreement_id)->orderBy('id','asc')->get();

            ///Update balance in / out of all next invoices
            foreach($upcominginvoices as $upinv)
            {
                $nextinv = Mainvoice::where('id','>',($upinv->id))->where('merchantagreement_id','=',$upinv->merchantagreement_id)->orderBy('id','asc')->first();
                if(@$nextinv->id)
                {
                    $nextinv->balance_in   = $upinv->balance_out;
                    $nextinv->save();
                }
            }

            /*
             * Update status
            */

            //min payout met
            if($invoice->rowval('payable')->amount >= $invoice->merchantagreement()->paramval('min_payout')->map_value && $invoice->invoicestatus($invoice->id)->status == 'min_payout_not_met')
            {
                //Set to draft when minmapayout met
                $updatestatus_minpayout = Mainvoicestatus::where('mainvoice_id','=',$invoice->id)->update(array('invoicestatus_id' => '1'));
            }

            //if payoutdate changed
            if(strtotime($invoice->payout_date) > strtotime(date("Y-m-d 00:00:00")) && $invoice->invoicestatus($invoice->id)->status == 'tobepaid')
            {
                //if has any payment
                $check_payment = Mapayment::where('mainvoice_id','=', $invoice->id)->first();
                if($check_payment) //Set to partially paid
                {
                    $updatestatus_tobepaid = Mainvoicestatus::where('mainvoice_id','=',$invoice->id)->update(array('invoicestatus_id' => '4'));
                }
                else //Set to draft
                {
                    $updatestatus_tobepaid = Mainvoicestatus::where('mainvoice_id','=',$invoice->id)->update(array('invoicestatus_id' => '1'));
                }
            }
            //To be paid
            if(strtotime($invoice->payout_date) == strtotime(date("Y-m-d 00:00:00")) && $invoice->invoicestatus($invoice->id)->status != 'tobepaid')
            {
                //Set to tobepaid
                $updatestatus_tobepaid = Mainvoicestatus::where('mainvoice_id','=',$invoice->id)->update(array('invoicestatus_id' => '2'));
            }
            //Overdue
            if(strtotime($invoice->payout_date) < strtotime(date("Y-m-d 00:00:00")) && $invoice->invoicestatus($invoice->id)->status != 'overdue')
            {
                //Set to overdue
                $updatestatus_overdue = Mainvoicestatus::where('mainvoice_id','=',$invoice->id)->update(array('invoicestatus_id' => '6'));
            }

            // Redirect to the new invoice page
            return Redirect::to("admin/mainvoice/$invoiceId/edit")->with('success', Lang::get('admin/invoices/message.update.success'));
        }


        // Redirect to the invoice edit page
        return Redirect::to("admin/mainvoice/$invoiceId/edit")->with('error', Lang::get('admin/invoices/message.update.error'));
    }

    /**
     * Delete the given invoice.
     *
     * @param  int  $invoicetId
     * @return Redirect
     */



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
