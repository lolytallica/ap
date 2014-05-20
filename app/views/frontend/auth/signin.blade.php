@extends('frontend/layouts/main')

{{-- Page title --}}
@section('title')
Account Sign in ::
@parent
@stop

{{-- Page content --}}
@section('content')

<!-- section content -->
<section class="section">
    <div class="container">
        <div class="signin-form row-fluid">
            <!--Sign In-->
            <div class="span5 offset3">
                <div class="box corner-all">
                    <div class="box-header grd-teal color-white corner-top">
                        <span>Sign in:</span>
                    </div>
                    <div class="box-body bg-white">
                        <form id="sign-in" method="post" action="{{ route('signin') }}">
                            <!-- CSRF Token -->
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="control-group">
                                <label class="control-label">Email</label>
                                <div class="controls">
                                    <input type="text" class="input-block-level" data-validate="{required: true, messages:{required:'Please enter field username'}}" name="email" id="email" autocomplete="off" value="{{ Input::old('email') }}" />
                                    {{ $errors->first('email', '<span class="help-block">:message</span>') }}
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Password</label>
                                <div class="controls">
                                    <input type="password" class="input-block-level" data-validate="{required: true, messages:{required:'Please enter field password'}}" name="password" id="password" autocomplete="off" />
                                    {{ $errors->first('password', '<span class="help-block">:message</span>') }}
                                </div>
                            </div>
                            <div class="control-group">

                                <label class="checkbox">
                                    <input type="checkbox" data-form="uniform" name="remember_me" id="remember_me_yes" value="yes"> Remember me
                                </label>
                            </div>
                            <div class="form-actions">
                                <input type="submit" class="btn btn-block btn-large btn-primary" value="Sign into account" />
                                <p class="recover-account">Recover your <a href="{{ route('forgot-password') }}" class="link" data-toggle="modal">username or password</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div><!--/Sign In-->

        </div><!-- /row -->
    </div><!-- /container -->



    <!-- modal recover -->
    <div id="modal-recover" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="modal-recoverLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h3 id="modal-recoverLabel">Reset password <small>Email Address</small></h3>
        </div>
        <div class="modal-body">
            <form id="form-recover" method="post">
                <div class="control-group">
                    <div class="controls">
                        <input type="text" data-validate="{required: true, email:true, messages:{required:'Please enter field email', email:'Please enter a valid email address'}}" name="recover" value="{{ Input::old('email') }}"  />
                        <p class="help-block helper-font-small">Enter your email address and we will send you a link to reset your password.</p>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
            <input type="submit" form="form-recover" class="btn btn-primary" value="Send reset link" >
        </div>
    </div><!-- /modal recover-->
</section>

<!-- javascript
================================================== -->
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/uniform/jquery.uniform.js') }}"></script>

<script src="{{ asset('assets/js/validate/jquery.metadata.js') }}"></script>
<script src="{{ asset('assets/js/validate/jquery.validate.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // try your js

        // uniform
        $('[data-form=uniform]').uniform();

        // validate
        $('#sign-in').validate();
        $('#sign-up').validate();
        $('#form-recover').validate();
    })
</script>

@stop
