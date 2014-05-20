@extends('backend/layouts/main')

{{-- Page title --}}
@section('title')
User Management ::
@parent
@stop

{{-- Page content --}}
@section('content')
{{-- Content --}}
@section('content')
<!-- content-header -->
<div class="content-header">

    <h2><i class="icofont-user"></i> Users </h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">

        @if(Sentry::getUser()->hasAccess('create_users'))
        <li class="divider"></li>
        <li class="btn-group">
            <a href="{{route('create/user') }}" class="btn btn-small btn-link">
                <a class="btn btn-small btn-link"  href="{{ route('create/user') }}" title="create">
                    <i class="typicn-plus "></i>  Create User
                </a>
            </a>
        </li>
        @endif
        <li class="divider"></li>
        <li class="btn-group">
            <a href="{{ URL::previous()}}" class="btn btn-small btn-link">
                <a class="btn btn-small btn-link"  href="{{ URL::previous() }}" title="Back">
                    <i class="typicn-back"></i> Back
                </a>
            </a>
        </li>

    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="index.html"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li class="active">System users</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->

<!-- content-body -->
<div class="content-body">

    <div class="row-fluid">
        <div class="span12">
            <div class="box corner-all">
                <div class="box-header grd-white corner-top">
                    <div class="header-control">

                    </div>
                    <span>User Management</span>
                </div>
                <div class="box-body">
                    <table id="datatablestools" class="table table-hover responsive">
	<thead>
		<tr>
			<th class="span1">@lang('admin/users/table.id')</th>
			<th class="span2">@lang('admin/users/table.first_name')</th>
			<th class="span2">@lang('admin/users/table.last_name')</th>
			<th class="span3">@lang('admin/users/table.email')</th>
			<th class="span2">@lang('admin/users/table.activated')</th>
			<th class="span2">@lang('admin/users/table.created_at')</th>
			<th class="span2">@lang('table.actions')</th>
		</tr>
	</thead>
	<tbody>
        <?php $printed= array(); ?>
		@foreach ($users as $user)
        @foreach (Sentry::getGroups() as $group)
        <?php $print_user=0; ?>
        @foreach($group->hasGroups($group->id) as $ghg)
        <?php

        $gh = Sentry::getGroupProvider()->findByName($ghg->name);
      //  var_dump($gh); exit;
        if($user->inGroup($gh))
        {
        $print_user = 1;
        }
        ?>
        @endforeach
        @if($print_user==1 && !@$printed[$user->id])
		<tr>
			<td>{{ $user->id }}</td>
			<td>{{ $user->first_name }}</td>
			<td>{{ $user->last_name }}</td>
			<td>{{ $user->email }}</td>
			<td>@lang('general.' . ($user->isActivated() ? 'yes' : 'no'))</td>
			<td>{{ $user->created_at->diffForHumans() }}</td>
			<td>
				<a href="{{ route('update/user', $user->id) }}" class="btn btn-mini">@lang('button.edit')</a>

				@if ( ! is_null($user->deleted_at))
				<a href="{{ route('restore/user', $user->id) }}" class="btn btn-mini btn-warning">@lang('button.restore')</a>
				@else
				@if (Sentry::getId() !== $user->id)
				<a href="{{ route('delete/user', $user->id) }}" class="btn btn-mini btn-danger">@lang('button.delete')</a>
				@else
				<span class="btn btn-mini btn-danger disabled">@lang('button.delete')</span>
				@endif
				@endif
			</td>
		</tr>
        <?php $printed[$user->id]=1;?>
        @endif
        @endforeach
		@endforeach
	</tbody>
                    </table>

                </div><!-- /box-body -->
            </div><!-- /box -->
        </div><!-- /span -->
    </div><!--/datatables tools-->
@stop
