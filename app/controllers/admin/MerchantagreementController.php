<?php namespace Controllers\Admin;

use AdminController;
use Input;
use Lang;
use Merchantagreement;
use Bankaccount;
use Merchant;
use Group;
use Map;
use Redirect;
use Sentry;
use Str;
use Validator;
use View;
use DB;
use Classes\Merchantexceptions;


class MerchantagreementController extends AdminController {

    public function __construct()
    {
        $this->beforeFilter('hasAccess:merchantagreement');
    }

/**
* Declare the rules for the form validation
*
* @var array
*/
    protected $validationRules = array(
            'name' => 'required',
            'description' => 'required',
            'percentage' => 'required|numeric',
            'report_length' => 'required|numeric',
            'min_payout' => 'required|numeric',
            'exchange_rate_profit' => 'required',
            'payout_delay' => 'required',
            'payoutcurrency' => 'required',
            //'holdback_percentage' => 'required_if_attribute:holdback_length,>,0',
            //'holdback_length' => 'required_if_attribute:holdback_percentage,>,0'
    );




    /**
     * Show a list of all the Mecrhant agreements.
     *
     * @return View
     */
    public function getIndex()
    {
        // Show all merchant agreements
          $merchantagreements = Merchantagreement::orderBy('id')->paginate(10);

        // Show the page
        return View::make('backend/merchantagreement/index', compact('merchantagreements'));
    }

    /**
     * Create Merchant agreement
     *
     * @return View
     */

    public function getShow($id)

    {
        $ma = Merchantagreement::find($id);

        //$map = $ma->map()->pivot;
        $maps = New Map();
        //Dispaly merchants parameter that are not deleted (status_id=4 Deleted)
        $map = $maps::join('ma_map','map.id','=', 'ma_map.map_id')->join('map_status','ma_map.status_id','=', 'map_status.id')->where('ma_map.merchantagreement_id', '=', $id)->get();

        $allmapstatus = $map;
       // var_dump($allmapstatus); exit;


        $statuses = $maps->currentstatus($id);

        $map = $statuses;

        //var_dump($statuses); exit;

        return View::make('backend/merchantagreement/show', compact('ma','map', 'allmapstatus'));

    }

    /**
     * @return status history
     *
     */
    public function getStatusHistorical($id)
    {
        $statushistorical = DB::table('map_status')->join('ma_map','map_status.id', '=', 'ma_map.status_id')->where('ma_map.merchantagreement_id','=', $id)->get();

        return $statushistorical;
    }


    public function getCreate()
    {
        $allparameters = Map::get();

        $currencies = DB::table('currency')->orderBy('alphacode')->get();

        $bankaccounts = Bankaccount::get();

        // Show the page
        return View::make('backend/merchantagreement/create', compact('allparameters','currencies','bankaccounts'));
    }



    /**
     * create form processing.
     *
     * @return Redirect
     */
    public function postCreate()
    {
        //Grab all merchant agreement parameters
        $allparameters = Map::get();

        $map = array();

        foreach($allparameters as $params)
        {
            $map[$params->parameter] = '';
        }


        $validator = Validator::make(Input::all(), $this->validationRules);


        // If validation fails, we'll exit the operation now.
        if ($validator->fails())
        {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try
        {
            //Flags
            $ins=0;

            //
            $check = Merchantagreement::where('name' ,'=', Input::get('name'))->where('description' ,'=', Input::get('description'))->where('activated' ,'=', Input::get('activated'))->first();
            if($check)
            {
                return Redirect::route('create/ma')->withInput()->with('error', 'Merchant Agreement already exists');
            }

           //Create New merchant agreement
            $ma = Merchantagreement::firstOrCreate(array('name' => Input::get('name'), 'bankaccount_id' => Input::get('bankaccount'), 'description' => Input::get('description'), 'activated' => Input::get('activated')));

            $merchantid = $ma->id;

            //Create merchant agreement parameters
            foreach($allparameters as $params)
            {
                $parameterid =  $params->id;
                $parameter = $params->parameter;
                $parametervalue = Input::get($parameter);
                if($parametervalue!='')
                {
                $insert_map = DB::table('ma_map')->insert(array('merchantagreement_id'=> $merchantid,  'map_id'=>  $parameterid, 'map_value' => $parametervalue)) ;
                if($insert_map) $ins++;
                }

            }// end foreach


            // Was the merchant agreement saved?
            if ($merchantid && $ins>0)
            {
                update_cache();
                // Redirect to the ma page
                return Redirect::route('create/ma')->with('success', Lang::get('admin/merchantagreement/message.success.create'));
            }
            else
            {
                // Redirect to the ma page
                return Redirect::route('create/ma')->with('error', Lang::get('admin/merchantagreement/message.error.create'));
            }
        }
        catch (NameRequiredException $e)
        {
            $error = Lang::get('admin/merchantagreement/message.ma_name_required');
        }

        // Redirect to the ma page
        return Redirect::route('update/merchantagreement', $id)->withInput()->with('error', $error);


    }

    /**
     * Edit merchant agreement
     *
     * @return View
     */
    public function getEdit($id)
    {
        $ma = Merchantagreement::find($id);

        $map = Map::join('ma_map','map.id','=', 'ma_map.map_id')->where('ma_map.merchantagreement_id', '=', $id)->get();

        $allparameters = Map::get();

        $allstatus = DB::table('map_status')->get();

        $mapstatus = Map::join('ma_map','map.id','=', 'ma_map.map_id')->join('map_status','map_status.id','=', 'ma_map.status_id')->where('ma_map.merchantagreement_id', '=', $id)->get();

        $currencies = DB::table('currency')->orderBy('alphacode')->get();

        $bankaccounts = Bankaccount::get();

        // Show the page

        return View::make('backend/merchantagreement/edit', compact('ma','map','allparameters','allstatus','mapstatus','currencies','bankaccounts'));
    }

    /**
     * Edit form processing.
     *
     * @return Redirect
     */
    public function postEdit($id = null)
    {
        //Grab all merchant agreement parameters
        $allparameters = Map::get();

        $map = array();

        foreach($allparameters as $params)
        {
            $map[$params->parameter] = '';
        }

        $ma = Merchantagreement::find($id);

        foreach($ma->map()->select('parameter', 'map_value', 'ma_map')->get() as $params)
        {

            $map[$params->parameter] = $params->map_value;

        }


       $validator = Validator::make(Input::all(), $this->validationRules);


        // If validation fails, we'll exit the operation now.
        if ($validator->fails())
        {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try
        {
            // Update the merchant agreement data
            $merchantid          = $ma->id;
            $ma->name            = Input::get('name');
            $ma->bankaccount_id  = Input::get('bankaccount');
            $ma->description     = Input::get('description');
            $ma->activated       = Input::get('activated');



            //flags
            $saveparams = 1;

            $up = 0;
            $ins = 0;

            //Update merchant agreement parameters
            foreach($allparameters as $params)
            {

                $parameterid =  $params->id;
                $parameter = $params->parameter;
                $status = 'status_'.$params->parameter;

                $parametervalue  = Input::get($parameter);
                $parameterstatus = Input::get($status);

                //$check = DB::table('ma_map')->where('merchantagreement_id','=', $merchantid)->where('map_id','=', $parameterid)->update(array('map_value' => $parametervalue, 'status_id' => $parameterstatus)) ;
                $check = DB::table('ma_map')->where('merchantagreement_id','=', $merchantid)->where('map_id','=', $parameterid)->orderBy('created_at','desc')->first(); //update(array('map_value' => $parametervalue, 'status_id' => $parameterstatus)) ;


               // echo $check->status_id.' | '.$check->map_value.'<br>'; exit;
               // var_dump($check); exit;
                /* try {

                    $update_pivot = DB::table('ma_map')->where('merchantagreement_id','=', $merchantid)->where('map_id','=', $parameterid)->update(array('map_value' => $parametervalue)) ;
                    $up++;
                }

                catch(\RuntimeException $e)
                {
                    $insert_pivot  = DB::table('ma_map')->insert(array('merchantagreement_id'=> $merchantid,  'map_id'=>  $parameterid, 'map_value' => $parametervalue)) ;
                    $ins++;
                } */

                if(!$check && ($parametervalue!=''))
                {
                    $insert_pivot  = DB::table('ma_map')->insert(array('merchantagreement_id'=> $merchantid,  'map_id'=>  $parameterid, 'map_value' => $parametervalue, 'status_id' => $parameterstatus)) ;
                    if($insert_pivot ) $ins++;

                }
                else
                {
                    if($check)
                    {

                    //get old status and value
                    $oldstatus = $check->status_id;
                    $oldvalue  = $check->map_value;

                if($parametervalue!= $oldvalue || $parameterstatus != $oldstatus)
                {
                    //Update old row with status = historical
                    $update_pivot  = DB::table('ma_map')->where('merchantagreement_id','=', $merchantid)->where('map_id','=', $parameterid)->where('status_id','=', $oldstatus)->update(array('updated_at' => date("Y-m-d H:i:s"),'status_id' => '2' )) ;

                    //insert new row with new values
                    $insert_new = DB::table('ma_map')->insert(array('merchantagreement_id'=> $merchantid,  'map_id'=>  $parameterid, 'map_value' => $parametervalue, 'status_id' => $parameterstatus)) ;

                    if($update_pivot && $insert_new) $up++;
                     }
                }
                }

            }// end foreach


            // Was the merchant agreement updated?
            if ($ma->save() || ( $up>0 || $ins>0))
            {
                // Redirect to the ma page
                return Redirect::route('update/ma', $id)->with('success', Lang::get('admin/merchantagreement/message.success.update'));
            }
            else
            {
                // Redirect to the ma page
                return Redirect::route('update/ma', $id)->with('error', Lang::get('admin/merchantagreement/message.error.update'));
            }
        }
        catch (NameRequiredException $e)
        {
            $error = Lang::get('admin/merchantagreement/message.ma_name_required');
        }

        // Redirect to the ma page
        return Redirect::route('update/merchantagreement', $id)->withInput()->with('error', $error);

    }



}
