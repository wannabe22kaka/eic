<script src="http://code.jquery.com/jquery.js"></script>
<script src="/static/lib/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">


$(function () {
// Create the chart
$('#container').highcharts({
    chart: {
        type: 'column'
    },
    title: {
        text: <?php
                        require_once "/var/www/html/static/lib/function/funchighchartdata.php";
                        $mgr = new ChartdataMgr();
                        $mgr->getRippleffctTitleofDay();
                        ?>
    },
    subtitle: {
        text: <?php
                        require_once "/var/www/html/static/lib/function/funchighchartdata.php";
                        $mgr = new ChartdataMgr();
                        $mgr->getTitleofDay($day);
                        ?>
    },
    xAxis: {
        type: 'category'
    },
    yAxis: {
        title: {
            text: 'Keyword Ripple Effect'
        }

    },
    legend: {
        enabled: false
    },
    plotOptions: {
        series: {
            borderWidth: 0,
            dataLabels: {
                enabled: true,
                format: '{point.y:.1f}%'
            }
        }
    },

    tooltip: {
        headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
        pointFormat: '<span style="color:{point.color}">{point.name}</span>: <b>{point.y:.2f}%</b> of total<br/>'
    },

    series: [{
        name: 'Brands',
        colorByPoint: true,
        data: [<?php
                        require_once "/var/www/html/static/lib/function/funchighchartdata.php";
                        $mgr = new ChartdataMgr();
                        $mgr->ripplechartdatarray($data);
                        ?>]
    }]
});
});
</script>
<script src="/static/lib/js/highcharts.js"></script>
<script src="/static/lib/js/modules/exporting.js"></script>  <!--우측상단에 차트를 이미지로 출력할 수 있게하는 버튼을 넣는다.-->
<div id="container"></div>
