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
    @include('backend/layouts/header_right')
    <h2><i class="icofont-briefcase"></i> Create New Merchant Agreement</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        <li class="divider"></li>
        <li class="btn-group">
            <a class="btn btn-small btn-link"  href="{{ URL::to('admin/merchantagreement')}}" title="Back">
                <i class="typicn-back"></i> Back
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="{{ URL::to('admin/merchantagreement')}}">Merchant Agreement</a> <span class="divider">&rsaquo;</span></li>
        <li> <span class="divider">&rsaquo;</span></li>
        <li class="active">Create</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->

<!-- content-body -->
<div class="content-body">


    <!-- Parameters -->
    <?php
    $pv = array();
    ?>

    @foreach($allparameters as $param)
    <?php $pv[$param->parameter]='';?>
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
                        <input type="text" id="name" name="name" class="grd-white" value="{{ Input::old('name') }}" />
                        {{ $errors->first('name', '<span class="help-inline">:message</span>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('description') ? 'error' : '' }}">
                    <label class="control-label" for="description">Description</label>
                    <div class="controls">
                        <input type="text" name="description" id="description" class="grd-white"  value="{{ Input::old('description') }}" />
                        {{ $errors->first('description', '<span class="help-inline">:message</span>') }}
                    </div>
                </div>

                <!-- Bank account -->
                <div class="control-group {{ $errors->has('bankaccount') ? 'error' : '' }}">
                    <label class="control-label" for="bankaccount">Bank account</label>
                    <div class="controls">
                        <select id="bankaccount" name="bankaccount">
                            <option value="0"></option>
                            @foreach($bankaccounts as $bankaccount)
                            <option value="{{$bankaccount->id}}">{{$bankaccount->description}}</option>
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
                            <option value="1">@lang('admin/merchantagreement/table.activated')</option>
                            <option value="0">@lang('admin/merchantagreement/table.notactivated')</option>
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
                <div class="control-group  {{ $errors->has($param->parameter) ? 'error' : '' }}">
                    <label class="control-label" for="{{ $param->parameter }}">@lang('admin/merchantagreement/table.' .$param->parameter)</label>
                    <div class="controls">
                        @if($param->parameter == 'payoutcurrency' || $param->parameter == 'processcurrency')
                        <select name="{{ $param->parameter }}" id="{{ $param->parameter }}">
                            @foreach($currencies as $currency)
                            <option value="{{$currency->alphacode}}">{{$currency->alphacode}}</option>
                            @endforeach
                        </select>
                        @else
                        <input type="text" class="grd-white" name="{{ $param->parameter }}" id="{{ $param->parameter }}" value="{{ Input::old($param->parameter) }}"  /> @lang('admin/merchantagreement/parameterval.' .$param->parameter)
                        @endif
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
