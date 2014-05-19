@extends('backend/layouts/main')

{{-- Page title --}}
@section('title')
Merchant Agreement Update:
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
    <h2><i class="icofont-briefcase"></i> Merchant Agreement Update</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        <li class="divider"></li>
        <li class="btn-group">

        <a class="btn btn-small btn-link"  href="{{ route('show/ma', $ma->id) }}" title="Back">
            <i class="icofont-back"></i> Back
        </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="{{ URL::to('admin/merchantagreement')}}">Merchant Agreement</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="{{ route('show/ma', $ma->id) }}">{{ $ma->name}}</a> <span class="divider">&rsaquo;</span></li>

        <li> <span class="divider">&rsaquo;</span></li>
        <li class="active">Edit</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->

<!-- content-body -->
<div class="content-body">


		<!-- Parameters -->
        <?php
        $pv = array();
        $ps = array();
        ?>

        @foreach($allparameters as $param)
        <?php
            $pv[$param->parameter]='';
            $ps[$param->parameter]='';
        ?>
        @foreach($map as $mparam)
        <?php
        if($mparam->parameter == $param->parameter && $mparam->map_value != '')
        {
            $pv[$param->parameter] = $mparam->map_value;

        }
        ?>
        @endforeach

         @foreach($mapstatus as $ms)
         <?php
         if($ms->parameter==$param->parameter && $ms->status_id)
         {
             $ps[$param->parameter] = $ms->status;
         }
         ?>
         @endforeach


        @endforeach


<!--box-->
<div class="box corner-all">
<!--box header-->
<div class="box-header grd-white color-silver-dark corner-top">
    <div class="header-control">
        <a data-box="collapse"><i class="icofont-caret-up"></i></a>
        <a data-box="close">&times;</a>
    </div>
    <span>Details</span>
</div><!--/box header-->
<!--box body-->
<div class="box-body">
<!--element-->

    <form class="form-horizontal" method="post" action="" autocomplete="off">
        <!-- CSRF Token -->
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
<div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
    <label class="control-label" for="name">Name</label>
    <div class="controls">
        <input type="text" id="name" name="name" class="grd-white" value="{{ Input::old('name', $ma->name) }}" />
        {{ $errors->first('name', '<span class="help-inline">:message</span>') }}
    </div>
</div>

    <div class="control-group {{ $errors->has('description') ? 'error' : '' }}">
        <label class="control-label" for="description">Description</label>
        <div class="controls">
            <input type="text" name="description" id="description" class="grd-white"  value="{{ Input::old('description', $ma->description) }}" />
            {{ $errors->first('description', '<span class="help-inline">:message</span>') }}
        </div>
    </div>

        <!-- Bankaccount Status -->
        <div class="control-group {{ $errors->has('bankaccount') ? 'error' : '' }}">
            <label class="control-label" for="bankaccount">Bank Account</label>
            <div class="controls">
                <select id="bankaccount" name="bankaccount">
                    <option value="0"></option>
                    @foreach($bankaccounts as $bankaccount)
                    <option value="{{ $bankaccount->id}}"{{ (($ma->bankaccount_id==$bankaccount->id)  ? ' selected="selected"' : '') }}>{{$bankaccount->description}}</option>
                    @endforeach
                </select>
                {{ $errors->first('activated', '<span class="help-inline">:message</span>') }}
            </div>
        </div>

    <!-- Activation Status -->
    <div class="control-group {{ $errors->has('activated') ? 'error' : '' }}">
        <label class="control-label" for="activated">Merchant Agreement Activated</label>
        <div class="controls">
            <select id="activated" name="activated">
                <option value="1"{{ (($ma->activated==1)  ? ' selected="selected"' : '') }}>@lang('admin/merchantagreement/table.activated')</option>
                <option value="0"{{ (($ma->activated==0)  ? ' selected="selected"' : '') }}>@lang('admin/merchantagreement/table.notactivated')</option>
            </select>
            {{ $errors->first('activated', '<span class="help-inline">:message</span>') }}
        </div>
    </div>
    <hr>
    <div class="control-group">
        <label>Parameters</label>
        </div>
    <hr>



    @foreach($allparameters as $param)
        <div class="row-fluid">

            <div class="control-group span6  {{ $errors->has($param->parameter) ? 'error' : '' }}">
                <label class="control-label" for="{{ $param->parameter }}">@lang('admin/merchantagreement/table.' .$param->parameter)</label>&nbsp;&nbsp;&nbsp;
                @if($param->parameter == 'payoutcurrency' || $param->parameter == 'processcurrency')
                <select name="{{ $param->parameter }}" id="{{ $param->parameter }}">
                    @foreach($currencies as $currency)
                    <option value="{{$currency->alphacode}}" {{ (($pv[$param->parameter] == $currency->alphacode) ? ' selected' : '') }}>{{$currency->alphacode}}</option>
                    @endforeach
                </select>
                @else
                <input type="text" class="grd-white" name="{{ $param->parameter }}" id="{{ $param->parameter }}" {{ (($pv[$param->parameter] != '')  ? ' value="'.$pv[$param->parameter].'"' : '') }}  /> @lang('admin/merchantagreement/parameterval.' .$param->parameter)
                @endif

            <div class="span6" style="float: right">
                @foreach($allstatus as $status)
                <input type="radio" value="{{ $status->id }}" id="status_{{$param->parameter}}" name="status_{{$param->parameter}}"{{ (($ps[$param->parameter] == $status->status) ? ' checked="checked"' : '') }}>  @lang('admin/merchantagreement/table.' .$status->status)
                @endforeach
            </div>
                {{ $errors->first($param->parameter, '<span class="help-inline">:message</span>') }}
                </div>

        </div>


        @endforeach



<div class="form-actions">
    <button type="submit" class="btn btn-primary">Save changes</button>
    <button type="button" class="btn" href="{{ URL::to('admin/merchantagreement') }}">Cancel</button>
</div>
</form>
<!--/element-->
</div><!--/box body-->
</div><!--/box-->


    @stop
