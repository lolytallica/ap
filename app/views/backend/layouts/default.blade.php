<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic Page Needs
    ================================================== -->
    <meta charset="utf-8" />
    <title>
        @section('title')
        ARP Administration
        @show
    </title>
    <meta name="keywords" content="" />
    <meta name="author" content="Jon Doe" />
    <meta name="description" content="Lorem ipsum dolor sit amet, nihil fabulas et sea, nam posse menandri scripserit no, mei." />

    <!-- Mobile Specific Metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS
    ================================================== -->
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-responsive.css') }}" rel="stylesheet">



    <style>
        @section('styles')
        body {
            padding: 60px 0;
        }
        @show
    </style>

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- Favicons
    ================================================== -->
    <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/ico/favicon.png') }}">
</head>

<body>
<!-- Container -->
<div class="container">
    <!-- Navbar -->
    <div class="navbar navbar-inverse navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="nav-collapse collapse">
                    @if(Sentry::check())
                    <ul class="nav">

                        <li><a href="{{ URL::to('account/profile') }}"><i class="icon-home icon-white"></i> Home</a></li>


                        @if(Sentry::getUser()->hasAccess('manage_internal_invoices') || Sentry::getUser()->hasAccess('manage_external_invoices'))
                        <li class="dropdown{{ (Request::is('admin/users*|admin/groups*') ? ' active' : '') }}">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="">
                                <i class="icon-list-alt icon-white"></i> Invoices <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                @if(Sentry::getUser()->hasAccess('manage_internal_invoices') )
                                <li{{ (Request::is('admin/internalinvoice*') ? ' class="active"' : '') }}><a href="{{ URL::to('admin/internalinvoice') }}"> Internal Invoices</a></li>
                        @endif

                        @if(Sentry::getUser()->hasAccess('manage_external_invoices') )
                        <li{{ (Request::is('admin/externalinvoice*') ? ' class="active"' : '') }}><a href="{{ URL::to('admin/externalinvoice') }}"> External Invoices</a></li>
                        @endif
                    </ul>
                    </li>
                    @endif

                        @if(Sentry::getUser()->hasAccess('manage_payments'))
                        <li{{(Request::is('admin/payment*') ? ' class="active"' : '') }}><a href="{{ URL::to('admin/payments') }}"><i class="icon-calendar icon-white"></i> Payments</a></li>
                        @endif

                        @if(Sentry::getUser()->hasAccess('manage_reports'))
                        <li{{ (Request::is('admin/report*') ? ' class="active"' : '') }}><a href="{{ URL::to('admin/reports') }}"><i class="icon-file icon-white"></i> Reports</a></li>
                        @endif

                        <?php /*
                        <li{{ (Request::is('admin/payments*') ? ' class="active"' : '') }}><a href="{{ URL::to('admin/payments') }}"><i class="icon-list-alt icon-white"></i> Payments</a></li>
                        <li{{ (Request::is('admin/reports*') ? ' class="active"' : '') }}><a href="{{ URL::to('admin/reports') }}"><i class="icon-list-alt icon-white"></i> Reports</a></li> */
                        ?>
                    @if(Sentry::getUser()->hasAccess('merchantagreement'))
                    <li{{(Request::is('admin/merchantagreement*') ? ' class="active"' : '') }}><a href="{{ URL::to('admin/merchantagreement') }}"><i class="icon-calendar icon-white"></i> Merchant Agreements</a></li>

                    @endif
                        @if(Sentry::getUser()->hasAccess('manage_groups') || Sentry::getUser()->hasAccess('manage_users') || Sentry::getUser()->hasAccess('view_users'))
                        <li class="dropdown {{ (Request::is('admin/users*|admin/groups*') ? ' active' : '') }}">
                            <a class="dropdown-toggle" data-toggle="dropdown" href="{{ URL::to('admin/users') }}">
                                <i class="icon-user icon-white"></i> Users <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" >
                                @if(Sentry::getUser()->hasAccess('manage_users') || Sentry::getUser()->hasAccess('view_users'))
                                <li{{ (Request::is('admin/users*') ? ' class="active"' : '') }}><a href="{{ URL::to('admin/users') }}"><i class="icon-user"></i> Users</a></li>
                        @endif

                        @if(Sentry::getUser()->hasAccess('manage_groups'))
                        <li{{ (Request::is('admin/groups*') ? ' class="active"' : '') }}><a href="{{ URL::to('admin/groups') }}"><i class="icon-user"></i> Groups</a></li>
                        @endif
                    </ul>
                    </li>
                    @endif
                    </ul>

                    <ul class="nav pull-right">
                        <li {{ (Request::is('admin') ? ' class="active"' : '') }} ><a href="{{ route('admin') }}"><i class="icon-cog icon-white"></i> Administration</a></li>
                        <li class="divider-vertical"></li>
                        <<li><a href="{{ route('logout') }}"><i class="icon-off icon-white"></i> Logout</a></li>
                    </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    @include('frontend/notifications')

    <!-- Content -->
    @yield('content')
</div>

<!-- Javascripts
================================================== -->
<script src="{{ asset('assets/js/jquery.1.10.2.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap/bootstrap.min.js') }}"></script>
</body>
</html>
