<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Sign In - A.R.P</title>
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



        </div>
    </div><!--/nav bar helper-->
</header>

<body>
<!-- Container -->
<div class="container">
    <!-- Navbar -->
    <div class="navbar navbar-inverse">
        <div class="navbar-inner">
            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <div class="nav-collapse collapse">
                    <ul class="nav">
                        <li {{ (Request::is('/') ? 'class="active"' : '') }}><a href="{{ route('home') }}"><i class="icon-home icon-white"></i> Home</a></li>

                        <li {{ (Request::is('contact-us') ? 'class="active"' : '') }}><a href="{{ URL::to('contact-us') }}"><i class="icon-file icon-white"></i> Contact us</a></li>
                    </ul>

                    <ul class="nav pull-right">
                        @if (Sentry::check())

                        <li class="dropdown{{ (Request::is('account*') ? ' active' : '') }}">
                            <a  id="dLabel" role="button" data-toggle="dropdown" data-target="#" href="{{ route('account') }}">
                                Welcome, {{ Sentry::getUser()->first_name }}
                            </a>
                        </li>
                        <li{{ (Request::is('account/profile') ? ' class="active"' : '') }}><a href="{{ route('profile') }}"><i class="icon-user"></i> Your profile</a></li>
                        @if(Sentry::getUser()->hasAccess('admin') || Sentry::getUser()->hasAccess('manage*') || Sentry::getUser()->hasAccess('view*'))
                        <li><a href="{{ route('admin') }}"><i class="icon-cog icon-white"></i> Administration</a></li>
                        @endif

                        <li class="divider"></li>
                        <li><a href="{{ route('logout') }}"><i class="icon-off icon-white"></i> Logout</a></li>


                        @else
                        <li {{ (Request::is('auth/signin') ? 'class="active"' : '') }}><a href="{{ route('signin') }}">Sign in</a></li>

                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    @include('frontend/notifications')

    <!-- Content -->
    @yield('content')

    <hr />

    <!-- Footer -->
    <footer>

    </footer>
</div>

<!-- Javascripts
================================================== -->
<script src="{{ asset('assets/js/jquery.1.10.2.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap/bootstrap.min.js') }}"></script>
</body>
</html>
