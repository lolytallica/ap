<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Dashboard - A.R.P</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- google font -->
    <link href="http://fonts.googleapis.com/css?family=Aclonica:regular" rel="stylesheet" type="text/css" />

    <!-- styles -->
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-responsive.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/stilearn.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/stilearn-responsive.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/css/select2.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/fullcalendar.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/bootstrap-wysihtml5.css') }}" rel="stylesheet">

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <style>
        body {
            font-size:10px;
        }
    </style>
</head>

<body>
<!-- section header -->
@if(Sentry::check())


<!-- section content -->
<section class="section">
<div class="row-fluid">


    <!-- span content -->
    <div class="span1">
        <!-- content -->


            <!-- Notifications -->
            @include('frontend/notifications')

            <!-- Content -->
            @yield('content')

    </div><!-- /content -->
</div><!-- /span content -->




</div>


</section>


<!-- javascript
================================================== -->
<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
<script src="{{ asset('assets/js/jquery.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/uniform/jquery.uniform.js') }}"></script>
<script src="{{ asset('assets/js/peity/jquery.peity.js') }}"></script>

<script src="{{ asset('assets/js/select2/select2.js') }}"></script>
<script src="{{ asset('assets/js/knob/jquery.knob.js') }}"></script>
<script src="{{ asset('assets/js/flot/jquery.flot.js') }}"></script>
<script src="{{ asset('assets/js/flot/jquery.flot.resize.js') }}"></script>
<script src="{{ asset('assets/js/flot/jquery.flot.categories.js') }}"></script>
<script src="{{ asset('assets/js/wysihtml5/wysihtml5-0.3.0.js') }}"></script>
<script src="{{ asset('assets/js/wysihtml5/bootstrap-wysihtml5.js') }}"></script>
<script src="{{ asset('assets/js/calendar/fullcalendar.js') }}"></script> <!-- this plugin required jquery ui-->

<!-- required stilearn template js, for full feature-->
<script src="{{ asset('assets/js/holder.js') }}"></script>
<script src="{{ asset('assets/js/stilearn-base.js') }}"></script>
<script src="{{ asset('assets/js/datepicker/bootstrap-datepicker.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
    // try your js

    ////INVOICE ADDITIONAL COSTS
    $(".addCost").click(function(){
        $("#customFields").append('<tr valign="top"><th scope="row"><label for="description">Description</label></th><td><input type="text" class="code" id="description" name="description[]" value="" placeholder="Cost Description" /> &nbsp; <input type="text" class="code" id="amount" name="amount[]" value="" placeholder="Cost Amount" /> &nbsp; <input type="text" class="code" id="comments" name="comments[]" value="" placeholder="Comments" /> &nbsp; <a href="javascript:void(0);" class="remCF"><i class="icon-remove-sign"></i> Remove</a>  </td></tr>');
    });
    $("#customFields").on('click','.remCF',function(){
        $(this).parent().parent().remove();
    });

    ////INVOICE ADDITIONAL HELDS
    $(".addHeld").click(function(){
        $("#customHeld").append('<tr valign="top"><th scope="row"><label for="held_reason">Description</label></th><td colspan="3"><input type="text" class="code" id="held_reason" name="held_reason[]" value="" placeholder="Held reason" /> &nbsp; <input type="text" class="code" id="held_amount" name="held_amount[]" value="" placeholder="Held Amount" /> &nbsp; <input type="text" class="code" id="held_comments" name="held_comments[]" value="" placeholder="Comments" /> &nbsp; <a href="javascript:void(0);" class="remCH"><i class="icon-remove-sign"></i> Remove</a>  </td></tr>');
    });
    $("#customHeld").on('click','.remCH',function(){
        $(this).parent().parent().remove();
    });

    ////INVOICE ADDITIONAL INCOME
    $(".addIncome").click(function(){
        $("#incomeFields").append('<tr valign="top"><th scope="row"><label for="incomeDescription">Description</label></th><td><input type="text" class="code" id="incomeDescription" name="incomeDescription[]" value="" placeholder="Income Description" /> &nbsp; <input type="text" class="code" id="incomeAmount" name="incomeAmount[]" value="" placeholder="Income Amount" /> &nbsp; <input type="text" class="code" id="incomeComments" name="incomeComments[]" value="" placeholder="Comments" /> &nbsp; <a href="javascript:void(0);" class="remIF"><i class="icon-remove-sign"></i> Remove</a>  </td></tr>');
    });
    $("#incomeFields").on('click','.remIF',function(){
        $(this).parent().parent().remove();
    });
//////////////////////////////////////////////////////////////

    // normalize event tab-stat, we hack something here couse the flot re-draw event is any some bugs for this case
    $('#tab-stat > a[data-toggle="tab"]').on('shown', function(){
        if(sessionStorage.mode == 4){ // this hack only for mode side-only
            $('body,html').animate({
                scrollTop: 0
            }, 'slow');
        }
    });

    // datepicker
    $('[data-form=datepicker]').datepicker();

    // peity chart
    $("span[data-chart=peity-bar]").peity("bar");

    // Input tags with select2
    $('input[name=reseiver]').select2({
        tags:[]
    });

    // uniform
    $('[data-form=uniform]').uniform();

    // wysihtml5
    $('[data-form=wysihtml5]').wysihtml5()
    toolbar = $('[data-form=wysihtml5]').prev();
    btn = toolbar.find('.btn');

    $.each(btn, function(k, v){
        $(v).addClass('btn-mini')
    });

    // Server stat circular by knob
    $("input[data-chart=knob]").knob();

    // system stat flot
    d1 = [ ['jan', 231], ['feb', 243], ['mar', 323], ['apr', 352], ['maj', 354], ['jun', 467], ['jul', 429] ];
    d2 = [ ['jan', 87], ['feb', 67], ['mar', 96], ['apr', 105], ['maj', 98], ['jun', 53], ['jul', 87] ];
    d3 = [ ['jan', 34], ['feb', 27], ['mar', 46], ['apr', 65], ['maj', 47], ['jun', 79], ['jul', 95] ];

    var invoice = $("#invoice-stat"),
        order = $("#order-stat"),
        user = $("#user-stat"),

        data_invoice = [{
            data: d1,
            color: '#00A600'
        }],
        data_order = [{
            data: d2,
            color: '#2E8DEF'
        }],
        data_user = [{
            data: d3,
            color: '#DC572E'
        }],


        options_lines = {
            series: {
                lines: {
                    show: true,
                    fill: true
                },
                points: {
                    show: true
                },
                hoverable: true
            },
            grid: {
                backgroundColor: '#FFFFFF',
                borderWidth: 1,
                borderColor: '#CDCDCD',
                hoverable: true
            },
            legend: {
                show: false
            },
            xaxis: {
                mode: "categories",
                tickLength: 0
            },
            yaxis: {
                autoscaleMargin: 2
            }

        };

    // render stat flot
    $.plot(invoice, data_invoice, options_lines);
    $.plot(order, data_order, options_lines);
    $.plot(user, data_user, options_lines);

    // tootips chart
    function showTooltip(x, y, contents) {
        $('<div id="tooltip" class="bg-black corner-all color-white">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '0px',
            padding: '2px 10px 2px 10px',
            opacity: 0.9,
            'font-size' : '11px'
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $('#invoice-stat, #order-stat, #user-stat').bind("plothover", function (event, pos, item) {

        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;

                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);
                label = item.series.xaxis.ticks[item.datapoint[0]].label;

                showTooltip(item.pageX, item.pageY,
                    label + " = " + y);
            }
        }
        else {
            $("#tooltip").remove();
            previousPoint = null;
        }

    });
    // end tootips chart


    // Schedule Calendar
    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    var calendar = $('#schedule').fullCalendar({
        header: {
            left: 'title',
            center: '',
            right: 'prev,next today,month,basicWeek,basicDay'
        },
        events: [
            {
                title: 'Merchant 1 Payment',
                start: new Date(y, m, 2)
            },
            {
                title: 'Merchant 2 Invoice',
                start: new Date(y, m, 3),
                end: new Date(y, m, 7)
            },
            {
                title: 'Merchant 1 Invoice',
                start: new Date(y, m, 9),
                end: new Date(y, m, 12)
            },
            {
                title: 'Merchnat 3 Payment',
                start: new Date(y, m, 19, 10, 30),
                allDay: false
            },
            {
                title: 'Partner 1 Payment',
                start: new Date(y, m, 28, 10, 30),
                allDay: false
            },
            {
                title: 'Partner 1 Invoice',
                start: new Date(y, m, d, 12, 0),
                end: new Date(y, m, d, 14, 0),
                allDay: false
            },
            {
                title: 'Merchant 2 Payment',
                start: new Date(y, m, d+1, 19, 0),
                end: new Date(y, m, d+1, 22, 30),
                allDay: false
            }
        ]
    });
    // end Schedule Calendar
});

</script>
@endif
</body>
</html>
