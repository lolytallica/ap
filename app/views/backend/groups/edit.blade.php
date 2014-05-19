@extends('backend/layouts/main')

{{-- Web site Title --}}
@section('title')
Group Update ::
@parent
@stop

{{-- Content --}}
@section('content')
<!-- content-header -->
<div class="content-header">

    <h2><i class="icofont-group"></i> Users Groups : {{$group->name}}</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
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
        <li><a href="{{route('admin')}}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="{{route('groups')}}">User Groups</a></li> <span class="divider">&rsaquo;</span></li>
        <li class="active">Group {{$group->name}} Update</li>
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
<!-- Tabs -->
<ul class="nav nav-tabs">
	<li class="active"><a href="#tab-general" data-toggle="tab">General</a></li>
	<li><a href="#tab-permissions" data-toggle="tab">Permissions</a></li>
</ul>

<form class="form-horizontal" method="post" action="" autocomplete="off">
	<!-- CSRF Token -->
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />

	<!-- Tabs Content -->
	<div class="tab-content">
		<!-- General tab -->
		<div class="tab-pane active" id="tab-general">
			<!-- Name -->
			<div class="control-group {{ $errors->has('name') ? 'error' : '' }}">
				<label class="control-label" for="name">Name</label>
				<div class="controls">
					<input type="text" name="name" id="name" value="{{ Input::old('name', $group->name) }}" />
					{{ $errors->first('name', '<span class="help-inline">:message</span>') }}
				</div>
			</div>
		</div>

		<!-- Permissions tab -->
		<div class="tab-pane" id="tab-permissions">
			<div class="controls">
                <div class="control-group span8">
                <table class="table">

					@foreach ($permissions as $area => $permissions)
					<fieldset>
                        <tr>
						<td>{{ $area }}</td>
                            <td>
						@foreach ($permissions as $permission)

						<div class="control-group">


                            <label class="radio" for="{{ $permission['permission'] }}_allow">
                                <input type="radio" data-form="uniform" name="permissions[{{ $permission['permission'] }}]" id="{{ $permission['permission'] }}_allow" value="1" {{ (array_get($groupPermissions, $permission['permission']) === 1 ? ' checked="checked"' : '') }}>
                                Allow
                            </label>

                            <label class="radio" for="{{ $permission['permission'] }}_deny"">
                                <input type="radio" data-form="uniform" name="permissions[{{ $permission['permission'] }}]" id="{{ $permission['permission'] }}_deny" value="0" {{ ( ! array_get($groupPermissions, $permission['permission']) ? ' checked="checked"' : '') }}>
                                Deny
                            </label>


						</div>
						@endforeach
                        </td>
					</fieldset>
					@endforeach
                    </tr>
                    </table>
				</div>
			</div>
		</div>
	</div>

	<!-- Form Actions -->
	<div class="control-group">
		<div class="controls">
			<a class="btn btn-link" href="{{ route('groups') }}">Cancel</a>

			<button type="reset" class="btn">Reset</button>

			<button type="submit" class="btn btn-success">Update Group</button>
		</div>
	</div>
</form>

                </div><!-- /box-body -->
            </div><!-- /box -->
        </div><!-- /span -->
    </div>
@stop
