<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - A.R.P</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- google font -->
    <link href="http://fonts.googleapis.com/css?family=Aclonica:regular" rel="stylesheet" type="text/css" />

    <!-- styles -->
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/stilearn.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/stilearn-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/stilearn-helper.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/stilearn-icon.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/uniform.default.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-wysihtml5.css') }}" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
</head>

<body>
<!-- section header -->
@if(Sentry::check())
<header class="header">
    <!--nav bar helper-->
    <div class="navbar-helper">
        <div class="row-fluid">
            <!--panel site-name-->
            <div class="span2">
                <div class="panel-sitename">
                    <h2><a href="{{ URL::to('admin') }}">A.R.P</a></h2>
                </div>
            </div>
            <!--/panel name-->

            <?php
           /* $paid_invoices = Cache::get('paid_invoices');
            $ppaid_invoices = Cache::get('ppaid_invoices');
            $overdue_invoices = Cache::get('overdue_invoices');
            $payments = Cache::get('payments');
            $merchantagreements = Cache::get('merchantagreements'); */

            //Notifications
            $notifications = array(); $url=array();
            if(Cache::get('numoverdueinvoices')>0)
            {
                $notifications['Invoices overdue'] = Cache::get('numoverdueinvoices');
                $url['Invoices overdue'] = route('status/mainvoice', 6) ;
            }

            if(Cache::get('numtobepaid')>0)
            {
                $notifications['Invoices to be paid'] = Cache::get('numtobepaid');
                $url['Invoices to be paid'] = route('status/mainvoice', 2) ;
            }

            if(Cache::get('numdraftinvoices')>0 && !Sentry::getUser()->merchant_id && !Sentry::getUser()->merchantagreement_id)
            {
                $notifications['Invoices to be approved'] = Cache::get('numdraftinvoices');
                $url['Invoices to be approved'] = route('status/mainvoice', 1) ;
            }

            ?>

            <div class="span4">
                <!--panel button ext-->
                <div class="panel-ext">
                    <div class="btn-group">
                        @if(count($notifications)>0)
                        <!--notification-->
                        <a class="btn btn-danger btn-small" data-toggle="dropdown" href="#" title="{{count($notifications)}} notification">{{count($notifications)}}</a>
                        <ul class="dropdown-menu dropdown-notification">
                            <li class="dropdown-header grd-white"><a href="#">View All Notifications</a></li>
                            @foreach(array_keys($notifications) as $note)
                            <li class="new">
                                <a href="{{$url[$note]}}">
                                    <div class="notification">{{$notifications[$note].' ' .$note}}</div>

                                </a>
                            </li>
                            @endforeach


                            <!-- <li class="dropdown-footer"><a href=""></a></li> -->
                        </ul><!--notification-->
                        @endif
                    </div>
                    <div class="btn-group">
                        <a class="btn btn-inverse btn-small dropdown-toggle" data-toggle="dropdown" href="#">
                            Shortcut
                        </a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <li><a tabindex="-1" href="">Pending Invoices</a></li>
                            <li><a tabindex="-1" href="">Overdue</a></li>

                            <li class="divider"></li>
                            <li class="dropdown-submenu">
                                <a tabindex="-1" href="#">Pending Payments</a>
                                <ul class="dropdown-menu">
                                    <li><a tabindex="-1" href="pricing.html">Search</a></li>

                                </ul>
                            </li>

                        </ul>
                    </div>



                </div><!--panel button ext-->
            </div>
        </div>
    </div><!--/nav bar helper-->
</header>

<!-- section content -->
<section class="section">
<div class="row-fluid">
<!-- span side-left -->
<div class="span1" style="height: 1100px">

    <!--side bar-->
    <aside class="side-left" >
        <ul class="sidebar">
            <li {{(Request::is('admin') ? ' class="active"' : '')}}> <!--always define class .first for first-child of li element sidebar left-->
                <a href="{{ route('admin') }}" title="dashboard">
                    <div class="helper-font-24">
                        <i class="icofont-dashboard"></i>
                    </div>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>
            @if(Sentry::getUser()->hasAccess('view_ma_invoices') || Sentry::getUser()->hasAccess('view_partner_invoices'))
            <li {{ (Request::is('admin/mainvoice*') ? ' class="active"' : '') }}>
                <a href="" title="invoice">
                    <div class="helper-font-24">
                        <i class="icofont-barcode"></i>
                    </div>
                    <span class="sidebar-text">Invoices</span>
                </a>
                <ul class="sub-sidebar corner-top shadow-silver-dark">
                    @if(Sentry::getUser()->hasAccess('view_ma_invoices') )
                    <li>
                        <a href="{{ URL::to('admin/mainvoice') }}" title="Merchant Agreement Invoices">
                            <div class="helper-font-24">

                            </div>
                            <span class="sidebar-text">Merchants</span>
                        </a>
                    </li>
                    @endif
                    @if(Sentry::getUser()->hasAccess('view_partner_invoices'))
                    <li>
                        <a href="{{ URL::to('admin/partnerinvoice') }}" title="Partner Agreement Invoices">
                            <div class="helper-font-24">

                            </div>
                            <span class="sidebar-text">Partners</span>
                        </a>
                    </li>
                    @endif
                    </ul>
            </li>

            @endif

            @if(Sentry::getUser()->hasAccess('view_ma_payments') || Sentry::getUser()->hasAccess('view_partner_payments'))
            <li {{ (Request::is('admin/mapayment*') ? ' class="active"' : '') }}>
                <a href="" title="Merchant Agreement Payments">
                    <div class="helper-font-24">
                        <i class="icofont-money"></i>
                    </div>
                    <span class="sidebar-text">Payments</span>
                </a>
            <ul class="sub-sidebar corner-top shadow-silver-dark">
                @if(Sentry::getUser()->hasAccess('view_ma_payments') )
                <li {{ (Request::is('admin/mapayment*') ? ' class="active"' : '') }}>
                    <a href="{{ URL::to('admin/mapayment') }}" title="Merchant Agreement Payments">
                        <div class="helper-font-24">

                        </div>
                        <span class="sidebar-text">Merchants</span>
                    </a>
                </li>
                @endif
                @if(Sentry::getUser()->hasAccess('view_partner_payments'))
                <li {{ (Request::is('admin/papayment*') ? ' class="active"' : '') }}>
                    <a href="{{ URL::to('admin') }}" title="Partner Agreement Invoices">
                        <div class="helper-font-24">

                        </div>
                        <span class="sidebar-text">Partners</span>
                    </a>
                </li>
                @endif
            </ul>
            </li>
            @endif

            @if(Sentry::getUser()->hasAccess('manage_reports'))
            <li {{ (Request::is('admin/report*') ? ' class="active"' : '') }}>
                <a href="{{ URL::to('admin/reports/searchreport') }}" title="reporting">
                    <div class="helper-font-24">
                        <i class="icofont-bar-chart"></i>
                    </div>
                    <span class="sidebar-text">Reporting</span>
                </a>
            </li>
            @endif

            @if(Sentry::getUser()->hasAccess('merchantagreement'))
            <li {{ (Request::is('admin/merchantagreement*') ? ' class="active"' : '') }}>
                <a href=" {{ URL::to('admin/merchantagreement') }} " title="merchantagreement">
                    <div class="helper-font-24">
                        <i class="icofont-briefcase"></i>
                    </div>
                    <span class="sidebar-text">Merchant Agreements</span>
                </a>
            </li>
            @endif

            @if(Sentry::getUser()->hasAccess('merchant'))
            <li {{ ( ((Request::is('admin/merchant*')) && (!Request::is('admin/merchantagreement*')))  ? ' class="active"' : '') }}>
            <a href=" {{ URL::to('admin/merchant') }} " title="merchants">
                <div class="helper-font-24">
                    <i class="icofont-briefcase"></i>
                </div>
                <span class="sidebar-text">Merchants</span>
            </a>
            </li>
            @endif

            @if(Sentry::getUser()->hasAccess('partner'))
            <li {{ (Request::is('admin/partner*') ? ' class="active"' : '') }}>
            <a href=" {{ URL::to('admin/partner') }} " title="partner">
                <div class="helper-font-24">
                    <i class="icofont-briefcase"></i>
                </div>
                <span class="sidebar-text">Partners</span>
            </a>
            </li>
            @endif

            @if(Sentry::getUser()->hasAccess('manage_groups')  || Sentry::getUser()->hasAccess('view_groups') || Sentry::getUser()->hasAccess('manage_users') || Sentry::getUser()->hasAccess('view_users'))
            <li>
                <a href="" title="users">
                    <div class="helper-font-24">
                        <i class="icofont-group"></i>
                    </div>
                    <span class="sidebar-text">Users</span>
                </a>
                <ul class="sub-sidebar corner-top shadow-silver-dark">
                    @if(Sentry::getUser()->hasAccess('manage_users') || Sentry::getUser()->hasAccess('view_users'))
                    <li >
                        <a href="{{ URL::to('admin/users') }}" title="not found">
                            <div class="helper-font-24">

                            </div>
                            <span class="sidebar-text">Users</span>
                        </a>
                    </li>
                    @endif
                    @if(Sentry::getUser()->hasAccess('manage_groups') || Sentry::getUser()->hasAccess('view_groups'))
                    <li>
                        <a href="{{ URL::to('admin/groups') }}" title="login">
                            <div class="helper-font-24">

                            </div>
                            <span class="sidebar-text">Groups</span>
                        </a>
                    </li>
                    @endif
                </ul>
            </li>

            @endif


            <li>
                <a href="{{ URL::to('account/profile') }}" title="profile">
                    <div class="helper-font-24">
                        <i class="icofont-user"></i>
                    </div>
                    <span class="sidebar-text">Your profile</span>
                </a>
            </li>

            <li>
                <a href="{{ URL::route('logout') }}" title="logout">
                    <div class="helper-font-24">
                        <i class="icofont-signout"></i>
                    </div>
                    <span class="sidebar-text">Logout</span>
                </a>
            </li>


                </ul>
            </li>
        </ul>
    </aside><!--/side bar -->
</div><!-- span side-left -->


<!-- span content -->
<div class="span9">
<!-- content -->
<div class="content">





    <!-- Notifications -->
    @include('frontend/notifications')

    <!-- Content -->
    @yield('content')

</div><!-- /content -->
</div><!-- /span content -->





</div>


</section>

<!-- section footer -->
<footer>
    <a rel="to-top" href="#top"><i class="icofont-circle-arrow-up"></i></a>
</footer>

<!-- javascript
================================================== -->
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/uniform/jquery.uniform.js') }}"></script>
<script src="{{ asset('assets/js/peity/jquery.peity.js') }}"></script>

<script src="{{ asset('assets/js/select2/select2.js') }}"></script>
<script src="{{ asset('assets/js/knob/jquery.knob.js') }}"></script>
<script src="{{ asset('assets/js/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('assets/js/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('assets/js/flot/jquery.flot.categories.js') }}"></script>
<script src="{{ asset('assets/js/wysihtml5/wysihtml5-0.3.0.js') }}"></script>
<script src="{{ asset('assets/js/wysihtml5/bootstrap-wysihtml5.js') }}"></script>
<script src="{{ asset('assets/js/calendar/fullcalendar.js') }}"></script> <!-- this plugin required jquery ui-->

<!-- required stilearn template js, for full feature-->
<script src="{{ asset('assets/js/holder.js') }}"></script>
<script src="{{ asset('assets/js/stilearn-base.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/bootstrap-datepicker.js') }}"></script>

<script src="{{ asset('assets/js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/extras/ZeroClipboard.js') }}"></script>
<script src="{{ asset('assets/js/datatables/extras/TableTools.min.js') }}"></script>
<script src="{{ asset('assets/js/datatables/DT_bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/responsive-tables/responsive-tables.js') }}"></script>

<link href="{{ asset('assets/css/DT_bootstrap.css') }}" rel="stylesheet">
<link href="{{ asset('assets/css/responsive-tables.css') }}" rel="stylesheet">

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery-form-validator/2.1.47/jquery.form-validator.min.js"></script>

<script type="text/javascript">

<?php
            if(!@$merchantlist) { $merchantlist = '';}
            if(!@$merchantagreementlist) { $merchantagreementlist = '';}
            if(!@$searchform) { $searchform = '';}

            if(!@count($merchantprofile)) { $merchantprofile = '';}
            if(!@$searchfield) { $searchfield = '';}





            ?>

    $(document).ready(function() {
       /*===================================================== My JS ========================================
       =================================================================================================== */


        /*============= Report Form =============
         ========================================*/

///////
        $("#reportType").change(function(){
            var reporttypeID = $("#reportType").val();

            var searchform = '{{ $searchform }}';

            var merchants = '{{ $merchantlist }}' ;

            var statuses = '{{ $statuses }}';


            ///Enable save form when report type is selected

            ///

            var formfields='';

            $.each($.parseJSON(searchform), function(x,ffield) {

                if(ffield.reporttype_id == reporttypeID)
                {

                    if(ffield.fieldname == 'merchant' || ffield.fieldname == 'merchant_id')
                    {

                        formfields += '<tr><td>'+ffield.fielddescription+'</td><td><select id="'+ ffield.fieldname +'" name="'+ffield.fieldname+'" data-form="select2" style="width:200px" data-placeholder="Select merchant">';

                        formfields += '<option value="0" selected>All</option>';

                        $.each($.parseJSON(merchants), function(x, merchantname) {

                            formfields += '<option value="'+ merchantname.id +'" >'+ merchantname.merchant +'</option>';

                        });

                        formfields += '</select></td></tr>';
                    }
                    else
                    if((ffield.fieldname === 'date_from') || (ffield.fieldname === 'date_to'))
                    {
                        formfields += ' <tr><td>'+ffield.fielddescription+'</td><td><a href="javascript:void(0);" class="clickdate"><div class="input-append date" data-form="datepicker" data-date="" data-date-format="yyyy-mm-dd" id="'+ffield.fieldname+'"> <input id="'+ffield.fieldname+'" name="'+ffield.fieldname+'" class="grd-white" data-form="" size="16" type="text" value="" data-validation="'+ffield.data_validation+'" data-validation-format="'+ffield.data_validation_condition+'" > <span class="add-on"><i class="icon-th"></i></span> </div></a></td></tr>';


                        $(document).on('click','.clickdate',function(){
                            $('[data-form=datepicker]').datepicker();
                        });



                    }
                    else
                    if(ffield.fieldname == 'status')
                    {
                        switch(reporttypeID){
                            case '1':
                            {
                                statuses = '{{ $redemptions_statuses}}'; break;
                            }
                            case '2':
                            case '5':
                            {
                                statuses = '{{ $order_statuses}}'; break;
                            }
                            case '3':
                            {
                                statuses = '{{ $transaction_statuses}}'; break;
                            }
                            case '4':
                            {
                                statuses = '{{ $validation_statuses}}'; break;
                            }
                        }

          //  alert(statuses);

                        formfields += '<tr><td>'+ffield.fielddescription+'</td><td><select id="'+ ffield.fieldname +'" name="'+ffield.fieldname+'" data-form="select2" style="width:200px" data-placeholder="Select Status">';

                        formfields += '<option value="all" selected>All</option>';
                        formfields += '<option value="summary" selected>Summary</option>';

                        $.each($.parseJSON(statuses), function(x, status) {


                            formfields += '<option value="'+ status.id +'" >'+status.id+' '+ status.event +'</option>';

                        });

                        formfields += '</select></td></tr>';
                    }
                    else
                    {
                        formfields += '<tr><td>'+ffield.fielddescription+'</td><td><input type="text" class="grd-white" id="'+ ffield.fieldname +'" name="'+ ffield.fieldname +'" value="" placeholder="" data-validation = "'+ffield.data_validation+'" data-validation-optional="true" /></td></tr>';
                    }


                    $("#formFields").empty();
                    $("#formFields").append(formfields);

                }
                else
                {
                    formfields = '';


                }

                /*============= Submit Search Form ===*/

                $("#savesearchform").click(function () {

                    alert('modal submit');
                   /* $.ajax({
                        type: "POST",
                        url: "{{URL::to('savereportsearch')}},
                        data: $($('form#savesearchform')).serialize(),
                        success: function (msg) {
                            $("#thanks").html(msg)
                           // $('savesearchform.saveform').modal('hide');
                        },
                        error: function () {
                            alert("failure");
                        }
                    });*/
                    return false;
                });

            });



        });

        //////////////////
        $(".addSearchField").click(function(){

            var addedSearchField = '';
            var searchFieldName =  $('#searchField').val();

            var merchants = '{{ $merchantlist }}' ;
            var merchantagreements = '{{ $merchantagreementlist }}' ;



            if($('#searchField').val() === 'merchant')
            {

                addedSearchField = '<select id="'+$('#searchField').val()+'" name="'+$('#searchField').val()+'" data-form="select2" style="width:150px" data-placeholder="Select merchant">';

                $.each($.parseJSON(merchants), function(x, merchantname) {

                    addedSearchField += '<option value="'+ merchantname.id +'" selected>'+ merchantname.merchant +'</option>';

                });

                addedSearchField += '</select>';

            }
            else
            if($('#searchField').val() === 'merchantagreement')
            {
                addedSearchField = '<select id="'+$('#searchField').val()+'" name="'+$('#searchField').val()+'" data-form="select2" style="width:150px" data-placeholder="Select merchantagreement" >';

                $.each($.parseJSON(merchantagreements), function(x, ma) {

                    addedSearchField += '<option value="'+ ma.id +'" selected>'+ ma.name +'</option>';

                });

                addedSearchField += '</select>';
            }
            else
            if(($('#searchField').val() === 'merchantcreateddate') || ($('#searchField').val() === 'validateddatetime'))
            {
                addedSearchField = ' <a href="javascript:void(0);" class="clickdate"><div class="input-append date" data-form="datepicker" data-date="" data-date-format="yyyy-mm-dd"> <input id="'+$('#searchField').val()+'" name="'+$('#searchField').val()+'" class="grd-white" data-form="" size="16" type="text" value="" data-validation="date" data-validation-format="yyyy-mm-dd" data-validation-help="Please enter a valid date"> <span class="add-on"><i class="icon-th"></i></span> </div></a>';


                $(document).on('click','.clickdate',function(){
                    $('[data-form=datepicker]').datepicker();
                });

            }
            else
            {
                var dataValidation = '';

                switch($('#searchField').val())
                {
                    case 'status_id':
                    case 'order_id':
                    case 'merchant_id':
                    case 'product_id':
                    case 'pspaccount_id' :
                    {
                        dataValidation = 'number';
                        break;
                    }
                    case 'email' :
                    {
                        dataValidation = 'email';
                        break;
                    }
                    default:
                    {
                        dataValidation = 'required';
                    }
                }

                addedSearchField = '<input type="text" class="grd-white" id="'+ $('#searchField').val() +'" name="'+ $('#searchField').val() +'" value="" placeholder="" data-validation = "'+dataValidation+'" />';
            }

            if($('#searchField').val() === 'select')
            {
                alert('Please select a field');
            }
            else
            if ($('#'+searchFieldName).length == 0){
            $("#searchFields").append('<tr><td>' + searchFieldName + '</td><td>'+ addedSearchField +' &nbsp; </td><td><a href="javascript:void(0);" class="remSF"><button id="removeSearchField" name="removeSearchField" type="button" class="btn btn-small btn-danger removeSearchField"><i class="icon-minus-sign icon-white"></i>  </button></a>  </td></tr>');
            }
            else
            {
                alert(searchFieldName+' already added');
            }
        });
        $("#searchFields").on('click','.remSF',function(){
            $(this).parent().parent().remove();
        });


        $.validate();

                                              /*============= Invoice Edit =============
                                              ========================================*/

        ////INVOICE ADDITIONAL COSTS
        $(".addCost").click(function(){
            $("#customFields").append('<tr valign="top"><th scope="row"><label for="description">Description</label></th><td><input type="text" class="code" id="description" name="description[]" value="" placeholder="Cost Description" /> &nbsp; <input type="text" class="code" id="amount" name="amount[]" value="" placeholder="Cost Amount" /> &nbsp; <input type="text" class="code" id="comments" name="comments[]" value="" placeholder="Comments" /> &nbsp; <a href="javascript:void(0);" class="remCF"><i class="icon-remove-sign"></i> Remove</a>  </td></tr>');
        });
        $("#customFields").on('click','.remCF',function(){
            $(this).parent().parent().remove();
        });

        ////INVOICE ADDITIONAL HELDS
        $(".addHeld").click(function(){
            $("#customHeld").append('<tr valign="top"><th scope="row"><label for="held_reason">Description</label></th><td colspan="3"><input type="text" class="code" id="held_reason" name="held_reason[]" value="" placeholder="Held reason" /> &nbsp; <input type="text" class="code" id="held_amount" name="held_amount[]" value="" placeholder="Held Amount" /> &nbsp; <input type="text" class="code" id="held_comments" name="held_comments[]" value="" placeholder="Comments" /> &nbsp; <a href="javascript:void(0);" class="remCH"><i class="icon-remove-sign"></i> Remove</a>  </td></tr>');
        });
        $("#customHeld").on('click','.remCH',function(){
            $(this).parent().parent().remove();
        });

        ////INVOICE ADDITIONAL INCOME
        $(".addIncome").click(function(){
            $("#incomeFields").append('<tr valign="top"><th scope="row"><label for="incomeDescription">Description</label></th><td><input type="text" class="code" id="incomeDescription" name="incomeDescription[]" value="" placeholder="Income Description" /> &nbsp; <input type="text" class="code" id="incomeAmount" name="incomeAmount[]" value="" placeholder="Income Amount" /> &nbsp; <input type="text" class="code" id="incomeComments" name="incomeComments[]" value="" placeholder="Comments" /> &nbsp; <a href="javascript:void(0);" class="remIF"><i class="icon-remove-sign"></i> Remove</a>  </td></tr>');
        });
        $("#incomeFields").on('click','.remIF',function(){
            $(this).parent().parent().remove();
        });
                                            /*======================================================================================*/

        // normalize event tab-stat, we hack something here couse the flot re-draw event is any some bugs for this case
        $('#tab-stat > a[data-toggle="tab"]').on('shown', function(){
            if(sessionStorage.mode == 4){ // this hack only for mode side-only
                $('body,html').animate({
                    scrollTop: 0
                }, 'slow');
            }
        });

        // datepicker
        $('[data-form=datepicker]').datepicker();

        // peity chart
        $("span[data-chart=peity-bar]").peity("bar");

        // Input tags with select2
        $('input[name=reseiver]').select2({
            tags:[]
        });



        // select2

        $('[data-form=select2]').select2();
        $('[data-form=select2-group]').select2();


        // uniform
        $('[data-form=uniform]').uniform();

        // wysihtml5
        $('[data-form=wysihtml5]').wysihtml5()
        toolbar = $('[data-form=wysihtml5]').prev();
        btn = toolbar.find('.btn');

        $.each(btn, function(k, v){
            $(v).addClass('btn-mini')
        });

        // Server stat circular by knob
        $("input[data-chart=knob]").knob();

        ////groups multiselect
        $('#select_groups').select2({
            placeholder: 'Groups',
            minimumInputLength: 3,
            ajax: {
                url: '/users/select_groups',
                dataType: 'json',
                data: function (term, page) {
                    return {
                        select_groups: term
                    };
                },
                results: function (data, page) {
                    return {results: data};
                }
            },
            tags: true
        });
        //////////

                                                /*================= Payment =================*/
                                                /*===========================================*/

        //CONVERT PAYABLE AMOUNT
        function floorFigure(figure, decimals){
            if (!decimals) decimals = 2;
            var d = Math.pow(10,decimals);
            return (parseInt(figure*d)/d).toFixed(decimals);
        }


        ////CALCULATE PAYMENT AMOUNTS

        var payout_total;
        var amountpaid;
        var payout_val = 0;
        var payout_currency;
        var process_currency;
        var new_balance;
        var convrate_val;
        var payment_held = 0;
        var errorminpayout = 0;


        var min_payout = $("#min_payout").val();

        //var max_payout = parseFloat($("#payable_hid").val()) - parseFloat($("#amountpaid_hid").val());
        var max_payout = parseFloat($("#payable_hid").val() - $("#amountpaid_hid").val());

        var payout_processed = $("#payable_hid").val() - $("#amountpaid_hid").val();

        var amount_processed = $('#amount_processed').val();

        var res = amount_processed - min_payout; // payout_processed % min_payout

     //   alert(payout_processed.toFixed(2));
     //   alert(max_payout);

        $('#conversionrate').change(function(){

            if(parseFloat($('#amount_processed').val()) < parseFloat(min_payout) )
            {
                alert('Amount paid can not be lower than '+min_payout);
                errorminpayout = 1;
                $('#amount_processed').attr('value',payout_processed.toFixed(2));
            }
            else
            //if((parseFloat(res) < parseFloat(min_payout)) && (res != 0) && ($('#amount_processed').val() < payout_processed) )
            if((Math.round(parseFloat(res)*100)/100) < (Math.round(parseFloat(min_payout)*100)/100) && (res!='0') && (Math.round(parseFloat($('#amount_processed').val() )*100)/100)<(Math.round(parseFloat(payout_processed)*100)/100) )
            {
                alert('Amount paid can not be lower than '+ payout_processed.toFixed(2)+' !!');
                 errorminpayout = 2;
                $('#amount_processed').attr('value',payout_processed.toFixed(2));
            }
            else
            {
            payout_currency  = $("#payoutcurrency").val()
            process_currency = $("#processcurrency").val()
            payout_total     = $("#payable_hid").val() * $(this).val()
            amountpaid       = $("#amountpaid_hid").val() * $(this).val()
            payout_val       = $("#amount_processed").val() * $(this).val()


            $('#payout').attr('value',payout_val.toFixed(2))
            $('#payout_hid').attr('value',$("#payout").val())

            if(payout_val!=0)
            {
                payment_held = $("#payable_hid").val() - $("#amountpaid_hid").val() - $("#amount_processed").val()


                $('#paymentheld').attr('value',payment_held.toFixed(2) );
                $('#paymentheld_hid').attr('value',$('#paymentheld').val() );

                new_balance = parseFloat($("#balance").val()) - parseFloat($("#amount_processed").val())
                $('#newbalance').attr('value',new_balance.toFixed(2) + ' ' + process_currency);
            }
            return false;

            }

        });

        $('#amount_processed').change(function(){

            if($(this).val() > max_payout)
            {
                alert('Amount to pay can not be higher than '+max_payout.toFixed(2));
                $(this).attr('value',payout_processed.toFixed(2) );
            }
            else
            if(parseFloat($(this).val()) < parseFloat(min_payout) )
            {
                alert('Amount paid can not be lower than '+min_payout);
                errorminpayout = 1;
                $(this).attr('value',payout_processed.toFixed(2));
            }
            else
            //if((parseFloat(res) < parseFloat(min_payout) ) && (res != 0) && ( parseFloat($(this).val()) < parseFloat(payout_processed) ) )
            if((Math.round(parseFloat(res)*100)/100) < (Math.round(parseFloat(min_payout)*100)/100) && (res!='0') && (Math.round(parseFloat($('#amount_processed').val() )*100)/100)<(Math.round(parseFloat(payout_processed)*100)/100) )
            {
                alert('Amount paid can not be lower than '+payout_processed.toFixed(2));
                errorminpayout = 2;
                $(this).attr('value',payout_processed.toFixed(2));
            }
            else
            {
            convrate_val     = $("#conversionrate").val()
            payout_currency  = $("#payoutcurrency").val()
            process_currency = $("#processcurrency").val()
            payout_total     = $("#payable_hid").val() * convrate_val
            amountpaid       = $("#amountpaid_hid").val() *  $('#amount_processed').val()

            payout_val = convrate_val *  $(this).val()


            $('#payout').attr('value',payout_val);
            $('#payout_hid').attr('value',$("#payout").val())


            if(payout_val!=0)
            {
                payment_held = $("#payable_hid").val() - $("#amountpaid_hid").val() - $(this).val()


                $('#paymentheld').attr('value',payment_held )
                $('#paymentheld_hid').attr('value',$('#paymentheld').val() );

                new_balance = parseFloat($("#balance").val()) - parseFloat($(this).val())
                $('#newbalance').attr('value',new_balance.toFixed(2) + ' ' + process_currency);
            }

            return false;
            }

        });
        ///////////////////////////////////////////////////////END CONVERSION
        ////////////////////////////////////////////////////////////////////////////////////////////////
        ////////////////////////////////////////////////////////////////////////////////////////////////

        // datatables
        $('#datatables').dataTable( {
            "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "sPaginationType": "bootstrap",
            "oLanguage": {
                "sLengthMenu": "_MENU_ records per page"
            }
        });

        // datatables table tools
        $('#datatablestools').dataTable({
            "sDom": "<'row-fluid'<'span6'T><'span6'f>r>t<'row-fluid'<'span6'i><'span6'p>>",
            "oTableTools": {
                "aButtons": [
                    "copy",
                    "print",
                    {
                        "sExtends":    "collection",
                        "sButtonText": 'Save <span class="caret" />',
                        "aButtons":    [
                            "xls",
                            "csv",
                            {
                                "sExtends": "pdf",
                                "sPdfOrientation": "landscape",
                                "sPdfMessage": ""
                            }
                        ]
                    }
                ],
                "sSwfPath": "{{ asset('assets/js/datatables/swf/copy_csv_xls_pdf.swf') }}"
            }
        });
        //End datatables

        // system stat flot
        d1 = [ ['jan', 231], ['feb', 243], ['mar', 323], ['apr', 352], ['maj', 354], ['jun', 467], ['jul', 429] ];
        d2 = [ ['jan', 87], ['feb', 67], ['mar', 96], ['apr', 105], ['maj', 98], ['jun', 53], ['jul', 87] ];
        d3 = [ ['jan', 34], ['feb', 27], ['mar', 46], ['apr', 65], ['maj', 47], ['jun', 79], ['jul', 95] ];

        var invoice = $("#transaction-ma"),
            order = $("#chb-ma"),
            user = $("#refunds-ma"),

            data_invoice = [{
                data: d1,
                color: '#00A600'
            }],
            data_order = [{
                data: d2,
                color: '#2E8DEF'
            }],
            data_user = [{
                data: d3,
                color: '#DC572E'
            }],


            options_lines = {
                series: {
                    lines: {
                        show: true,
                        fill: true
                    },
                    points: {
                        show: true
                    },
                    hoverable: true
                },
                grid: {
                    backgroundColor: '#FFFFFF',
                    borderWidth: 1,
                    borderColor: '#CDCDCD',
                    hoverable: true
                },
                legend: {
                    show: false
                },
                xaxis: {
                    mode: "categories",
                    tickLength: 0
                },
                yaxis: {
                    autoscaleMargin: 2
                }

            };

        // render stat flot
        $.plot(invoice, data_invoice, options_lines);
        $.plot(order, data_order, options_lines);
        $.plot(user, data_user, options_lines);

        // tootips chart
        function showTooltip(x, y, contents) {
            $('<div id="tooltip" class="bg-black corner-all color-white">' + contents + '</div>').css( {
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 5,
                border: '0px',
                padding: '2px 10px 2px 10px',
                opacity: 0.9,
                'font-size' : '11px'
            }).appendTo("body").fadeIn(200);
        }

        var previousPoint = null;
        $('#invoice-stat, #order-stat, #user-stat').bind("plothover", function (event, pos, item) {

            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;

                    $("#tooltip").remove();
                    var x = item.datapoint[0].toFixed(2),
                        y = item.datapoint[1].toFixed(2);
                    label = item.series.xaxis.ticks[item.datapoint[0]].label;

                    showTooltip(item.pageX, item.pageY,
                        label + " = " + y);
                }
            }
            else {
                $("#tooltip").remove();
                previousPoint = null;
            }

        });
        // end tootips chart




        // Schedule Calendar
        var date = new Date();
        var d = date.getDate();
        var m = date.getMonth();
        var y = date.getFullYear();

        var calendar = $('#schedule').fullCalendar({
            header: {
                left: 'title',
                center: '',
                right: 'prev,next today,month,basicWeek,basicDay'
            },
            events: [
                {
                    title: 'Merchant 1 Payment',
                    start: new Date(y, m, 2)
                },
                {
                    title: 'Merchant 2 Invoice',
                    start: new Date(y, m, 3),
                    end: new Date(y, m, 7)
                },
                {
                    title: 'Merchant 1 Invoice',
                    start: new Date(y, m, 9),
                    end: new Date(y, m, 12)
                },
                {
                    title: 'Merchnat 3 Payment',
                    start: new Date(y, m, 19, 10, 30),
                    allDay: false
                },
                {
                    title: 'Partner 1 Payment',
                    start: new Date(y, m, 28, 10, 30),
                    allDay: false
                },
                {
                    title: 'Partner 1 Invoice',
                    start: new Date(y, m, d, 12, 0),
                    end: new Date(y, m, d, 14, 0),
                    allDay: false
                },
                {
                    title: 'Merchant 2 Payment',
                    start: new Date(y, m, d+1, 19, 0),
                    end: new Date(y, m, d+1, 22, 30),
                    allDay: false
                }
            ]
        });
        // end Schedule Calendar
    });

</script>
@endif
</body>
</html>
