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
    <h2><i class="icofont-briefcase"></i> Merchant Agreement</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        <li class="divider"></li>
        <li class="btn-group">
            <a class="btn btn-small btn-link" href="{{ route('create/ma') }}" class="btn btn-small btn-info" title="Add new merchant agreement">
                <i class="icofont-plus"></i> Create
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li class="active">Merchant Agreements</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->


<!-- content-body -->
<div class="content-body">
    <!--datatables tools-->
    <div class="row-fluid">
        <div class="span12">
            <div class="box corner-all">
                <div class="box-header grd-white corner-top">
                    <div class="header-control">

                    </div>
                    <span>Merchant Agreements</span>
                </div>
                <div class="box-body">
                    <table id="datatablestools" class="table table-hover responsive">
	<thead>
		<tr>
			<th class="span1">@lang('admin/merchantagreement/table.id')</th>
			<th class="span2">@lang('admin/merchantagreement/table.name')</th>
			<th class="span2">@lang('admin/merchantagreement/table.description')</th>

			<th class="span2">@lang('admin/merchantagreement/table.activated')</th>
			<th class="span2">@lang('admin/merchantagreement/table.created_at')</th>
			<th class="span2">@lang('table.actions')</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($merchantagreements as $ma)

		<tr>
			<td>{{ $ma->id }}</td>
			<td>{{ $ma->name }}</td>
			<td>{{ $ma->description }}</td>

			<td><?php echo ($ma->activated==1) ? '<span class="label label-success">Yes</span>' : '<span class="label label-important">No</span>' ?> </td>
            <td>{{ $ma->created_at->diffForHumans() }}</td>
			<td>

                <a href="{{ route('show/ma', $ma->id) }}" class="btn btn-mini">@lang('button.show')</a>

				<a href="{{ route('update/ma', $ma->id) }}" class="btn btn-mini btn-primary">@lang('button.edit')</a>


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
