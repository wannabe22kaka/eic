<script src="http://code.jquery.com/jquery.js"></script>
<script src="/static/lib/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">

$(function () {
    $('#container').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false,
            type: 'pie'
        },
        title: {
            text: <?php echo "'".$title."'";?>,
        },
        tooltip: {
            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                    style: {
                        color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                    }
                }
            }
        },
        series: [{
            name: 'Brands',
            colorByPoint: true,
            data: [<?php
                require_once "/var/www/html/static/lib/function/funchighchartdata.php";
                $chartmgr =  new ChartdataMgr();
                $chartmgr->circlechartdatarray($sharechartdata);
              ?>]
        }]
    });
});

</script>
<script src="/static/lib/js/highcharts.js"></script>
<script src="/static/lib/modules/exporting.js"></script>  <!--우측상단에 차트를 이미지로 출력할 수 있게하는 버튼을 넣는다.-->
<div id="container"></div>
