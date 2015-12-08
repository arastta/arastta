<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> <?php echo $heading_title; ?></h3>
        <div class="pull-right" id="chart-date-range">
            <div id="block-range" class="btn-group">
                <li class="btn btn-default active" id="day"><?php echo $text_day; ?></li>
                <li class="btn btn-default" id="month"><?php echo $text_month; ?></li>
                <li class="btn btn-default " id="year"><?php echo $text_year; ?></li>
            </div>
            <div id="reportrange" class="pull-right" style="background: #fff; cursor: pointer; padding: 0px 10px; border: 1px solid #ccc; font-weight: normal;">
                <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
                <span></span> <b class="caret"></b>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <div id="tab_toolbar" class="panel-body" style="width: 100%; display: table; color: #555555;">
            <dl onclick="getChart(this, 'sales');" class="col-xs-4 col-lg-2 active" style="background-color: #008db9">
                <dt><?php echo $text_sale; ?></dt>
                <dd class="data_value size_l"><span id="sales_score"></span></dd>
            </dl>
            <dl onclick="getChart(this, 'orders');" class="col-xs-4 col-lg-2 passive" style="background-color: #5cb85c">
                <dt><?php echo $text_order; ?></dt>
                <dd class="data_value size_l"><span id="orders_score"></span></dd>
            </dl>
            <dl onclick="getChart(this, 'customers');" class="col-xs-4 col-lg-2 passive" style="background-color: #d9534f">
                <dt><?php echo $text_customer; ?></dt>
                <dd class="data_value size_l"><span id="customers_score"></span></dd>
            </dl>
            <dl onclick="getChart(this, 'affiliates');" class="col-xs-4 col-lg-2 passive  fourth-tab" style="background-color: #6b399c">
                <dt><?php echo $text_affiliates; ?></dt>
                <dd class="data_value size_l"><span id="affiliates_score"></span></dd>
            </dl>
            <dl onclick="getChart(this, 'reviews');" class="col-xs-4 col-lg-2 passive" style="background-color: #f2994b">
                <dt><?php echo $text_reviews; ?></dt>
                <dd class="data_value size_l"><span id="reviews_score"></span></dd>
            </dl>
            <dl onclick="getChart(this, 'rewards');" class="col-xs-4 col-lg-2 passive" style="background-color: #b3591f">
                <dt><?php echo $text_rewards; ?></dt>
                <dd class="data_value size_l"><span id="rewards_score"></span></dd>
            </dl>
        </div>
        <div id="charts" class="panel-body">
            <div id="chart-sales" class="chart chart_active"></div>
            <div id="chart-orders" class="chart "></div>
            <div id="chart-customers" class="chart "></div>
            <div id="chart-affiliates" class="chart "></div>
            <div id="chart-reviews" class="chart "></div>
            <div id="chart-rewards" class="chart "></div>
        </div>
    </div>
</div>
<link type="text/css" href="view/javascript/jquery/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/jquery/daterangepicker/daterangepicker.js"></script>
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script>
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.tickrotor.js"></script>

<script type="text/javascript">
    var start_date = '';
    var end_date = '';
    var block_range = 'day';
    jQuery(document).ready(function() {
        var cb = function(start, end, label) { /* date range picker callback */
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            /* set global dates */
            start_date = start.format('YYYY-MM-DD');
            end_date = end.format('YYYY-MM-DD');
            /******************************************/

            getCharts();
        };

        var lang_code = moment.locale();
        var lang_data = moment.localeData();

        var option_daterangepicker = {
            startDate: moment().subtract('days', 14),
            endDate: moment(),
            minDate: '01/01/2012',
            maxDate: '12/31/2050',
            showDropdowns: true,
            showWeekNumbers: true,
            timePicker: false,
            timePickerIncrement: 1,
            timePicker12Hour: true,
            opens: 'left',
            buttonClasses: ['btn btn-default'],
            applyClass: 'btn-small btn-primary',
            cancelClass: 'btn-small',
            format: 'MM/DD/YYYY',
            separator: ' to ',
            ranges: {
                '<?php echo $text_today; ?>' : [moment(), moment()],
                '<?php echo $text_yesterday; ?>': [moment().subtract('days', 1), moment().subtract('days', 1)],
                '<?php echo $text_last_week; ?>': [moment().subtract('days', 6), moment()],
                '<?php echo $text_last_half_mount; ?>': [moment().subtract('days', 14), moment()],
                '<?php echo $text_mount; ?>': [moment().subtract('days', 29), moment()],
                '<?php echo $text_this_mount; ?>': [moment().startOf('month'), moment().endOf('month')],
                '<?php echo $text_last_mount; ?>': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            locale: {
                applyLabel: '<?php echo $text_submit; ?>',
                cancelLabel: '<?php echo $text_clear; ?>',
                fromLabel: '<?php echo $text_from; ?>',
                toLabel: '<?php echo $text_to; ?>',
                customRangeLabel: '<?php echo $text_custom; ?>',
                daysOfWeek: lang_data._weekdaysShort,
                monthNames: lang_data._months,
                firstDay: 1
            }
        };



        jQuery('#reportrange span').html(moment().subtract('days', 14).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

        jQuery('#reportrange').daterangepicker(option_daterangepicker, cb);

        start_date  = option_daterangepicker.startDate.format('YYYY-MM-DD');
        end_date    = option_daterangepicker.endDate.format('YYYY-MM-DD')
        block_range = $('#block-range li.active').attr('id');

        getCharts();
    });

    $('#block-range li').on('click', function(e) {
        e.preventDefault();

        $(this).parent().find('li').removeClass('active');
        $(this).addClass('active');

        block_range = $(this).attr('id');

        getCharts();
    });

    function getChart(tab, chart) {
        jQuery('#tab_toolbar dl').removeClass('active');
        jQuery('#tab_toolbar dl').addClass('passive');
        jQuery('#charts div').removeClass('chart_active');

        jQuery('#chart-'+chart).addClass('chart_active');
        jQuery(tab).removeClass('passive');
        jQuery(tab).addClass('active');

        switch (chart) {
            case 'sales' :
                sales();
                break;
            case 'orders' :
                orders();
                break;
            case 'customers' :
                customers();
                break;
            case 'affiliates' :
                affiliates();
                break;
            case 'reviews' :
                reviews();
                break;
            case 'rewards' :
                rewards();
                break;
            default :
                break;
        }
    }

    function getCharts(){
        sales();
        orders();
        customers();
        affiliates();
        reviews();
        rewards();
    }

    function sales() {
        $('#sales_score').html('<img src="view/image/loader.gif">');
        $('#chart-sales').html('<div class="loading"><img src="view/image/loader.gif"></div>');

        $.ajax({
            type: 'get',
            url: 'index.php?route=dashboard/charts/sales&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
            dataType: 'json',
            success: function(json) {
                var option = {
                    shadowSize: 0,
                    lines: {
                        show: true
                    },
                    grid: {
                        backgroundColor: '#FFFFFF',
                        hoverable: true
                    },
                    points: {
                        show: true,
                        fillColor: '#008db9'
                    },
                    xaxis: {
                        show: true,
                        ticks: json['xaxis'],
                        rotateTicks : 45
                    },
                    yaxis: {
                        mode: "money",
                        min: 0,
                        tickDecimals: 2,
                        tickFormatter: function (v, axis) { return "<?php echo $symbol_left; ?>" + v.toFixed(axis.tickDecimals) + "<?php echo $symbol_right; ?>" }
                    }
                };

                json['order']['color'] = "#008db9";
                $.plot('#chart-sales', [json['order']], option);

                $('#chart-sales').bind('plothover', function(event, pos, item) {
                    $('.tooltip').remove();

                    if (item) {
                        $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                        $('#tooltip').css({
                            position: 'absolute',
                            left: item.pageX - ($('#tooltip').outerWidth() / 2),
                            top: item.pageY - $('#tooltip').outerHeight(),
                            pointer: 'cusror'
                        }).fadeIn('slow');

                        $('#chart-sales').css('cursor', 'pointer');
                    } else {
                        $('#chart-sales').css('cursor', 'auto');
                    }
                });

                $('#sales_score').html(json['order']['total']);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

    }

    function orders() {
        $('#orders_score').html('<img src="view/image/loader.gif">');
        $('#chart-orders').html('<div class="loading"><img src="view/image/loader.gif"></div>');

        $.ajax({
            type: 'get',
            url: 'index.php?route=dashboard/charts/orders&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
            dataType: 'json',
            success: function(json) {
                var option = {
                    shadowSize: 0,
                    lines: {
                        show: true
                    },
                    grid: {
                        backgroundColor: '#FFFFFF',
                        hoverable: true
                    },
                    points: {
                        show: true,
                        fillColor: '#5cb85c'
                    },
                    xaxis: {
                        show: true,
                        ticks: json['xaxis'],
                        rotateTicks : 45
                    },
                    yaxis : {
                        min: 0,
                        tickDecimals: 0
                    }
                };

                json['order']['color'] = "#5cb85c";
                $.plot('#chart-orders', [json['order']], option);

                $('#chart-orders').bind('plothover', function(event, pos, item) {
                    $('.tooltip').remove();

                    if (item) {
                        $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                        $('#tooltip').css({
                            position: 'absolute',
                            left: item.pageX - ($('#tooltip').outerWidth() / 2),
                            top: item.pageY - $('#tooltip').outerHeight(),
                            pointer: 'cusror'
                        }).fadeIn('slow');

                        $('#chart-orders').css('cursor', 'pointer');
                    } else {
                        $('#chart-orders').css('cursor', 'auto');
                    }
                });

                $('#orders_score').html(json['order']['total']);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    function customers() {
        $('#customers_score').html('<img src="view/image/loader.gif">');
        $('#chart-customers').html('<div class="loading"><img src="view/image/loader.gif"></div>');

        $.ajax({
            type: 'get',
            url: 'index.php?route=dashboard/charts/customers&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
            dataType: 'json',
            success: function(json) {
                var option = {
                    shadowSize: 0,
                    lines: {
                        show: true
                    },
                    grid: {
                        backgroundColor: '#FFFFFF',
                        hoverable: true
                    },
                    points: {
                        show: true,
                        fillColor: '#d9534f'
                    },
                    xaxis: {
                        show: true,
                        ticks: json['xaxis'],
                        rotateTicks : 45
                    },
                    yaxis : {
                        min: 0,
                        tickDecimals: 0
                    }
                };

                json['order']['color'] = "#d9534f";
                $.plot('#chart-customers', [json['order']], option);

                $('#chart-customers').bind('plothover', function(event, pos, item) {
                    $('.tooltip').remove();

                    if (item) {
                        $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                        $('#tooltip').css({
                            position: 'absolute',
                            left: item.pageX - ($('#tooltip').outerWidth() / 2),
                            top: item.pageY - $('#tooltip').outerHeight(),
                            pointer: 'cusror'
                        }).fadeIn('slow');

                        $('#chart-customers').css('cursor', 'pointer');
                    } else {
                        $('#chart-customers').css('cursor', 'auto');
                    }
                });

                $('#customers_score').html(json['order']['total']);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    function affiliates() {
        $('#affiliates_score').html('<img src="view/image/loader.gif">');
        $('#chart-affiliates').html('<div class="loading"><img src="view/image/loader.gif"></div>');

        $.ajax({
            type: 'get',
            url: 'index.php?route=dashboard/charts/affiliates&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
            dataType: 'json',
            success: function(json) {
                var option = {
                    shadowSize: 0,
                    lines: {
                        show: true
                    },
                    grid: {
                        backgroundColor: '#FFFFFF',
                        hoverable: true
                    },
                    points: {
                        show: true,
                        fillColor: '#6b399c'
                    },
                    xaxis: {
                        show: true,
                        ticks: json['xaxis'],
                        rotateTicks : 45
                    },
                    yaxis : {
                        min: 0,
                        tickDecimals: 0
                    }
                };

                json['order']['color'] = "#6b399c";
                $.plot('#chart-affiliates', [json['order']], option);

                $('#chart-affiliates').bind('plothover', function(event, pos, item) {
                    $('.tooltip').remove();

                    if (item) {
                        $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                        $('#tooltip').css({
                            position: 'absolute',
                            left: item.pageX - ($('#tooltip').outerWidth() / 2),
                            top: item.pageY - $('#tooltip').outerHeight(),
                            pointer: 'cusror'
                        }).fadeIn('slow');

                        $('#chart-affiliates').css('cursor', 'pointer');
                    } else {
                        $('#chart-affiliates').css('cursor', 'auto');
                    }
                });

                $('#affiliates_score').html(json['order']['total']);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    function reviews() {
        $('#reviews_score').html('<img src="view/image/loader.gif">');
        $('#chart-reviews').html('<div class="loading"><img src="view/image/loader.gif"></div>');

        $.ajax({
            type: 'get',
            url: 'index.php?route=dashboard/charts/reviews&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
            dataType: 'json',
            success: function(json) {
                var option = {
                    shadowSize: 0,
                    lines: {
                        show: true
                    },
                    grid: {
                        backgroundColor: '#FFFFFF',
                        hoverable: true
                    },
                    points: {
                        show: true,
                        fillColor: '#f2994b'
                    },
                    xaxis: {
                        show: true,
                        ticks: json['xaxis'],
                        rotateTicks : 45
                    },
                    yaxis : {
                        min: 0,
                        tickDecimals: 0
                    }
                };

                json['order']['color'] = "#f2994b";
                $.plot('#chart-reviews', [json['order']], option);

                $('#chart-reviews').bind('plothover', function(event, pos, item) {
                    $('.tooltip').remove();

                    if (item) {
                        $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                        $('#tooltip').css({
                            position: 'absolute',
                            left: item.pageX - ($('#tooltip').outerWidth() / 2),
                            top: item.pageY - $('#tooltip').outerHeight(),
                            pointer: 'cusror'
                        }).fadeIn('slow');

                        $('#chart-reviews').css('cursor', 'pointer');
                    } else {
                        $('#chart-reviews').css('cursor', 'auto');
                    }
                });

                $('#reviews_score').html(json['order']['total']);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    function rewards() {
        $('#rewards_score').html('<img src="view/image/loader.gif">');
        $('#chart-rewards').html('<div class="loading"><img src="view/image/loader.gif"></div>');

        $.ajax({
            type: 'get',
            url: 'index.php?route=dashboard/charts/rewards&start='+ start_date +'&end='+ end_date +'&token=<?php echo $token; ?>&range=' + block_range,
            dataType: 'json',
            success: function(json) {
                var option = {
                    shadowSize: 0,
                    lines: {
                        show: true
                    },
                    grid: {
                        backgroundColor: '#FFFFFF',
                        hoverable: true
                    },
                    points: {
                        show: true,
                        fillColor: '#b3591f'
                    },
                    xaxis: {
                        show: true,
                        ticks: json['xaxis'],
                        rotateTicks : 45
                    },
                    yaxis : {
                        min: 0,
                        tickDecimals: 0
                    }
                };

                json['order']['color'] = "#b3591f";
                $.plot('#chart-rewards', [json['order']], option);

                $('#chart-rewards').bind('plothover', function(event, pos, item) {
                    $('.tooltip').remove();

                    if (item) {
                        $('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');

                        $('#tooltip').css({
                            position: 'absolute',
                            left: item.pageX - ($('#tooltip').outerWidth() / 2),
                            top: item.pageY - $('#tooltip').outerHeight(),
                            pointer: 'cusror'
                        }).fadeIn('slow');

                        $('#chart-rewards').css('cursor', 'pointer');
                    } else {
                        $('#chart-rewards').css('cursor', 'auto');
                    }
                });

                $('#rewards_score').html(json['order']['total']);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                //alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
</script>
