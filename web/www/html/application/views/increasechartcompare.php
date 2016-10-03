<script src="http://code.jquery.com/jquery.js"></script>
<script src="/static/lib/bootstrap/js/bootstrap.min.js"></script>

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript">

    $(function () {
       $( '#container1').highcharts({
         title: {
            text:<?php
                require_once "/var/www/html/static/lib/function/funchighchartdata.php";
                $increasechartdata = $increasechartdataArray[0];
                $firstdata = $increasechartdata[0];
                echo "'".$firstdata['Bigtitle']."'";
                ?>,
          x: -20 //center
         },
             subtitle: {
                text: '',
                   x: -20
             },
         xAxis: {  //x축
              categories: <?php
                  require_once "/var/www/html/static/lib/function/funchighchartdata.php";
                  $increasechartdata = $increasechartdataArray[0];
                  $firstdata = $increasechartdata[0];
                  $chartmgr =  new ChartdataMgr();
                  $chartmgr->LinearChartsetXAlies($firstdata['timestamp']);
                ?>
        },
           yAxis: {  //y축
                title: {
                    text: <?php echo "'".$unit."'";?>
                },
                plotLines: [{   //선
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
              },
              tooltip: {
                  valueSuffix: <?php echo "'".$unit."'";?>
              },
              legend: {  //범례
                  layout: 'vertical',
                  align: 'right',
                  verticalAlign: 'middle',
                  borderWidth: 0
              },
              series: [<?php
                  require_once "/var/www/html/static/lib/function/funchighchartdata.php";
                  $chartmgr =  new ChartdataMgr();
                  $chartmgr->LinearChartsetYAlies($increasechartdata);
                ?>]
              });
              $( '#container2').highcharts({
                title: {
                   text:"Commodity",
                 x: -20 //center
                },
                    subtitle: {
                       text: '',
                          x: -20
                    },
                xAxis: {  //x축
                     categories: <?php
                         require_once "/var/www/html/static/lib/function/funchighchartdata.php";
                         $increasechartdata = $increasechartdataArray[1];
                         $firstdata = $increasechartdata[0];
                         $chartmgr =  new ChartdataMgr();
                         $chartmgr->LinearChartsetXAlies($firstdata['timestamp']);
                       ?>
               },
                  yAxis: {  //y축
                       title: {
                           text: <?php echo "'".$unit."'";?>
                       },
                       plotLines: [{   //선
                           value: 0,
                           width: 1,
                           color: '#808080'
                       }]
                     },
                     tooltip: {
                         valueSuffix: <?php echo "'".$unit."'";?>
                     },
                     legend: {  //범례
                         layout: 'vertical',
                         align: 'right',
                         verticalAlign: 'middle',
                         borderWidth: 0
                     },
                     series: [<?php
                         require_once "/var/www/html/static/lib/function/funchighchartdata.php";
                         $chartmgr =  new ChartdataMgr();
                         $chartmgr->LinearChartsetYAlies($increasechartdata);
                       ?>]
                     });
          });


</script>
<script src="/static/lib/js/highcharts.js"></script>
<script src="/static/lib/modules/exporting.js"></script>
<div id="container1"></div>
<div id="container2"></div>
?>
