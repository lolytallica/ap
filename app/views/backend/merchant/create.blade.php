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
    <h2><i class="icofont-briefcase"></i> Create New Merchant</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        <li class="divider"></li>
        <li class="btn-group">
            <a class="btn btn-small btn-link"  href="{{  URL::previous()}}" title="Back">
                <i class="typicn-back"></i> Back
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="{{ URL::to('admin/merchant')}}">Merchant</a> <span class="divider">&rsaquo;</span></li>
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



    <!--box-->
    <div class="box corner-all">
        <!--box header-->
        <div class="box-header grd-white color-silver-dark corner-top">

            <span>Details</span>
        </div><!--/box header-->
        <!--box body-->
        <div class="box-body">
            <!--element-->

            <form class="form-horizontal" method="post" action="" autocomplete="off">
                <!-- CSRF Token -->
                <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                <div class="control-group {{ $errors->has('merchant') ? 'error' : '' }}">
                    <label class="control-label" for="merchant">Merchant</label>
                    <div class="controls">
                        <input type="text" id="merchant" name="merchant" class="grd-white" value="{{ Input::old('merchant') }}" />
                        {{ $errors->first('merchant', '<span class="help-inline">:message</span>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('merchantemail') ? 'error' : '' }}">
                    <label class="control-label" for="merchantemail">Email</label>
                    <div class="controls">
                        <input type="text" name="merchantemail" id="merchantemail" class="grd-white"  value="{{ Input::old('merchantemail') }}" />
                        {{ $errors->first('merchantemail', '<span class="help-inline">:message</span>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('abbreviation') ? 'error' : '' }}">
                    <label class="control-label" for="abbreviation">Abbreviation</label>
                    <div class="controls">
                        <input type="text" name="abbreviation" id="abbreviation" class="grd-white"  value="{{ Input::old('abbreviation') }}" />
                        {{ $errors->first('abbreviation', '<span class="help-inline">:message</span>') }}
                    </div>
                </div>

                <div class="control-group {{ $errors->has('aguid') ? 'error' : '' }}">
                    <label class="control-label" for="aguis">Aguid</label>
                    <div class="controls">
                        <input type="text" name="aguid" id="aguid" class="grd-white"  value="{{ Input::old('aguid') }}" />
                        {{ $errors->first('aguid', '<span class="help-inline">:message</span>') }}
                    </div>
                </div>

                <!-- Activation Status -->
                <div class="control-group {{ $errors->has('activated') ? 'error' : '' }}">
                    <label class="control-label" for="activated">Merchant Activated</label>
                    <div class="controls">
                        <select id="active" name="active">
                            <option value="1">@lang('admin/merchant/table.activated')</option>
                            <option value="0">@lang('admin/merchant/table.notactivated')</option>
                        </select>
                        {{ $errors->first('active', '<span class="help-inline">:message</span>') }}
                    </div>
                </div>
                <hr>
                <div class="control-group">
                    <label>Assign Agreements</label>
                </div>
                <hr>


                @foreach($allma as $ma)
                <div class="control-group  {{ $errors->has($ma->id) ? 'error' : '' }}">

                    <div class="controls">

                        <label class="checkbox">
                            <input type="checkbox"  name="ma_{{ $ma->id }}" id="ma_{{ $ma->id }}" value="{{$ma->id}}"> {{$ma->name}}
                        </label>
                        {{ $errors->first('ma_'.$ma->id, '<span class="help-inline">:message</span>') }}
                    </div>
                </div>
                @endforeach


                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                    <button type="button" class="btn" href="{{ URL::to('admin/merchant') }}">Cancel</button>
                </div>
            </form>
            <!--/element-->
        </div><!--/box body-->
    </div><!--/box-->
    @stop
