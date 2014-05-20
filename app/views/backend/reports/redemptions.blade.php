@extends('backend/layouts/main')

{{-- Web site Title --}}
@section('title')
Redemptions ::
@parent
@stop

{{-- Content --}}
@section('content')
<!-- content-header -->
<div class="content-header">
    @include('backend/layouts/header_right')
    <h2><i class="icofont-file"></i> Redemptions</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        <li class="divider"></li>
        <li class="btn-group">
            <a class="btn btn-small btn-link"  href="{{ URL::to('admin/')}}" title="Back">
                <i class="typicn-back"></i> Back
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <ul class="breadcrumb">
            <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
            <li><a href="">Invoices</a> <span class="divider">&rsaquo;</span></li>
            <li class="active">Merchant Invoice</li>
        </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->

<!-- content-body -->
<div class="content-body">


    <div class="page-header">
        <h3>
            Redemptions

        </h3>
    </div>



    <!--datatables tools-->
    <div class="row-fluid">
        <div class="span12">
            <div class="box corner-all">
                <div class="box-header grd-white corner-top">
                    <div class="header-control">

                    </div>
                    <span>Merchant Agreement Invoices</span>
                </div>
                <div class="box-body">
                    <table id="datatablestools" class="table table-hover responsive">
                        <thead>
                        <tr>
                            <th class="span2">Merchant</th>
                            <th class="span4">Voucher ID</th>
                            <th class="span4">Name</th>
                            <th class="span4">Username</th>
                            <th class="span2">Profile</th>
                            <th class="span2">IP Address</th>
                            <th class="span2">Status</th>
                            <th class="span2">Message</th>
                            <th class="span2">Trace ID</th>
                            <th class="span4">Amount</th>
                            <th class="span4">Cur</th>
                            <th class="span4">Purchased</th>
                            <th class="span4">Created</th>
                            <th class="span4">Time taken</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(@count($redemptions))
                        @foreach($redemptions as $redemption)
                        <tr>
                            <td></td>
                        </tr>
                        @endforeach
                        @endif
                        </tbody>
                    </table>

                </div><!-- /box-body -->
            </div><!-- /box -->
        </div><!-- /span -->
    </div><!--/datatables tools-->

    @stop
