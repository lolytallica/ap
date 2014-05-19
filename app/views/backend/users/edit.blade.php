@extends('backend/layouts/main')

{{-- Page title --}}
@section('title')
User Update ::
@parent
@stop

{{-- Page content --}}
@section('content')
<!-- content-header -->
<div class="content-header">

    <h2><i class="icofont-user"></i> Users: {{$user->first_name.' '.$user->last_name}}</h2>
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
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="{{ route('users') }}">Users</a></li> <span class="divider">&rsaquo;</span></li>
        <li class="active">User {{$user->first_name.' '.$user->last_name}} Update</li>
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
                    <span>Users</span>
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
			<!-- First Name -->
			<div class="control-group {{ $errors->has('first_name') ? 'error' : '' }}">
				<label class="control-label" for="first_name">First Name</label>
				<div class="controls">
					<input type="text" name="first_name" id="first_name" value="{{ Input::old('first_name', $user->first_name) }}" />
					{{ $errors->first('first_name', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Last Name -->
			<div class="control-group {{ $errors->has('last_name') ? 'error' : '' }}">
				<label class="control-label" for="last_name">Last Name</label>
				<div class="controls">
					<input type="text" name="last_name" id="last_name" value="{{ Input::old('last_name', $user->last_name) }}" />
					{{ $errors->first('last_name', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Email -->
			<div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
				<label class="control-label" for="email">Email</label>
				<div class="controls">
					<input type="text" name="email" id="email" value="{{ Input::old('email', $user->email) }}" />
					{{ $errors->first('email', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Password -->
			<div class="control-group {{ $errors->has('password') ? 'error' : '' }}">
				<label class="control-label" for="password">Password</label>
				<div class="controls">
					<input type="password" name="password" id="password" value="" />
					{{ $errors->first('password', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Password Confirm -->
			<div class="control-group {{ $errors->has('password_confirm') ? 'error' : '' }}">
				<label class="control-label" for="password_confirm">Confirm Password</label>
				<div class="controls">
					<input type="password" name="password_confirm" id="password_confirm" value="" />
					{{ $errors->first('password_confirm', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Activation Status -->
			<div class="control-group {{ $errors->has('activated') ? 'error' : '' }}">
				<label class="control-label" for="activated">User Activated</label>
				<div class="controls">
					<select{{ ($user->id === Sentry::getId() ? ' disabled="disabled"' : '') }} name="activated" id="activated">
						<option value="1"{{ ($user->isActivated() ? ' selected="selected"' : '') }}>@lang('general.yes')</option>
						<option value="0"{{ ( ! $user->isActivated() ? ' selected="selected"' : '') }}>@lang('general.no')</option>
					</select>
					{{ $errors->first('activated', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Groups -->
			<div class="control-group {{ $errors->has('groups') ? 'error' : '' }}">
				<label class="control-label" for="groups">Groups</label>
				<div class="controls">
					<select name="groups[]" id="groups[]" multiple>
						@foreach ($groups as $group)
						<option value="{{ $group->id }}"{{ (array_key_exists($group->id, $userGroups) ? ' selected="selected"' : '') }}>{{ $group->name }}</option>
						@endforeach
					</select>

					<span class="help-block">
						Select a group to assign to the user, remember that a user takes on the permissions of the group they are assigned.
					</span>
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
                                <input type="radio" data-form="uniform" name="permissions[{{ $permission['permission'] }}]" id="{{ $permission['permission'] }}_allow" value="1" {{ (array_get($userPermissions, $permission['permission']) === 1 ? ' checked="checked"' : '') }}>
                                Allow
                            </label>

                            <label class="radio" for="{{ $permission['permission'] }}_deny">
                            <input type="radio" data-form="uniform" name="permissions[{{ $permission['permission'] }}]" id="{{ $permission['permission'] }}_deny" value="-1" {{ (array_get($userPermissions, $permission['permission']) === -1 ? ' checked="checked"' : '') }}>
                            Deny
                            </label>

                            @if($permission['can_inherit'])
                            <label class="radio" for="{{ $permission['permission'] }}_inherit">
                                <input type="radio" data-form="uniform" name="permissions[{{ $permission['permission'] }}]" id="{{ $permission['permission'] }}_inherit" value="0" {{ ( ! array_get($userPermissions, $permission['permission']) ? ' checked="checked"' : '') }}>
                                Inherit
                            </label>
                            @endif


						</div>
						@endforeach
                        </td>
					</fieldset>
                        </tr>
					@endforeach
                        </table>

				</div>
			</div>
		</div>
	</div>

	<!-- Form Actions -->
	<div class="control-group">
		<div class="controls">
			<a class="btn btn-link" href="{{ route('users') }}">Cancel</a>

			<button type="reset" class="btn">Reset</button>

			<button type="submit" class="btn btn-success">Update User</button>
		</div>
	</div>
</form>

                </div><!-- /box-body -->
            </div><!-- /box -->
        </div><!-- /span -->
    </div>
@stop
