@extends('backend/layouts/main')

{{-- Web site Title --}}
@section('title')
Pay Merchant Invoice::
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
            <a class="btn btn-small btn-link"  href="{{  URL::previous() }}" title="Back">
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
        <li><a href="{{ route('show/mainvoice', $invoice->id) }}">Invoice  #{{$invoice->invoiceid}}</a> </li><span class="divider">&rsaquo;</span></li>
        <li class="active">Payment</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->


<!-- content-body -->
<div class="content-body">
<form class="form-horizontal" method="post" action="" autocomplete="off">
<!-- CSRF Token -->
<input type="hidden" name="_token" value="{{ csrf_token() }}" />

<div class="page-header">
    <h3>
        Payment:
    </h3>
</div>

<div class="row-fluid">
    <div class="span2"></div>
    <div class="span3">
        <p class="muted">To Merchant: {{$invoice->merchant()->merchant}}</p>
        <p>{{$invoice->merchantagreement()->address}}</p>
        <p>{{$invoice->merchantagreement()->city}}, {{$invoice->merchantagreement()->country}} {{$invoice->merchantagreement()->zip}}</p>
    </div>
    <div class="span3">
        <p>Agreement: {{$invoice->merchantagreement()->name}}</p>
        <p>Invoice From: {{date('F d, Y', strtotime($invoice->date_from))}}</p>
        <p>Invoice To: {{date('F d, Y', strtotime($invoice->date_to))}}</p>

    </div>
    <div class="span3">
        <p>Invoice No. #{{$invoice->invoiceid}}</p>
        <p>Invoice Date. {{date('F d, Y', strtotime($invoice->created_at))}}</p>
        <p>Payment Due. {{date('F d, Y', strtotime($invoice->payout_date))}}</p>
    </div>
</div>
<hr>

<div class="row-fluid">
    <div class="span2"></div>
    <div class="span8">
        <div class="box corner-all">
            <div class="box-header grd-white color-silver-dark corner-top">
                <div class="header-control">



                </div>
                <span>Details</span>
            </div>
            <div class="box-body">
                <div class="row-fluid">
                    <div class="span12">
                        <table class="table">
                            <tr>
                                <td>
                                    <div class="control-group info">
                                        <label class="control-label" for="payable"> Total Payable <p class="muted">({{$invoice->merchantagreement()->paramval('processcurrency')->map_value}})</p></label>
                                        <div class="controls">
                                            <input type="text"   name="payable" id="payable" class="grd-white"  value="{{$invoice->rowval('payable')->amount}} " disabled />
                                            <input type="hidden" name="payable_hid" id="payable_hid"  value="{{$invoice->rowval('payable')->amount}} "  />
                                            <input type="hidden" name="payoutcurrency" id="payoutcurrency" class="grd-white"  value="{{$invoice->merchantagreement()->paramval('payoutcurrency')->map_value}} " />
                                            <input type="hidden" name="processcurrency" id="processcurrency" class="grd-white"  value="{{$invoice->merchantagreement()->paramval('processcurrency')->map_value}} " />
                                            <input type="hidden" name="balance" id="balance" class="grd-white"  value="{{$invoice->balance_out}} " />
                                            <input type="hidden" name="min_payout" id="min_payout" class="grd-white"  value="{{$invoice->merchantagreement()->paramval('min_payout')->map_value}} " />

                                        </div>
                                    </div>

                                    @if($amount_paid>0)
                                    <div class="control-group success">
                                        <label class="control-label" for="amountpaid"> Amount paid <p class="muted">({{$invoice->merchantagreement()->paramval('payoutcurrency')->map_value}})</p></label>
                                        <div class="controls">
                                            <input type="text" name="amountpaid" id="amountpaid" class="grd-white"  value="{{$amount_paid}} " disabled />

                                        </div>
                                    </div>

                                    @endif
                                    <input type="hidden" name="amountpaid_hid" id="amountpaid_hid" class="grd-white"  value="{{$amount_processed_paid}} "  />

                                    <div class="control-group {{ $errors->has('amount_processed') ? 'error' : '' }}">
                                        <label class="control-label" for="amount_processed">Amount to pay<p class="muted">({{$invoice->merchantagreement()->paramval('processcurrency')->map_value}})</p></label>
                                        <div class="controls">
                                            <input type="text" name="amount_processed" id="amount_processed" class="grd-white"  value="{{$invoice->rowval('payable')->amount - $amount_processed_paid}}" placeholder="{{$invoice->rowval('payable')->amount - $amount_paid}}" />
                                            <p class="muted"> Min payout: {{$invoice->merchantagreement()->paramval('min_payout')->map_value.' '.$invoice->merchantagreement()->paramval('processcurrency')->map_value}}
                                            {{ $errors->first('amount_processed', '<span class="help-inline">:message</span>') }}
                                        </div>
                                    </div>

                            <!-- Conversion rate -->


                                <div class="control-group {{ $errors->has('conversionrate') ? 'error' : '' }}">
                                    <label class="control-label" for="conversionrate">Conversion Rate
                                        <p class="muted">
                                            {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}} To {{$invoice->merchantagreement()->paramval('payoutcurrency')->map_value}}
                                        </p>
                                    </label>

                                    <div class="controls">
                                        <select id="conversionrate" data-form="select2" name="conversionrate"  style="width:200px" data-placeholder="Conversion Rate" >
                                            <option value=""></option>
                                            <optgroup label="{{$invoice->merchantagreement()->paramval('processcurrency')->map_value}} To {{$invoice->merchantagreement()->paramval('payoutcurrency')->map_value}}">
                                                @foreach($conversionrate as $convrate)
                                                <option value="{{$convrate->conversionrate}}">{{$convrate->conversionrate .' : ('.date('M d, Y', strtotime($convrate->created_at))  .')' }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        {{ $errors->first('conversionrate', '<span class="help-inline">:message</span>') }}
                                    </div>

                                    </div>


                            </td>

                            </tr>

                            <tr>
                                <td>
                                    <div class="control-group info {{ $errors->has('payout') ? 'error' : '' }} ">
                                        <label class="control-label" for="payout"><strong>Total payable</strong> <p class="muted">({{$invoice->merchantagreement()->paramval('payoutcurrency')->map_value}})</p></label>
                                        <div class="controls">
                                            <input type="text" name="payout" id="payout" class="grd-white"  value="" placeholder="Payout amount" disabled />
                                            <input type="hidden" name="payout_hid" id="payout_hid" class="grd-white"  value="" />
                                            {{ $errors->first('payout', '<span class="help-inline">:message</span>') }}
                                        </div>
                                    </div>
                                </td>
                                </tr>

                                <tr>
                                    <td>
                                    <div class="control-group warning {{ $errors->has('paymentheld') ? 'error' : '' }}">
                                        <label class="control-label" for="paid">Held on this payment <p class="muted">({{$invoice->merchantagreement()->paramval('processcurrency')->map_value}})</p></label>
                                        <div class="controls">
                                            <input type="text" name="paymentheld" id="paymentheld" class="grd-white"  value="0 {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}} "  disabled />
                                            <input type="hidden" name="paymentheld_hid" id="paymentheld_hid" class="grd-white"  value="" />
                                            {{ $errors->first('paymentheld', '<span class="help-inline">:message</span>') }}
                                        </div>
                                    </div>

                                    <div class="control-group warning {{ $errors->has('newbalance') ? 'error' : '' }}">
                                        <label class="control-label" for="newbalance">Balance after payment <p class="muted">({{$invoice->merchantagreement()->paramval('processcurrency')->map_value}})</p></label>
                                        <div class="controls">
                                            <input type="text" name="newbalance" id="newbalance" class="grd-white"  value="Balance ({{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}) "  disabled />
                                            {{ $errors->first('newbalance', '<span class="help-inline">:message</span>') }}
                                        </div>
                                    </div>
                                </td>

                            </tr>

                            <tr>
                                <td>
                                <div class="control-group {{ $errors->has('comments') ? 'error' : '' }}">
                                    <label class="control-label" for="paid">Comments</label>
                                    <div class="controls">
                                        <textarea style="width: 350px" id="comments" name="comments" class="form-control grd-white" rows="5" placeholder="Enter comments" value="{{ Input::old('comments') }}"></textarea>
                                        {{ $errors->first('comments', '<span class="help-inline">:message</span>') }}
                                    </div>
                                </div>



                                </td>

                            </tr>
                            <tr>
                                <td>
                                    <div class="control-group success">
                                        <div class="alert alert-success">
                                            <strong>Payment Reference: </strong> <span class="badge badge-success">#{{$invoice->invoiceid}}</span>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="form-actions" style="text-align: center">
                                        <button type="button" class="btn">Cancel</button>
                                        <button type="submit" class="btn btn-primary">Make Payment</button>

                                    </div>
                                </td>
                            </tr>

                        </table>
                    </div>


                </div>

            </div>
        </div>
    </div>
    <div class="span2"></div>
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
                    <h2><span class="label label-info">{{ $invoice->balance_out }} {{$invoice->payoutcurrency}}</span></h2>
                </div>
                <div class="sr-header-left">
                    <p class="bold">Balance</p>
                    <small class="muted">{{ date("M j, Y")}}</small>
                </div>
            </div><!--/sidebar-right-header-->

@stop
