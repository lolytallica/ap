<?php namespace Controllers\Admin;

use AdminController;
use Input;
use Lang;
use Merchantagreement;
use Map;
use Redirect;
use Sentry;
use Str;
use Validator;
use View;
use DB;
use Classes\Merchantexceptions;


class PartnerinvoiceController extends AdminController {

    public function __construct()
    {
       $this->beforeFilter('hasAccess:manage_partner_invoices');
    }

    /**
     * Declare the rules for the form validation
     *
     * @var array
     */
    protected $validationRules = array(
        'name' => 'required',

    );


    /**
     * Show a list of all the Mecrhant agreements.
     *
     * @return View
     */
    public function getIndex()
    {
        // Grab ALL Partner Invoices and show on index
        $partnerinvoice  = ''; //Partnerinvoice::orderBy('id')->paginate(10);

        // Show the page
        return View::make('backend/partnerinvoice/index', compact('partnerinvoice'));
    }

    /**
     * Create Merchant agreement
     *
     * @return View
     */

    public function getShow($id)

    {


        return View::make('backend/partnerinvoice/show');

    }



    public function getCreate()
    {


        // Show the page
        return View::make('backend/partnerinvoice/create');
    }



    /**
     * create form processing.
     *
     * @return Redirect
     */
    public function postCreate()
    {


        // Redirect to the ma page
       // return Redirect::route('update/partnerinvoice', $id)->withInput()->with('error', $error);


    }

    /**
     * Edit merchant agreement
     *
     * @return View
     */
    public function getEdit($id)
    {
        /*$ma = Merchantagreement::find($id);

        $map = Map::join('ma_map','map.id','=', 'ma_map.map_id')->where('ma_map.merchantagreement_id', '=', $id)->get();

        $allparameters = Map::get();

        $allstatus = DB::table('map_status')->get();

        $mapstatus = Map::join('ma_map','map.id','=', 'ma_map.map_id')->join('map_status','map_status.id','=', 'ma_map.status_id')->where('ma_map.merchantagreement_id', '=', $id)->get();

        // Show the page

        return View::make('backend/partner/edit', compact('ma','map','allparameters','allstatus','mapstatus'));  */
    }

    /**
     * Edit form processing.
     *
     * @return Redirect
     */
    public function postEdit($id = null)
    {


        // Redirect to the ma page
      //  return Redirect::route('update/partner', $id)->withInput()->with('error', $error);

    }



}
