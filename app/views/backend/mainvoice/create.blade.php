@extends('backend/layouts/main')

{{-- Web site Title --}}
@section('title')
Create Merchant Invoice::
@parent
@stop

{{-- Content --}}
@section('content')

<!-- content-header -->
<div class="content-header">
    <ul class="content-header-action pull-right">
        <li>
            <a href="#">
                <span data-chart="peity-bar" data-height="32" data-colours='["#00A600", "#00A600"]'>5,3,9,6,5,9,7,3,5,2</span>
                <div class="action-text color-green">8765 <span class="helper-font-small color-silver-dark">Invoices</span></div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <span data-chart="peity-bar" data-height="32" data-colours='["#00A0B1", "#00A0B1"]'>9,7,9,6,3,5,3,5,5,2</span>
                <div class="action-text color-teal">1437 <span class="helper-font-small color-silver-dark">Payments</span></div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <span data-chart="peity-bar" data-height="32" data-colours='["#BF1E4B", "#BF1E4B"]'>6,5,9,7,3,5,2,5,3,9</span>
                <div class="action-text color-red">4367 <span class="helper-font-small color-silver-dark">Orders</span></div>
            </a>
        </li>
    </ul>
    <h2><i class="icofont-home"></i>Merchant Invoices </h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        <li class="divider"></li>
        <li class="btn-group">
            <a class="btn btn-small btn-link"  href="{{ URL::to('admin/mainvoice')}}" title="Back">
                <i class="typicn-back"></i> Back
            </a>
        </li>

    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="{{ route('mainvoice') }}">Invoices</a> <span class="divider">&rsaquo;</span></li>
        <li class="active">Merchant Invoice</li>
        <li class="active">Create</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->


<!-- content-body -->
<div class="content-body">

    <div class="page-header">
        <h3>
            Create New Invoice


        </h3>
    </div>






    @stop
