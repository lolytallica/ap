<?php
use DB;

$permissions = DB::table('permissions')->get();

$arr = '';
$perm = array();
foreach($permissions as $permission)
{

    $perm[$permission->label] = array(array('permission' => $permission->permission, 'label' => $permission->label));

}

return $perm;

//return $arr;
//@todo: get Permissions from table
/*return array(
    ///USERS
    'Create Users'  => array( array( 'permission' => 'create_users', 'label' => 'Create Users' , ), ),
    'Manage Users'  => array( array( 'permission' => 'manage_users', 'label' => 'Manage Users' , ), ),
    'Manage Groups' => array( array( 'permission' => 'manage_groups', 'label' => 'Manage Groups' , ), ),


    ///MERCHANTS, MA, PARTNERS && PA
    'Manage merchants' => array( array( 'permission' => 'merchant', 'label' => 'Manage merchants' , ), ),
    'Manage Merchant Agreements' => array( array( 'permission' => 'merchantagreement', 'label' => 'Manage Merchant Agreements' , ), ),
    'Manage Partners' => array( array( 'permission' => 'partner', 'label' => 'Manage Partners' , ), ),

    ///INVOICES
    'View Merchant Agreement Invoices' => array( array( 'permission' => 'view_ma_invoices', 'label' => 'View Merchant Agreement Invoices' , ), ),
    'Manage Merchant Agreement Invoices' => array( array( 'permission' => 'manage_ma_invoices', 'label' => 'Manage Merchant Agreement Invoices' , ), ),

    'View Partner Invoices' => array( array( 'permission' => 'view_partner_invoices', 'label' => 'View Partner Invoices' , ), ),
    'Manage Partner Invoices' => array( array( 'permission' => 'manage_partner_invoices', 'label' => 'Manage Partner Invoices' , ), ),

    ///PAYMENTS
    'View Merchants Payments' => array( array( 'permission' => 'view_ma_payments', 'label' => 'View Merchants Payments' , ), ),
    'Manage Merchants Payments' => array( array( 'permission' => 'manage_ma_payments', 'label' => 'Manage Merchants Payments' , ), ),

    'View Partner Payments' => array( array( 'permission' => 'view_partner_payments', 'label' => 'View Partner Payments' , ), ),
    'Manage Partner payments' => array( array( 'permission' => 'manage_partner_payments', 'label' => 'Manage Partner Payments' , ), ),

    ///REPORTS
    'Manage Reports' => array( array( 'permission' => 'manage_reports', 'label' => 'Manage Reports' , ), ),





    /*'Global' => array(
		array(
			'permission' => 'superuser',
			'label'      => 'Super User',
		),

	),


      'Admin' => array(
          array(
              'permission' => 'admin',
              'label'      => 'Admin Rights',
          ),
      ),*/

//);


