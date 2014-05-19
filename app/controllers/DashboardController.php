<?php
/**
 * Created by PhpStorm.
 * User: loly
 * Date: 2/5/14
 * Time: 5:31 PM
 */


class DashboardController extends BaseController {


    public function __construct()
    {


    }

    /**
     * Display the dashboard
     * @return View
     */
    public function getIndex()
    {

       return route('admin');


    }

}
