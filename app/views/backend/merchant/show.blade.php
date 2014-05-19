@extends('backend/layouts/main')

{{-- Page title --}}
@section('title')
Merchant
@parent
@stop

{{-- Page content --}}
@section('content')

<!-- content-header -->
<div class="content-header">
    @include('backend/layouts/header_right')
    <h2><i class="icofont-briefcase"></i> Merchant Agreement Details</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        <li class="divider"></li>
        <li class="btn-group">
            <a class="btn btn-small btn-link"  href="{{ route('update/merchant', $merchant->id) }}" title="Edit Merchant Agreement">
                <i class="icofont-edit"></i> Edit
            </a>
        <li class="divider"></li>
            <a class="btn btn-small btn-link"  href="{{  URL::previous() }}" title="Back">
                <i class="icofont-back"></i> Back
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="{{ URL::to('admin/merchant')}}">Merchant</a> <span class="divider">&rsaquo;</span></li>
        <li class="active">{{ $merchant->merchant}}</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->

<!-- content-body -->
<div class="content-body">

    <!-- merchant  details -->
    <div id="invoice-container" class="invoice-container">
        <div class="page-header">

            <h3>{{ strtoupper($merchant->merchant) }}</h3>
        </div>
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
                                <table class="table ">
                                    <tr>
                                        <th>Merchant</th>
                                        <td>{{ strtoupper($merchant->merchant)}}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $merchant->merchantemail }}</td>
                                    </tr>
                                    <tr>
                                        <th>Abbreviation</th>
                                        <td>{{ $merchant->abbreviation }}</td>
                                    </tr>
                                    <tr>
                                        <th>Aguid</th>
                                        <td>{{ $merchant->aguid }}</td>
                                    </tr>
                                    <tr>
                                        <th>Active</th>
                                        <td>{{ (($merchant->active==1)?'<span class="label label-success">Yes</span>':'<span class="label label-important">No</span>') }}</td>
                                    </tr>
                                </table>
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

                                <table class="table  table-striped table-hover">
                                    <tr>
                                        <th>Id</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th></th>
                                    </tr>
                                    @foreach($merchantagreements as $ma)
                                    <tr>
                                        <td>{{ $ma->id }}</td>
                                        <td>{{ $ma->name }}</td>
                                        <td>{{ $ma->description }}</td>
                                        <td><a href="{{ route('show/ma', $ma->id) }}" class="btn btn-mini">@lang('button.show')</a></td>
                                    </tr>
                                    @endforeach
                                </table>

                            </div>
                        </div>
                        @else
                        <div class="row-fluid">
                            <div class="span2"></div>
                            <div class="span4">
                                <div class="alert">
                                    <strong>No agreements have been assigned to this merchant.</strong>
                                </div>
                            </div>
                            @endif

                    </div>
                </div>
            </div>
        </div>
</div>

    <!--/merchant details-->

@stop
