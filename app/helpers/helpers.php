<?php
use Merchantagreement;
use Mainvoicestatus;
use Mapayment;
use Mainvoice;
use Cache;
use Transactionorder;
use Resultfields;
use Reporttype;
use Currency;
use DB;

                            /*===========================================
                                           Header Values
                            =============================================*/

function update_cache()
{

    Cache::flush();

    $merchantagreements = Cache::rememberForever('nummerchantagreements', function()
    {
        $user = Sentry::getUser();

        if($user->merchant_id >0 || $user->merchantagreement_id>0)
        {
            $agreements = array();

            if($user->merchant_id >0)
            {
                $mag = Merchantagreement::where('merchant_id','=',$user->merchant_id)->get();

            }
            else
                if($user->merchantagreement_id >0)
                {
                    $mag = Merchantagreement::where('id','=',$user->merchantagreement_id)->get();

                }
            foreach($mag as $ma_ids)
            {
                $agreements[$ma_ids->id] = $ma_ids->id;

            }

            $ma =  Merchantagreement::whereIn('id', $agreements)->count();
        }
        else
        {
            $ma =  Merchantagreement::count();
        }

        return $ma;
    });

    $numpaidinvoices = Cache::rememberForever('numpaidinvoices', function()
    {
        if(Sentry::getUser()->merchant_id>0)
        {
            $invoices = Mainvoice::bystatus(3)->bymerchant(Sentry::getUser()->merchant_id)->count();

           /* $invoices = Mainvoicestatus::join('mainvoice','mainvoice.id','=','mainvoicestatus.mainvoice_id')
                ->join('merchantagreement','merchantagreement.id','=','mainvoice.merchantagreement_id')
                ->where('merchantagreement.merchant_id','=',Sentry::getUser()->merchant_id)
                ->where('invoicestatus_id','=','3')->count(); */

        }
        else
            if(Sentry::getUser()->merchantagreement_id>0)
            {

                $invoices = Mainvoice::bystatus(3)->bymerchantagreement(Sentry::getUser()->merchantagreement_id)->count();
            }
        else
        {
        $invoices = Mainvoice::bystatus(3)->count();


        }

        return $invoices;
    });

    $numupcominginvoices = Cache::rememberForever('numupcominginvoices', function()
    {
        if(Sentry::getUser()->merchant_id>0)
        {
                $upcoming_invoices = Mainvoicestatus::join('mainvoice','mainvoice.id','=','mainvoicestatus.mainvoice_id')
                    ->join('merchantagreement','merchantagreement.id','=','mainvoice.merchantagreement_id')
                    ->where('merchantagreement.merchant_id','=',Sentry::getUser()->merchant_id)
                    ->where('invoicestatus_id','=','1')->orWhere('invoicestatus_id','=','4')->count();
        }
        else
            if(Sentry::getUser()->merchantagreement_id>0)
            {
                $upcoming_invoices = Mainvoicestatus::join('mainvoice','mainvoice.id','=','mainvoicestatus.mainvoice_id')
                    ->join('merchantagreement','merchantagreement.id','=','mainvoice.merchantagreement_id')
                    ->where('merchantagreement.id','=',Sentry::getUser()->merchantagreement_id)
                    ->where('invoicestatus_id','=','1')->orWhere('invoicestatus_id','=','4')->count();
            }
            else
            {
                $upcoming_invoices = Mainvoicestatus::where('invoicestatus_id','=','1')->orWhere('invoicestatus_id','=','4')->count();
            }
        //$upcoming_invoices = Mainvoice::bystatus(1)->bystatus(4)->count();

        return $upcoming_invoices;
    });

    $numoverdueinvoices = Cache::rememberForever('numoverdueinvoices', function()
    {
        if(Sentry::getUser()->merchant_id>0)
        {
                $overdue_invoices = Mainvoice::bystatus(6)->bymerchant(Sentry::getUser()->merchant_id)->count();
        }
        else
            if(Sentry::getUser()->merchantagreement_id>0)
            {
                $overdue_invoices = Mainvoice::bystatus(6)->bymerchantagreement(Sentry::getUser()->merchantagreement_id)->count();
            }
        else
        {
            $overdue_invoices = Mainvoice::bystatus(6)->count();

        }

        return $overdue_invoices;
    });

    $numtobepaid = Cache::rememberForever('numtobepaid', function()
    {
        if(Sentry::getUser()->merchant_id>0)
        {
            //$tobepaid = Mainvoice::bystatus(2)->bymerchant(Sentry::getUser()->merchant_id)->count();
            $tobepaid = Mainvoice::join('mainvoicestatus','mainvoice.id','=','mainvoicestatus.mainvoice_id')
                 ->join('merchantagreement','merchantagreement.id','=','mainvoice.merchantagreement_id')
                ->where('merchantagreement.merchant_id','=',Sentry::getUser()->merchant_id)
                ->where('mainvoicestatus.invoicestatus_id','=','2')
                ->count();
        }
        else
            if(Sentry::getUser()->merchantagreement_id>0)
            {
                //$tobepaid = Mainvoice::bystatus(2)->bymerchantagreement(Sentry::getUser()->merchantagreement_id)->count();
                $tobepaid = Mainvoice::join('mainvoicestatus','mainvoice.id','=','mainvoicestatus.mainvoice_id')
                    ->where('mainvoice.merchantagreement_id','=',Sentry::getUser()->merchantagreement_id)
                    ->where('mainvoicestatus.invoicestatus_id','=','2')
                    ->count();
            }
            else
            {
               // $tobepaid = Mainvoice::bystatus(2)->count();
                $tobepaid = Mainvoice::join('mainvoicestatus','mainvoice.id','=','mainvoicestatus.mainvoice_id')->where('mainvoicestatus.invoicestatus_id','=','2')->count();

            }

        return $tobepaid;
    });


    $numdraftinvoices = Cache::rememberForever('numdraftinvoices', function()
    {
        if(Sentry::getUser()->merchant_id>0)
        {
            //$draft_invoices = Mainvoice::bystatus(1)->bymerchant(Sentry::getUser()->merchant_id)->count();

            $draft_invoices = Mainvoice::join('mainvoicestatus','mainvoice.id','=','mainvoicestatus.mainvoice_id')
                ->join('merchantagreement','merchantagreement.id','=','mainvoice.merchantagreement_id')
                ->where('merchantagreement.merchant_id','=',Sentry::getUser()->merchant_id)
                ->where('mainvoicestatus.invoicestatus_id','=','1')
                ->count();
        }
        else
            if(Sentry::getUser()->merchantagreement_id>0)
            {
               // $draft_invoices = Mainvoice::bystatus(1)->bymerchantagreement(Sentry::getUser()->merchantagreement_id)->count();

                $draft_invoices = Mainvoice::join('mainvoicestatus','mainvoice.id','=','mainvoicestatus.mainvoice_id')
                    ->where('mainvoice.merchantagreement_id','=',Sentry::getUser()->merchantagreement_id)
                    ->where('mainvoicestatus.invoicestatus_id','=','2')
                    ->count();

            }
            else
            {
                //$draft_invoices = Mainvoice::bystatus(1)->count();

                $draft_invoices = Mainvoice::join('mainvoicestatus','mainvoice.id','=','mainvoicestatus.mainvoice_id')->where('mainvoicestatus.invoicestatus_id','=','1')->count();


                //echo 'Draft '. $draft_invoices; exit;
            }

        return $draft_invoices;
    });




    $numpayments = Cache::rememberForever('numpayments', function()
    {
       /*
        if(Sentry::getUser()->merchant_id>0)
        {
            $payments = Mapayment::bymerchant(Sentry::getUser()->merchant_id)->count();
        }
        else
            if(Sentry::getUser()->merchantagreement_id>0)
        {
            $payments = Mapayment::bymerchantagreement(Sentry::getUser()->merchantagreement_id)->count();
        }
        else
        {
            $payments = Mapayment::count();
        }
        */

        $user = Sentry::getUser();

        if($user->merchant_id >0 || $user->merchantagreement_id>0)
        {
            $agreements = array();

            if($user->merchant_id >0)
            {
                $mag = Merchantagreement::where('merchant_id','=',$user->merchant_id)->get();

            }
            else
                if($user->merchantagreement_id >0)
                {
                    $mag = Merchantagreement::where('id','=',$user->merchantagreement_id)->get();

                }
            foreach($mag as $ma_ids)
            {
                $agreements[$ma_ids->id] = $ma_ids->id;

            }
            ////

            $payments =  Mapayment::join('mainvoice','mapayment.mainvoice_id','=','mainvoice.id')
                                    ->whereIn('mainvoice.merchantagreement_id', $agreements)->count();
        }
        else
        {
            $payments = Mapayment::count();
        }
        return $payments;
    });

    $allcurrencies = Cache::rememberForever('allcurrencies', function()
    {

        if(Sentry::getUser()->merchant_id>0)
        {
            $allcurrencies = Transactionorder::join('transaction','order.transaction_id','=','transaction.id')
                ->where('transaction.merchant_id','=',Sentry::getUser()->merchant_id)
                ->select('order.currency')
                ->distinct()
                ->get();
        }
        else
            if(Sentry::getUser()->merchantagreement_id>0)
            {
                $allcurrencies = Transactionorder::join('transaction','order.transaction_id','=','transaction.id')
                    ->join('merchantagreement','transaction.merchant_id','=','merchantagreement.merchant_id')
                    ->where('merchantagreement.id','=',Sentry::getUser()->merchantagreement_id)
                    ->select('order.currency')
                    ->distinct()
                    ->get();
            }
        else{
            $allcurrencies = Transactionorder::select('currency')->distinct()->get();
        }

        return  $allcurrencies;
    });

}

function allcurrencies()
{
    if(Sentry::getUser()->merchant_id>0)
    {
        $allcurrencies = Transactionorder::join('transaction','order.transaction_id','=','transaction.id')
            ->where('transaction.merchant_id','=',Sentry::getUser()->merchant_id)
            ->select('order.currency')
            ->distinct()
            ->get();
    }
    else
        if(Sentry::getUser()->merchantagreement_id>0)
        {
            $allcurrencies = Transactionorder::join('transaction','order.transaction_id','=','transaction.id')
                ->join('merchantagreement','transaction.merchant_id','=','merchantagreement.merchant_id')
                ->where('merchantagreement.id','=',Sentry::getUser()->merchantagreement_id)
                ->select('order.currency')
                ->distinct()
                ->get();
        }
        else{
            $allcurrencies = Transactionorder::select('currency')->distinct()->get();
        }

    return $allcurrencies;
}

/*===========================================
                       User Actions
    =============================================*/

//@TODO: Load all actions dynamically

function invoiceactions($invoiceID)
{
    $user = Sentry::getUser();

    $invoice = Mainvoice::find($invoiceID);

    $actions = array();

    if ($user->merchant_id>0 || $user->merchantagreement_id>0)
    {
        if($user->hasAccess('view_mainvoices') )
        {
            $actions['view']['text'] = 'Show';
            $actions['view']['link'] = 'show/mainvoice';
            $actions['view']['btn']  = '';

        }
    }
    else
    {
        if($user->hasAccess('view_mainvoices') )
        {
            $actions['view']['text'] = 'Show';
            $actions['view']['link'] = "{{ route('show/mainvoice', $invoiceID) }}";
            $actions['view']['btn']  = '';

        }

        if($user->hasAccess('manage_mainvoices'))
        {
            $actions['view']['text'] = 'Show';
            $actions['view']['link'] = "{{ route('show/mainvoice', $invoiceID) }}";
            $actions['view']['btn']  = '';


            if($invoice->invoicestatus($invoice->id)->status == 'draft' || $invoice->invoicestatus($invoice->id)->status == 'approved' || $invoice->invoicestatus($invoice->id)->status == 'tobepaid'  || $invoice->invoicestatus($invoice->id)->status == 'min_payout_not_met')
            {
                $actions['edit']['text'] = 'Edit';
                $actions['edit']['link'] = "{{ route('update/mainvoice', $invoiceID) }}";
                $actions['edit']['btn']  = 'btn-primary';
            }


        }

    }

    var_dump( $actions); exit;

}

/*=====================================================================
                             Reports
=====================================================================*/

function reporttitle($searchfields)
{
    $title ='';
    foreach($searchfields as $key => $val)
    {
        $excluded_fields = array('_token','reportType','search','saveform');
        if($val && !in_array($key, $excluded_fields) )
        {   $title .= ' | '.$key.' : '.$val; }
    }

    return $title;
}
/////////////

function currencylist()
{

}

////

function reportStatuses($reporttypeID)
{

    // $reporttype = Reporttype::where('id','=','1')->first();

   // var_dump($reporttype);

    switch($reporttypeID)
    {
        case '2':
        case '5':
        {
            //$statuses = report_api_call('shop', 'statuses/order');

           // $status_query = 'select distinct "event".id, "event".event from "event" join "order" on "event".id = "order".status_id order by event.id';

            $statuses = DB::table('event')->whereIn('id', array('305','403','901'))->orderBy('id')->get();

            //$statuses = DB::select(DB::raw($status_query));

            break;
        }
        case '1':
        {
            // $statuses = report_api_call('voucher', 'statuses/voucher_event');

            $statuses = DB::table('event')->whereIn('id', array('403'))->orderBy('id')->get();

            break;
        }
        case '3':
        {
            // $statuses = report_api_call('shop', 'statuses/transaction');

            //$status_query = 'select distinct "event".id, "event".event from "event" join "transaction" on "event".id = "transaction".status_id order by event.id';

            $statuses = DB::table('event')->whereIn('id', array('102','103','105','403','901','908'))->orderBy('id')->get();

            //$statuses = DB::select(DB::raw($status_query));

            break;
        }
        case '4':
        {
            $statuses = DB::table('event')->whereIn('id', array('91','811','901'))->orderBy('id')->get();

         //   $statuses = DB::select(DB::raw($status_query));
            break;
        }

    }

    return $statuses;
}

///////////////

/*============== Result Fields ====================*/

function reportresultsfields($reporttypeID, $status)
{
    if($status == 'summary')
    {
        $resultfields = Resultfields::join('report_result_fields','result_fields.id','=','report_result_fields.resultfields_id')->where('report_result_fields.reporttype_id','=',''.$reporttypeID.'')
            ->select('result_fields.id', 'result_fields.fieldclass', 'result_fields.fieldname','result_fields.fielddescription', 'report_result_fields.reportsearch_id','report_result_fields.reporttype_id')->orderby('fieldorder')
            ->whereIn('summary',array('1','2'))
            ->get();

         //var_dump($resultfields); exit;
    }
    else
    {
    $resultfields = Resultfields::join('report_result_fields','result_fields.id','=','report_result_fields.resultfields_id')->where('report_result_fields.reporttype_id','=',''.$reporttypeID.'')
        ->select('result_fields.id', 'result_fields.fieldclass', 'result_fields.fieldname','result_fields.fielddescription', 'report_result_fields.reportsearch_id','report_result_fields.reporttype_id')
        ->whereIn('summary',array('0','2'))
        ->orderby('fieldorder')
        ->get();
    }


    return $resultfields;
}



function summaryresultsfields($reporttypeID)
{
    $resultfields = Resultfields::join('report_result_fields','result_fields.id','=','report_result_fields.resultfields_id')->where('report_result_fields.reporttype_id','=',''.$reporttypeID.'')
        ->select('result_fields.id', 'result_fields.fieldclass', 'result_fields.fieldname','result_fields.fielddescription', 'report_result_fields.reportsearch_id','report_result_fields.reporttype_id')->orderby('fieldorder')
        ->whereIn('summary',array('1','2'))
        ->get();

    return $resultfields;
}

/*============== Result Values ====================*/

function fieldval($report, $fieldname)
{

    switch($fieldname)
    {
        case 'firstname':
        {
           @ $fieldval = $report->firstname.' '.$report->lastname;

            //reporttype = orders? set spokeolookup url

          //  <a style='float:right;' class='' target='_blank' href='https://www.spokeo.com/search?q=".$record['firstname']."+".$record['lastname'].",".preg_replace('/ /', '+', $record['city'])."'><img width='22' src='images/spokeo16.png' alt='Spokeo Lookup'/></a>
            $spokeourl = 'https://www.spokeo.com/search?q='.$report->firstname.'+'.$report->lastname.'+'.preg_replace('/ /', '+', $report->city);
            $spokeoimg = 'images/spokeo16.png';

            @ $fieldval .= ($reporttypeID==1?'<a href="'.$spokeourl.'"><img src = '' /></a>':'');

            break;
        }
        case 'purchased':
        {
           @ $fieldval  = date('F d, Y H.i:s', strtotime($report->$fieldname));
            break;
        }

        case 'datetimecreated':
        {
            @ $fieldval  = date('F d, Y H.i:s', strtotime($report->$fieldname));
            break;
        }
        case 'timetaken':
        {
            @ $fieldval = sec2time($report->$fieldname) ;
            break;
        }
      //  case 'ordercount':
        case 'percent':
       // case 'traceid':
        {
            @ $fieldval = ''; //$report->$fieldname;
            break;
        }
        default:
            {
           @ $fieldval  = $report->$fieldname;
            }
    }

    return $fieldval;
}

/*===========================================
                        Seconds to time
   ===============================================*/

function sec2time($sec){
    $returnstring = " ";

    $days = intval($sec/86400);
    $hours = intval ( ($sec/3600) - ($days*24));
    $minutes = intval( ($sec - (($days*86400)+ ($hours*3600)))/60);
    $seconds = $sec - ( ($days*86400)+($hours*3600)+($minutes * 60));


    $returnstring .= ($days)?($days."d "):"";
    $returnstring .= ($hours)?( $hours."h "):"";
    $returnstring .= ($minutes)?( $minutes."min "):"";
    $returnstring .= ($seconds)?( $seconds."s"):"";

    return ($returnstring);
}

/*===========================================
                  Other
   =============================================*/
///////////////////////////////////////////////////////////////////////////////////
function makeRecursive($d, $r = 0, $pk = 'parent', $k = 'id', $c = 'children') {
    $m = array();
    foreach ($d as $e) {
        isset($m[$e[$pk]]) ?: $m[$e[$pk]] = array();
        isset($m[$e[$k]]) ?: $m[$e[$k]] = array();
        $m[$e[$pk]][] = array_merge($e, array($c => &$m[$e[$k]]));
    }

    return $m[$r][0]; // remove [0] if there could be more than one root nodes
}
///////////////////////////////////////////////////////////////////////////////////