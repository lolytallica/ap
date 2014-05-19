@extends('backend/layouts/main')

{{-- Web site Title --}}
@section('title')
Group Management ::
@parent
@stop

{{-- Content --}}
@section('content')

<!-- content-header -->
<div class="content-header">


    <h2><i class="icofont-group"></i> Users Groups</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        @if(Sentry::getUser()->hasAccess('manage_groups'))
        <li class="divider"></li>
        <li class="btn-group">
            <a href="{{route('create/group') }}" class="btn btn-small btn-link">
                <a class="btn btn-small btn-link"  href="{{ route('create/group') }}" title="create">
                    <i class="typicn-plus "></i>  Create Group
                </a>
            </a>
        </li>
        @endif
        <li class="divider"></li>
        <li class="btn-group">
            <a href="{{ URL::to('admin/merchantagreement') }}" class="btn btn-small btn-link">
                <a class="btn btn-small btn-link"  href="{{ URL::previous() }}" title="Back">
                    <i class="typicn-back"></i> Back
                </a>
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{route('admin')}}"><i class="icofont-home"></i> Dashboard</a>

            <span class="divider">&rsaquo;</span></li>
        <li class="active">User Groups</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->
<div class="content-body">



<div class="row-fluid">
    <div class="span1"></div>
    <div class="span10">
        <div class="box corner-all">
            <div class="box-header grd-white corner-top">
                <div class="header-control">

                </div>
                <span>Groups</span>
            </div>
            <div class="box-body">
                <table id="datatablestools" class="table table-hover responsive">
	<thead>
		<tr>
			<th class="span1">@lang('admin/groups/table.id')</th>
			<th class="span6">@lang('admin/groups/table.name')</th>
			<th class="span6">@lang('admin/groups/table.groups')</th>
			<th class="span2">@lang('admin/groups/table.users')</th>
			<th class="span2">@lang('admin/groups/table.created_at')</th>
			<th class="span2">@lang('table.actions')</th>
		</tr>
	</thead>
	<tbody>
		@if ($groups->count() >= 1)
           <?php $printed = array(); ?>
        @foreach (Sentry::getGroups() as $group)
        @foreach($group->hasGroups($group->id) as $ghg)
            <?php $printed[$ghg->id] = 0;?>
        @endforeach
        @endforeach

        @foreach (Sentry::getGroups() as $group)
        @foreach($group->hasGroups($group->id) as $ghg)
        <?php $gh = Sentry::getGroupProvider()->findByName($ghg->name);  ?>
        @if(!$printed[$gh->id])
		<tr>
			<td>{{ $gh->id }}</td>
			<td> @lang('admin/groups/table.'.$gh->name) </td>
			<td>@foreach($gh->hasGroups($gh->id) as $ghc) {{ '>> '}} @lang('admin/groups/table.'.$ghc->name) <br> @endforeach</td>
            <td>{{ $gh->users()->count() }}</td>
			<td>{{ $gh->created_at->diffForHumans() }}</td>
			<td>
				<a href="{{ route('update/group', $gh->id) }}" class="btn btn-mini">@lang('button.edit')</a>
				<a href="{{ route('delete/group', $gh->id) }}" class="btn btn-mini btn-danger">@lang('button.delete')</a>
			</td>
		</tr>
        <?php $printed[$gh->id] = 1;?>
        @endif
        @endforeach
		@endforeach
        <?php //var_dump($printed);?>
		@else
		<tr>
			<td colspan="5">No results</td>
		</tr>
		@endif
	</tbody>
                </table>

            </div><!-- /box-body -->
        </div><!-- /box -->
    </div><!-- /span -->
</div><!--/datatables tools-->


@stop
