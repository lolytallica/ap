<?php

return array(

    'id'                        => 'Id',
    'invoiceid'                 => 'Invoice Id',
    'paymentid'                 => 'Payment Id',

    'date'                      => 'Created',
    'created_by'                => 'Created by',

    'agreement'                 => 'Agreement',
    'from'                      => 'From',
    'to'                        => 'To',
    'actions'                   => 'Actions',
    'amount'                    => 'Amount',
    'total_processed'           => 'Total processed',
    'amount_payout'             => 'Payout amount',
    'conversion_rate'           => 'Conversion rate',
    'comments'                  => 'Comments',
    'held'                      => 'Held on payment',
    'merchant'                  => 'Merchant',

    ////Invoice Status
    'status'                    => 'Status',
    'draft'                     => 'Draft',
    'sent'                      => 'Sent',
    'paid'                      => 'Paid',
    'partiallypaid'             => 'Partially Paid',
    'archieved'                 => 'Archieved',
    'overdue'                   => 'Overdue',

    ////Rows
    'processed_amount'          => 'Processed Amount',
    'deducted_amount'           => 'Deducted Amount',
    'refunded_amount'           => 'Refunded Amount',
    'chargebacked_amount'       => 'Chargebacked Amount',
    'payable_sum'               => 'Payable Sum',

    ////Costs
    'rate_processed_amount'     => 'Rate on processed amount',
    'cost_per_refund'           => 'Cost per refund',
    'cost_per_chargeback'       => 'Cost per chargeback',
    'cost_per_transaction'      => 'Cost per transaction',
    'sum_costs'                 => 'Sum Costs',
    'sum_costs_fixed'           => 'Sum Costs',
    'sum_additional_costs'      => 'Additional Costs',
    'sum_additional_income'     => 'Additional Income',

    ///REPORT
    'payable'                   => 'Payable',
    'minus_costs'               => 'Minus Costs',


    //BALANCE
    'balancein'                 => 'Balance In',
    'balanceout'                => 'Balance Out',


    //Voucher Events
    'Refund on Voucher, Merchant request' => 'Refunded on request from Merchant',
    'Chargeback on Voucher' => 'Chargeback on Voucher',
    'CB prevention on Voucher' => 'CB prevention on Voucher',
    'Fraud alert on Voucher' => 'Fraud alert on Voucher',
    'Chargeback - Voucher not refunded' => 'Chargeback - Voucher not refunded',
    'CB Prevention by staff decision' => 'CB Prevention by staff decision',
    'Voucher credited, not through PSP' => 'Voucher credited, not through PSP',
    'Other Voucher Error' => 'Other Voucher Error',
    '' => '',
    '' => '',


);
