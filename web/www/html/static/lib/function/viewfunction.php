<?php
function searchString($id) {

  switch ($id){
    case 1:
        break;
    case 2:
        echo "ex)20161224";
        break;
    case 3:
        echo "ex)20160808,금리,중국";
        break;
    case 4:
        break;
    }

}

function rankingTable($array,$object,$topic){
  $rank = 1;
  var_dump($topic);
  $totalcount = intval($object->crawlingdatacount);
  DataSingleton::getInstance()->cleanData();
  foreach($array as $value){
    $object = json_decode($value->jsondata,TRUE);
    $rate = round($object['sum']/$totalcount,2) * 100;
    $index = $rank - 1;
    echo "<tr>\n".
    "<td>".strval($rank)."</td>\n".
    "<td>".$object['keyword']."</td>\n".
    "<td>".strval($rate)."%</td>\n".
    '<td> <a href="/index.php/'.$topic['class']."/".$topic['func']."/".$topic['id']."/".$index.'" class="btn btn-link">Article</a></td>\n'.
    "</tr>\n";
    $rank = $rank + 1;
  }
  var_dump(DataSingleton::getInstance()->getData(1));
}

  function articleTable($JsonObject){
    $array = $object['keywordindex'];
    foreach($array as $value){
      var_dump($value);
      /*
      $object = json_decode($value->jsondata,TRUE);
      var_dump($object);
      $rate = round($object['sum']/$totalcount,2) * 100;
      echo "<tr>\n".
      "<td>".strval($rank)."</td>\n".
      "<td>".$object['keyword']."</td>\n".
      "<td>".strval($rate)."%</td>\n".
      '<td> <a href="#" class="btn btn-link">Article</a></td>'."\n".
      "</tr>\n";
      $rank = $rank + 1;
      */
    }
  }



?>
