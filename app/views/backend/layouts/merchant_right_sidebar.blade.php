<!-- span side-right -->
<div class="span2">
    <!-- side-right -->
    <aside class="side-right" style="right: 20px">
        <!-- sidebar-right -->
        <div class="sidebar-right">
            <!--sidebar-right-header-->
            <div class="sidebar-right-header">
                <div class="sr-header-right">
                    <h2><span class="label label-info"></span></h2>
                </div>
                <div class="sr-header-left">
                    <p class="bold">Totals</p>
                    <small class="muted">{{date('F d Y')}}</small>
                </div>
            </div><!--/sidebar-right-header-->
            <!--sidebar-right-control-->
            <div class="sidebar-right-control">
                <ul class="sr-control-item">
                    <li class="active"><a href="#alt1" data-toggle="tab" title="alternative 1"><i class="icofont-tasks"></i> Transactions</a></li>
                    <li><a href="#alt2" data-toggle="tab" title="alternative 2"><i class="icofont-fullscreen"></i> CHB</a></li>
                    <li><a href="#alt3" data-toggle="tab" title="alternative 3"><i class="icofont-fullscreen"></i> Refunds</a></li>

                </ul>
            </div><!-- /sidebar-right-control-->
            <!-- sidebar-right-content -->
            <div class="sidebar-right-content">
                <div class="tab-content">
                    <!--alternate 1-->
                    <div class="tab-pane fade active in" id="alt1">
                        <div class="divider-content"><span></span></div>

                        <div class="side-nav">
                            <ul class="nav-side">
                                @foreach($transactions as $tr)
                                @foreach($tr as $transaction)
                                <li class="active">
                                    <a href="">
                                        <i class="icofont-random"></i>
                                        <span>{{$transaction->name}}: {{$transaction->totaltransaction}}</span>
                                    </a>
                                </li>
                                @endforeach
                                @endforeach
                            </ul>
                        </div>

                        <div class="divider-content"><span></span></div>

                        <div class="side-box">
                            <div class="bar-st bar-mini green">
                                <span style="width: 70%;"></span>
                                <p class="color-white">70%</p>
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar" style="width: 60%;"></span>
                                <!--                                                <p class="color-white">60%</p>-->
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar bar-success" style="width: 60%;"></span>
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar bar-danger" style="width: 60%;"></span>
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar bar-warning" style="width: 60%;"></span>
                            </div>
                        </div>

                        <div class="divider-content"><span></span></div>

                    </div><!--/alternative 1-->

                    <!--alternative 2-->
                    <div class="tab-pane fade" id="alt2">
                        <div class="divider-content"><span></span></div>

                        <div class="side-nav">
                            <ul class="nav-side">
                                @foreach($chbs as $chargebacks)
                                @foreach($chargebacks as $chb)
                                <li class="active">
                                    <a href="">
                                        <i class="icofont-random"></i>
                                        <span>{{$chb->name}}: {{$chb->totalchb}}</span>
                                    </a>
                                </li>
                                @endforeach
                                @endforeach
                            </ul>
                        </div>

                        <div class="divider-content"><span></span></div>

                        <div class="side-box">
                            <div class="bar-st bar-mini green">
                                <span style="width: 70%;"></span>
                                <p class="color-white">70%</p>
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar" style="width: 60%;"></span>
                                <!--                                                <p class="color-white">60%</p>-->
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar bar-success" style="width: 60%;"></span>
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar bar-danger" style="width: 60%;"></span>
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar bar-warning" style="width: 60%;"></span>
                            </div>
                        </div>

                        <div class="divider-content"><span></span></div>
                    </div><!--/alternative 2-->

                    <!--alternative 3-->
                    <div class="tab-pane fade" id="alt3">
                        <div class="divider-content"><span></span></div>

                        <div class="side-nav">
                            <ul class="nav-side">

                                @foreach($refunds as $refs)
                                @foreach($refs as $refund)
                                <li class="active">
                                    <a href="">
                                        <i class="icofont-random"></i>
                                        <span>{{$refund->name}}: {{$refund->totalrefunds}}</span>
                                    </a>
                                </li>
                                @endforeach
                                @endforeach

                            </ul>
                        </div>

                        <div class="divider-content"><span></span></div>

                        <div class="side-box">
                            <div class="bar-st bar-mini green">
                                <span style="width: 70%;"></span>
                                <p class="color-white">70%</p>
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar" style="width: 60%;"></span>
                                <!--                                                <p class="color-white">60%</p>-->
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar bar-success" style="width: 60%;"></span>
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar bar-danger" style="width: 60%;"></span>
                            </div>
                            <div rel="tooltip" title="60%" class="progress progress-mini">
                                <span class="bar bar-warning" style="width: 60%;"></span>
                            </div>
                        </div>

                        <div class="divider-content"><span></span></div>
                    </div><!--/alternative 2-->

                </div>
            </div><!-- /sidebar-right-content -->
        </div><!-- /sidebar-right -->
    </aside><!-- /side-right -->
</div><!-- /span side-right -->