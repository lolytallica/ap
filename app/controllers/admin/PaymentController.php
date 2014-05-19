<?php namespace Controllers\Admin;

use AdminController;
use Input;
use Lang;
use InternalReport;
use Redirect;
use Sentry;
use Str;
use Validator;
use View;

class PaymentController extends AdminController {

    public function __construct()
    {
        $this->beforeFilter('hasAccess:manage_payments_invoices');
    }



    /**
     * Show a list of all the Reports.
     *
     * @return View
     */
    public function getIndex()
    {
        // Grab all the internal Reports
        //    $Reports = InternalReport::orderBy('created_at', 'DESC')->paginate(10);

        // Show the page
        return View::make('backend/payments/index');
    }

    /**
     * Internal Report create.
     *
     * @return View
     */
    public function getCreate()
    {
        // Show the page
        return View::make('backend/payments/create');
    }



    /**
     * Payment create form processing.
     *
     * @return Redirect
     */
    public function postCreate()
    {

        
    }

    /**
     * Payment update.
     *
     * @param  int  $postId
     * @return View
     */


}
