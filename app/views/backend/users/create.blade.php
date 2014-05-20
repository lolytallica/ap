@extends('backend/layouts/main')

{{-- Page title --}}
@section('title')
Create User ::
@parent
@stop

{{-- Page content --}}
@section('content')
<!-- content-header -->
<div class="content-header">

    <h2><i class="icofont-user"></i> Users: </h2>
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
        <li><a href="index.html"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li>Users</li> <span class="divider">&rsaquo;</span></li>
        <li class="active">Create a New User</li>
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
					<input type="text" name="first_name" id="first_name" value="{{ Input::old('first_name') }}" />
					{{ $errors->first('first_name', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Last Name -->
			<div class="control-group {{ $errors->has('last_name') ? 'error' : '' }}">
				<label class="control-label" for="last_name">Last Name</label>
				<div class="controls">
					<input type="text" name="last_name" id="last_name" value="{{ Input::old('last_name') }}" />
					{{ $errors->first('last_name', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Email -->
			<div class="control-group {{ $errors->has('email') ? 'error' : '' }}">
				<label class="control-label" for="email">Email</label>
				<div class="controls">
					<input type="text" name="email" id="email" value="{{ Input::old('email') }}" />
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
					<select name="activated" id="activated">
						<option value="1"{{ (Input::old('activated', 0) === 1 ? ' selected="selected"' : '') }}>@lang('general.yes')</option>
						<option value="0"{{ (Input::old('activated', 0) === 0 ? ' selected="selected"' : '') }}>@lang('general.no')</option>
					</select>
					{{ $errors->first('activated', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Groups -->
			<div class="control-group {{ $errors->has('groups') ? 'error' : '' }}">
				<label class="control-label" for="groups">Groups</label>
				<div class="controls">

					<select name="groups[]" id="groups[]" class="multiselect" multiple="multiple">
                        @foreach (Sentry::getGroups() as $gr)
                        @foreach($gr->hasGroups($gr->id) as $ghg)

                        <?php $group = Sentry::getGroupProvider()->findByName($ghg->name); ?>

						<option value="{{ $group->id }}"{{ (in_array($group->id, $selectedGroups) ? ' selected="selected"' : '') }}>@lang('admin/groups/table.'.$group->name) </option>

						@endforeach
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
			<div class="control-group">
				<div class="controls">
                    <table class="table">
                    <fieldset>
                        <?php $printed= array(); ?>
					@foreach ($permissions as $area => $permissions)

                    @foreach ($permissions as $permission)
                        <?php $print_permission = 0;?>
                    @foreach (Sentry::getGroups() as $group)
                    @foreach($group->hasGroups($group->id) as $ghg)

                    <?php $gh = Sentry::getGroupProvider()->findByName($ghg->name); ?>

                    @if( $gh->hasAccess(base64_decode($permission['permission'])) )
                    <?php $print_permission = 1;?>
                        @endif
                        @endforeach

                        @if($print_permission==1 && @!$printed[$permission['permission']])
                        <tr>
						<td>{{ $area }}</td>
                        <td>
						<div class="control-group">

                            <label class="radio" for="{{ $permission['permission'] }}_allow">
                                <input type="radio" data-form="uniform" value="1" id="{{ $permission['permission'] }}_allow" name="permissions[{{ $permission['permission'] }}]"{{ (array_get($selectedPermissions, $permission['permission']) === 1 ? ' checked="checked"' : '') }}>
                                Allow
                            </label>

                            <label class="radio" for="{{ $permission['permission'] }}_deny">
                                <input type="radio" data-form="uniform" value="-1" id="{{ $permission['permission'] }}_deny" name="permissions[{{ $permission['permission'] }}]"{{ (array_get($selectedPermissions, $permission['permission']) === -1 ? ' checked="checked"' : '') }}>
                                Deny
                            </label>
                            @if ($permission['can_inherit'])
                            <label class="radio" for="{{ $permission['permission'] }}_inherit">
                                <input type="radio" data-form="uniform" value="0" id="{{ $permission['permission'] }}_inherit" name="permissions[{{ $permission['permission'] }}]"{{ ( ! array_get($selectedPermissions, $permission['permission']) ? ' checked="checked"' : '') }}>
                                Inherit
                            </label>
                            @endif
                            </td>

                        </tr>
						</div>
                        <?php $printed[$permission['permission']]=1; ?>
                        @endif

                        @endforeach
                        @endforeach
                    </fieldset>
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

			<button type="submit" class="btn btn-success">Create User</button>
		</div>
	</div>
</form>

                    </div><!-- /box-body -->
                </div><!-- /box -->
            </div><!-- /span -->
        </div>

@stop
