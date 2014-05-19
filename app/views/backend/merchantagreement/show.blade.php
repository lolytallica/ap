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
    <ul class="content-header-action pull-right">
        <li>
            <a href="#">
                <div class="badge-circle grd-green color-white"><i class="icofont-plus-sign"></i></div>
                <div class="action-text color-green">8765 <span class="helper-font-small color-silver-dark">Payments</span></div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div class="badge-circle grd-teal color-white"><i class="icofont-user-md"></i></div>
                <div class="action-text color-teal">1437 <span class="helper-font-small color-silver-dark">Merchants</span></div>
            </a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="#">
                <div class="badge-circle grd-orange color-white">$</div>
                <div class="action-text color-orange">4367 <span class="helper-font-small color-silver-dark">Balance</span></div>
            </a>
        </li>
    </ul>
    <h2><i class="icofont-briefcase"></i> Merchant Agreement Details</h2>
</div><!-- /content-header -->

<!-- content-breadcrumb -->
<div class="content-breadcrumb">
    <!--breadcrumb-nav-->
    <ul class="breadcrumb-nav pull-right">
        <li class="divider"></li>
        <li class="btn-group">
            <a class="btn btn-small btn-link"  href="{{ route('update/ma', $ma->id) }}" title="Edit Merchant Agreement">
                <i class="icofont-edit"></i> Edit
            </a>
        <li class="divider"></li>
            <a class="btn btn-small btn-link"  href="{{ route('merchantagreement') }}" title="Back">
                <i class="icofont-back"></i> Back
            </a>
        </li>
    </ul><!--/breadcrumb-nav-->

    <!--breadcrumb-->
    <ul class="breadcrumb">
        <li><a href="{{ route('admin') }}"><i class="icofont-home"></i> Dashboard</a> <span class="divider">&rsaquo;</span></li>
        <li><a href="{{ URL::to('admin/merchantagreement')}}">Merchant Agreement</a> <span class="divider">&rsaquo;</span></li>
        <li class="active">{{ $ma->name}}</li>
    </ul><!--/breadcrumb-->
</div><!-- /content-breadcrumb -->

<!-- content-body -->
<div class="content-body">

    <!-- merchant agreement details -->
    <div id="invoice-container" class="invoice-container">
        <div class="page-header">

            <h3>{{ strtoupper($ma->name) }}</h3>
        </div>
        <div class="row-fluid">
            <div class="span4">
                <p>{{ strtoupper($ma->name)}}</p>
                <p>{{ $ma->description}}</p>

            </div>
            <div class="span4">
                <p >Created: {{ $ma->created_at->diffForHumans() }}</p>
                <p>@lang('general.' . (($ma->activated==1) ? 'activated' : 'notactivated'))</p>

            </div>
            <div class="span4">
                <p>Bank account: {{($ma->bankaccount_id>0 ? $ma->bankaccount()->description:'No bank account assigned')}}</p>

            </div>
        </div>
        <hr>
        <div class="invoice-table" style="width: 90%">
            <table class="table table-bordered invoice responsive">
                <thead>
                <tr>

                    <th style="width: 120px">PARAMETER</th>
                    <th style="width: 160px">DESCRIPTION</th>
                    <th style="width: 80px">VALUE</th>
                    <th style="width: 80px">STATUS</th>
                    <th style="width: 120px">HISTORICAL</th>


                </tr>
                </thead>
                <tbody>
                @foreach ($map as $parameter)
                <tr>

                    <td class="left">@lang('admin/merchantagreement/table.' .$parameter->parameter)</td>
                    <td class="left">{{ $parameter->description }}</td>
                    <td class="center">{{ $parameter->map_value }} {{(@$parameter->extension) ? $parameter->extension:''}}</td>
                    <td class="left">@lang('admin/merchantagreement/table.' .$parameter->status)</td>
                    <td class="center">
                        <!-- Button to trigger modal -->
                        <a href="#statusModal_{{$parameter->parameter}}" role="button" class="btn" data-toggle="modal">Show</a>

                        <!-- Modal -->
                        <div id="statusModal_{{$parameter->parameter}}" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="statusModalLabel" aria-hidden="true">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="statusModal_{{$parameter->parameter}}Label">{{ strtoupper($ma->name) }}</h3> <h4>{{ $parameter->description }} Historical</h4>
                            </div>
                            <div class="modal-body">
                                <table class="table table-bordered table-striped responsive">
                                    <tr>
                                        <th>Status</th>
                                        <th>Value</th>
                                        <th>Date</th>
                                    </tr>
                                @foreach($allmapstatus as $mapstat)
                                @if($mapstat->parameter == $parameter->parameter)
                                    <tr>
                                    <td><p>{{$mapstat->status}} </p></td>
                                    <td><p>{{$mapstat->map_value}} {{(@$parameter->extension) ? $parameter->extension:''}} </p></td>
                                    <td>{{$mapstat->created_at}}</td>
                                    </tr>
                                @endif
                                @endforeach
                                    </table>
                            </div>
                            <div class="modal-footer">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>

                            </div>
                        </div>

                    </td>
                </tr>
                @endforeach

                </tbody>

            </table>
        </div>
    </div>
    <!--/merchant agreement details-->





@stop
