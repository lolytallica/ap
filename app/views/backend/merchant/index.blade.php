@extends('backend/layouts/main')

{{-- Page title --}}
@section('title')
User Management ::
@parent
@stop

{{-- Page content --}}
@section('content')

<!-- content-header -->
<div class="content-header">
    @include('backend/layouts/header_right')
    <h2><i class="icofont-briefcase"></i> Merchants</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        <li class="divider"></li>
        <li class="btn-group">
            <a class="btn btn-small btn-link" href="{{ route('create/merchant') }}" class="btn btn-small btn-info" title="Add new merchant agreement">
                <i class="icofont-plus"></i> Create
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li class="active">Merchants</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->


<!-- content-body -->
<!-- content-body -->
<div class="content-body">
    <!--datatables tools-->
    <div class="row-fluid">
        <div class="span12">
            <div class="box corner-all">
                <div class="box-header grd-white corner-top">
                    <div class="header-control">

                    </div>
                    <span>Merchants</span>
                </div>
                <div class="box-body">
                    <table id="datatablestools" class="table table-hover responsive">
	<thead>
		<tr>
			<th class="span1">@lang('admin/merchant/table.id')</th>
			<th class="span2">@lang('admin/merchant/table.merchant')</th>
			<th class="span2">@lang('admin/merchant/table.abbreviation')</th>
			<th class="span2">@lang('admin/merchant/table.merchantemail')</th>
			<th class="span2">@lang('admin/merchant/table.aguid')</th>

			<th class="span2">@lang('admin/merchant/table.active')</th>
			<th class="span2">@lang('admin/merchant/table.created_at')</th>
			<th class="span2">@lang('table.actions')</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($merchants as $merchant)

		<tr>
			<td>{{ $merchant->id }}</td>
			<td>{{ $merchant->merchant }}</td>
			<td>{{ $merchant->abbreviation }}</td>
			<td>{{ $merchant->merchantemail }}</td>
			<td>{{ $merchant->aguid }}</td>

			<td><?php echo ($merchant->active==1) ? '<span class="label label-success">Yes</span>' : '<span class="label label-important">No</span>' ?> </td>
            <td>{{ date('F d, Y', strtotime($merchant->created_at)) }}</td>
			<td>

                <a href="{{ route('show/merchant', $merchant->id) }}" class="btn btn-mini">@lang('button.show')</a>

				<a href="{{ route('update/merchant', $merchant->id) }}" class="btn btn-mini btn-primary">@lang('button.edit')</a>


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
