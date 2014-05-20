@extends('backend/layouts/main')

{{-- Web site Title --}}
@section('title')
Edit Merchant Invoice::
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
            <a class="btn btn-small btn-link"  href="{{ URL::previous() }}" title="Back">
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
<form class="form-horizontal" method="post" action="" autocomplete="off">
<!-- CSRF Token -->
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

@if(($invoice->rowval('payable')->amount < $invoice->merchantagreement()->paramval('min_payout')->map_value || !count(@$conversionrate)) && $invoice->invoicestatus($invoice->id)->status != 'paid')
<div class="row-fluid">
    <div class="alert alert-error pull-right">
        <strong>This invoice can not be paid yet due to:<br></strong>
        @if($invoice->rowval('payable')->amount < $invoice->merchantagreement()->paramval('min_payout')->map_value)

        Min Payout not met.<br>

        @endif
        @if(!count(@$conversionrate))

        No conversion rate is available yet.
        @endif
    </div>

</div>
@endif

<div class="page-header">

    <h3>
        Invoice # {{$invoice->invoiceid}} <span class="muted"> - @lang('admin/invoices/invoices.'. $invoice->invoicestatus($invoice->id)->status )</span>

        @if(!Sentry::getUser()->merchant_id && !Sentry::getUser()->merchantagreement_id)
        <div class="pull-right">
            @if($invoice->invoicestatus($invoice->id)->status == 'paid')
            <span class="label label-success">Invoice Paid</span>
            @elseif($haspayments==0 && ($invoice->invoicestatus($invoice->id)->status == 'draft' || $invoice->invoicestatus($invoice->id)->status == 'approved' || $invoice->invoicestatus($invoice->id)->status == 'min_payout_not_met' || $invoice->invoicestatus($invoice->id)->status == 'tobepaid'))
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
        @endif
    </h3>

</div>

<div class="row-fluid">
    <div class="span4">

    </div>
    <div class="span4">
        <p class="muted">To Merchant: {{$invoice->merchantagreement()->name}}</p>
        <p>{{$invoice->merchantagreement()->address}}</p>
        <p>{{$invoice->merchantagreement()->city}}, {{$invoice->merchantagreement()->country}} {{$invoice->merchantagreement()->zip}}</p>
    </div>
    <div class="span4">
        <p>Invoice No. #{{$invoice->id}}</p>
        <p>Invoice Date. {{date('M d, Y', strtotime($invoice->created_at))}}</p>

        <div class="control-group">
            <label class="control-label" for="inputDate">Payment Date</label>
            <div class="controls">
                <div class="input-append date" data-form="datepicker" data-date="{{ Input::old('payout_date', date('Y-m-d', strtotime($invoice->payout_date))) }}" data-date-format="yyyy-mm-dd">
                    <input id="payout_date" name="payout_date" class="grd-white" data-form="" size="16" type="text" value="{{ Input::old('payout_date', date('Y-m-d', strtotime($invoice->payout_date))) }}">
                    <span class="add-on"><i class="icon-th"></i></span>
                    {{ $errors->first('payout_date', '<span class="help-inline">:message</span>') }}
                </div>
            </div>
        </div>

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
                        <p>Agreement</p>
                    </div>
                    <div class="span4">
                        <p>Year: {{date('Y', strtotime($invoice->created_at) )}} Month: {{date('M', strtotime($invoice->created_at) )}}</p>
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
                        <table class="table ">
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
                        <table class="table">
                            <tr>
                                <th></th>
                                <th>Total</th>
                                <th>% Total</th>
                                <th>% Proc.</th>
                                <th>amount</th>

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
                                <th> <div class="alert alert-info">
                                        {{(@$invoice->rowval('payable_sum')->amount>0)?($invoice->rowval('payable_sum')->amount) : 0}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}
                                </div>
                                </th>
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
                                <th> <div class="alert alert-error">
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

                <span> Additional costs</span>
            </div>
            <div class="box-body">
                <div class="row-fluid">
                    <div class="span4">

                    </div>

                    <div class="span8">

                        @if(@count($customrows))
                        <table class="table">
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Comments</th>
                            </tr>
                            @foreach($customrows as $custrow)
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
                                <th colspan="2">
                                    <div class="alert alert-error">
                                        {{$invoice->rowval('sum_custom_costs')->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}
                                    </div>
                                </th>
                                <th></th>
                            </tr>
                            </table>
                        @endif
                        <table id="customFields" name="customFields" class="table">
                            <tr>
                                <th colspan="3">Add Costs</th>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><label for="description">Description</label></th>
                                <td>
                                    <input type="text" class="code" id="description" name="description[]" value="" placeholder="Cost Description" /> &nbsp;
                                    <input type="text" class="code" id="amount" name="amount[]" value="" placeholder="Cost Amount" /> &nbsp;
                                    <input type="text" class="code" id="comments" name="comments[]" value="" placeholder="Comments" /> &nbsp;

                                </td>
                            </tr>


                        </table>
                        <button id="addCost" name="addCost" type="button" class="btn btn-small btn-info addCost"><i class="icon-plus-sign icon-white"></i> Add Cost</button>
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

                        @if(@count($incomerows))
                        <table class="table">
                            <tr>
                                <th>Description</th>
                                <th>Amount</th>
                                <th>Comments</th>
                            </tr>
                            @foreach($incomerows as $incomerow)
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
                        @endif
                        <table id="incomeFields" name="incomeFields" class="table">
                            <tr>
                                <th colspan="3">Add Income Amounts</th>
                            </tr>

                            <tr valign="top">
                                <th scope="row"><label for="description">Description</label></th>
                                <td>
                                    <input type="text" class="code" id="incomeDescription" name="incomeDescription[]" value="" placeholder="Income Description" /> &nbsp;
                                    <input type="text" class="code" id="incomeAmount" name="incomeAmount[]" value="" placeholder="Income Amount" /> &nbsp;
                                    <input type="text" class="code" id="incomeComments" name="incomeComments[]" value="" placeholder="Comments" /> &nbsp;

                                </td>
                            </tr>


                        </table>
                        <button id="addIncome" name="addIncome" type="button" class="btn btn-small btn-info addIncome"><i class="icon-plus-sign icon-white"></i> Add Income</button>
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
                                <td>{{($invoice->rowval('sum_custom_costs')->amount!=0) ? ('-'.abs($invoice->rowval('sum_custom_costs')->amount)) : 0}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                <td></td>
                            </tr>
                            @endif

                            @if(count($incomerows))

                            <tr>
                                <th>@lang('admin/invoices/invoices.sum_additional_income')</th>
                                <th></th>
                                <td>{{($invoice->rowval('sum_income')->amount!=0) ? ('+'.$invoice->rowval('sum_income')->amount) : 0}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
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
                                <td>{{($heldrow->amount>0) ? (-$heldrow->amount) : 0}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
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
                            <table id="customHeld" name="customHeld" class="table">
                                <tr>
                                    <th colspan="4">Add Held</th>
                                </tr>

                                <tr valign="top">
                                    <th scope="row"><label for="reason">Description</label></th>
                                    <td colspan="3">
                                        <input type="text" class="code" id="held_reason" name="held_reason[]" value="" placeholder="Held Reason" /> &nbsp;
                                        <input type="text" class="code" id="held_amount" name="held_amount[]" value="" placeholder="Held Amount" /> &nbsp;
                                        <input type="text" class="code" id="held_comments" name="held_comments[]" value="" placeholder="Comments" /> &nbsp;

                                    </td>
                                </tr>


                            </table>
                            <button id="addHeld" name="addHeld" type="button" class="btn btn-small btn-info addHeld"><i class="icon-plus-sign icon-white"></i> Add Held</button>


                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

@if(@count($payments))
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
                            <tr>
                                <td>{{$payment->amount_payout.' '. $invoice->merchantagreement()->paramval('payoutcurrency')->map_value}})</td>
                                <td>{{$payment->conversionrate}} <p class="muted">({{$invoice->merchantagreement()->paramval('processcurrency')->map_value .' To '. $invoice->merchantagreement()->paramval('payoutcurrency')->map_value}}</td>
                                <td> {{date('M d, Y', strtotime($payment->created_at))}} </td>
                                <td> {{$payment->comments}} </td>

                            </tr>
                            @endforeach

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
                                <th>Due to:</th>
                            </tr>
                            @foreach($heldrows as $heldrow)

                            <tr>
                                <td>{{$heldrow->description}}</td>
                                <td>{{$heldrow->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                                <td>{{$heldrow->custom_reason}}</td>
                            </tr>
                            @endforeach
                            @endif
                            <tr>
                                <th>
                                    <div class="alert alert-warning">
                                        Balance Out
                                </div>
                                </th>
                                <th colspan="2"><div class="alert alert-warning">
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

<div class="row-fluid">
    <div class="span3">
    </div>
    <div class="span9">
<div class="form-actions" style="text-align: center">
    <button type="submit" class="btn btn-primary"><i class="icofont-save"></i> Save changes</button>
    <button type="button" class="btn"><i class="icofont-remove"></i> Cancel</button>
</div>
</div>
    </div>

</form>

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
