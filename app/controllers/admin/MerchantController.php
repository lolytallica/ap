<?php namespace Controllers\Admin;

use AdminController;
use Input;
use Lang;
use Merchantagreement;
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


class MerchantController extends AdminController {

    public function __construct()
    {
        $this->beforeFilter('hasAccess:merchant');
    }

    /**
     * Declare the rules for the form validation
     *
     * @var array
     */
    protected $validationRules = array(
        'merchant'      => 'required',
        'merchantemail' => 'required|email|unique:merchant',
        'abbreviation'  => 'required|max:3',
        'aguid'         => 'required',

    );

    protected $updateRules = array(
        'merchant'      => 'required',
        'merchantemail' => 'required|email',
        'abbreviation'  => 'required|max:3',
        'aguid'         => 'required',

    );



    /**
     * Show a list of all the Mecrhant agreements.
     *
     * @return View
     */
    public function getIndex()
    {
        // Show all merchant agreements
        $merchants = Merchant::orderBy('id')->paginate(20);

        // Show the page
        return View::make('backend/merchant/index', compact('merchants'));
    }

    /**
     * Create Merchant agreement
     *
     * @return View
     */

    public function getShow($id)

    {

        $merchant = Merchant::find($id);

        $merchantagreements = Merchantagreement::where('merchant_id','=', $id)->get();

        return View::make('backend/merchant/show', compact('merchant', 'merchantagreements', 'allma'));

    }


    public function getCreate()
    {
        $allma = Merchantagreement::get();

        // Show the page
        return View::make('backend/merchant/create', compact('allma'));
    }



    /**
     * create merchant form processing.
     *
     * @return Redirect
     */
    public function postCreate()
    {
        //Grab all merchant agreement
        $allma = Merchantagreement::get();

        $merchantagreements = array();

        foreach($allma as $ma)
        {
            $merchantagreements[$ma->id] = '';
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
            $ups = 0;
            //
            $check = Merchant::where('merchant' ,'=', Input::get('merchant'))
                ->where('merchantemail' ,'=', Input::get('merchantemail'))
                ->first();

            if($check)
            {
                return Redirect::route('create/merchant')->withInput()->with('error', 'Merchant already exists');
            }


            //Create New merchant agreement
            $merchant = Merchant::firstOrCreate(
                array(
                'merchant'       => Input::get('merchant'),
                'merchantemail'  => Input::get('merchantemail'),
                'abbreviation'   => Input::get('abbreviation'),
                'aguid'          => Input::get('aguid'),
                'active'         => Input::get('active'),
                 )
            );

            $merchantId = $merchant->id;

            //Assign Merchant agreements
            foreach($allma as $ma)
            {
                $machecked = 'ma_'.$ma->id;
                $maId = Input::get($machecked);


                if($maId!='')
                {
                    $upma = Merchantagreement::where('id','=',$maId)->update(array('merchant_id' => $merchantId));

                    //Update merchant id in merchantagreement table
                    if($upma)
                    {
                        $ups ++;
                    }

                }

            }// end foreach


            // Was the merchant agreement saved?
            if ($merchantId && $ups>0)
            {
                // Redirect to the ma page
                return Redirect::route('show/merchant', $merchantId)->with('success', Lang::get('admin/merchant/message.success.create'));
            }
            else
            {
                // Redirect to the ma page
                return Redirect::route('create/merchant')->with('error', Lang::get('admin/merchant/message.error.create'));
            }
        }
        catch (MerchantRequiredException $e)
        {
            $error = Lang::get('admin/merchant/message.merchant_required');
        }

        // Redirect to the ma page
        return Redirect::route('update/merchant', $id)->withInput()->with('error', $error);


    }

    /**
     * Edit merchant
     *
     * @return View
     */
    public function getEdit($id)
    {
        $merchant = Merchant::find($id);

        $merchantagreements = Merchantagreement::where('merchant_id','=', $id)->orderBy('name', 'desc')->get();

        $allma = Merchantagreement::get();

        // Show the page

        return View::make('backend/merchant/edit', compact('merchant','merchantagreements','allma'));
    }

    /**
     * Edit form processing.
     *
     * @return Redirect
     */
    public function postEdit($id = null)
    {
        //Grab all merchant agreements
        $allma = Merchantagreement::get();

        $merchant = Merchant::find($id);

        $agreements = array();
        foreach($allma as $ma)
        {
            $agreements[$ma->id] = 0;
        }

        foreach($allma as $ma)
        {
            $machecked = 'ma_'.$ma->id;
            $maId = Input::get($machecked);

            if($maId)
            {
                $agreements[$ma->id] = $merchant->id;

            }


        }


      /*  foreach(array_keys($agreements) as $key)
        {
            echo $key.' : '.$agreements[$key].'<br>';
        }
        exit;*/



        if($merchant->merchantemail != Input::get('merchantemail'))
        {
            $updateRules['merchantemail'] = 'required|email|unique:merchant';

        }


        $validator = Validator::make(Input::all(), $this->updateRules);


        // If validation fails, we'll exit the operation now.
        if ($validator->fails())
        {
            // Ooops.. something went wrong
            return Redirect::back()->withInput()->withErrors($validator);
        }

        try
        {
            // Update the merchant  data
            $merchantId        = $merchant->id;
            $merchant->merchant      = Input::get('merchant');

            if($merchant->merchantemail != Input::get('merchantemail'))
            {
                $merchant->merchantemail = Input::get('merchantemail');
            }

            $merchant->abbreviation  = Input::get('abbreviation');
            $merchant->aguid         = Input::get('aguid');
            $merchant->active        = Input::get('active');

            //update assigned agreements
            foreach($allma as $mag)
            {
                ///check if agreement already assigned
                $check_ma = Merchantagreement::where('merchant_id','=', $merchantId)->first();

                if(!$check_ma && $agreements[$mag->id]!=0) //add
                {
                    $addagreement = Merchantagreement::where('id','=',$mag->id)->update(array('merchant_id' => $merchant->id));
                  //  echo 'add: '.$mag->name.' to '.$merchant->merchant.' <br>';
                }
                else //unlink
                {
                    if($check_ma && ($agreements[$mag->id]==0))
                    {
                        $unlinkagreement = Merchantagreement::where('id','=',$mag->id)
                            ->where('merchant_id','=',$id)
                            ->update(array('merchant_id' => '0'));
                   //     echo 'unlink: '.$mag->id.' and '.$merchant->merchant.' <br>';
                    }
                }

            }// end foreach
           // exit;

     /*       foreach(array_keys($agreements) as $agreementId)
            {
            //    echo $agreementId.' : '.$agreements[$agreementId].''; exit;
                $updateagreement = DB::table('merchantagreement')
                    ->where('id','=',$agreementId)
                    ->where('merchant_id','=',0)
                    ->update(array('merchant_id' => $agreements[$agreementId]));
            }*/


            // Was the merchant updated?
            if ($merchant->save() )
            {
                // Redirect to the merchant update page
                return Redirect::route('update/merchant', $id)->with('success', Lang::get('admin/merchant/message.success.update'));
            }
            else
            {
                // Redirect to the merchant update page
                return Redirect::route('update/merchant', $id)->with('error', Lang::get('admin/merchant/message.error.update'));
            }
        }
        catch (MerchantRequiredException $e)
        {
            $error = Lang::get('admin/merchant/message.merchant_required');
        }
        // Redirect to the merchant update page
        return Redirect::route('update/merchant', $id)->withInput()->with('error', $error);

    }



}
