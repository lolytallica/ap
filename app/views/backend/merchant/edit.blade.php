@extends('backend/layouts/main')

{{-- Page title --}}
@section('title')
Merchant Update:
@parent
@stop

{{-- Page content --}}
@section('content')



<!-- content-header -->
<div class="content-header">
    @include('backend/layouts/header_right')
    <h2><i class="icofont-briefcase"></i> Edit Merchant</h2>
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
        <li class="active">Edit</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->

<!-- content-body -->
<div class="content-body">


    <!-- Parameters -->
    <?php
    $agreements = array();

    foreach($allma as $allagreements)
    {
        $agreements[$allagreements->id] = '';
    }

    foreach($merchantagreements as $massigned)
    {
        $agreements[$massigned->id] = $massigned->merchant;
    }
    ?>



    <!--box-->
    <form class="form-horizontal" method="post" action="" autocomplete="off">
    <!-- CSRF Token -->
    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
    <div class="box corner-all">


        <div class="row-fluid">

            <div class="span12">
                <div class="box corner-all">
                    <div class="box-header grd-black color-white corner-top">

                        <span>Merchant Details</span>
                    </div>
                    <div class="box-body">
                        <div class="row-fluid">
                            <div class="span2"></div>
                            <div class="span4">

                                <div class="control-group {{ $errors->has('merchant') ? 'error' : '' }}">
                                    <label class="control-label" for="merchant">Merchant</label>
                                    <div class="controls">
                                        <input type="text" id="merchant" name="merchant" class="grd-white" value="{{ Input::old('merchant', $merchant->merchant) }}" />
                                        {{ $errors->first('merchant', '<span class="help-inline">:message</span>') }}
                                    </div>
                                </div>

                                <div class="control-group {{ $errors->has('merchantemail') ? 'error' : '' }}">
                                    <label class="control-label" for="merchantemail">Email</label>
                                    <div class="controls">
                                        <input type="text" name="merchantemail" id="merchantemail" class="grd-white"  value="{{ Input::old('merchantemail', $merchant->merchantemail) }}" />
                                        {{ $errors->first('merchantemail', '<span class="help-inline">:message</span>') }}
                                    </div>
                                </div>

                                <div class="control-group {{ $errors->has('abbreviation') ? 'error' : '' }}">
                                    <label class="control-label" for="abbreviation">Abbreviation</label>
                                    <div class="controls">
                                        <input type="text" name="abbreviation" id="abbreviation" class="grd-white"  value="{{ Input::old('abbreviation', $merchant->abbreviation) }}" />
                                        {{ $errors->first('abbreviation', '<span class="help-inline">:message</span>') }}
                                    </div>
                                </div>

                                <div class="control-group {{ $errors->has('aguid') ? 'error' : '' }}">
                                    <label class="control-label" for="aguis">Aguid</label>
                                    <div class="controls">
                                        <input type="text" name="aguid" id="aguid" class="grd-white"  value="{{ Input::old('aguid', $merchant->aguid) }}" />
                                        {{ $errors->first('aguid', '<span class="help-inline">:message</span>') }}
                                    </div>
                                </div>

                                <!-- Activation Status -->
                                <div class="control-group {{ $errors->has('activated') ? 'error' : '' }}">
                                    <label class="control-label" for="activated">Merchant Activated</label>
                                    <div class="controls">
                                        <select id="active" name="active">
                                            <option value="1" {{ (($merchant->active==1)  ? ' selected="selected"' : '') }} >@lang('admin/merchant/table.activated')</option>
                                            <option value="0" {{ (($merchant->active==0)  ? ' selected="selected"' : '') }} >@lang('admin/merchant/table.notactivated')</option>
                                        </select>
                                        {{ $errors->first('active', '<span class="help-inline">:message</span>') }}
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="row-fluid">
        <div class="span12">
            <div class="box corner-all">
                <div class="box-header grd-black color-white corner-top">

                    <span>Merchant Agreements</span>
                </div>
                <div class="box-body">
                    @if(count($merchantagreements)>0)
                    <div class="row-fluid">
                        <div class="span2"></div>
                        <div class="span4">

                            @foreach($allma as $ma)
                            <div class="control-group  {{ $errors->has($ma->id) ? 'error' : '' }}">
                                <div class="controls">

                                    <label class="checkbox">
                                        <input type="checkbox"  name="ma_{{ $ma->id }}" id="ma_{{ $ma->id }}" value="{{$ma->id}}" {{ (($agreements[$ma->id])  ? ' checked' : '') }}> {{$ma->name}}
                                    </label>
                                    {{ $errors->first('ma_'.$ma->id, '<span class="help-inline">:message</span>') }}
                                </div>
                            </div>
                            @endforeach


                        </div>
                    </div>
                    @else
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
                    @endif
                </div>
            </div>
        </div>
    </div>
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save changes</button>
            <button type="button" class="btn" href="{{ URL::to('admin/merchant') }}">Cancel</button>
        </div>
        </form>


    </div><!--/box-->
    @stop
