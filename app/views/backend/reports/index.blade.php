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
                    <div class="span8">

                        <div class="btn-group">
                            <button class="btn btn-large">Choose search form</button>
                            <button class="btn btn-large dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="{{ route('search/reports', 1) }}">Default search</a></li>
                                @foreach($reports as $rep)
                                <li><a href="{{ route('search/reports', $rep->id) }}">{{$rep->reptype().' : '.ucwords($rep->name)}}</a></li>
                                @endforeach

                            </ul>
                        </div>
                        <h4>
                        <span class="label label-info">Search form: {{ ucwords($reportsearch->name) }}</span>
                        </h4>
                    </div>

                    <?php

                    /*@todo: get all from same json*/

                    $redemptions_statuses = json_encode(reportStatuses('1'));
                    $order_statuses = json_encode(reportStatuses('2'));
                    $transaction_statuses = json_encode(reportStatuses('3'));
                    $validation_statuses = json_encode(reportStatuses('4'));

                  //  var_dump($ffields); exit;

                    ?>
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

                        <!---- Additional Fields -->
                        <select id="searchField" data-form="select2" style="width:200px" data-placeholder="Select field name">
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
                                                    <select id="reportType" data-form="select2" style="width:200px" data-placeholder="Select report" name="reportType">
                                                        <option value="select" selected>--- Select report ---</option>
                                                        @foreach($reporttypes as $reptype)
                                                        <option value="{{$reptype->id}}" {{(($reportsearchID>1 && $reporttypeID == $reptype->id)? 'selected':'')}} >{{ucwords($reptype->description)}}</option>
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
                                                <!---- Dynamic Form from js -->
                                                    @if($reportsearchID>1)
                                                    @foreach($ffields as $field)
                                                    <tr><td>
                                                            {{ucwords($field->fielddescription)}}</td>
                                                        <td>
                                                            @if($field->fieldname =='merchant' || $field->fieldname=='merchant_id')
                                                            <select>
                                                                @foreach($merchants as $merchant)
                                                                <option value="{{$merchant->id}}">{{$merchant->merchant}}</option>
                                                                @endforeach
                                                                </select>

                                                            @elseif($field->fieldname=='date_from' || $field->fieldname=='date_to')
                                                            <a href="javascript:void(0);" class="clickdate"><div class="input-append date" data-form="datepicker" data-date="" data-date-format="yyyy-mm-dd" id="{{$field->fieldname}}"> <input id="{{$field->fieldname}}" name="{{$field->fieldname}}" class="grd-white" data-form="" size="16" type="text" value="" data-validation="{{$field->data_validation}}" data-validation-format="{{$field->data_validation_condition}}" > <span class="add-on"><i class="icon-th"></i></span> </div></a>

                                                            @else
                                                            <input type="text" class="grd-white" id="{{$field->fieldname}}" name="{{$field->fieldname}}" value="" placeholder="" data-validation = "{{$field->data_validation}}" data-validation-optional="true" />
                                                            @endif

                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    @endif
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
                                                <!----- Dynamic Fields from js -->
                                                </table>
                                        </div>
                                    </div>
                                </div>

                <div class="row-fluid">
                <div class="span8"></div>
                <div class="span4s">
                    <button  type="button" class="btn btn-warning">Reset</button>
                    <a href="#saveformModal" role="button" class="btn btn-primary" data-toggle="modal" name="saveForm" id="saveForm" >Save form</a>

                    <!-- Modal -->

                    <div id="saveformModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="saveformModalLabel" aria-hidden="true">

                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                            <h4 id="statusModalLabel">Enter Report Name</h4>
                        </div>
                        <div class="modal-body">
                            <div class="control-group" >

                                <label class="control-label" for="inputSelect">Report name </label>
                                <div class="controls">
                                    <input type="text" class="grd-white" id="'form_name" name="form_name" value="" placeholder="" data-validation="required" data-validation-optional="true"  />

                                </div>

                            </div>
                        </div>
                        <div class="modal-footer">

                            {{ Form::submit('Save',['class'=>'btn btn-success','id' => 'saveform', 'name' => 'saveform']) }}

                        </div>

                    </div>
                             {{ Form::submit('Search',['class'=>'btn btn-success','id' => 'search', 'name' => 'search']) }}
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

                            if(@$searchfields['reportType'])
                            $resultfields = reportresultsfields($searchfields['reportType']);
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

                                                <span>{{ ucwords($reporttype->searchtype).': '.reporttitle($searchfields) }}</span>
                                            </div>
                                            <div class="box-body">

                                                <!------------------------------------ Search Report ----------------------------->

                                                <table id="datatablestools" class="table table-hover responsive">
                                                    <thead>
                                                    <tr>
                                                       @foreach($resultfields as $field)
                                                        <th class="{{$field->fieldclass}}">{{$field->fielddescription}}</th>
                                                        @endforeach

                                                    </tr>
                                                    </thead>
                                                    <tbody>

                                                    @if(@count($results))
                                                    @foreach($results as $red)
                                                    @foreach($red as $redemptions)
                                                    <tr>
                                                    @foreach($resultfields as $resultfield)

                                                        <td>{{fieldval($redemptions,$resultfield->fieldname)}}</td>

                                                    @endforeach
                                                    </tr>
                                                    @endforeach
                                                    @endforeach
                                                    @endif

                                                    </tbody>
                                                </table>

                                                <!-------------------------------- END Report ----------------------------->
                                                <!-- ======================================================================== -->
                                                <!-- ======================================================================== -->


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
