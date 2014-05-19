@extends('backend/layouts/main')

{{-- Web site Title --}}
@section('title')
Merchant Agreement Invoices ::
@parent
@stop

{{-- Content --}}
@section('content')
<!-- content-header -->
<div class="content-header">
    @include('backend/layouts/header_right')
    <h2><i class="icofont-file"></i> Invoice</h2>
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
        Merchant Invoices

    </h3>
</div>

    <?php

    $statuslabel = array();
    foreach($allstatuses as $invoicestatus)
    {

    switch($invoicestatus->status)
    {
        case 'draft':
            $statuslabel[$invoicestatus->status] = '';
            break;
        case 'sent':
            $statuslabel[$invoicestatus->status] = 'label-info';
            break;
        case 'partiallypaid':
            $statuslabel[$invoicestatus->status] = 'label-info';
            break;
        case 'paid':
            $statuslabel[$invoicestatus->status] = 'label-success';
            break;
        case 'archieved':
            $statuslabel[$invoicestatus->status] = '';
            break;
        case 'overdue':
            $statuslabel[$invoicestatus->status] = 'label-inverse';
            break;
        case 'tobepaid':
            $statuslabel[$invoicestatus->status] = 'label-important';
            break;
        case 'min_payout_not_met':
            $statuslabel[$invoicestatus->status] = 'label-warning';
            break;
        case 'approved':
            $statuslabel[$invoicestatus->status] = 'label-info';
            break;
    }
    }//endforeach
    ?>


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
    <th class="span2">@lang('admin/invoices/invoices.id')</th>
    <th class="span4">@lang('admin/invoices/invoices.merchant')</th>
    <th class="span4">@lang('admin/invoices/invoices.agreement')</th>
    <th class="span4">@lang('admin/invoices/invoices.description')</th>
    <th class="span2">@lang('admin/invoices/invoices.from')</th>
    <th class="span2">@lang('admin/invoices/invoices.to')</th>
    <th class="span2">@lang('admin/invoices/invoices.date')</th>
    <th class="span2">@lang('admin/invoices/invoices.amount')</th>
    <th class="span2">@lang('admin/invoices/invoices.status')</th>
    <th class="span4">@lang('admin/invoices/invoices.actions')</th>
</tr>
</thead>
<tbody>
@foreach($invoices as $invoice)
<tr>
    <td># {{ $invoice->invoiceid }} </td>
    <td>{{ $invoice->merchant()->merchant}}</td>
    <td>{{ $invoice->merchantagreement()->name }}</td>
    <td>{{ $invoice->description }}</td>
    <td>{{ date('F d, Y', strtotime($invoice->date_from) ) }}</td>
    <td>{{ date('F d, Y', strtotime($invoice->date_to) ) }}</td>
    <td>{{ date('F d, Y', strtotime($invoice->created_at) )}}</td>
    <td>{{ ($invoice->amount!=0? $invoice->amount : 0)}} {{$invoice->processcurrency}}</td>

    <td><span class="label {{$statuslabel[$invoice->invoicestatus($invoice->id)->status]}}">@lang('admin/invoices/invoices.'. $invoice->invoicestatus($invoice->id)->status )</span> </td>
    <td>

        <a href="{{ route('show/mainvoice', $invoice->id) }}" class="btn btn-mini">@lang('button.show')</a>
        @if($invoice->invoicestatus($invoice->id)->status == 'draft' || $invoice->invoicestatus($invoice->id)->status == 'approved' || $invoice->invoicestatus($invoice->id)->status == 'tobepaid'  || $invoice->invoicestatus($invoice->id)->status == 'min_payout_not_met' && !Sentry::getUser()->merchant_id && !Sentry::getUser()->merchantagreement_id)
        <a href="{{ route('update/mainvoice', $invoice->id) }}" class="btn btn-mini btn-primary">@lang('button.edit')</a>
        @endif
    </td>
</tr>
@endforeach
</tbody>
</table>

</div><!-- /box-body -->
</div><!-- /box -->
</div><!-- /span -->
</div><!--/datatables tools-->

@stop
