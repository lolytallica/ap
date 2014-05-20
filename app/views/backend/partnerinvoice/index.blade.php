@extends('backend/layouts/default')

{{-- Web site Title --}}
@section('title')
Partner Invoices
@parent
@stop

{{-- Content --}}
@section('content')
<div class="page-header">
    <h3>
        Partner Invoices
        @if(Sentry::getUser()->hasAccess('manage_partner_invoices'))
        <div class="pull-right">
            <a href="{{ route('create/partnerinvoice') }}" class="btn btn-small btn-info"><i class="icon-plus-sign icon-white"></i> Create</a>
        </div>
        @endif
    </h3>
</div>


<table class="table table-bordered table-striped table-hover">
    <thead>
    <tr>
        <th class="span1">@lang('admin/invoices/invoices.id')</th>
        <th class="span6">@lang('admin/invoices/invoices.description')</th>
        <th class="span6">@lang('admin/invoices/invoices.from')</th>
        <th class="span2">@lang('admin/invoices/invoices.to')</th>
        <th class="span2">@lang('admin/invoices/invoices.date')</th>
        <th class="span2">@lang('admin/invoices/invoices.actions')</th>
    </tr>
    </thead>
    <tbody>
    <tr></tr>

    </tbody>
</table>



@stop
