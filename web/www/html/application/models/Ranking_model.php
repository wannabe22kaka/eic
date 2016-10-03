<?php
class Ranking_model extends CI_Model {

  private $DataArray = null;

  function __construct()
  {
      parent::__construct();
  }

  public function cleanData(){
    if($this->DataArray != null)
      unset($this->DataArray);

    $this->DataArray = array();
  }

  public function pushData($data){
    array_push($this->DataArray,$data);
  }

  public function getData($index){
    return $this->DataArray[$index];
  }

  public function getDataArray(){
    return $this->DataArray;
  }

  function rankingTable($array,$object){
    $rank = 1;
    $totalcount = intval($object->crawlingdatacount);
    $this->cleanData();
    $rankingTableData = array();
    foreach($array as $value){
      $object = json_decode($value->jsondata,TRUE);
      $rate = round($object['sum']/$totalcount,2) * 100;
      $index = $rank - 1;
      $tableData = array('rank'=>strval($rank),'keyword'=>$object['keyword'],'rate'=>strval($rate),'index'=>$index);
      array_push($rankingTableData,(object)$tableData);
      $rank = $rank + 1;
      /*
      echo "<tr>\n".
      "<td>".strval($rank)."</td>\n".
      "<td>".$object['keyword']."</td>\n".
      "<td>".strval($rate)."%</td>\n".
      '<td> <a href="/index.php/'.$topic['class']."/".$topic['func']."/".$topic['id']."/".$index.'" class="btn btn-link">Article</a></td>\n'.
      "</tr>\n";
      $rank = $rank + 1;
      */
      $this->pushData($object);
    }
    return $rankingTableData;
  }
}
?>
