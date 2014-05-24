<?php namespace Controllers\Admin;

use AdminController;
use Merchantagreement;
use Merchant;
use Mainvoice;
use Mainvoicestatus;
use Mapayment;
use Transactionorder;
use Transaction;
use View;
use Cache;
use DB;
use Lava;
use Sentry;

class DashboardController extends AdminController {

<<<<<<< HEAD
    /**
     * Show the administration dashboard page.
     *
     * @return View
     */
    public function getIndex()
    {
=======
	/**
	 * Show the administration dashboard page.
	 *
	 * @return View
	 */
	public function getIndex()
	{
>>>>>>> origin/develop

        ///Access Type

        $user = Sentry::getUser();

        $date_from = date("Y-m-d 00:00:00", (strtotime(date("Y-m-d")) - (3600*24*10) ) );
        $date_to = date("Y-m-d H:i:s");

        if($user->merchant_id>0)
        {
            $allcurrencies = Transactionorder::join('transaction','order.transaction_id','=','transaction.id')
                ->where('transaction.merchant_id','=',Sentry::getUser()->merchant_id)
                ->select('order.currency')
                ->distinct()
                ->get();
        }
        else{
            $allcurrencies = Transactionorder::select('currency')->distinct()->get();
        }

        /*-- =========================================
                        ADMIN GROUPS
        =========================================== --*/

        $usersgroups = $user->getGroups();

        if($user->inGroup(Sentry::getGroupProvider()->findByName('admin')) ||
<<<<<<< HEAD
            $user->inGroup(Sentry::getGroupProvider()->findByName('manager')) ||
            $user->inGroup(Sentry::getGroupProvider()->findByName('accounting')) ||
            $user->inGroup(Sentry::getGroupProvider()->findByName('fraud')) ||
            $user->inGroup(Sentry::getGroupProvider()->findByName('helpdesk')) ||
            $user->inGroup(Sentry::getGroupProvider()->findByName('users'))
        )
        {

            $total  = array();
            $paid   = array();
            $unpaid = array();

            update_cache();
            /////////////////////

            $new_invoices = Mainvoice::where('created_at','>=',date("Y-m-d"))
                ->orderBy('id','Desc')
                ->limit(3)
                ->get();

            $paid_invoices = Mainvoice::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mainvoice.id')
                ->where('mainvoicestatus.invoicestatus_id','=','3')
                ->orderBy('mainvoice.id','Desc')
                ->limit(3)
                ->get();

            $tobepaid = Mainvoice::where('payout_date','=',date("Y-m-d 00:00:00"))->orWhere('payout_date','=',date("Y-m-d 23:59:59"))
                ->orderBy('id')
                ->limit(3)
                ->get();
=======
           $user->inGroup(Sentry::getGroupProvider()->findByName('manager')) ||
           $user->inGroup(Sentry::getGroupProvider()->findByName('accounting')) ||
           $user->inGroup(Sentry::getGroupProvider()->findByName('fraud')) ||
           $user->inGroup(Sentry::getGroupProvider()->findByName('helpdesk')) ||
           $user->inGroup(Sentry::getGroupProvider()->findByName('users'))
        )
        {

        $total  = array();
        $paid   = array();
        $unpaid = array();

        update_cache();
        /////////////////////

        $new_invoices = Mainvoice::where('created_at','>=',date("Y-m-d"))
            ->orderBy('id','Desc')
            ->limit(3)
            ->get();

        $paid_invoices = Mainvoice::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mainvoice.id')
            ->where('mainvoicestatus.invoicestatus_id','=','3')
            ->orderBy('mainvoice.id','Desc')
            ->limit(3)
            ->get();

        $tobepaid = Mainvoice::where('payout_date','=',date("Y-m-d 00:00:00"))->orWhere('payout_date','=',date("Y-m-d 23:59:59"))
            ->orderBy('id')
            ->limit(3)
            ->get();
>>>>>>> origin/develop

            $totaltobepaid = Mainvoice::where('payout_date','=',date("Y-m-d 00:00:00"))->orWhere('payout_date','=',date("Y-m-d 23:59:59"))
                ->orderBy('id')
                ->get();

            $num_tobepaid = count($totaltobepaid); //Mainvoice::where('payout_date','=',date("Y-m-d 00:00:00"))->orWhere('payout_date','=',date("Y-m-d 23:59:59"))->count();

<<<<<<< HEAD
            $ppaid_invoices = Mainvoice::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mainvoice.id')
                ->where('mainvoicestatus.invoicestatus_id','=','4')
                ->orderBy('mainvoice.id','Desc')
                ->limit(3)
                ->get();

            foreach($ppaid_invoices as $ppi)
            {
                $paid[$ppi->mainvoice_id] = 0;

            }

            $overdue_invoices = Mainvoice::where('payout_date','<',date("Y-m-d H:i:s"))
                ->orderBy('id','asc')
                ->limit(3)
                ->get();
=======
        $ppaid_invoices = Mainvoice::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mainvoice.id')
            ->where('mainvoicestatus.invoicestatus_id','=','4')
            ->orderBy('mainvoice.id','Desc')
            ->limit(3)
            ->get();

        foreach($ppaid_invoices as $ppi)
        {
            $paid[$ppi->mainvoice_id] = 0;

        }

        $overdue_invoices = Mainvoice::where('payout_date','<',date("Y-m-d H:i:s"))
            ->orderBy('id','asc')
            ->limit(3)
            ->get();
>>>>>>> origin/develop

            $num_overdue = Mainvoice::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mainvoice.id')
                ->where('mainvoicestatus.invoicestatus_id','=','6')->count();

<<<<<<< HEAD
            $recentpayments = Mapayment::orderBy('id','DESC')->limit(5)->get();

            $payments = Mapayment::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mapayment.mainvoice_id')
                ->where('mainvoicestatus.invoicestatus_id','=','4')
                ->get();


            foreach($payments as $pay)
            {
                $total[$pay->mainvoice_id] = $pay->total_processed;
                $paid[$pay->mainvoice_id] += $pay->amount_processed;
                $unpaid[$pay->mainvoice_id] =  $total[$pay->mainvoice_id] - $paid[$pay->mainvoice_id];
            }
=======
        $recentpayments = Mapayment::orderBy('id','DESC')->limit(5)->get();

        $payments = Mapayment::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mapayment.mainvoice_id')
                    ->where('mainvoicestatus.invoicestatus_id','=','4')
                    ->get();


        foreach($payments as $pay)
        {
            $total[$pay->mainvoice_id] = $pay->total_processed;
            $paid[$pay->mainvoice_id] += $pay->amount_processed;
            $unpaid[$pay->mainvoice_id] =  $total[$pay->mainvoice_id] - $paid[$pay->mainvoice_id];
        }
>>>>>>> origin/develop

            /*-- =========================================================
                           Graphs per merchant agreement last 10 days
            ========================================================== --*/

<<<<<<< HEAD
            $date_from = date("Y-m-d", (strtotime(date("Y-m-d")) - (3600*24*10) ) );
            $date_to = date("Y-m-d");

            $allcurrencies = Transactionorder::select('currency')->distinct()->get();

            ////////Transactions

            //Number
            $transactions = $this->getTransactions($date_from, $date_to);

            $transactionTable = Lava::DataTable('Transactions');

            $transactionTable->addColumn('string', 'Merchant agreement');
            $transactionTable->addColumn('number', 'Transactions');

            foreach($transactions as $transaction)
            {
                foreach($transaction as $tr)
                {
                    $data_tr[0] = $tr->name;
                    $data_tr[1] = $tr->totaltransaction;

                    $transactionTable->addRow($data_tr);
                }
            }


            $config_transaction = array(
                'title' => 'Transactions per merchant agreement'
            );

            Lava::PieChart('Transactions')->setConfig($config_transaction);

            /**** Currency ****/
            foreach($allcurrencies as $currency)
            {
                //Amounts
                $transactions_amount = $this->getTransactionsamount($currency->currency, $date_from, $date_to);

                $transactionchart = 'transaction_'.$currency->currency;

                $transactionamountTable = Lava::DataTable($transactionchart);

                $transactionamountTable->addColumn('string', 'Merchant agreement');
                $transactionamountTable->addColumn('number', 'Transactions amount');

                foreach($transactions_amount as $transaction)
                {
                    foreach($transaction as $tr)
                    {
                        $data_tra[0] = $tr->name;
                        $data_tra[1] = $tr->totaltransaction;

                        $transactionamountTable->addRow($data_tra);
                    }
                }

                $config_transaction_amount = array(

                    'colors' => array('green'),
                    'hAxis' => Lava::hAxis(array(

                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontName' => 'Arial',
                                    'fontSize' => 10
                                )),
                            'slantedText' => TRUE,
                            'slantedTextAngle' => 30,


                        )),
                    'vAxis' => Lava::vAxis(array(
                            'baseline' => 5,

                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontName' => 'Arial',
                                    'fontSize' => 10
                                )),

                            'titleTextStyle' => Lava::textStyle(array(
                                    'color' => '#5C6DAB',
                                    'fontSize' => 14
                                )),
                        ))
                );

                Lava::ColumnChart($transactionchart)->setConfig($config_transaction_amount);
            }
            //

            ////////Refunds

            //Numbers
            $refunds = $this->getRefunds($date_from, $date_to);

            $refundsTable = Lava::DataTable('Refunds');

            $refundsTable->addColumn('string', 'Merchant agreement');
            $refundsTable->addColumn('number', 'Refunds');

            foreach($refunds as $ref)
            {
                foreach($ref as $refund)
                {
                    $data_ref[0] = $refund->name;
                    $data_ref[1] = $refund->totalrefunds;

                    $refundsTable->addRow($data_ref);
                }
            }


            $config_refunds = array(
=======
        $date_from = date("Y-m-d", (strtotime(date("Y-m-d")) - (3600*24*10) ) );
        $date_to = date("Y-m-d");

        $allcurrencies = Transactionorder::select('currency')->distinct()->get();

            ////////Transactions

        //Number
        $transactions = $this->getTransactions($date_from, $date_to);

        $transactionTable = Lava::DataTable('Transactions');

        $transactionTable->addColumn('string', 'Merchant agreement');
        $transactionTable->addColumn('number', 'Transactions');

        foreach($transactions as $transaction)
        {
            foreach($transaction as $tr)
            {
           $data_tr[0] = $tr->name;
           $data_tr[1] = $tr->totaltransaction;

           $transactionTable->addRow($data_tr);
            }
        }


        $config_transaction = array(
            'title' => 'Transactions per merchant agreement'
        );

        Lava::PieChart('Transactions')->setConfig($config_transaction);

        /**** Currency ****/
        foreach($allcurrencies as $currency)
        {
        //Amounts
        $transactions_amount = $this->getTransactionsamount($currency->currency, $date_from, $date_to);

        $transactionchart = 'transaction_'.$currency->currency;

        $transactionamountTable = Lava::DataTable($transactionchart);

        $transactionamountTable->addColumn('string', 'Merchant agreement');
        $transactionamountTable->addColumn('number', 'Transactions amount');

        foreach($transactions_amount as $transaction)
        {
            foreach($transaction as $tr)
            {
                $data_tra[0] = $tr->name;
                $data_tra[1] = $tr->totaltransaction;

                $transactionamountTable->addRow($data_tra);
            }
        }

        $config_transaction_amount = array(

            'colors' => array('green'),
            'hAxis' => Lava::hAxis(array(

                    'textPosition' => 'out',
                    'textStyle' => Lava::textStyle(array(
                            'color' => '#DDAA88',
                            'fontName' => 'Arial',
                            'fontSize' => 10
                        )),
                    'slantedText' => TRUE,
                    'slantedTextAngle' => 30,


                )),
            'vAxis' => Lava::vAxis(array(
                    'baseline' => 5,

                    'textPosition' => 'out',
                    'textStyle' => Lava::textStyle(array(
                            'color' => '#DDAA88',
                            'fontName' => 'Arial',
                            'fontSize' => 10
                        )),

                    'titleTextStyle' => Lava::textStyle(array(
                            'color' => '#5C6DAB',
                            'fontSize' => 14
                        )),
                ))
        );

        Lava::ColumnChart($transactionchart)->setConfig($config_transaction_amount);
        }
        //

            ////////Refunds

        //Numbers
        $refunds = $this->getRefunds($date_from, $date_to);

        $refundsTable = Lava::DataTable('Refunds');

        $refundsTable->addColumn('string', 'Merchant agreement');
        $refundsTable->addColumn('number', 'Refunds');

        foreach($refunds as $ref)
        {
            foreach($ref as $refund)
            {
                $data_ref[0] = $refund->name;
                $data_ref[1] = $refund->totalrefunds;

                $refundsTable->addRow($data_ref);
            }
        }


        $config_refunds = array(

            'colors' => array('darkred'),
            'hAxis' => Lava::hAxis(array(
                    'baselineColor' => '#fc32b0',


                    'textPosition' => 'out',
                    'textStyle' => Lava::textStyle(array(
                            'color' => '#DDAA88',
                            'fontSize' => 10
                        )),
                    'slantedText' => TRUE,
                    'slantedTextAngle' => 30,


                )),
            'vAxis' => Lava::vAxis(array(

                    'textPosition' => 'out',
                    'textStyle' => Lava::textStyle(array(
                            'color' => '#DDAA88',
                            'fontName' => 'Arial Bold',
                            'fontSize' => 10
                        )),

                    'titleTextStyle' => Lava::textStyle(array(
                            'color' => '#5C6DAB',
                            'fontSize' => 14
                        )),
                ))
        );

        Lava::ColumnChart('Refunds')->setConfig($config_refunds);

        //Amounts

            ////////**** Currency ****////////

        foreach($allcurrencies as $currency)
        {
            //////Amounts
            $refunds_amount = $this->getRefundsamount($currency->currency, $date_from, $date_to);

            $refundschart = 'refunds_'.$currency->currency;

            $refundsamountTable = Lava::DataTable($refundschart);

            $refundsamountTable->addColumn('string', 'Merchant agreement');
            $refundsamountTable->addColumn('number', 'Refunds amount');

            foreach($refunds_amount as $refamount)
            {
                foreach($refamount as $refa)
                {
                    $data_refa[0] = $refa->name;
                    $data_refa[1] = $refa->totalrefunds;

                    $refundsamountTable->addRow($data_refa);
                }
            }

            $config_refunds_amount = array(
>>>>>>> origin/develop

                'colors' => array('darkred'),
                'hAxis' => Lava::hAxis(array(
                        'baselineColor' => '#fc32b0',


                        'textPosition' => 'out',
                        'textStyle' => Lava::textStyle(array(
                                'color' => '#DDAA88',
                                'fontSize' => 10
                            )),
                        'slantedText' => TRUE,
                        'slantedTextAngle' => 30,


                    )),
                'vAxis' => Lava::vAxis(array(

                        'textPosition' => 'out',
                        'textStyle' => Lava::textStyle(array(
                                'color' => '#DDAA88',
                                'fontName' => 'Arial Bold',
                                'fontSize' => 10
                            )),

                        'titleTextStyle' => Lava::textStyle(array(
                                'color' => '#5C6DAB',
                                'fontSize' => 14
                            )),
                    ))
            );
<<<<<<< HEAD

            Lava::ColumnChart('Refunds')->setConfig($config_refunds);

            //Amounts

            ////////**** Currency ****////////

            foreach($allcurrencies as $currency)
            {
                //////Amounts
                $refunds_amount = $this->getRefundsamount($currency->currency, $date_from, $date_to);

                $refundschart = 'refunds_'.$currency->currency;

                $refundsamountTable = Lava::DataTable($refundschart);

                $refundsamountTable->addColumn('string', 'Merchant agreement');
                $refundsamountTable->addColumn('number', 'Refunds amount');

                foreach($refunds_amount as $refamount)
                {
                    foreach($refamount as $refa)
                    {
                        $data_refa[0] = $refa->name;
                        $data_refa[1] = $refa->totalrefunds;

                        $refundsamountTable->addRow($data_refa);
                    }
                }

                $config_refunds_amount = array(

                    'colors' => array('darkred'),
                    'hAxis' => Lava::hAxis(array(
                            'baselineColor' => '#fc32b0',


                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontSize' => 10
                                )),
                            'slantedText' => TRUE,
                            'slantedTextAngle' => 30,


                        )),
                    'vAxis' => Lava::vAxis(array(

                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontName' => 'Arial Bold',
                                    'fontSize' => 10
                                )),

                            'titleTextStyle' => Lava::textStyle(array(
                                    'color' => '#5C6DAB',
                                    'fontSize' => 14
                                )),
                        ))
                );
                Lava::ColumnChart($refundschart)->setConfig($config_refunds_amount);
            }
            //

            ////////Chargebacks

            //Numbers
            $chbs = $this->getChargebacks($date_from, $date_to);

            $chbTable = Lava::DataTable('Chargebacks');

            $chbTable->addColumn('string', 'Merchant agreement');
            $chbTable->addColumn('number', 'CHB');

            foreach($chbs as $chargeback)
            {
                foreach($chargeback as $chb)
                {
                    $data_chb[0] = $chb->name;
                    $data_chb[1] = $chb->totalchb;

                    $chbTable->addRow($data_chb);
                }
            }


            $config_chb = array(
                'colors' => array('teal'),
            );

            Lava::AreaChart('Chargebacks')->setConfig($config_chb);


            //Amounts
            /**** Currency ****/
            foreach($allcurrencies as $currency)
            {
                $chb_amount = $this->getChargebacksamount($currency->currency, $date_from, $date_to);

                $chbchart = 'chb_'.$currency->currency;

                $chbamountTable = Lava::DataTable($chbchart);

                $chbamountTable->addColumn('string', 'Merchant agreement');
                $chbamountTable->addColumn('number', 'CHB amount');

                foreach($chb_amount as $chbamount)
                {
                    foreach($chbamount as $chba)
                    {
                        $data_chba[0] = $chba->name;
                        $data_chba[1] = $chba->totalchb;

                        $chbamountTable->addRow($data_chba);
                    }
                }



                $config_chb_amount = array(

                    'colors' => array('teal'),
                    'hAxis' => Lava::hAxis(array(
                            'baselineColor' => '#fc32b0',


                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontSize' => 10
                                )),
                            'slantedText' => TRUE,
                            'slantedTextAngle' => 30,


                        )),
                    'vAxis' => Lava::vAxis(array(

                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontName' => 'Arial Bold',
                                    'fontName' => 'Arial Bold',
                                    'fontSize' => 10
                                )),

                            'titleTextStyle' => Lava::textStyle(array(
                                    'color' => '#5C6DAB',
                                    'fontSize' => 14
                                )),
                        ))
                );
                Lava::AreaChart($chbchart)->setConfig($config_chb_amount);
            }
            //Currency
=======
            Lava::ColumnChart($refundschart)->setConfig($config_refunds_amount);
        }
        //

        ////////Chargebacks

        //Numbers
        $chbs = $this->getChargebacks($date_from, $date_to);

        $chbTable = Lava::DataTable('Chargebacks');

        $chbTable->addColumn('string', 'Merchant agreement');
        $chbTable->addColumn('number', 'CHB');

        foreach($chbs as $chargeback)
        {
            foreach($chargeback as $chb)
            {
                $data_chb[0] = $chb->name;
                $data_chb[1] = $chb->totalchb;

                $chbTable->addRow($data_chb);
            }
        }


        $config_chb = array(
            'colors' => array('teal'),
        );

        Lava::AreaChart('Chargebacks')->setConfig($config_chb);


        //Amounts
            /**** Currency ****/
        foreach($allcurrencies as $currency)
        {
            $chb_amount = $this->getChargebacksamount($currency->currency, $date_from, $date_to);

            $chbchart = 'chb_'.$currency->currency;

            $chbamountTable = Lava::DataTable($chbchart);

            $chbamountTable->addColumn('string', 'Merchant agreement');
            $chbamountTable->addColumn('number', 'CHB amount');

            foreach($chb_amount as $chbamount)
            {
                foreach($chbamount as $chba)
                {
                    $data_chba[0] = $chba->name;
                    $data_chba[1] = $chba->totalchb;

                    $chbamountTable->addRow($data_chba);
                }
            }



            $config_chb_amount = array(

                'colors' => array('teal'),
                'hAxis' => Lava::hAxis(array(
                        'baselineColor' => '#fc32b0',


                        'textPosition' => 'out',
                        'textStyle' => Lava::textStyle(array(
                                'color' => '#DDAA88',
                                'fontSize' => 10
                            )),
                        'slantedText' => TRUE,
                        'slantedTextAngle' => 30,


                    )),
                'vAxis' => Lava::vAxis(array(

                        'textPosition' => 'out',
                        'textStyle' => Lava::textStyle(array(
                                'color' => '#DDAA88',
                                'fontName' => 'Arial Bold',
                                'fontName' => 'Arial Bold',
                                'fontSize' => 10
                            )),

                        'titleTextStyle' => Lava::textStyle(array(
                                'color' => '#5C6DAB',
                                'fontSize' => 14
                            )),
                    ))
            );
            Lava::AreaChart($chbchart)->setConfig($config_chb_amount);
        }
        //Currency
>>>>>>> origin/develop



            /*-- =========================================================
                          Graphs Daily Values last 10 days
           ========================================================== --*/




<<<<<<< HEAD
            // Show the View
            $view =  View::make('backend/dashboard',array(
                'paid_invoices' => $paid_invoices,
                'overdue_invoices' => $overdue_invoices,
                'payments' => $payments,
                'ppaid_invoices' => $ppaid_invoices,
                'new_invoices' => $new_invoices,
                'paid' => $paid,
                'unpaid' => $unpaid,
                'tobepaid' => $tobepaid,
                'total' => $total,
                'recentpayments' => $recentpayments,
                'allcurrencies' => $allcurrencies,
                'transactions' => $transactions,
                'refunds' => $refunds,
                'chbs' => $chbs,
                'num_tobepaid' => $num_tobepaid,
                'num_overdue' => $num_overdue

            ));

            return $view;

        } //Admin Groups
=======
		// Show the View
		$view =  View::make('backend/dashboard',array(
            'paid_invoices' => $paid_invoices,
            'overdue_invoices' => $overdue_invoices,
            'payments' => $payments,
            'ppaid_invoices' => $ppaid_invoices,
            'new_invoices' => $new_invoices,
            'paid' => $paid,
            'unpaid' => $unpaid,
            'tobepaid' => $tobepaid,
            'total' => $total,
            'recentpayments' => $recentpayments,
            'allcurrencies' => $allcurrencies,
            'transactions' => $transactions,
            'refunds' => $refunds,
            'chbs' => $chbs,
            'num_tobepaid' => $num_tobepaid,
            'num_overdue' => $num_overdue

        ));

            return $view;

	} //Admin Groups
>>>>>>> origin/develop

        /*-- =========================================
                        MERCHANT GROUPS
        =========================================== --*/
        else
        {
            /////GET Merchant

            $user = Sentry::getUser();


            if($user->merchant_id >0)
            {
                $ma = Merchantagreement::where('merchant_id','=',$user->merchant_id)->get();

            }
            else
<<<<<<< HEAD
                if($user->merchantagreement_id >0)
                {
                    $ma = Merchantagreement::where('id','=',$user->merchantagreement_id)->get();


                }
=======
            if($user->merchantagreement_id >0)
            {
                $ma = Merchantagreement::where('id','=',$user->merchantagreement_id)->get();


            }
>>>>>>> origin/develop

            $agreements = array();

            foreach($ma as $ma_ids)
            {
                $agreements[$ma_ids->id] = $ma_ids->id;

            }


            $total  = array();
            $paid   = array();
            $unpaid = array();

            update_cache();

            $new_invoices = Mainvoice::where('created_at','>=',date("Y-m-d"))
                ->whereIn('merchantagreement_id',$agreements)
                ->orderBy('id','Desc')
                ->limit(3)
                ->get();

            /*$paid_invoices = Mainvoice::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mainvoice.id')
                ->whereIn('mainvoice.merchantagreement_id',$agreements)
                ->where('mainvoicestatus.invoicestatus_id','=','3')
                ->orderBy('mainvoice.id','Desc')
                ->limit(3)
                ->get();*/

            $paid_invoices = Mainvoice::bystatus(3)->whereIn('mainvoice.merchantagreement_id',$agreements)->limit(3)->get();

            $tobepaid = Mainvoice::where('payout_date','=',date("Y-m-d 00:00:00"))->orWhere('payout_date','=',date("Y-m-d 23:59:59"))
                ->whereIn('mainvoice.merchantagreement_id',$agreements)
                ->orderBy('id')
                ->limit(3)
                ->get();

            //$tobepaid = Mainvoice::bystatus(2)->whereIn('mainvoice.merchantagreement_id',$agreements)->limit(3)->get();


<<<<<<< HEAD
            /*   $num_tobepaid = Mainvoice::where('payout_date','=',date("Y-m-d 00:00:00"))->orWhere('payout_date','=',date("Y-m-d 23:59:59"))
                   ->whereIn('mainvoice.merchantagreement_id',$agreements)->count();*/
=======
         /*   $num_tobepaid = Mainvoice::where('payout_date','=',date("Y-m-d 00:00:00"))->orWhere('payout_date','=',date("Y-m-d 23:59:59"))
                ->whereIn('mainvoice.merchantagreement_id',$agreements)->count();*/
>>>>>>> origin/develop




            $ppaid_invoices = Mainvoice::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mainvoice.id')
                ->whereIn('mainvoice.merchantagreement_id',$agreements)
                ->where('mainvoicestatus.invoicestatus_id','=','4')
                ->orderBy('mainvoice.id','Desc')
                ->limit(3)
                ->get();

<<<<<<< HEAD
            // $ppaid_invoices = Mainvoice::bystatus(4)->bymerchant(Sentry::getUser()->merchant_id)->limit(3)->get();
=======
           // $ppaid_invoices = Mainvoice::bystatus(4)->bymerchant(Sentry::getUser()->merchant_id)->limit(3)->get();
>>>>>>> origin/develop

            foreach($ppaid_invoices as $ppi)
            {
                $paid[$ppi->mainvoice_id] = 0;

            }

            $overdue_invoices = Mainvoice::where('payout_date','<',date("Y-m-d H:i:s"))
                ->whereIn('mainvoice.merchantagreement_id',$agreements)
                ->orderBy('id','asc')
                ->limit(3)
                ->get();


            $recentpayments = Mapayment::join('mainvoice','mainvoice.id','=','mapayment.mainvoice_id')
                ->whereIn('mainvoice.merchantagreement_id',$agreements)
                ->orderBy('mapayment.id','DESC')->limit(5)->get();

            $payments = Mapayment::join('mainvoicestatus','mainvoicestatus.mainvoice_id','=','mapayment.mainvoice_id')
                ->join('mainvoice','mainvoice.id','=','mapayment.mainvoice_id')
                ->whereIn('mainvoice.merchantagreement_id',$agreements)
                ->where('mainvoicestatus.invoicestatus_id','=','4')
                ->get();


            foreach($payments as $pay)
            {
                $total[$pay->mainvoice_id] = $pay->total_processed;
                $paid[$pay->mainvoice_id] += $pay->amount_processed;
                $unpaid[$pay->mainvoice_id] =  $total[$pay->mainvoice_id] - $paid[$pay->mainvoice_id];
            }

            /*-- ======================================================================
                           MERCHANT Values per merchant agreement Graphs last 10 days
            ========================================================================== --*/
<<<<<<< HEAD
            /**************************************
            Daily values
             **************************************/
=======
                /**************************************
                               Daily values
                **************************************/
>>>>>>> origin/develop

            //////Transactions

            //Number
            $transactions = $this->getDailyTransactions($date_from, $date_to, $user->merchant_id, null);

            $transactionTable = Lava::DataTable('Transactions');

            $transactionTable->addColumn('string', 'Date');
            $transactionTable->addColumn('number', 'Transactions');

            foreach($transactions as $transaction)
            {
                foreach($transaction as $tr)
                {
                    $data_tr[0] = date('d M, Y', strtotime($tr->transactiondate));
                    $data_tr[1] = $tr->totaltransaction;

                    $transactionTable->addRow($data_tr);
                }
            }


            $config_transaction = array(
                'title' => 'Daily Transactions',
                'colors' => array('green'),
            );

            Lava::AreaChart('Transactions')->setConfig($config_transaction);

            /**** Currency ****/
            foreach($allcurrencies as $currency)
            {
                //Amounts
                $transactions_amount = $this->getDailyTransactionsamount($currency->currency, $date_from, $date_to, $user->merchant_id, null);

                $transactionchart = 'transaction_'.$currency->currency;

                $transactionamountTable = Lava::DataTable($transactionchart);

                $transactionamountTable->addColumn('string', 'Days');
                $transactionamountTable->addColumn('number', 'Transactions amount');

                foreach($transactions_amount as $transaction)
                {
                    foreach($transaction as $tr)
                    {
                        $data_tra[0] = date('d M, Y', strtotime($tr->transactiondate));
                        $data_tra[1] = $tr->totaltransaction;

                        $transactionamountTable->addRow($data_tra);
                    }
                }

                $config_transaction_amount = array(

                    'colors' => array('green'),
                    'hAxis' => Lava::hAxis(array(

                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontName' => 'Arial',
                                    'fontSize' => 10
                                )),
                            'slantedText' => TRUE,
                            'slantedTextAngle' => 30,


                        )),
                    'vAxis' => Lava::vAxis(array(
                            'baseline' => 5,

                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontName' => 'Arial',
                                    'fontSize' => 10
                                )),

                            'titleTextStyle' => Lava::textStyle(array(
                                    'color' => '#5C6DAB',
                                    'fontSize' => 14
                                )),
                        ))
                );

                Lava::AreaChart($transactionchart)->setConfig($config_transaction_amount);
            }
            //

            //////Refunds

            //Numbers
            $refunds = $this->getDailyRefunds($date_from, $date_to, $user->merchant_id, null);

            $refundsTable = Lava::DataTable('Refunds');

            $refundsTable->addColumn('string', 'Days');
            $refundsTable->addColumn('number', 'Refunds');

            foreach($refunds as $ref)
            {
                foreach($ref as $refund)
                {
                    $data_ref[0] = date('d M, Y', strtotime($refund->refunddate));
                    $data_ref[1] = $refund->totalrefunds;

                    $refundsTable->addRow($data_ref);
                }
            }


            $config_refunds = array(

                'colors' => array('darkred'),
                'hAxis' => Lava::hAxis(array(
                        'baselineColor' => '#fc32b0',


                        'textPosition' => 'out',
                        'textStyle' => Lava::textStyle(array(
                                'color' => '#DDAA88',
                                'fontSize' => 10
                            )),
                        'slantedText' => TRUE,
                        'slantedTextAngle' => 30,


                    )),
                'vAxis' => Lava::vAxis(array(

                        'textPosition' => 'out',
                        'textStyle' => Lava::textStyle(array(
                                'color' => '#DDAA88',
                                'fontName' => 'Arial Bold',
                                'fontSize' => 10
                            )),

                        'titleTextStyle' => Lava::textStyle(array(
                                'color' => '#5C6DAB',
                                'fontSize' => 14
                            )),
                    ))
            );

            Lava::ColumnChart('Refunds')->setConfig($config_refunds);

            //Amounts
            /**** Currency ****/
            foreach($allcurrencies as $currency)
            {
                //////Amounts
                $refunds_amount = $this->getDailyRefundsamount($currency->currency, $date_from, $date_to, $user->merchant_id, null);

                $refundschart = 'refunds_'.$currency->currency;

                $refundsamountTable = Lava::DataTable($refundschart);

                $refundsamountTable->addColumn('string', 'Days');
                $refundsamountTable->addColumn('number', 'Refunds amount');

                foreach($refunds_amount as $refamount)
                {
                    foreach($refamount as $refa)
                    {
                        $data_refa[0] = date('d M, Y', strtotime($refa->refunddate));
                        $data_refa[1] = $refa->totalrefunds;

                        $refundsamountTable->addRow($data_refa);
                    }
                }

                $config_refunds_amount = array(

                    'colors' => array('darkred'),
                    'hAxis' => Lava::hAxis(array(
                            'baselineColor' => '#fc32b0',


                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontSize' => 10
                                )),
                            'slantedText' => TRUE,
                            'slantedTextAngle' => 30,


                        )),
                    'vAxis' => Lava::vAxis(array(

                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontName' => 'Arial Bold',
                                    'fontSize' => 10
                                )),

                            'titleTextStyle' => Lava::textStyle(array(
                                    'color' => '#5C6DAB',
                                    'fontSize' => 14
                                )),
                        ))
                );
                Lava::ColumnChart($refundschart)->setConfig($config_refunds_amount);
            }
            //

            //////Chargebacks

            //Numbers
            $chbs = $this->getDailyChargebacks($date_from, $date_to, $user->merchant_id, null);

            $chbTable = Lava::DataTable('Chargebacks');

            $chbTable->addColumn('string', 'Days');
            $chbTable->addColumn('number', 'CHB');

            foreach($chbs as $chargeback)
            {
                foreach($chargeback as $chb)
                {
                    $data_chb[0] = date("d M, Y", strtotime($chb->chbdate));
                    $data_chb[1] = $chb->totalchb;

                    $chbTable->addRow($data_chb);
                }
            }


            $config_chb = array(
                'colors' => array('teal'),
            );

            Lava::ColumnChart('Chargebacks')->setConfig($config_chb);


            //Amounts
            /**** Currency ****/
            foreach($allcurrencies as $currency)
            {
                $chb_amount = $this->getDailyChargebacksamount($currency->currency, $date_from, $date_to, $user->merchant_id, null);

                $chbchart = 'chb_'.$currency->currency;

                $chbamountTable = Lava::DataTable($chbchart);

                $chbamountTable->addColumn('string', 'Days');
                $chbamountTable->addColumn('number', 'CHB amount');

                foreach($chb_amount as $chbamount)
                {
                    foreach($chbamount as $chba)
                    {
                        $data_chba[0] = date("d M, Y", strtotime($chba->chbdate));
                        $data_chba[1] = $chba->totalchb;

                        $chbamountTable->addRow($data_chba);
                    }
                }



                $config_chb_amount = array(

                    'colors' => array('teal'),
                    'hAxis' => Lava::hAxis(array(
                            'baselineColor' => '#fc32b0',


                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontSize' => 10
                                )),
                            'slantedText' => TRUE,
                            'slantedTextAngle' => 30,


                        )),
                    'vAxis' => Lava::vAxis(array(

                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontName' => 'Arial Bold',
                                    'fontSize' => 10
                                )),

                            'titleTextStyle' => Lava::textStyle(array(
                                    'color' => '#5C6DAB',
                                    'fontSize' => 14
                                )),
                        ))
                );
                Lava::AreaChart($chbchart)->setConfig($config_chb_amount);
            }
            //Currency







            /*-- =========================================
                       Per merchant agreement
           =========================================== --*/

            if(count($agreements)>1)
            {

                /////Transactions

                //Number
                $transactions = $this->getTransactions($date_from, $date_to, $user->merchant_id, null);

                $transactionTable = Lava::DataTable('matransaction');

                $transactionTable->addColumn('string', 'Mecrhant Agreement');
                $transactionTable->addColumn('number', 'Transactions');

                foreach($transactions as $transaction)
                {
                    foreach($transaction as $tr)
                    {
                        $data_tr[0] = $tr->name;
                        $data_tr[1] = $tr->totaltransaction;

                        $transactionTable->addRow($data_tr);
                    }
                }


                $config_transaction = array(
                    'title' => 'Transactions',
                    'colors' => array('green'),
                );

                Lava::AreaChart('matransaction')->setConfig($config_transaction);

                /**** Currency ****/
                foreach($allcurrencies as $currency)
                {
                    //Amounts
                    $transactions_amount = $this->getTransactionsamount($currency->currency, $date_from, $date_to, $user->merchant_id, null);

                    $transactionchart = 'matransaction_'.$currency->currency;

                    $transactionamountTable = Lava::DataTable($transactionchart);

                    $transactionamountTable->addColumn('string', 'Merchant Agreement');
                    $transactionamountTable->addColumn('number', 'Transactions amount');

                    foreach($transactions_amount as $transaction)
                    {
                        foreach($transaction as $tr)
                        {
                            $data_tra[0] = $tr->name;
                            $data_tra[1] = $tr->totaltransaction;

                            $transactionamountTable->addRow($data_tra);
                        }
                    }

                    $config_transaction_amount = array(

                        'colors' => array('green'),
                        'hAxis' => Lava::hAxis(array(

                                'textPosition' => 'out',
                                'textStyle' => Lava::textStyle(array(
                                        'color' => '#DDAA88',
                                        'fontName' => 'Arial',
                                        'fontSize' => 10
                                    )),
                                'slantedText' => TRUE,
                                'slantedTextAngle' => 30,


                            )),
                        'vAxis' => Lava::vAxis(array(
                                'baseline' => 5,

                                'textPosition' => 'out',
                                'textStyle' => Lava::textStyle(array(
                                        'color' => '#DDAA88',
                                        'fontName' => 'Arial',
                                        'fontSize' => 10
                                    )),

                                'titleTextStyle' => Lava::textStyle(array(
                                        'color' => '#5C6DAB',
                                        'fontSize' => 14
                                    )),
                            ))
                    );

                    Lava::AreaChart($transactionchart)->setConfig($config_transaction_amount);
                }
                //

                ////////Refunds

                //Numbers
                $refunds = $this->getRefunds($date_from, $date_to, $user->merchant_id, null);

                $refundsTable = Lava::DataTable('marefund');

                $refundsTable->addColumn('string', 'Merchant agreement');
                $refundsTable->addColumn('number', 'Refunds');

                foreach($refunds as $ref)
                {
                    foreach($ref as $refund)
                    {
                        $data_ref[0] = $refund->name;
                        $data_ref[1] = $refund->totalrefunds;

                        $refundsTable->addRow($data_ref);
                    }
                }


                $config_refunds = array(

                    'colors' => array('darkred'),
                    'hAxis' => Lava::hAxis(array(
                            'baselineColor' => '#fc32b0',


                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontSize' => 10
                                )),
                            'slantedText' => TRUE,
                            'slantedTextAngle' => 30,


                        )),
                    'vAxis' => Lava::vAxis(array(

                            'textPosition' => 'out',
                            'textStyle' => Lava::textStyle(array(
                                    'color' => '#DDAA88',
                                    'fontName' => 'Arial Bold',
                                    'fontSize' => 10
                                )),

                            'titleTextStyle' => Lava::textStyle(array(
                                    'color' => '#5C6DAB',
                                    'fontSize' => 14
                                )),
                        ))
                );

                Lava::ColumnChart('marefund')->setConfig($config_refunds);

                //Amounts

                ////////**** Currency ****////////

                foreach($allcurrencies as $currency)
                {
                    //////Amounts
                    $refunds_amount = $this->getRefundsamount($currency->currency, $date_from, $date_to, $user->merchant_id, null);

                    $refundschart = 'marefund_'.$currency->currency;

                    $refundsamountTable = Lava::DataTable($refundschart);

                    $refundsamountTable->addColumn('string', 'Merchant agreement');
                    $refundsamountTable->addColumn('number', 'Refunds amount');

                    foreach($refunds_amount as $refamount)
                    {
                        foreach($refamount as $refa)
                        {
                            $data_refa[0] = $refa->name;
                            $data_refa[1] = $refa->totalrefunds;

                            $refundsamountTable->addRow($data_refa);
                        }
                    }

                    $config_refunds_amount = array(

                        'colors' => array('darkred'),
                        'hAxis' => Lava::hAxis(array(
                                'baselineColor' => '#fc32b0',


                                'textPosition' => 'out',
                                'textStyle' => Lava::textStyle(array(
                                        'color' => '#DDAA88',
                                        'fontSize' => 10
                                    )),
                                'slantedText' => TRUE,
                                'slantedTextAngle' => 30,


                            )),
                        'vAxis' => Lava::vAxis(array(

                                'textPosition' => 'out',
                                'textStyle' => Lava::textStyle(array(
                                        'color' => '#DDAA88',
                                        'fontName' => 'Arial Bold',
                                        'fontSize' => 10
                                    )),

                                'titleTextStyle' => Lava::textStyle(array(
                                        'color' => '#5C6DAB',
                                        'fontSize' => 14
                                    )),
                            ))
                    );
                    Lava::ColumnChart($refundschart)->setConfig($config_refunds_amount);
                }
                //

                ////////Chargebacks

                //Numbers
                $chbs = $this->getChargebacks($date_from, $date_to, $user->merchant_id, null);

                $chbTable = Lava::DataTable('machb');

                $chbTable->addColumn('string', 'Merchant agreement');
                $chbTable->addColumn('number', 'CHB');

                foreach($chbs as $chargeback)
                {
                    foreach($chargeback as $chb)
                    {
                        $data_chb[0] = $chb->name;
                        $data_chb[1] = $chb->totalchb;

                        $chbTable->addRow($data_chb);
                    }
                }


                $config_chb = array(
                    'colors' => array('teal'),
                );

                Lava::AreaChart('machb')->setConfig($config_chb);


                //Amounts
                /**** Currency ****/
                foreach($allcurrencies as $currency)
                {
                    $chb_amount = $this->getChargebacksamount($currency->currency, $date_from, $date_to, $user->merchant_id, null);

                    $chbchart = 'machb_'.$currency->currency;

                    $chbamountTable = Lava::DataTable($chbchart);

                    $chbamountTable->addColumn('string', 'Merchant agreement');
                    $chbamountTable->addColumn('number', 'CHB amount');

                    foreach($chb_amount as $chbamount)
                    {
                        foreach($chbamount as $chba)
                        {
                            $data_chba[0] = $chba->name;
                            $data_chba[1] = $chba->totalchb;

                            $chbamountTable->addRow($data_chba);
                        }
                    }



                    $config_chb_amount = array(

                        'colors' => array('teal'),
                        'hAxis' => Lava::hAxis(array(
                                'baselineColor' => '#fc32b0',


                                'textPosition' => 'out',
                                'textStyle' => Lava::textStyle(array(
                                        'color' => '#DDAA88',
                                        'fontSize' => 10
                                    )),
                                'slantedText' => TRUE,
                                'slantedTextAngle' => 30,


                            )),
                        'vAxis' => Lava::vAxis(array(

                                'textPosition' => 'out',
                                'textStyle' => Lava::textStyle(array(
                                        'color' => '#DDAA88',
                                        'fontName' => 'Arial Bold',
                                        'fontSize' => 10
                                    )),

                                'titleTextStyle' => Lava::textStyle(array(
                                        'color' => '#5C6DAB',
                                        'fontSize' => 14
                                    )),
                            ))
                    );
                    Lava::AreaChart($chbchart)->setConfig($config_chb_amount);
                }
                //Currency

            }

            // Show the View
            $view =  View::make('backend/dashboard/merchant',array(
                'paid_invoices' => $paid_invoices,
                'overdue_invoices' => $overdue_invoices,
                'payments' => $payments,
                'ppaid_invoices' => $ppaid_invoices,
                'new_invoices' => $new_invoices,
                'paid' => $paid,
                'unpaid' => $unpaid,
                'tobepaid' => $tobepaid,
                'total' => $total,
                'recentpayments' => $recentpayments,
                'allcurrencies' => $allcurrencies,
                'transactions' => $transactions,
                'refunds' => $refunds,
                'chbs' => $chbs,
                'agreements' => $agreements,


            ));

            return $view;
        }
        //End merchant Groups

    }


    /*-- ==========================================================================================
                 Merchant Daily Values  per merchant agreement
   ============================================================================================ --*/


    public function getTransactions($date_from, $date_to, $merchantID = null, $merchantagreementID = null)
    {
        /*@todo: Replace with Json data from Shop */



        $query = 'SELECT merchantagreement.name, COUNT(DISTINCT "order".id) as totaltransaction FROM "order"
                  JOIN transaction ON transaction.id = "order".transaction_id
                  JOIN merchantagreement ON merchantagreement.merchant_id = transaction.merchant_id
                  WHERE "order".datetimecreated >= '."'".$date_from."'".'
                  AND "order".datetimecreated <= '."'".$date_to."'".'
                  ';

        if(!is_null($merchantID))
        {

            $query .= ' AND transaction.merchant_id = '. $merchantID.'';
        }


        if(!is_null($merchantagreementID))
        {
            $query .= ' AND merchantagreement.id = '.$merchantagreementID.' ';
        }
<<<<<<< HEAD
        $query .= 'GROUP BY name';
=======
            $query .= 'GROUP BY name';
>>>>>>> origin/develop


        $transactions = DB::select(DB::raw($query));


        return array(
            'transactions' => $transactions
        );

    }


    public function getTransactionsamount($currency, $date_from, $date_to, $merchantID=null, $merchantagreementID=null)
    {
        $query_amount = 'SELECT merchantagreement.name, SUM("order".amount) as totaltransaction FROM "order"
                  JOIN transaction ON transaction.id = "order".transaction_id
                  JOIN merchantagreement ON merchantagreement.merchant_id = transaction.merchant_id
                  WHERE "order".datetimecreated >= '."'".$date_from."'".'
                  AND "order".datetimecreated <= '."'".$date_to."'".'
                  AND "order".currency = '."'".$currency."'".'
                  ';

        if(!is_null($merchantID))
        {
            $query_amount .= '  AND transaction.merchant_id = '. $merchantID.'
                 ';
        }

        if(!is_null($merchantagreementID))
        {
            $query_amount .= '  AND merchantagreement.id = '. $merchantagreementID.'
                 ';
        }
<<<<<<< HEAD
        $query_amount .= ' GROUP BY merchantagreement.name';
=======
            $query_amount .= ' GROUP BY merchantagreement.id';
>>>>>>> origin/develop

        $transactions_amount = DB::select(DB::raw($query_amount));



        return array(
            'transactions_amount' => $transactions_amount
        );

    }


<<<<<<< HEAD
    /* public function getRedemptions($merchantID, $date_from, $date_to)
     {
         ///@todo: Replace with data from voucher
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

     }*/
=======
   /* public function getRedemptions($merchantID, $date_from, $date_to)
    {
        ///@todo: Replace with data from voucher
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

    }*/
>>>>>>> origin/develop


    public function getRefunds($date_from, $date_to, $merchantID=null, $merchantagreementID=null)
    {
        /*@todo: Replace with data from voucher */

        $query = 'SELECT merchantagreement.name, COUNT(voucher_event.id) as totalrefunds FROM voucher_event
                  JOIN merchantagreement ON merchantagreement.merchant_id = voucher_event.merchant_id
                  WHERE voucher_event.datetimecreated >= '."'".$date_from."'".'
                  AND voucher_event.datetimecreated <= '."'".$date_to."'".'
                  AND voucher_event.event_id = 490
                  ';

        if(!is_null($merchantID))
        {
            $query .= '  AND merchantagreement.merchant_id = '. $merchantID.'
                ';
        }

        if(!is_null($merchantagreementID))
        {
            $query .= '  AND merchantagreement.id = '. $merchantagreementID.'
                ';
        }
<<<<<<< HEAD
        $query .= 'GROUP BY merchantagreement.name';
=======
            $query .= 'GROUP BY merchantagreement.id';
>>>>>>> origin/develop


        $refunds = DB::select(DB::raw($query));

        return array(
            'refunds'    => $refunds
        );

    }

    public function getRefundsamount($currency, $date_from, $date_to, $merchantID=null, $merchantagreementID=null)
    {

        $query_amount = 'SELECT merchantagreement.name, SUM(voucher_event.amount) as totalrefunds FROM voucher_event
                  JOIN merchantagreement ON merchantagreement.merchant_id = voucher_event.merchant_id
                  WHERE voucher_event.datetimecreated >= '."'".$date_from."'".'
                  AND voucher_event.datetimecreated <= '."'".$date_to."'".'
                  AND voucher_event.event_id = 490
                  AND voucher_event.currency = '."'".$currency."'".'
                  ';

        if(!is_null($merchantID))
        {

            $query_amount .= '  AND merchantagreement.merchant_id = '. $merchantID.'
                  ';
        }

        if(!is_null($merchantagreementID))
        {

            $query_amount .= '  AND merchantagreement.merchant_id = '. $merchantagreementID.'
                  ';
        }
<<<<<<< HEAD
        $query_amount .= ' GROUP BY merchantagreement.name';
=======
            $query_amount .= ' GROUP BY merchantagreement.id';
>>>>>>> origin/develop

        $refunds_amount = DB::select(DB::raw($query_amount));

        return array(

            'refunds_amount'    => $refunds_amount
        );

    }

    public function getChargebacks($date_from, $date_to, $merchantID=null, $merchantagreementID=null)
    {
        /*@todo: Get chargebacks from voucher */

        $query = 'SELECT merchantagreement.name, COUNT(voucher_event.id) as totalchb FROM voucher_event
                  JOIN merchant ON merchant.id = voucher_event.merchant_id
                  JOIN merchantagreement ON merchantagreement.merchant_id = merchant.id
                  WHERE voucher_event.datetimecreated >= '."'".$date_from."'".'
                  AND voucher_event.datetimecreated <= '."'".$date_to."'".'
                  AND (voucher_event.event_id =491 OR voucher_event.event_id =492 OR voucher_event.event_id =494 OR voucher_event.event_id =495)
                  ';

        if(!is_null($merchantID))
        {

            $query .= ' AND merchantagreement.merchant_id = '.$merchantID.' ';
        }

        if(!is_null($merchantagreementID))
        {

            $query .= ' AND merchantagreement.id = '.$merchantagreementID.' ';
        }
<<<<<<< HEAD
        $query .= ' GROUP BY merchantagreement.name';
=======
            $query .= ' GROUP BY merchantagreement.id';
>>>>>>> origin/develop



        $chb = DB::select(DB::raw($query));

        return array(
            'chb'    => $chb
        );

    }

    public function getChargebacksamount($currency, $date_from, $date_to, $merchantID=null, $merchantagreementID=null)
    {

        $query_amount = 'SELECT merchantagreement.name, SUM(voucher_event.amount) as totalchb FROM voucher_event
                  JOIN merchantagreement ON merchantagreement.merchant_id = voucher_event.merchant_id
                  WHERE voucher_event.datetimecreated >= '."'".$date_from."'".'
                  AND voucher_event.datetimecreated <= '."'".$date_to."'".'
                  AND (voucher_event.event_id =491 OR voucher_event.event_id =492 OR voucher_event.event_id =494 OR voucher_event.event_id =495)
                  AND voucher_event.currency = '."'".$currency."'".'
                  ';

        if(!is_null($merchantID))
        {
<<<<<<< HEAD
            $query_amount .= '  AND merchantagreement.merchant_id = '.$merchantID.'';
=======
        $query_amount .= '  AND merchantagreement.merchant_id = '.$merchantID.'';
>>>>>>> origin/develop
        }

        if(!is_null($merchantagreementID))
        {
            $query_amount .= '  AND merchantagreement.id = '.$merchantagreementID.'';
        }
<<<<<<< HEAD
        $query_amount .= 'GROUP BY merchantagreement.name';
=======
            $query_amount .= 'GROUP BY merchantagreement.id';
>>>>>>> origin/develop

        $chb_amount = DB::select(DB::raw($query_amount));




        return array(

            'chb_amount'    => $chb_amount,
        );



    }

<<<<<<< HEAD
    /*-- =========================================
                   Daily values
    =========================================== --*/
=======
              /*-- =========================================
                             Daily values
              =========================================== --*/
>>>>>>> origin/develop

    //transactions

    public function getDailyTransactions($date_from, $date_to, $merchantID = null, $merchantagreementID = null)
    {
        /*@todo: Replace with Json data from Shop */

        $query = 'SELECT merchantagreement.name, "order".datetimecreated::DATE as transactiondate,  COUNT(DISTINCT "order".id) as totaltransaction FROM "order"
                  JOIN transaction ON transaction.id = "order".transaction_id
                  JOIN merchantagreement ON merchantagreement.merchant_id = transaction.merchant_id
                  WHERE "order".datetimecreated >= '."'".$date_from."'".'
                  AND "order".datetimecreated <= '."'".$date_to."'".'
                ';

        if(!is_null($merchantID))
        {

            $query .= ' AND transaction.merchant_id = '. $merchantID.'
                  ';
        }


        if(!is_null($merchantagreementID))
        {
            $query .= ' AND merchantagreement.id = '.$merchantagreementID.' ';
        }
<<<<<<< HEAD
        $query .= 'GROUP BY transactiondate, merchantagreement.name';
=======
            $query .= 'GROUP BY transactiondate, merchantagreement.name';
>>>>>>> origin/develop

        $transactions = DB::select(DB::raw($query));


        return array(
            'transactions' => $transactions
        );

    }


    public function getDailyTransactionsamount($currency, $date_from, $date_to, $merchantID=null, $merchantagreementID=null)
    {
        $query_amount = 'SELECT merchantagreement.name, "order".datetimecreated::DATE as transactiondate,  SUM("order".amount) as totaltransaction FROM "order"
                  JOIN transaction ON transaction.id = "order".transaction_id
                  JOIN merchantagreement ON merchantagreement.merchant_id = transaction.merchant_id
                  WHERE "order".datetimecreated >= '."'".$date_from."'".'
                  AND "order".datetimecreated <= '."'".$date_to."'".'
                  AND "order".currency = '."'".$currency."'".'
                  ';

        if(!is_null($merchantID))
        {

            $query_amount .= 'AND transaction.merchant_id = '. $merchantID.'';
        }

        if(!is_null($merchantagreementID))
        {

            $query_amount .= 'AND merchantagreement.id = '. $merchantagreementID.'';
        }

        $query_amount .= 'GROUP BY transactiondate, merchantagreement.name';

        $transactions_amount = DB::select(DB::raw($query_amount));



        return array(
            'transactions_amount' => $transactions_amount
        );

    }

    //Chargebacks

    public function getDailyChargebacks($date_from, $date_to, $merchantID=null, $merchantagreementID=null)
    {
        /*@todo: Get chargebacks from voucher */

        $query = 'SELECT voucher_event.datetimecreated::DATE as chbdate, merchantagreement.name, COUNT(voucher_event.id) as totalchb FROM voucher_event
                  JOIN merchantagreement ON merchantagreement.merchant_id = voucher_event.merchant_id
                  WHERE voucher_event.datetimecreated >= '."'".$date_from."'".'
                  AND voucher_event.datetimecreated <= '."'".$date_to."'".'
                  AND (voucher_event.event_id =491 OR voucher_event.event_id =492 OR voucher_event.event_id =494 OR voucher_event.event_id =495)
                  ';

        if(!is_null($merchantID))
        {

            $query .= ' AND merchantagreement.merchant_id = '.$merchantID.'';
        }

        if(!is_null($merchantagreementID))
        {

            $query .= ' AND merchantagreement.id = '.$merchantagreementID.'';
        }
<<<<<<< HEAD
        $query .= ' GROUP BY merchantagreement.name, chbdate';
=======
            $query .= ' GROUP BY merchantagreement.id, chbdate';
>>>>>>> origin/develop


        $chb = DB::select(DB::raw($query));

        return array(
            'chb'    => $chb
        );

    }

    public function getDailyChargebacksamount($currency, $date_from, $date_to, $merchantID=null, $merchantagreementID=null)
    {

        $query_amount = 'SELECT voucher_event.datetimecreated::DATE as chbdate, merchantagreement.name, SUM(voucher_event.amount) as totalchb FROM voucher_event
                  JOIN merchantagreement ON merchantagreement.merchant_id = voucher_event.merchant_id
                  WHERE voucher_event.datetimecreated >= '."'".$date_from."'".'
                  AND voucher_event.datetimecreated <= '."'".$date_to."'".'
                  AND voucher_event.currency = '."'".$currency."'".'
                  AND (voucher_event.event_id =491 OR voucher_event.event_id =492 OR voucher_event.event_id =494 OR voucher_event.event_id =495)
                  ';

        if(!is_null($merchantID))
        {
            $query_amount .= ' AND merchantagreement.merchant_id = '.$merchantID.'';
        }

        if(!is_null($merchantagreementID))
        {
            $query_amount .= ' AND merchantagreement.id = '.$merchantagreementID.'';
        }
<<<<<<< HEAD
        $query_amount .= ' GROUP BY merchantagreement.name, chbdate';
=======
            $query_amount .= ' GROUP BY merchantagreement.id, chbdate';
>>>>>>> origin/develop

        $chb_amount = DB::select(DB::raw($query_amount));

        return array(

            'chb_amount'    => $chb_amount,
        );



    }

    //Refunds
    public function getDailyRefunds($date_from, $date_to, $merchantID=null, $merchantagreementID=null)
    {
        /*@todo: Replace with data from voucher */

        $query = 'SELECT merchantagreement.name, voucher_event.datetimecreated::DATE as refunddate,  COUNT(DISTINCT voucher_event.id) as totalrefunds FROM voucher_event
                  JOIN merchantagreement ON merchantagreement.merchant_id = voucher_event.merchant_id
                  WHERE voucher_event.datetimecreated >= '."'".$date_from."'".'
                  AND voucher_event.datetimecreated <= '."'".$date_to."'".'
                  AND voucher_event.event_id = 490
                  ';

        if(!is_null($merchantID))
        {

            $query .=' AND merchantagreement.merchant_id = '. $merchantID.'';
        }

        if(!is_null($merchantagreementID))
        {

            $query .= ' AND merchantagreement.id = '. $merchantagreementID.'';
        }
<<<<<<< HEAD
        $query .= 'GROUP BY refunddate, merchantagreement.name';
=======
            $query .= 'GROUP BY refunddate, merchantagreement.name';
>>>>>>> origin/develop


        $refunds = DB::select(DB::raw($query));

        return array(
            'refunds'    => $refunds
        );

    }

    public function getDailyRefundsamount($currency, $date_from, $date_to, $merchantID=null, $merchantagreementID=null)
    {

        $query_amount = 'SELECT merchantagreement.name, voucher_event.datetimecreated::DATE as refunddate,  SUM(voucher_event.amount) as totalrefunds FROM voucher_event
                  JOIN merchantagreement ON merchantagreement.merchant_id = voucher_event.merchant_id
                  WHERE voucher_event.datetimecreated >= '."'".$date_from."'".'
                  AND voucher_event.datetimecreated <= '."'".$date_to."'".'
                  AND voucher_event.currency = '."'".$currency."'".'
                  ';

        if(!is_null($merchantID))
        {

            $query_amount .= ' AND merchantagreement.merchant_id = '. $merchantID.'';
        }
<<<<<<< HEAD
        $query_amount .= 'GROUP BY refunddate, merchantagreement.name';
=======
            $query_amount .= 'GROUP BY refunddate, merchantagreement.name';
>>>>>>> origin/develop

        $refunds_amount = DB::select(DB::raw($query_amount));

        return array(

            'refunds_amount'    => $refunds_amount
        );

    }

}
