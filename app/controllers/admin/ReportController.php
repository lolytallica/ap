<?php namespace Controllers\Admin;

use AdminController;
use Input;
use Lang;
use Redirect;
use Sentry;
use Str;
use Validator;
use View;
use DB;
use Merchant;
use Merchantagreement;
use Reportsearch;
use Searchfields;
use Transaction;
use Reporttype;
use Voucherevent;
use Resultfields;
use Reportsearchfields;
use Session;



class ReportController extends AdminController {

    public function __construct()
    {
        $this->beforeFilter('hasAccess:manage_reports');
    }

    protected $searchRules = array(
        'reportType' => 'required',
        'date_from'  => 'required|date'
    );

    protected $formRules = array(
        'reportType' => 'required',
        'date_from'  => 'required|date',
        'form_name'  => 'required'
    );


    /**
     * Show a list of all the Reports.
     *
     * @return View
     */
    public function getIndex($reportsearchID = null, $reporttypeID = null)
    {

        if(is_null($reportsearchID))
        {
            $reportsearchID = 1; //default search form
          //  return Redirect::to("admin/reports/searchreport")->with('conversionrate', $conversionrate);

        }


        if(is_null($reporttypeID))
        {
            $reporttypeID = 1;
           // return Redirect::to();
        }




        //var_dump(json_encode($statuses)); exit;

        $reportsearch = Reportsearch::find($reportsearchID);

        if(Session::get('reporttypeID'))
        {
            $reporttypeID = Session::get('reporttypeID');
        }


        $searchform = Reportsearch::find($reportsearchID);

        $formfields = Searchfields::join('report_search_fields','search_fields.id','=','report_search_fields.searchfields_id')
            ->where('reportsearch_id','=',$reportsearchID)
            //->where('reporttype_id','=',$reporttypeID)
            ->select('search_fields.id','search_fields.fieldname','search_fields.fieldtype', 'search_fields.fielddescription', 'report_search_fields.reportsearch_id','report_search_fields.reporttype_id','search_fields.data_validation','search_fields.data_validation_condition')
            ->orderBy('reportsearch_id')
            ->orderBy('reporttype_id')
            ->orderBy('search_fields.fieldorder')
            ->get();

       // $formfields = $searchform->searchfields();
        $ff = $formfields->toArray();

        $allreporttypes = Reporttype::all();

        $allreportforms = Reportsearch::where('id','<>','1')->get();

       $rf = $this->reportresultfields($reporttypeID);


        $merchant  = Merchant::all();
        $merchants = $merchant->toArray();

        $merchantagreements = Merchantagreement::all();
        $merchantagreements = $merchantagreements->toArray();


        ///Build Report Form
        $fields = $this->buildSearch();

        ///statuses

        $statuses = $this->reportStatuses();


        $transactionsbase = date('Y-m-d').' 00:00';

        // Show the page
        return View::make('backend/reports/index')
            ->with('fields',$fields)
            ->with('reports',$allreportforms)
            //->with('formfields',$formfields)
            ->with('formfields',json_encode($formfields))   //form fields by report type
           // ->with('resultfields',$rf)
            ->with('merchantlist',json_encode($merchants)) //merchants for dropdowns
            ->with('merchants',$merchant)
            ->with('merchantagreementlist',json_encode($merchantagreements)) //ma for added dropdown
            ->with('searchform',json_encode($ff))
            ->with('reporttypes',$allreporttypes) //dropdown
            ->with('statuses',json_encode($statuses)) //dropdown
            ->with('conversionrate',$this->conversionrate())
            ->with('reportsearch',$reportsearch)
            ->with('transactionsbase', $transactionsbase);
    }


    /*======================================================
                     Report Statuses
    =======================================================*/

    public function reportStatuses()
    {

      /*  switch($reporttype)
        {
            case 'redemptions':{
                $statuses = DB::table('event')->whereIn('id', array('403','406','491'))->orderBy('id')->select('id','event')->get();
                                   break;

            }
            case 'customers':
            case 'orders':{
                $status_query = 'select distinct "event".id, "event".event from "event" join "order" on "event".id = "order".status_id order by event.id';
                $statuses = DB::select(DB::raw($status_query));
                break;
            }
            case 'transactions':{
                $status_query = 'select distinct "event".id, "event".event from "event" join "transaction" on "event".id = "transaction".status_id order by event.id';

                $statuses = DB::select(DB::raw($status_query));
            }

            return $statuses;

        }*/


        $allstatuses = array();
            //Orders, Customers

                //$statuses = report_api_call('shop', 'statuses/order');

                $status_query = 'select distinct "event".id, "event".event from "event" join "order" on "event".id = "order".status_id order by event.id';

                $orders_statuses['orders'] = DB::select(DB::raw($status_query));
                $customers_statuses['customers'] = DB::select(DB::raw($status_query));

            //Redemptions

               // $statuses = report_api_call('voucher', 'statuses/voucher_event');

                $redemptions_statuses['redemptions'] = DB::table('event')->whereIn('id', array('403','406','491'))->orderBy('id')->select('id','event')->get();

                 //Shop Transactions

               // $statuses = report_api_call('shop', 'statuses/transaction');

                $status_query = 'select distinct "event".id, "event".event from "event" join "transaction" on "event".id = "transaction".status_id order by event.id';

                $transaction_statuses['transactions'] = DB::select(DB::raw($status_query));


            //Validation

  //      $validation_statuses['validation'] = array();

        $allstatuses['statuses'] = array($transaction_statuses, $redemptions_statuses, $orders_statuses);

       // var_dump(json_encode($allstatuses)); exit;


        return $allstatuses;
    }


    public function getSearchreport($reportsearchID = null, $searchfields = array())
    {
       // return $this->getIndex($reportsearchID = null, $searchfields = array())
    }


    public function postSearchreport()
    {
        $searchfields = Input::all();

        if(Input::has('search'))
        {
            return $this->searchResults($searchfields);
        }
        else
        if(Input::has('saveform'))
        {
            return $this->saveReportsearch($searchfields);
        }
    }

    /*=================================================
                    Search form action submitted
    =================================================*/

    public function searchResults($searchfields)
    {

        $validator = Validator::make($searchfields, $this->searchRules);

        $report = Input::get('reportType');


        if ($validator->fails())
        {

            return Redirect::back()->withInput()->withErrors($validator);
        }

        $reporttype = Reporttype::find($searchfields['reportType']);

        switch($reporttype->searchtype)
        {
            case 'orders':
            {
                $results = $this->searchOrders($searchfields);
                break;
            }
            case 'redemptions':
            {
                $results = $this->searchRedemptions($searchfields);
                break;
            }
            case 'shopTransactions':
            {
                $results = $this->searchShoptransactions($searchfields);
                break;
            }
            case 'validationrequests':
            {
                $results = $this->searchValidationrequests($searchfields);
                break;
            }
            case 'customers':
            {
                $results = $this->searchCustomers($searchfields);
                break;
            }
        }


        return Redirect::to("admin/reports/searchreport")->with('conversionrate', $this->conversionrate())
            ->with('searchfields',$searchfields)
            ->with('results',$results)
            ->with('reporttype',$reporttype);
    }

    /*===========================================
                    Save form action submitted
    =================================================*/

    public function saveReportsearch($searchfields)
    {
        //echo 'save modal';



        $validator = Validator::make($searchfields, $this->formRules);

        if ($validator->fails())
        {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $reporttype = Reporttype::find($searchfields['reportType']);

        $reportform = New Reportsearch();

        $reportform->name = $searchfields['form_name'];
        $reportform->user_id = Sentry::getUser()->id;
        $reportform->save();

        $excluded_fields = array('_token', 'reportType', 'saveform', 'search', 'form_name');

        $fields_saved=0;
        foreach($searchfields as $key => $val)
        {
            if($val && !in_array($key, $excluded_fields) )
            {
                $sf = Searchfields::byname($key)->first();

                $reportfields = New Reportsearchfields();

                $reportfields->reportsearch_id = $reportform->id;
                $reportfields->searchfields_id = $sf->id;
                $reportfields->reporttype_id   = $searchfields['reportType'];

               if($reportfields->save()) $fields_saved++;
            }
        }

        if($fields_saved>0)
        {
        return Redirect::to("admin/reports/searchreport")->with('conversionrate', $this->conversionrate())
            ->with('message','success');
        }
        else
        {
            return Redirect::back()->withInput()->with('Error','error');
        }
    }

    public function reportresultfields($reporttypeID)
    {
        $resultfields = Resultfields::join('report_result_fields','result_fields.id','=','report_result_fields.resultfields_id')->where('report_result_fields.reporttype_id','=',''.$reporttypeID.'')->select('result_fields.id', 'result_fields.fieldclass', 'result_fields.fieldname','result_fields.fielddescription', 'report_result_fields.reportsearch_id','report_result_fields.reporttype_id')->get();

        return $resultfields;
    }



    public function conversionrate()
    {
        $postfields = array();
        $conversion = ($this->report_api_call('shop', 'conversion', $postfields) ? $this->report_api_call('shop', 'conversion', $postfields): 60);

        $conversionrate = number_format($conversion[0]['percentage'], 1);

        $account = (@$service_name? $service_name: '');
        $conversionratebad = 100 - $conversionrate ;

        return $conversionrate;

    }


    /* =================================================================
        ====================== Search functions =========================
         ================================================================== */

    public function scopeSearch($query, $field)
    {
        return $query->where(''.$field.'', '=', $field);
    }





    public function searchRedemptions($searchfields)
    {

        $postfields = array();

       /* $postfields['from'] = request('from', date('Y-m-d')) . ' 00:00:00';
        $postfields['to'] = request('to', date('Y-m-d')) . ' 23:59:59';
        $postfields['status_id'] = request('status'); */


      /*  if (request('trace_id')) {
            $postfields['trace_id'] = request('trace_id');
        } elseif (request('order_id'))


      {
            $postfields['order_id'] = request('order_id');
        } elseif (request('transaction_id')) {
            $postfields['transaction_id'] = request('transaction_id');
        } elseif (request('request_id')) {
            $postfields['request_id'] = request('request_id');
        } elseif (request('voucher_id')) {
            $postfields['voucher_id'] = request('voucher_id');
        }

        $postfields['multisearch'] = request('multisearch');
        if (!$_SESSION['admin']) {
            $postfields['service_id'] = $_SESSION['service_id'];
        } else $postfields['service_id'] = request('service_id');*/

     //   $postfields['secret'] = 'c0e5b59224f41c09c996be5c10b3ca07b3223c82';

       //$redemptions = report_api_call('voucher', 'redemptions', $postfields);

     /*   $redemptions = Voucherevent::where('merchant_id','=',$merchantID)
            ->where('event_id','=', 403)
            ->where('datetimecreated','>=',$date_from)
            ->where('datetimecreated','<=',$date_to)
            ->get(); */

        /*@TODO: GET dynamically from table*/
        $excluded_fields = array('_token','date_from','date_to','reportType', 'search', 'saveform');

        $date_from = $searchfields['date_from'];
        $date_to   = $searchfields['date_to'];

        $query = 'SELECT merchant.abbreviation as merchant, voucher_event.voucher_id, voucher_event.firstname, voucher_event.lastname, voucher_event.merchantusername, voucher_event.merchantprofile, voucher_event.event_id , event.event, voucher_event.purchased, voucher_event.merchantredemptionid AS traceid, voucher_event.ipaddress, voucher_event.amount, voucher_event.currency, voucher_event.datetimecreated FROM voucher_event JOIN merchant ON voucher_event.merchant_id = merchant.id JOIN event ON voucher_event.event_id = event.id  WHERE voucher_event.datetimecreated>='."'".$date_from."'".' ';
        if($searchfields['date_to'])
        {
            $query .= ' AND voucher_event.datetimecreated<= '."'".$date_to."'".' ';
        }
        foreach($searchfields as $key => $val)
        {
            if($val && !in_array($key, $excluded_fields) )
            {if($key=='status') $key = 'event_id';
            $query .= ' AND "'.$key.'" = '."'".$val."'".' ';
            }
        }


        $redemptions = DB::select(DB::raw($query));

        return array(
            'redemptions'    => $redemptions
        );


    }

    public function getRedemptionDetails($searchfields)
    {

    }

    public function searchOrders($searchfields)
    {

        $postfields = array();

        /*@TODO: GET dynamically from table*/
        $excluded_fields = array('_token','date_from','date_to','reportType', 'search', 'saveform');

        $date_from = $searchfields['date_from'];
        $date_to   = $searchfields['date_to'];

        $query = 'SELECT merchant.abbreviation as merchant, "order".id AS order_id, "transaction".firstname, "transaction".lastname, "transaction".merchantusername, transaction.merchantprofile, transaction.ipaddress, "order".status_id AS event_id, event.event, "order".cctype, "order".cardnumber, "order".datetimecompleted as purchased, transaction.merchanttransactionid AS traceid, "order".amount, "order".currency, "order".datetimecreated
                  FROM "order" JOIN transaction ON "order".transaction_id = transaction.id JOIN merchant ON transaction.merchant_id = merchant.id JOIN event ON "order".status_id = event.id
                  WHERE "order".datetimecreated>='."'".$date_from."'".' ';

        if($searchfields['date_to'])
        {
            $query .= ' AND "order".datetimecreated<= '."'".$date_to."'".' ';
        }
        foreach($searchfields as $key => $val)
        {
            if($val && !in_array($key, $excluded_fields) )
            {
                if($key=='status') $key = 'event.id';
                $query .= ' AND '.$key.' = '."'".$val."'".' ';
            }
        }

        $orders = DB::select(DB::raw($query));

        return array(
            'orders'    => $orders
        );
    }

    public function getOrderdetails()
    {

    }

    public function searchShoptransactions($searchfields)
    {

    }

    public function getShoptransactionDetails()
    {

    }

    public function searchValidationrequests($searchfields)
    {

    }

    public function searchCustomers($searchfields)
    {
        $postfields = array();

        /*@TODO: GET dynamically from table*/
        $excluded_fields = array('_token','date_from','date_to','reportType', 'search', 'saveform');

        $date_from = $searchfields['date_from'];
        $date_to   = $searchfields['date_to'];

        $query = 'SELECT merchant.abbreviation as merchant, "transaction".firstname, "transaction".lastname, "transaction".merchantusername, transaction.merchantprofile, transaction.email, transaction.merchantcreateddate, "order".amount, "order".currency
                  FROM "order" JOIN transaction ON "order".transaction_id = transaction.id JOIN merchant ON transaction.merchant_id = merchant.id JOIN event ON "order".status_id = event.id
                  WHERE "order".datetimecreated>='."'".$date_from."'".' ';

        if($searchfields['date_to'])
        {
            $query .= ' AND "order".datetimecreated<= '."'".$date_to."'".' ';
        }
        foreach($searchfields as $key => $val)
        {
            if($val && !in_array($key, $excluded_fields) )
            {
                if($key=='status') $key = 'event.id';
                $query .= ' AND '.$key.' = '."'".$val."'".' ';
            }
        }

     // $query = 'SELECT transaction.email, COUNT(transaction.id) AS ordercount FROM transaction WHERE transaction.datetimecreated>='."'".$date_from."'".' ';

       // echo $query; exit;

        $customers = DB::select(DB::raw($query));

        return array(
            'customers'    => $customers
        );
    }



    public function getTrace($traceID)
    {

    }

    public function buildSearch()
    {
        ///Transaction table columns

        $searchoptions = array();

        $sql = "select column_name from information_schema.columns where table_name='transaction' ";

        $search = DB::select(DB::raw($sql));

        foreach($search as $sf)
        {
            foreach($sf as $searchfield)
            {
                if($searchfield != 'id' && $searchfield != 'datetimecreated' && $searchfield != 'datetimemodified')
                    $searchoptions[$searchfield] = $searchfield;
            }
        }
        $searchoptions['merchantagreement'] = 'merchantagreement';
        $searchoptions['merchant'] = 'merchant';

        sort($searchoptions);
        return $searchoptions;
    }


    /*===========================================================================
                               === Old Code ===
    ==============================================================================*/

    public function report_api_call($system, $uri = '', $postfields = array()) {

        ////Curl Config Data
        define('_RESTRICT', 1);
        // 1 = Live Server, 2 = Test Server, 3 = Localhost

        $config = 2;

      /*  if ($config == 1) {
            define('APP_PATH', $_SERVER['REMOTE_ADDR'] . '/mrtokenforms/');
            define('DB_HOST', 'localhost');
            define('DB_NAME', 'poc_dev');
            define('DB_USER', 'poc_dev');
            define('DB_PASS', 'GqjRz4lw@*q1');
        }*/
        if ($config == 2) {
          //  $c = Constants::GetInstance();
            define('APP_PATH', $_SERVER['REMOTE_ADDR']);
            define('DB_HOST', 'localhost');
            define('DB_NAME', 'validation_future');
            define('DB_USER', 'validation_dev');
            define('DB_PASS', 'UWBAvOT01pqq');
        }
      /*  if ($config == 3) {
            define('APP_PATH', $_SERVER['REMOTE_ADDR'] . '/mrtoken_poc/');
            define('DB_HOST', 'localhost');
            define('DB_NAME', 'poc_dev');
            define('DB_USER', 'root');
            define('DB_PASS', '');
        } */

        //$ConfigSetting = ConfigSelect::GetConfigName();
        $ConfigSetting = 'test';

       /* if($ConfigSetting == 'dev') {
            define('SHOP_REPORT_API_ROOT', 'http://dev.mrtoken.com/report_api/');
            define('SHOP_SECRET', 'c606bced96e52460bee0d41970494d142fc9ec75');
            define('VOUCHER_REPORT_API_ROOT', 'http://dev.webcheque.eu/report_api/');
            define('VOUCHER_SECRET', 'c0e5b59224f41c09c996be5c10b3ca07b3223c82');
        }

        else*/
            if($ConfigSetting == 'test') {
                define('SHOP_REPORT_API_ROOT', 'https://test.mrtoken.com/report_api/');
                define('SHOP_SECRET', 'c606bced96e52460bee0d41970494d142fc9ec75');
                define('VOUCHER_REPORT_API_ROOT', 'https://test.webcheque.eu/report_api/');
                define('VOUCHER_SECRET', 'c0e5b59224f41c09c996be5c10b3ca07b3223c82');
        }

       /* elseif($ConfigSetting == 'live') {
            define('SHOP_REPORT_API_ROOT', 'https://www.mrtoken.com/report_api/');
            define('SHOP_SECRET', 'c606bced96e52460bee0d41970494d142fc9ec75');
            define('VOUCHER_REPORT_API_ROOT', 'https://www.webcheque.eu/report_api/');
            define('VOUCHER_SECRET', 'c0e5b59224f41c09c996be5c10b3ca07b3223c82');
        }*/


        /////////////////////////////
        ////////////////////////////

        switch($system) {
            case 'shop':
                $url = SHOP_REPORT_API_ROOT . $uri;
                $postfields['secret'] = SHOP_SECRET;
                break;
            case 'voucher':
                $url = VOUCHER_REPORT_API_ROOT . $uri;
                $postfields['secret'] = VOUCHER_SECRET;
                break;
            default: return array();
        }

       // fb($url, 'Report API Call URL');


        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $content = json_decode($response);
        curl_close($ch);

       // fb($response, 'Report API Call Response');

       // var_dump($response); exit;

        if(@$content)
        {
        $key_count = count($content->data->keys);
        $values_count = count($content->data->values);


    //    fb($key_count, 'Key Count');
    //    fb($values_count, 'Values Count');

    /*    if (isset($content->error))
            fb($content->error, 'Report API Error');
        if (isset($content->data->db_query))
            fb($content->data->db_query, 'Report API Call SQL');
        if (isset($content->data->db_error))
            fb($content->data->db_error, 'Report API Call SQL Error'); */

        $data = array();

        for ($r = 0; $r < $values_count; $r++) {
            for ($i = 0; $i < $key_count; $i++) {
                $data[$r][$content->data->keys[$i]] = $content->data->values[$r][$i];
            }
        }
        }
$data = array();
        return $data;
    }

    public function request($item, $default = '') {
        if (isset($_REQUEST[$item]) && $_REQUEST[$item]) {
            return $_REQUEST[$item];
        } else return $default;
    }
    /*==============================================================================
    ================================================================================
    ================================================================================*/


    public function getCreate()
    {
        // Show the page
        return View::make('backend/reports/create');
    }

    /**
     * Internal Report create.
     *
     * @return View
     */
    public function generate()
    {
        // Show the page
        return View::make('backend/reports/generate');
    }



    /**
     * Report create form processing.
     *
     * @return Redirect
     */
    public function postCreate()
    {

        
    }

    /**
     * Report update.
     *
     * @param  int  $postId
     * @return View
     */


}
