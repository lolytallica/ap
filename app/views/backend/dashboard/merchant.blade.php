@extends('backend/layouts/main')
{{-- Web site Title --}}
@section('title')
Admin Dashboard:
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
                <div class="action-text color-green">{{Cache::get('numpaidinvoices')}} <span class="helper-font-small color-silver-dark">Invoices paid</span></div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <span data-chart="peity-bar" data-height="32" data-colours='["#00A0B1", "#00A0B1"]'>9,7,9,6,3,5,3,5,5,2</span>
                <div class="action-text color-teal">{{Cache::get('numupcominginvoices')}} <span class="helper-font-small color-silver-dark">Upcoming invoices</span></div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <span data-chart="peity-bar" data-height="32" data-colours='["#BF1E4B", "#BF1E4B"]'>6,5,9,7,3,5,2,5,3,9</span>
                <div class="action-text color-red">{{Cache::get('numoverdueinvoices')}} <span class="helper-font-small color-silver-dark">Overdue</span></div>
            </a>
        </li>
    </ul>
    <h2><i class="icofont-home"></i> Dashboard <small>welcome</small></h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">

        <li class="divider"></li>
        <li class="btn-group">
            <a href="{{ URL::to('admin/mapayment') }}" class="btn btn-small btn-link">
                <i class="icofont-money"></i> Payments <span class="color-red">(+{{Cache::get('numpayments')}})</span>
            </a>
        </li>
        <li class="divider"></li>
        <li class="btn-group">
            <a href="{{ URL::to('admin/merchantagreement') }}" class="btn btn-small btn-link">
                <i class="icofont-user"></i> Merchant Agreements <span class="color-red">(+{{Cache::get('nummerchantagreements')}})</span>
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{route('admin')}}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li class="active">System overview</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->

<!-- content-body -->
<div class="content-body">


<!-- dashboar -->
<!-- shortcut button -->
<div class="shortcut-group">

    <ul class="a-btn-group">

        @if(Sentry::getUser()->hasAccess('view_ma_invoices'))
        <li>
            <a href="{{ URL::to('admin/mainvoice') }}" class="a-btn grd-white" rel="tooltip" title="Invoices">
                <span></span>
                <span><i class="icofont-barcode color-silver-dark"></i></span>
                <span class="color-silver-dark"><i class="icofont-file color-teal"></i></span>

            </a>
        </li>
        @endif

        @if(Sentry::getUser()->hasAccess('view_ma_payments'))
        <li>
            <a href="{{ URL::to('admin/mapayment') }}" class="a-btn grd-white" rel="tooltip" title="Payments">
                <span></span>
                <span><i class="icofont-money color-silver-dark"></i></span>
                <span class="color-silver-dark"><i class="icofont-money color-red"></i></span>
            </a>
        </li>
        @endif

        @if(Sentry::getUser()->hasAccess('merchantagreement'))
        <li>
            <a href="{{ URL::to('admin/merchantagreement') }}" class="a-btn grd-white" rel="tooltip" title="Merchant agreements">
                <span></span>
                <span><i class="icofont-briefcase color-silver-dark"></i></span>
                <span class="color-silver-dark"><i class="icofont-group color-silver-dark"></i></span>
            </a>
        </li>
        @endif

        @if(Sentry::getUser()->hasAccess('manage_reports'))
        <li>
            <a href="#" class="a-btn grd-white" rel="tooltip" title="Reporting">
                <span></span>
                <span><i class="icofont-bar-chart color-silver-dark"></i></span>
                <span class="color-silver-dark"><i class="typicn-lineChart"></i></span>
            </a>
        </li>
        @endif

        <li class="clearfix"></li>
    </ul>
</div><!-- /shortcut button -->

<div class="divider-content"><span></span></div>

<!-- tab stat -->
<div class="box-tab corner-all">
    <div class="box-header corner-top">
        <div class="header-control pull-right">
            <a data-box="collapse"><i class="icofont-caret-up"></i></a>
        </div>
        <h4>Last 10 days overview</h4>
        <ul class="nav nav-tabs" id="tab-stat">
            <li class="active"><a data-toggle="tab" href="#system-stat">Numbers</a></li>
            @foreach($allcurrencies as $currency)
            <li><a data-toggle="tab" href="#{{$currency->currency}}">{{$currency->currency}} Traffic</a></li>
            @endforeach

            @if(@count($agreements)>1)
            <li><a data-toggle="tab" href="#ma-tab">Traffic per merchant agreement</a></li>
            @endif
        </ul>
    </div>
    <div class="box-body">
        <div class="tab-content">
            <div class="tab-pane fade in active" id="system-stat">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="dashboard-stat" rel="tooltip" title="Daily Transactions">

                            {{Lava::AreaChart('Transactions')->outputInto('transactions_div'); }}
                            {{ Lava::div(600, 300);}}

                            <div class="stat-label grd-green color-white">Daily CHB</div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="dashboard-stat">

                            {{Lava::ColumnChart('Chargebacks')->outputInto('chb_div'); }}
                            {{ Lava::div(600, 300);}}

                            <div class="stat-label grd-teal color-white">Daily CHB</div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="dashboard-stat">
                            {{Lava::ColumnChart('Refunds')->outputInto('refunds_div'); }}
                            {{ Lava::div(600, 300);}}

                            <div class="stat-label grd-red color-white">Daily Refunds</div>
                        </div>
                    </div>
                </div>
            </div>

            @foreach($allcurrencies as $currency)
            <div class="tab-pane fade" id="{{$currency->currency}}">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="dashboard-stat" rel="tooltip" title="Transactions amount ({{$currency->currency}})">
                            {{Lava::AreaChart('transaction_'.$currency->currency)->outputInto('transactionsamount_div_'.$currency->currency); }}
                            {{ Lava::div(600, 300);}}
                            <div class="stat-label grd-green color-white">Daily Transactions amount ({{$currency->currency}})</div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="dashboard-stat" rel="tooltip" title="CHB amounts ({{$currency->currency}})">
                            {{Lava::ColumnChart('chb_'.$currency->currency)->outputInto('chbamount_div_'.$currency->currency); }}
                            {{ Lava::div(600, 300);}}
                            {{ Lava::div(600, 300);}}
                            <div class="stat-label grd-teal color-white">Daily CHB amounts ({{$currency->currency}})</div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="dashboard-stat" rel="tooltip" title="Refunds amount ({{$currency->currency}})">
                            {{Lava::ColumnChart('refunds_'.$currency->currency)->outputInto('refundssamount_div_'.$currency->currency); }}
                            {{ Lava::div(600, 300);}}
                            <div class="stat-label grd-red color-white">Daily Refunds amount ({{$currency->currency}})</div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            @if(@count($agreements)>1)
            <div class="tab-pane fade" id="ma-tab">
                <div class="row-fluid">
                    <div class="span4">
                        <div class="dashboard-stat" rel="tooltip" title="Transactions per merchant agreement">

                            {{Lava::AreaChart('matransaction')->outputInto('matransactions_div'); }}
                            {{ Lava::div(600, 300);}}

                            <div class="stat-label grd-green color-white">Transactions per merchant agreement</div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="dashboard-stat">

                            {{Lava::ColumnChart('machb')->outputInto('machb_div'); }}
                            {{ Lava::div(600, 300);}}

                            <div class="stat-label grd-teal color-white">CHB per merchant agreement</div>
                        </div>
                    </div>
                    <div class="span4">
                        <div class="dashboard-stat">
                            {{Lava::ColumnChart('marefund')->outputInto('marefunds_div'); }}
                            {{ Lava::div(600, 300);}}

                            <div class="stat-label grd-red color-white">Refunds per merchant agreement</div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div><!-- /tab stat -->

<div class="divider-content"><span></span></div>

<div class="row-fluid">
<!-- tab resume update -->
@if(Sentry::getUser()->hasAccess('view_ma_invoices'))
<div class="span6">
<div class="box-tab corner-all">
<div class="box-header corner-top">
    <!--tab action-->
    <div class="header-control pull-right">
        <a data-box="collapse"><i class="icofont-caret-up"></i></a>
        <a data-box="close" data-hide="rotateOutDownLeft">&times;</a>
    </div>
    <ul class="nav nav-pills">
        <!--tab menus-->
        <li class="active"><a data-toggle="tab" href="#new_invoices">New Invoices @if(Cache::get('numnewinvoices')) <span class="badge badge-important">{{Cache::get('numnewinvoices')}}</span> @endif</a></li>
        <li><a data-toggle="tab" href="#partially_paid">Partially Paid @if(Cache::get('numppaid')) <span class="badge badge-important">{{Cache::get('numppaid')}}</span> @endif</a></li>
        <li><a data-toggle="tab" href="#tobepaid">To be paid @if(Cache::get('numtobepaid')) <span class="badge badge-important">{{Cache::get('numtobepaid')}}</span> @endif</a>  </li>

        <li><a data-toggle="tab" href="#overdue">Overdue @if(Cache::get('numoverdueinvoices')>0) <span class="badge badge-important">{{Cache::get('numoverdueinvoices')}}</span> @endif</a></li>
        <!--tab menus-->
    </ul>
</div>
<div class="box-body">
    <!-- widgets-tab-body -->
    <div class="tab-content">
        <div class="tab-pane fade in active" id="new_invoices">
            @if(@count($new_invoices))
            @foreach($new_invoices as $new_inv)
            <div class="media">
                <a class="pull-left" href="#">

                    <a href="{{ route('show/mainvoice', $new_inv->id) }}" class="a-btn square grd-white" title="Show Invoice # {{$new_inv->id}}">
                        <span></span>

                        <span class="color-silver-dark" style="font-size: 16px; font-weight: bolder; color: lightcoral"> #{{$new_inv->invoiceid}}</span>
                        <span class="color-silver-dark" style="font-size: 16px; color: lightcoral"> #{{$new_inv->invoiceid}}</span>

                    </a>
                </a>
                <div class="media-body">
                    <h4 class="media-heading"><a href="#">{{ $new_inv->merchantagreement()->name }} </a><small class="helper-font-small"> From {{date('M d, Y', strtotime($new_inv->date_from))}} To {{date('M d, Y', strtotime($new_inv->date_to))}}</small></h4>
                    <table class="table" style="width: 500px">
                        <tr>
                            <th>Merchant</th>
                            <td>{{$new_inv->merchant()->merchant}}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <th><span class="label label-warning">{{ ($new_inv->amount!=0? $new_inv->amount : 0)}} {{$new_inv->processcurrency}}</span></th>
                        </tr>
                    </table>
                    <div class="btn-group pull-right">
                        <a href="{{ route('show/mainvoice', $new_inv->id) }}" class="btn btn-mini">Show</a>

                    </div>
                </div>
            </div>
            @endforeach


            <a href="{{ URL::to('admin/mainvoice') }}" class="btn btn-small btn-link pull-right">View all &rarr;</a>
            @else
            No invoices have been generated today.
            @endif
        </div>

        <div class="tab-pane fade" id="partially_paid">
            @if(@count($ppaid_invoices))
            @foreach($ppaid_invoices as $ppaid_inv)
            <div class="media">
                <a class="pull-left" href="#">

                    <a href="{{ route('show/mainvoice', $ppaid_inv->mainvoice_id) }}" class="a-btn square grd-white" title="Show Invoice # {{$ppaid_inv->id}}">
                        <span></span>

                        <span class="color-silver-dark" style="font-size: 16px; font-weight: bolder; color: teal"> #{{$ppaid_inv->invoiceid}}</span>
                        <span class="color-silver-dark" style="font-size: 16px; color: teal"> #{{$ppaid_inv->invoiceid}}</span>

                    </a>
                </a>
                <div class="media-body">
                    <h4 class="media-heading"><a href="#">{{ $ppaid_inv->merchantagreement()->name }} </a><small class="helper-font-small"> From {{date('M d, Y', strtotime($ppaid_inv->date_from))}} To {{date('M d, Y', strtotime($ppaid_inv->date_to))}}</small></h4>
                    <table class="table" style="width: 500px">
                        <tr>
                            <th>Merchant: {{$ppaid_inv->paid()}}</th>
                            <td>{{$ppaid_inv->merchant()->merchant}}</td>
                        </tr>
                        <tr>
                            <th>Amount Payable</th>
                            <th><span class="label label-warning">{{ $total[$ppaid_inv->mainvoice_id]}} {{$ppaid_inv->processcurrency}}</span></th>
                        </tr>
                        <tr>
                            <th>Paid</th>
                            <th><span class="label label-success">{{ $paid[$ppaid_inv->mainvoice_id]}} {{$ppaid_inv->processcurrency}}</span></th>
                        </tr>
                        <tr>
                            <th>Upaid</th>
                            <th><span class="label label-important">{{ $unpaid[$ppaid_inv->mainvoice_id] }} {{$ppaid_inv->processcurrency}}</span></th>
                        </tr>
                    </table>
                    <div class="btn-group pull-right">
                        <a href="{{ route('show/mainvoice',  $ppaid_inv->mainvoice_id) }}" class="btn btn-mini">Show</a>

                    </div>
                </div>
            </div>
            @endforeach

            <a href="{{ route('status/mainvoice', 4) }}" class="btn btn-small btn-link pull-right">View all &rarr;</a>
            @else
            No partially paid invoices found
            @endif
        </div>

        <div class="tab-pane fade" id="tobepaid">
            @if(@count($tobepaid))
            @foreach($tobepaid as $tbpaid)
            <div class="media">
                <a class="pull-left" href="#">

                    <a href="{{ route('show/mainvoice', $tbpaid->id) }}" class="a-btn square grd-white" title="Show Invoice # {{$tbpaid->id}}">
                        <span></span>

                        <span class="color-silver-dark" style="font-size: 16px; font-weight: bolder; color: darkred"> #{{$tbpaid->invoiceid}}</span>
                        <span class="color-silver-dark" style="font-size: 16px; color: darkred"> #{{$tbpaid->invoiceid}}</span>

                    </a>
                </a>
                <div class="media-body">
                    <h4 class="media-heading"><a href="#">{{ $tbpaid->merchantagreement()->name }} </a><small class="helper-font-small"> From {{date('M d, Y', strtotime($tbpaid->date_from))}} To {{date('M d, Y', strtotime($tbpaid->date_to))}}</small></h4>
                    <table class="table" style="width: 500px">
                        <tr>
                            <th>Merchant</th>
                            <td>{{$tbpaid->merchant()->merchant}}</td>
                        </tr>
                        <tr>
                            <th>Amount</th>
                            <th><span class="label label-warning">{{ ($tbpaid->amount!=0? $tbpaid->amount : 0)}} {{$tbpaid->processcurrency}}</span></th>
                        </tr>
                        <tr>
                            <th>Payout due</th>
                            <th><div class="alert alert-error">
                                    <span class="label label-important">{{ date("D d, Y", strtotime($tbpaid->payout_date)) }}</span>
                                </div>
                            </th>
                        </tr>
                    </table>
                    <div class="btn-group pull-right ">
                        <a href="{{ route('show/mainvoice', $tbpaid->id) }}" class="btn btn-mini">Show</a>

                    </div>
                </div>
            </div>
            @endforeach

            <a href="{{ route('status/mainvoice', 2) }}" class="btn btn-small btn-link pull-right">View all &rarr;</a>
            @else
            No invoices are to be paid today.
            @endif
        </div>

        <div class="tab-pane fade" id="overdue">
            @foreach($overdue_invoices as $od)
            <div class="media">
                <a class="pull-left" href="#">

                    <a href="{{ route('show/mainvoice', $od->id) }}" class="a-btn square grd-white" title="Show Invoice # {{$od->id}}">
                        <span></span>

                        <span class="color-silver-dark" style="font-size: 16px; font-weight: bolder; color: darkred"> #{{$od->invoiceid}}</span>
                        <span class="color-silver-dark" style="font-size: 16px; color: darkred"> #{{$od->invoiceid}}</span>

                    </a>
                </a>
                <div class="media-body">
                    <h4 class="media-heading"><a href="#">{{ $od->merchantagreement()->name }} </a><small class="helper-font-small"> From {{date('M d, Y', strtotime($od->date_from))}} To {{date('M d, Y', strtotime($od->date_to))}}</small></h4>
                    <table class="table" style="width: 500px">
                        <tr>
                            <th>Merchant</th>
                            <td>{{$od->merchant()->merchant}}</td>
                        </tr>

                        <tr>
                            <th>Amount</th>
                            <th>
                                <span class="label label-warning">{{ ($od->amount!=0? $od->amount : 0)}} {{$od->processcurrency}}</span></th>
                        </tr>
                        <tr>
                            <th>Payout date</th>
                            <th><div class="alert alert-error">{{date('M d, Y', strtotime($od->payout_date))}}</div></th>
                        </tr>
                    </table>
                    <div class="btn-group pull-right">
                        <a href="{{ route('show/mainvoice', $od->id) }}" class="btn btn-mini">Show</a>

                    </div>
                </div>
            </div>
            @endforeach

            <a href="{{ route('status/mainvoice', 6) }}" class="btn btn-small btn-link pull-right">View all &rarr;</a>
        </div>
    </div><!--/widgets-tab-body-->
</div><!--/box-body-->
</div><!--/box-tab-->
</div><!-- tab resume update -->
@endif

@if(Sentry::getUser()->hasAccess('view_ma_payments'))
<div class="span6">
    <div class="box corner-all">
        <div class="box-header corner-top grd-white">
            <div class="header-control">
                <a data-box="collapse"><i class="icofont-caret-up"></i></a>
                <a data-box="close" data-hide="rotateOutDownRight">&times;</a>
            </div>
            <span><i class="icofont-money"></i> Recent payments</span>
        </div>
        <div class="box-body">
            <table class="table table-striped">
                @if(count($recentpayments))
                <thead>
                <tr>
                    <th>Merchant Agreement</th>
                    <th>Merchant</th>
                    <th>Amount</th>
                    <th>Invoice</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>

                @foreach($recentpayments as $recentpayment)
                <tr>
                    <td>
                        @if(Sentry::getUser()->hasAccess('merchant'))
                        <a href="{{ route('show/merchant', $recentpayment->paymentinvoice()->merchant()->id) }}" class="btn btn-mini" target="_blank">{{$recentpayment->paymentinvoice()->merchant()->merchant}}</a>
                        @else
                        {{$recentpayment->paymentinvoice()->merchant()->merchant}}
                        @endif
                    </td>
                    <td>
                        @if(Sentry::getUser()->hasAccess('merchantagreement'))
                        <a href="{{ route('show/ma', $recentpayment->paymentinvoice()->merchantagreement()->id) }}" class="btn btn-mini" target="_blank">{{$recentpayment->paymentinvoice()->merchantagreement()->name}}</a>
                        @else
                        {{$recentpayment->paymentinvoice()->merchantagreement()->name}}
                        @endif
                    </td>
                    <td><span class="label label-info">{{($recentpayment->amount_payout!=0? $recentpayment->amount_payout : 0)}} {{$recentpayment->paymentinvoice()->merchantagreement()->paramval('payoutcurrency')->map_value}}</span></td>
                    <td>
                        @if(Sentry::getUser()->hasAccess('view_ma_invoices'))
                        <a href="{{ route('show/mainvoice', $recentpayment->mainvoice_id) }}" class="btn btn-mini"  target="_blank">#{{$recentpayment->paymentinvoice()->invoiceid}}</a>
                        @else
                        #{{$recentpayment->mainvoice_id}}
                        @endif
                    </td>
                    <td>{{date('M d, Y', strtotime($recentpayment->created_at))}}</td>
                </tr>
                @endforeach
                <tr>
                    <td colspan="5"><a href="{{ route('mapayment') }}" class="btn btn-small btn-link pull-right">View all &rarr;</a></td>
                </tr>

                </tbody>
                @else
                No Payments found!
                @endif
            </table>

        </div>

    </div>

</div>
@endif

</div><!-- tab stat -->

<div class="divider-content"><span></span></div>

@include('backend/layouts/right_sidebar')

<!--schedule-->


<!--schedule-->
@stop