@extends('backend/layouts/main')

{{-- Page title --}}
@section('title')
Page Not Found
@parent
@stop

{{-- Page content --}}
@section('content')

<section class="section">
    <div class="container">
        <div class="error-page">
            <h1 class="error-code color-red">Error 404</h1>
            <p class="error-description muted">Oops! The requested page could not be found</p>

            <a href="{{route('admin')}}" class="btn btn-small btn-primary"><i class="icofont-arrow-left"></i> Back to Dashboard</a>

        </div>
    </div>
</section>





@stop