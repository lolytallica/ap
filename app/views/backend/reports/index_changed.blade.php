@extends('backend/layouts/main')

{{-- Page title --}}
@section('title')
Reports ::
@parent
@stop

{{-- Page content --}}
@section('content')

<!-- content-header -->
<div class="content-header">
    <ul class="content-header-action pull-right">
        <li>
            <a href="#">
                <div class="badge-circle grd-green color-white"><i class="icofont-plus-sign"></i></div>
                <div class="action-text color-green">8765 <span class="helper-font-small color-silver-dark">Payments</span></div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div class="badge-circle grd-teal color-white"><i class="icofont-user-md"></i></div>
                <div class="action-text color-teal">1437 <span class="helper-font-small color-silver-dark">Merchants</span></div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div class="badge-circle grd-orange color-white">$</div>
                <div class="action-text color-orange">4367 <span class="helper-font-small color-silver-dark">Balance</span></div>
            </a>
        </li>
    </ul>
    <h2><i class="icofont-file"></i> Reports</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">


    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="">Reports</a> <span class="divider">&rsaquo;</span></li>

    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->


<!-- content-body -->
<div class="content-body">
    <!--datatables tools-->

    <div class="row-fluid">
        <h4>Current Conversion Rate</h4>

        <div class="progress progress-striped active span3">
            <div class="bar bar-success" style="width: 85%;"></div>
        </div>

        </div>
    <p class="muted span3">Based on transactions since {{ $transactionsbase }}</p>

    <div class="row-fluid">


        <div class="span12">



            <div class="box corner-all">
                <div class="box-header grd-white corner-top">


                    <div class="row-fluid">
                    <div class="span8">             <span>Reporting</span></div>


                    <div class="controls pull-right span4">
                        <table class="table table-responsive"><tr><td>

                                 <div class="span6"  >
                                <form class="form-search">
                                    <div class="input-icon-append">
                                        <button type="submit" rel="tooltip-bottom" title="search" class="icon"><i class="icofont-search"></i></button>
                                        <input class="input-large search-query grd-white" maxlength="23" placeholder="Trace ID..." type="text">
                                    </div>
                                </form>
                                     </div>
                                    </td><td>
                        <div class="span4">
                        <select id="searchField" data-form="
                        select2" style="width:200px" data-placeholder="Select field name">
                            <option value="select" selected>-- add search fields --</option>
                            @foreach($fields as $searchfield)
                            <option value="{{$searchfield}}">{{ucwords($searchfield)}}</option>
                            @endforeach
                        </select>
                            </td><td><button id="addSearchField" name="addSearchField" type="button" class="btn btn-small btn-info addSearchField"><i class="icon-plus-sign icon-white"></i>  </button>


                        </div>
                            </td></tr>
                            </table>
                    </div>

                    </div>
                    </div>

                <div class="box-body">


                            <div class="row-fluid">
                                <form class="form-horizontal" method="post" action="" autocomplete="off">
                                    <!-- CSRF Token -->
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />

                                <div class="span3">
                                    <div class="box corner-all">
                                        <div class="box-header grd-white">
                                            <div class="header-control">
                                                <a data-box="collapse"><i class="icofont-caret-up"></i></a>

                                            </div>
                                            <span>Report Type</span>
                                        </div>
                                        <div class="box-body">
                                            <!-- search fields -->

                                           <div class="control-group" style="margin-left: -15%">

                                                <label class="control-label" for="inputSelect">Search </label>
                                                <div class="controls">
                                                    <select id="reportType" data-form="" style="width:200px" data-placeholder="Select report" name="reportType">
                                                        <option value="select" selected>--- Select report ---</option>
                                                        @foreach($reporttypes as $reptype)
                                                        <option value="{{$reptype->id}}">{{ucwords($reptype->description)}}</option>
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="span4" id="searchform">
                                    <div class="box corner-all">
                                        <div class="box-header grd-white">
                                            <div class="header-control">
                                                <a data-box="collapse"><i class="icofont-caret-up"></i></a>

                                            </div>
                                            <span>Search Form</span>
                                        </div>
                                        <div class="box-body">


                                                <table id="formFields" class="table " style="vertical-align: top">

                                                    </table>




                                        </div>
                                    </div>


                                </div>

                                <div class="span4" id="additionalfields">
                                    <div class="box corner-all">
                                        <div class="box-header grd-white">
                                            <div class="header-control">
                                                <a data-box="collapse"><i class="icofont-caret-up"></i></a>

                                            </div>
                                            <span>Additional search fields</span>
                                        </div>
                                        <div class="box-body">

                                                <table id="searchFields" class="table " style="vertical-align: top">


                                                </table>

                                </div>

                            </div>


                   </div>

                <div class="row-fluid">
                <div class="span8"></div>
                <div class="span4s">
                    <button type="button" class="btn btn-warning">Reset</button>
                    <button type="button" class="btn btn-primary">Save Form</button>
                    <button type="submit" class="btn btn-success">Search</button>

                </div>
                </div>
               </form>

                    <div class="divider-content"><span></span></div>
                    <div class="row-fluid">
                    <div class="span12">
                        <div class="box corner-all">
                            <div class="box-header grd-white">
                                <div class="header-control">
                                    <a data-box="collapse"><i class="icofont-caret-up"></i></a>

                                </div>
                                <span>Results</span>
                            </div>
                            <?php
                            $searchfields = Session::get('searchfields');
                            $reporttype   = Session::get('reporttype');
                            $results      = Session::get('results');

                           // var_dump($searchfields);
                            ?>
                            @if(@count($searchfields))
                            <div class="box-body">
                                <div class="page-header">
                                    <h3>
                                        {{ ucwords($reporttype->searchtype) }}

                                    </h3>
                                </div>



                                <!--datatables tools-->


                                <div class="row-fluid">
                                    <div class="span12">
                                        <div class="box corner-all">
                                            <div class="box-header grd-white corner-top">
                                                <div class="header-control">

                                                </div>
                                                <?php
                                                $title = ucwords($reporttype->searchtype);
                                                foreach($searchfields as $key => $val)
                                                {
                                                    $excluded_fields = array('_token','reportType');
                                                    if($val && !in_array($key, $excluded_fields) )
                                                    {   $title .= ' | '.$key.' : '.$val; }
                                                }
                                                ?>
                                                <span>{{ $title }}</span>
                                            </div>
                                            <div class="box-body">

                                                <!------------------------------------ Redemptions ----------------------------->
                                                @if($searchfields['reportType']==1)
                                                <table id="datatablestools" class="table table-hover responsive">
                                                    <thead>
                                                    <tr>
                                                        <th class="span2">Merchant</th>
                                                        <th class="span2">Voucher ID</th>
                                                        <th class="span2">Name</th>
                                                        <th class="span2">Username</th>
                                                        <th class="span2">Profile</th>
                                                        <th class="span3">IP Address</th>
                                                        <th class="span2">Status</th>
                                                        <th class="span4">Message</th>
                                                        <th class="span2">Trace ID</th>
                                                        <th class="span2">Amount</th>
                                                        <th class="span2">Currency</th>
                                                        <th class="span4">Purchased</th>
                                                        <th class="span4">Created</th>
                                                        <th class="span3">Time taken</th>
                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @if(@count($results))
                                                    @foreach($results as $red)
                                                    @foreach($red as $redemptions)
                                                    <tr>
                                                        <td>{{$redemptions->merchant}}</td>
                                                        <td>{{$redemptions->voucher_id}}</td>
                                                        <td>{{$redemptions->firstname}}</td>
                                                        <td>{{$redemptions->merchantusername}}</td>
                                                        <td>{{$redemptions->merchantprofile}}</td>
                                                        <td>{{$redemptions->ipaddress}}</td>
                                                        <td>{{$redemptions->event_id}}</td>
                                                        <td>{{$redemptions->event}}</td>
                                                        <td>{{$redemptions->traceid}}</td>
                                                        <td>{{$redemptions->amount}}</td>
                                                        <td>{{$redemptions->currency}}</td>
                                                        <td></td>
                                                        <td>{{date('F d,Y H:i:s', strtotime($redemptions->datetimecreated)) }}</td>
                                                        <td></td>

                                                    </tr>
                                                    @endforeach
                                                    @endforeach
                                                    @endif

                                                    </tbody>
                                                </table>
                                                @endif
                                                <!-------------------------------- END Redemptions ----------------------------->

                                            </div><!-- /box-body -->
                                        </div><!-- /box -->
                                    </div><!-- /span -->
                                </div><!--/datatables tools-->
                                @endif
                            </div>
                        </div>
                    </div>
                        </div>
                </div><!-- /box-body -->
            </div><!-- /box -->

        </div><!-- /span -->
    </div><!--/datatables tools-->

    @stop
