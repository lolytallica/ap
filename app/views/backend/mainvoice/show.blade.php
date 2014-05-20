@extends('backend/layouts/main')

{{-- Web site Title --}}
@section('title')
Show Merchant Invoice::
@parent
@stop

{{-- Content --}}
@section('content')

<!-- content-header -->
<div class="content-header">
    @include('backend/layouts/header_right')
    <h2><i class="icofont-barcode"></i>Merchant Invoices </h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        <li class="divider"></li>
        <li class="btn-group">
            <a class="btn btn-small btn-link"  href="{{ URL::previous()}}" title="Back">
                <i class="typicn-back"></i> Back
            </a>
        </li>
        <li class="divider"></li>
        <li class="btn-group">
            <a class="btn btn-small btn-link" target="_blank" href="{{ route('invoicepdf/mainvoice', $invoice->id) }}" title="print invoice">
                <i class="icofont-print"></i> Print Invoice
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="{{ route('mainvoice') }}">Invoices</a> <span class="divider">&rsaquo;</span></li>
        <li class="active">Invoice Specification #{{$invoice->invoiceid}}</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->


<!-- content-body -->
<div class="content-body">
@if(($invoice->rowval('payable')->amount < $invoice->merchantagreement()->paramval('min_payout')->map_value || !count(@$conversionrate)) && $invoice->invoicestatus($invoice->id)->status != 'paid')
<div class="row-fluid">
<div class="alert alert-error pull-right">
        <strong>This invoice can not be paid yet due to:<br></strong>
    @if($invoice->rowval('payable')->amount < $invoice->merchantagreement()->paramval('min_payout')->map_value)

        Min Payout not met.<br>

    @endif
    @if(!@count($conversionrate))

        No conversion rate is available yet.
    @endif
    </div>

</div>
@endif

@if(Session::has('message_approved') && Session::get('message_approved')!='')

<p class="alert {{Session::get('message-approved-class')}}">{{ Session::get('message_approved') }}</p>

@endif

    <div class="page-header">

        <h3>
            Invoice # {{$invoice->invoiceid}}  <span class="muted"> - @lang('admin/invoices/invoices.'. $invoice->invoicestatus($invoice->id)->status )</span>

            <div class="pull-right">
                @if($invoice->invoicestatus($invoice->id)->status == 'paid')
                <span class="label label-success">Invoice Paid</span>
                @elseif(Sentry::getUser()->hasAccess('manage_ma_invoices') && $haspayments==0 && ($invoice->invoicestatus($invoice->id)->status == 'draft' || $invoice->invoicestatus($invoice->id)->status == 'approved' || $invoice->invoicestatus($invoice->id)->status == 'min_payout_not_met' || $invoice->invoicestatus($invoice->id)->status == 'tobepaid'))
                <a href="{{ route('update/mainvoice', $invoice->id) }}" class="btn btn-small btn-info"><i class="icon-edit icon-white"></i> Edit</a>
                @endif

                @if( count(@$conversionrate) && $invoice->invoicestatus($invoice->id)->status == 'draft')
                <a href="{{ route('approve/mainvoice', $invoice->id) }}" class="btn btn-small btn-success"><i class="icofont-ok"></i> Approve</a>
                @endif

                @if( $invoice->invoicestatus($invoice->id)->status == 'approved' && $haspayments==0)
                <a href="{{ route('draft/mainvoice', $invoice->id) }}" class="btn btn-small btn-warning"><i class="icofont-undo"></i> Draft</a>
                @endif

                @if($invoice->rowval('payable')->amount >= $invoice->merchantagreement()->paramval('min_payout')->map_value && count(@$conversionrate) && $invoice->invoicestatus($invoice->id)->status != 'paid' && $invoice->invoicestatus($invoice->id)->status != 'draft' && Sentry::getUser()->hasAccess('manage_ma_payments'))
                <a href="{{ route('pay/mapayment', $invoice->id) }}" class="btn btn-small btn-danger"><i class="icofont-money"></i> Pay</a>
                @endif
            </div>
        </h3>

    </div>


    <div class="row-fluid">
        <div class="span4">

        </div>
        <div class="span4">
            <p class="muted">To Merchant: {{$invoice->merchant()->merchant}}</p>
            <p>{{$invoice->merchant()->address}}</p>
            <p>{{$invoice->merchant()->city}}, {{$invoice->merchant()->country}} {{$invoice->merchant()->zip}}</p>
        </div>
        <div class="span4">
            <p>Invoice No. #{{$invoice->id}}</p>
            <p>Invoice Date. {{date('M d, Y', strtotime($invoice->created_at))}}</p>

            <p>Payment Due. {{date('M d, Y', strtotime($invoice->payout_date))}}</p>
        </div>
    </div>

    <div class="row-fluid">

    <div class="span12">
        <div class="box corner-all">
            <div class="box-header grd-black color-white corner-top">
                <div class="header-control">
                    <a class="custom-action"><i class="icofont-cog"></i></a>
                    <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                    <a data-box="close" data-hide="rotateOut">&times;</a>
                </div>
                <span>Description</span>
            </div>
            <div class="box-body">
                <div class="row-fluid">

                    <div class="span4">
                        <p >Date: <strong>{{date('M d, Y', strtotime($invoice->date_from))}} - {{date('M d, Y', strtotime($invoice->date_to))}}</strong></p>
                        <p>Transactions From <strong>{{$invoice->transactionid_from}}</strong> - To <strong>{{$invoice->transactionid_to}} </strong></p>

                    </div>
                    <div class="span4">
                        <p>Report No:</p>
                        <p>Report Type: </p>
                        <p>Agreement: <strong>{{ $invoice->merchantagreement()->name }} </strong></p>
                    </div>
                    <div class="span4">
                        <p>Year: <strong>{{date('Y', strtotime($invoice->created_at) )}}</strong> Month: <strong>{{date('F', strtotime($invoice->created_at) )}}</strong></p>
                    </div>
                </div>




            </div>
        </div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="box corner-all">
            <div class="box-header grd-white color-silver-dark corner-top">
                <div class="header-control">
                    <a class="custom-action"><i class="icofont-cog"></i></a>
                    <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                    <a data-box="close" data-hide="rotateOut">&times;</a>
                </div>
                <span>Underlaying Values</span>
            </div>
            <div class="box-body">
                <div class="row-fluid">
                    <div class="span6">
                        <table class="table table-hover">
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th>Redeemed</th>
                                <th>Conv.rate</th>

                            </tr>
                            <tr>
                                <td>Transactions</td>
                                <td>{{$invoice->transactions_number}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Amount</td>
                                <td>{{$invoice->transactions_amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                <td>{{($invoice->redemptions_amount>0)? $invoice->redemptions_amount:0}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                <td>{{$invoice->conversion_rate}} %</td>
                            </tr>
                            <tr>
                                <td>Average</td>
                                <td>{{ (($invoice->transactions_number>0) ? round($invoice->transactions_amount/$invoice->transactions_number, 2) : 0) }} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                <td>{{(($invoice->transactions_number>0) ? round($invoice->redemptions_amount/$invoice->transactions_number, 2) : 0) }} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                <td></td>
                            </tr>
                        </table>
                    </div>

                    <div class="span6">
                        <table class="table  table-hover">
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th>% Total</th>
                                <th>% Proc.</th>
                                <th>amount</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td>Refunds</td>
                                <td>{{ $invoice->refunds_number }} </td>
                                <td>{{ (($invoice->transactions_number>0) ? round(($invoice->refunds_number/$invoice->transactions_number)*100, 2) : 0) }} %</td>
                                <td></td>
                                <td>{{($invoice->refunds_amount>0)? -$invoice->refunds_amount:0}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Chargebacks</td>
                                <td>{{ $invoice->chargebacks_number }} </td>
                                <td>{{ (($invoice->transactions_number>0) ? round(($invoice->chargebacks_number/$invoice->transactions_number)*100, 2) : 0) }} %</td>
                                <td></td>
                                <td>{{ (($invoice->chargebacks_amount>0) ? '-'.($invoice->chargebacks_amount) : 0 )}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>

                                <td></td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
 </div>

    <div class="row-fluid">
        <div class="span3">
            </div>
        <div class="span9">
            <div class="box corner-all">
                <div class="box-header grd-blue color-white corner-top">
                    <div class="header-control">
                        <a class="custom-action"><i class="icofont-cog"></i></a>
                        <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                        <a data-box="close" data-hide="rotateOut">&times;</a>
                    </div>
                    <span>Specification</span>
                </div>
                <div class="box-body">
                    <div class="row-fluid">
                        <div class="span4">

                        </div>
                        <div class="span8">
                            <table class="table">


                                @foreach($specificationrows as $sprow)
                                @if($sprow->description!='payable_sum' && $sprow->description!='deducted_amount')
                                <tr>
                                    <th>@lang('admin/invoices/invoices.'.$sprow->description) </th>
                                    <td>{{$sprow->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td></td>
                                </tr>
                                @endif

                                @endforeach
                                <tr>
                                    <th>@lang('admin/invoices/invoices.deducted_amount') </th>
                                    <td>{{(@$invoice->rowval('deducted_amount')->amount>0)?($invoice->rowval('deducted_amount')->amount) : 0}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>
                                        <div class="alert alert-info">
                                            @lang('admin/invoices/invoices.payable_sum')
                                    </div>
                                    </th>
                                    <th colspan="2">
                                        <div class="alert alert-info">
                                            {{(@$invoice->rowval('payable_sum')->amount>0)?($invoice->rowval('payable_sum')->amount) : 0}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}
                                    </div>
                                    </th>

                                </tr>

                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span3">
        </div>
        <div class="span9">
            <div class="box corner-all">
                <div class="box-header grd-red color-white corner-top">
                    <div class="header-control">
                        <a class="custom-action"><i class="icofont-cog"></i></a>
                        <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                        <a data-box="close" data-hide="rotateOut">&times;</a>
                    </div>
                    <span>Costs</span>

                </div>
                <div class="box-body">
                    <div class="row-fluid">
                        <div class="span4">

                        </div>
                        <div class="span8">
                            <table class="table">

                                <tr>
                                    <th>@lang('admin/invoices/invoices.rate_processed_amount') </th>
                                    <td> {{ (@$invoice->redemptions_amount>0 ? $invoice->redemptions_amount:0 ) .' x '. $invoice->merchantagreement()->paramval('percentage')->map_value.'%'  }} = {{(@$invoice->rowval('rate_processed_amount')->amount >0 )? $invoice->rowval('rate_processed_amount')->amount : 0}}  {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                </tr>

                                @if(@$invoice->merchantagreement()->paramval('refund_cost')->map_value)
                                <tr>
                                    <th>@lang('admin/invoices/invoices.cost_per_refund') </th>
                                    <td> {{ (@$invoice->refunds_number>0 ? $invoice->refunds_number:0 ).' x '. $invoice->merchantagreement()->paramval('refund_cost')->map_value  }} = {{$invoice->rowval('cost_per_refund')->amount}}  {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                </tr>

                                @endif
                                @if(@$invoice->merchantagreement()->paramval('chb_cost')->map_value)
                                <tr>
                                    <th>@lang('admin/invoices/invoices.cost_per_chargeback') </th>
                                    <td> {{ (@$invoice->chargebacks_number>0 ? $invoice->chargebacks_number:0 ) .' x '. ((@$invoice->merchantagreement()->paramval('chb_cost')->map_value)? @$invoice->merchantagreement()->paramval('chb_cost')->map_value : 0)  }} = {{$invoice->rowval('cost_per_chargeback')->amount}}  {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                </tr>

                                @endif
                                @if(@$invoice->merchantagreement()->paramval('transaction_cost')->map_value)
                                <tr>
                                    <th>@lang('admin/invoices/invoices.cost_per_transaction') </th>
                                    <td> {{ (@$invoice->transactions_number>0 ? $invoice->transactions_number:0 ) .' x '. ((@$invoice->merchantagreement()->paramval('transaction_cost')->map_value)? @$invoice->merchantagreement()->paramval('transaction_cost')->map_value : 0)  }} = {{$invoice->rowval('cost_per_transaction')->amount}}  {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                </tr>

                                @endif

                                <tr>
                                    <th>
                                        <div class="alert alert-error">
                                            @lang('admin/invoices/invoices.sum_costs')
                                    </div>
                                    </th>
                                    <th><div class="alert alert-error">
                                            {{$invoice->rowval('sum_fixed_costs')->amount}}  {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}
                                    </div>
                                    </th>
                                </tr>

                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="row-fluid">
        <div class="span3">
        </div>
        <div class="span9">
            <div class="box corner-all">
                <div class="box-header grd-green color-white corner-top">
                    <div class="header-control">
                        <a class="custom-action"><i class="icofont-cog"></i></a>
                        <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                        <a data-box="close" data-hide="rotateOut">&times;</a>
                    </div>
                    <span>Holdback reserve</span>
                </div>
                <div class="box-body">
                    <div class="row-fluid">
                    <div class="span4">

                    </div>
                    <div class="span8">
                        <table class="table">

                            <tr>
                                <th> </th>
                                <td> </td>
                            </tr>


                        </table>
                    </div>

                </div>

                </div>
            </div>
        </div>
    </div>

    <?php $total_custom_costs=0;?>
    @if(count($customrows))

    <div class="row-fluid">
        <div class="span3">
        </div>
        <div class="span9">
            <div class="box corner-all">
                <div class="box-header grd-orange color-white corner-top">
                    <div class="header-control">
                        <a class="custom-action"><i class="icofont-cog"></i></a>
                        <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                        <a data-box="close" data-hide="rotateOut">&times;</a>
                    </div>

                    <span> Additional Costs</span>
                </div>
                <div class="box-body">
                    <div class="row-fluid">
                        <div class="span4">

                        </div>

                        <div class="span8">

                            <table class="table">
                                <tr>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Comments</th>
                                </tr>
                                @foreach($customrows as $custrow)
                                <?php
                                $total_custom_costs += $custrow->amount;
                                ?>
                                @if($custrow->description != 'sum_custom_costs')
                                <tr>
                                    <td>{{$custrow->description}}</td>
                                    <td>{{$custrow->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td>{{$custrow->custom_reason}}</td>
                                </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <th>
                                        <div class="alert alert-error">
                                            Total Additional Costs
                                    </div>
                                    </th>
                                    <th colspan="2"><div class="alert alert-error">
                                            {{$invoice->rowval('sum_custom_costs')->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}
                                    </div>
                                    </th>

                                </tr>
                            </table>


                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
    @endif

    <?php $total_income = 0;?>
    @if(count($incomerows))

    <div class="row-fluid">
        <div class="span3">
        </div>
        <div class="span9">
            <div class="box corner-all">
                <div class="box-header grd-teal color-white corner-top">
                    <div class="header-control">
                        <a class="custom-action"><i class="icofont-cog"></i></a>
                        <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                        <a data-box="close" data-hide="rotateOut">&times;</a>
                    </div>

                    <span> Additional Income</span>
                </div>
                <div class="box-body">
                    <div class="row-fluid">
                        <div class="span4">

                        </div>

                        <div class="span8">

                            <table class="table">
                                <tr>
                                    <th>Description</th>
                                    <th>Amount</th>
                                    <th>Comments</th>
                                </tr>
                                @foreach($incomerows as $incomerow)
                                <?php
                                $total_income += $incomerow->amount;
                                ?>
                                @if($incomerow->description!='sum_income')
                                <tr>
                                    <td>{{$incomerow->description}}</td>
                                    <td>{{$incomerow->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td>{{$incomerow->custom_reason}}</td>
                                </tr>
                                @endif
                                @endforeach
                                <tr>
                                    <th>
                                        <div class="alert alert-info">
                                            Total Additional Income
                                    </div>
                                    </th>
                                    <th colspan="2">
                                        <div class="alert alert-info">
                                            {{$invoice->rowval('sum_income')->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}
                                    </div>
                                    </th>
                                </tr>
                            </table>


                        </div>

                    </div>

                </div>
            </div>
        </div>

    </div>
    @endif

    <div class="row-fluid">
        <div class="span3">
        </div>
        <div class="span9">
            <div class="box corner-all">
                <div class="box-header grd-sky color-white corner-top">
                    <div class="header-control">
                        <a class="custom-action"><i class="icofont-cog"></i></a>
                        <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                        <a data-box="close" data-hide="rotateOut">&times;</a>
                    </div>
                    <span>To Report</span>
                </div>
                <div class="box-body">
                    <div class="row-fluid">
                        <div class="span4">

                        </div>
                        <div class="span8">
                            <table class="table">
                                <tr>
                                    <th>@lang('admin/invoices/invoices.payable_sum')</th>
                                    <td>{{$invoice->rowval('payable_sum')->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td></td>
                                    <td></td>
                                </tr>

                                <tr>
                                    <th>@lang('admin/invoices/invoices.sum_costs_fixed')</th>
                                    <th></th>
                                    <td>{{($invoice->rowval('sum_fixed_costs')->amount>0) ? ('-'.abs($invoice->rowval('sum_fixed_costs')->amount)) : ('+'.abs($invoice->rowval('sum_fixed_costs')->amount))}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td></td>
                                </tr>

                                @if(count($customrows))

                                <tr>
                                    <th>@lang('admin/invoices/invoices.sum_additional_costs')</th>
                                    <th></th>
                                    <td>{{($invoice->rowval('sum_custom_costs')->amount>0) ? ('-'.abs($invoice->rowval('sum_custom_costs')->amount)) : ('+'.abs($invoice->rowval('sum_custom_costs')->amount))}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td></td>
                                </tr>

                                @endif
                                @if(count($incomerows))

                                <tr>
                                    <th>@lang('admin/invoices/invoices.sum_additional_income')</th>
                                    <th></th>
                                    <td>+{{($invoice->rowval('sum_income')->amount>0) ? ($invoice->rowval('sum_income')->amount) : ($invoice->rowval('sum_income')->amount)}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td></td>
                                </tr>


                                @endif

                                <?php $total_held=0;?>
                                @if(@count($heldrows))
                                <tr>
                                    <th>With held:</th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    </tr>


                                @foreach($heldrows as $heldrow)
                                <?php
                                $total_held += $heldrow->amount;
                                ?>
                                <tr>
                                    <td></td>
                                    <td>{{$heldrow->description}}</td>
                                    <td>{{$heldrow->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td>{{$heldrow->custom_reason}}</td>
                                </tr>
                                @endforeach
                                @endif

                                <tr>
                                    <th colspan="2">
                                        <div class="alert alert-success">
                                            @lang('admin/invoices/invoices.payable')
                                    </div>
                                    </th>
                                    <th colspan="2">
                                        <div class="alert alert-success">
                                            {{$invoice->rowval('payable')->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}
                                    </div>
                                    </th>

                                </tr>

                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

@if(@count($payments))
<?php $total_partial_payments = 0;?>
<div class="row-fluid">
    <div class="span3">
    </div>
    <div class="span9">
        <div class="box corner-all">
            <div class="box-header grd-purple color-white corner-top">
                <div class="header-control">
                    <a class="custom-action"><i class="icofont-cog"></i></a>
                    <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                    <a data-box="close" data-hide="rotateOut">&times;</a>
                </div>
                <span>Payments</span>
            </div>
            <div class="box-body">
                <div class="row-fluid">
                    <div class="span4">

                    </div>
                    <div class="span8">
                        <table class="table">

                            <tr>
                                <th>Amount</th>
                                <th>Conversion rate</th>
                                <th>Date</th>
                                <th>Comments</th>
                            </tr>
                            @foreach($payments as $payment)
                            <?php $total_partial_payments += $payment->amount_processed; ?>
                            <tr>
                                <td>{{$payment->amount_processed.' '. $invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                <td>{{$payment->conversionrate}} <p class="muted">({{$invoice->merchantagreement()->paramval('processcurrency')->map_value .' To '. $invoice->merchantagreement()->paramval('payoutcurrency')->map_value}})</td>
                                <td> {{date('M d, Y', strtotime($payment->created_at))}} </td>
                                <td> {{$payment->comments}} </td>

                            </tr>

                            @endforeach
                            <tr>
                                <th>
                                    <div class="alert alert-info">
                                        Total Partial Payments
                                    </div>
                                </th>
                                <th colspan="3">
                                    <div class="alert alert-info">
                                        {{$total_partial_payments.' '.$invoice->merchantagreement()->paramval('processcurrency')->map_value }}
                                    </div>
                                </th>

                            </tr>

                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endif




    <div class="row-fluid">
        <div class="span3">
        </div>
        <div class="span9">
            <div class="box corner-all">
                <div class="box-header bg-black color-white corner-top">
                    <div class="header-control">
                        <a class="custom-action"><i class="icofont-cog"></i></a>
                        <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                        <a data-box="close" data-hide="rotateOut">&times;</a>
                    </div>
                    <span>Balance</span>
                </div>
                <div class="box-body">
                    <div class="row-fluid">
                        <div class="span4">

                        </div>
                        <div class="span8">
                            <table class="table">

                                <tr>
                                    <th>Balance in</th>
                                    <td>{{ $invoice->balance_in }} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td></td>
                                </tr>
                                @if(@count($heldrows))
                                <tr>
                                    <th>Amounts Held:</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                @foreach($heldrows as $heldrow)

                                <tr>
                                    <td>{{$heldrow->description}}</td>
                                    <td>{{$heldrow->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <td>{{$heldrow->custom_reason}}</td>
                                </tr>
                                @endforeach
                                @endif

                                @if(@count($payments))
                                <?php $sumpayments = 0; ?>
                                @foreach($payments as $payment)
                                <?php $sumpayments += $payment->amount_processed;?>
                                @endforeach
                                <tr>
                                    <th>Partial payments:</th>
                                    <td>-{{$sumpayments}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                    <th></th>
                                </tr>

                                @endif

                                <tr>
                                    <th>
                                        <div class="alert alert-warning">
                                            Balance Out
                                    </div></th>
                                    <th colspan="2">
                                        <div class="alert alert-warning">
                                            {{ $invoice->balance_out }} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}
                                    </div>
                                    </th>

                                </tr>

                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!--/dashboar-->
</div><!--/content-body -->
</div><!-- /content -->
</div><!-- /span content -->

<!-- span side-right -->
<div class="span2">
    <!-- side-right -->
    <aside class="side-right">
        <!-- sidebar-right -->
        <div class="sidebar-right">
            <!--sidebar-right-header-->
            <div class="sidebar-right-header">
                <div class="sr-header-right">
                    <h2><span class="label label-info">{{ $invoice->balance_out }} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</span></h2>
                </div>
                <div class="sr-header-left">
                    <p class="bold">Balance</p>
                    <small class="muted">{{ date("M j, Y")}}</small>
                </div>
            </div><!--/sidebar-right-header-->

    @stop
