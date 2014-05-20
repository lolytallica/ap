@extends('backend/layouts/invoice')

{{-- Web site Title --}}
@section('title')
Show Merchant Invoice::
@parent
@stop

{{-- Content --}}
@section('content')




<!-- content-body -->
<div class="content-body" style="width: 750px">

    <h4>Invoice # {{$invoice->invoiceid}}</h4>
    <hr>

    <table width="700px" >
        <tr>
            <td style="width: 350px"></td>
            <td >To Merchant: {{$invoice->merchant()->merchant}}</td>
            <td >Invoice No. #{{$invoice->invoiceid}}</td>
        </tr>
        <tr>
            <td ></td>
            <td >{{$invoice->merchantagreement()->address}}</td>
            <td >Invoice Date. {{date('M d, Y', strtotime($invoice->created_at))}}</td>
        </tr>
        <tr>
            <td ></td>
            <td >{{$invoice->merchantagreement()->city}}, {{$invoice->merchantagreement()->country}} {{$invoice->merchantagreement()->zip}}</td>
            <td ></td>
        </tr>
    </table>

<table data-width="700px" class="table invoice responsive">
<tr>
    <th colspan="4" style="text-align: left; background-color: #000000"><font color="white">Description</font></th>
</tr>
    <tr>
        <th style="width: 100px">Date</th>
        <td style="width: 200px"> {{date('M d, Y', strtotime($invoice->date_from))}} - {{date('M d, Y', strtotime($invoice->date_to))}}</td>
        <td >Report No:</td>
        <td style="width: 200px">Year: {{date('Y', strtotime($invoice->created_at) )}} Month: {{date('M', strtotime($invoice->created_at) )}} </td>
    </tr>
    <tr>
        <th>Transaction</th>
        <td > Transactions From {{$invoice->transactionid_from}}- To {{$invoice->transactionid_to}} </td>
        <td >Report Type</td>
        <td > </td>
    </tr>
    <tr>
        <th > Agreement </th>
        <td > {{ $invoice->merchantagreement()->name }} </td>
        <td></td>
        <td></td>
    </tr>
</table>

<table data-width="700px" class="table  invoice responsive">
<tr>
    <th colspan="2" style="text-align: left; background-color: #d3d3d3"><font color="white">Underlaying values</font></th>
</tr>
<tr>
    <td data-width="350px">
        <table class="table">
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
    </td>
    <td>
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

            </tr>
            <tr>
                <td>Chargebacks</td>
                <td>{{ $invoice->chargebacks_number }} </td>
                <td>{{ (($invoice->transactions_number>0) ? round(($invoice->chargebacks_number/$invoice->transactions_number)*100, 2) : 0) }} %</td>
                <td></td>
                <td>{{ (($invoice->chargebacks_amount>0) ? '-'.($invoice->chargebacks_amount) : 0 )}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
            </tr>
            <tr><td colspan="5"></td></tr>
        </table>
    </td>
</tr>
</table>

        <table style="width: 700px" class="table  invoice responsive">

            <tr>
                <td style="width: 200px">&nbsp;</td>
                <td style="width: 500px">
                    <table class="table">
                        <tr>
                            <th colspan="3" style="text-align: left; background-color: #4682b4"><font color="white">Specification</font></th>
                        </tr>



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
                        <th>@lang('admin/invoices/invoices.payable_sum') </th>
                        <th> {{(@$invoice->rowval('payable_sum')->amount>0)?($invoice->rowval('payable_sum')->amount) : 0}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</th>
                        <td></td>
                    </tr>

                </table>
            </td>
        </tr>

    </table>


<table  style="width: 700px" class="table  invoice responsive">


    <tr>
        <td style="width: 200px">&nbsp;</td>
        <td style="width: 500px">
            <table class="table">
                <tr>
                    <th colspan="2" style="text-align: left; background-color: darkred"><font color="white">Costs</font></th>
                </tr>

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
                    <th>@lang('admin/invoices/invoices.sum_costs') </th>
                    <th> {{$invoice->rowval('sum_fixed_costs')->amount}}  {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</th>
                </tr>

            </table>
        </td>
    </tr>

</table>

    @if(count($holdbackrows))
<table data-width="700px" class="table  invoice responsive">
    <tr>
        <th style="width: 200px">&nbsp;</th>
        <th style="text-align: left; background-color: forestgreen"><font color="white">Holdback reserve</font></th>

    </tr>
    <tr>
        <td></td>
        <td style="width: 500px">
            <table class="table">

                <tr>
                    <th> </th>
                    <td> </td>
                </tr>


            </table>
        </td>
    </tr>

</table>
    @endif

@if(count($customrows))
<table data-width="700px" class="table  invoice responsive">

    <tr>
        <td style="width: 200px">&nbsp;</td>
        <td style="width: 500px">
            <table class="table">
                <tr>
                    <th colspan="3" style="text-align: left; background-color: orangered"><font color=white>Additional Costs</font></th>
                </tr>
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
                    <th>Total Additional Costs</th>
                    <th>{{$invoice->rowval('sum_custom_costs')->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</th>
                    <th></th>
                </tr>
            </table>
        </td>
    </tr>

</table>
@endif

@if(count($incomerows))
<table data-width="700px" class="table  invoice responsive">


    <tr>
        <td style="width: 200px">&nbsp;</td>
        <td style="width: 500px">
            <table class="table">
                <tr>
                    <th colspan="3" style="text-align: left; background-color: #008080"><font color="white">Additional Income</font></th>
                </tr>
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
                    <th>Total Additional Income</th>
                    <th>{{$invoice->rowval('sum_income')->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</th>
                    <th></th>
                </tr>
            </table>
        </td>
    </tr>

</table>
@endif

<table data-width="700px" class="table  invoice responsive">


    <tr>
        <td style="width: 200px">&nbsp;</td>
        <td style="width: 500px">
            <table class="table">
                <tr>
                    <th colspan="4" style="text-align: left; background-color: #000080"><font color="white">To Report</font></th>
                </tr>
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

                <tr>
                    <td></td>
                    <td>{{$heldrow->description}}</td>
                    <td>{{$heldrow->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</td>
                    <td>{{$heldrow->custom_reason}}</td>
                </tr>
                @endforeach
                @endif

                <tr>
                    <th>@lang('admin/invoices/invoices.payable')</th>
                    <th>{{$invoice->rowval('payable')->amount}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</th>
                    <th></th>
                    <th></th>
                </tr>



            </table>
        </td>
    </tr>

</table>


    @if(count($payments))
    <?php
    $total_partial_payments = 0;
    ?>
    <table data-width="700px" class="table  invoice responsive">


        <tr>
            <td style="width: 200px">&nbsp;</td>
            <td style="width: 500px">
                <table class="table">
                    <tr>
                        <th colspan="4" style="text-align: left; background-color: #800080"><font color="white">Partial Payments</font></th>
                    </tr>
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
                        <th>Total Partial Payments</th>
                        <th colspan="3">{{$total_partial_payments}} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</th>

                    </tr>
                </table>
            </td>
        </tr>

    </table>
    @endif


<table data-width="700px" class="table  invoice responsive">

    <tr>
        <td style="width: 200px">&nbsp;</td>
        <td style="width: 500px">
            <table class="table">
                <tr>
                    <th colspan="3" style="text-align: left; background-color: darkslategrey"><font color="white">Balance</font></th>
                </tr>

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
                    <th>Balance Out</th>
                    <th>{{ $invoice->balance_out }} {{$invoice->merchantagreement()->paramval('processcurrency')->map_value}}</th>
                    <td></td>
                </tr>

            </table>
        </td>
    </tr>

</table>



    @stop
