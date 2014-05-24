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
                    <li class="active"><a href="#transactions" data-toggle="tab" title="alternative 1"><i class="icofont-tasks"></i> Transactions</a></li>
                    <li><a href="#chb" data-toggle="tab" title="alternative 2"><i class="icofont-fullscreen"></i> CHB</a></li>
                    <li><a href="#refunds" data-toggle="tab" title="alternative 3"><i class="icofont-fullscreen"></i> Refunds</a></li>

                </ul>
            </div><!-- /sidebar-right-control-->
            <!-- sidebar-right-content -->
            <div class="sidebar-right-content">
                <div class="tab-content">
                    <!--alternate 1-->
                    <div class="tab-pane fade active in" id="transactions">
                        <div class="divider-content"><span></span></div>

                        <div class="side-nav">
                            <ul class="nav-side">

                                <?php
                                $totaltransactions=0; $totalchb=0; $totalrefunds=0;
                                ?>
                                @foreach($transactions as $tr)
                                @foreach($tr as $transaction)
                                <li class="active">
                                    <a href="">
                                        <i class="icofont-random"></i>
                                        <span>{{$transaction->name}}: {{$transaction->totaltransaction}}</span>
                                    </a>
                                </li>
                                <?php $totaltransactions += $transaction->totaltransaction; ?>
                                @endforeach
                                @endforeach
                            </ul>
                        </div>

                        <div class="divider-content"><span></span></div>

                    </div><!--/alternative 1-->

                    <!--alternative 2-->
                    <div class="tab-pane fade" id="chb">
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
                            <?php $totalchb += $chb->totalchb; ?>
                            @endforeach
                            @endforeach
                        </ul>
                    </div>



                    <div class="divider-content"><span></span></div>
                    </div><!--/alternative 2-->

                <!--alternative 3-->
                <div class="tab-pane fade" id="refunds">
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
                        <?php $totalrefunds += $refund->totalrefunds; ?>
                        @endforeach
                        @endforeach
                    </ul>
                </div>



                <div class="divider-content"><span></span></div>
            </div><!--/alternative 2-->

                </div>
                <div class="side-box">
                    <div rel="tooltip" title="Transactions" class="bar-st bar-mini green">
                        <span style="width: 100%;"></span>
                        <p class="color-white">Transactions</p>
                    </div>
<<<<<<< HEAD
                    <div rel="tooltip" title="Chargebacks {{round(($totalchb/$totaltransactions)*100, 2)}}%" class="progress progress-mini">
                        <span class="bar bar-warning" style="width: {{round(($totalchb/$totaltransactions)*100, 2)}}%;"></span>

                        <!--                                                <p class="color-white">60%</p>-->
                    </div>
                    <div rel="tooltip" title="Refunds {{round(($totalrefunds/$totaltransactions)*100, 2)}}%" class="progress progress-mini">
                        <span class="bar bar-danger" style="width: {{round(($totalchb/$totaltransactions)*100, 2)}}%;"></span>
=======
                    <div rel="tooltip" title="Chargebacks {{(@$totaltransactions>0?round(($totalchb/$totaltransactions)*100, 2):0)}}%" class="progress progress-mini">
                        <span class="bar bar-warning" style="width: {{(@$totaltransactions>0?round(($totalchb/$totaltransactions)*100, 2):0)}}%;"></span>

                        <!--                                                <p class="color-white">60%</p>-->
                    </div>
                    <div rel="tooltip" title="Refunds {{(@$totaltransactions>0?round(($totalrefunds/$totaltransactions)*100, 2):0)}}%" class="progress progress-mini">
                        <span class="bar bar-danger" style="width: {{(@$totaltransactions>0?round(($totalrefunds/$totaltransactions)*100, 2):0)}}%;"></span>
>>>>>>> origin/develop

                    </div>

                </div>
            </div><!-- /sidebar-right-content -->
        </div><!-- /sidebar-right -->
    </aside><!-- /side-right -->
</div><!-- /span side-right -->