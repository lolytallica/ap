@extends('backend/layouts/main')

{{-- Web site Title --}}
@section('title')
:: Merchant Agreement Payments ::
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
            <a class="btn btn-small btn-link"  href="{{  URL::previous()}}" title="Back">
                <i class="typicn-back"></i> Back
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <ul class="breadcrumb">
            <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
            <li><a href="">Payments</a> <span class="divider">&rsaquo;</span></li>
            <li class="active">Merchant Agreement Payments</li>
        </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->

<!-- content-body -->
<div class="content-body">


    <div class="page-header">
        <h3>
            Merchant Invoices
            @if(Sentry::check() && Sentry::getUser()->hasAccess('view_ma_payments'))
            <div class="pull-right">


            </div>
            @endif
        </h3>
    </div>


    <!--datatables tools-->
    <div class="row-fluid">
        <div class="span12">
            <div class="box corner-all">
                <div class="box-header grd-white corner-top">
                    <div class="header-control">

                    </div>
                    <span>Merchant Agreement Payments</span>
                </div>
                <div class="box-body">
                    <table id="datatablestools" class="table table-hover responsive">
                        <thead>
                        <tr>
                            <th class="span2">@lang('admin/invoices/payments.invoiceid')</th>
                            <th class="span2">@lang('admin/invoices/payments.paymentid')</th>
                            <th class="span3">@lang('admin/invoices/payments.merchant')</th>
                            <th class="span3">@lang('admin/invoices/payments.agreement')</th>
                            <th class="span3">@lang('admin/invoices/payments.held')</th>
                            <th class="span3">@lang('admin/invoices/payments.comments')</th>


                            <th class="span2">@lang('admin/invoices/payments.date')</th>
                            <th class="span2">@lang('admin/invoices/payments.created_by')</th>
                            <th class="span3">@lang('admin/invoices/payments.total_processed')</th>
                            <th class="span3">@lang('admin/invoices/payments.amount_payout')</th>
                            <th class="span2">@lang('admin/invoices/payments.conversion_rate') </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            @if(Sentry::getUser()->hasAccess('view_ma_invoices'))
                            <td><a href="{{ route('show/mainvoice', $payment->mainvoice_id) }} " target="_blank" class="btn btn-mini"># {{ $payment->paymentinvoice()->invoiceid }}</a></td>
                            @else
                            <td># {{ $payment->paymentinvoice()->invoiceid }}</td>
                            @endif
                            <td># {{ $payment->paymentid }}</td>
                            <td>  {{ $payment->paymentinvoice()->merchant()->merchant}}</td>
                            <td>  {{ $payment->paymentinvoice()->merchantagreement()->name }}</td>
                            <td>  {{ $payment->held .' '.$payment->paymentinvoice()->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                            <td>  {{ $payment->comments }}</td>
                            <td>  {{ date('F d, Y', strtotime($payment->created_at) )}}</td>
                            <td>  {{ $payment->payer()->first_name.' '.$payment->payer()->last_name }}</td>
                            <td>  {{ ($payment->total_processed!=0? $payment->total_processed : 0)}} {{$payment->paymentinvoice()->merchantagreement()->paramval('processcurrency')->map_value }}</td>
                            <td>  {{ ($payment->amount_payout!=0? $payment->amount_payout : 0)}} {{$payment->paymentinvoice()->merchantagreement()->paramval('payoutcurrency')->map_value}}</td>
                            <td>  {{ $payment->conversionrate }}<p class="muted">From {{$payment->paymentinvoice()->merchantagreement()->paramval('processcurrency')->map_value}} To {{$payment->paymentinvoice()->merchantagreement()->paramval('payoutcurrency')->map_value}}</p></td>


                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div><!-- /box-body -->
            </div><!-- /box -->
        </div><!-- /span -->
    </div><!--/datatables tools-->



    @stop
