<?php
class Commoditydata_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('Commoditydata_model',TRUE);
    }

    function getCommoditylist(){
        return $this->db->query("select * from commoditycategory")->result();
    }

    function getCommodityData($commodity){
        return $this->db->query("select commodity,price,ratechange,time  from commoditydata where commodity='".$commodity."'"."ORDER BY UNIX_TIMESTAMP(time) ASC" )->result();
    }

    function getCommodityDataRateChange($commodity){
        return $this->db->query("select ratechange from commoditydata where commodity='".$commodity."'"."ORDER BY UNIX_TIMESTAMP(time) ASC" )->result();
    }

    function getCommodityDataPrice($commodity){
        return $this->db->query("select price from commoditydata where commodity='".$commodity."'"."ORDER BY UNIX_TIMESTAMP(time) ASC" )->result();
    }

    function getCommodityDataofYearlist(){
        return $this->db->query("select time from commoditydata where commodity='tea' ORDER BY UNIX_TIMESTAMP(time) ASC" )->result();
    }

    function getCommoditydatachangerate($date,$ordering){
        return $this->db->query("select * from ".$date."commoditydatachangerate order by changerate ".$ordering )->result();
    }

    function getCommoditydatachangerateandcategory($date,$ordering){
      return $this->db->query("select d.commodity as commodity, d.changerate as changerate, c.category as category from ".$date."commoditydatachangerate as d left join commoditycategory as c on d.commodity = c.commodity order by d.changerate ".$ordering)->result();
    }

    function gettableclassname($hashmap,$category){
      $count = 0;
      foreach ($hashmap as $object ) {
        if($object->category == $category)
            break;
        $count++;
      }

      switch($count){
        case 0:
          return "info";
          break;
        default:
          return "danger";
          break;
      }

    }

    function rankingTable($array,$hashmap){
      $rank = 1;
      $rankingTableData = array();
      foreach($array as $value){
        //var_dump($value);
        $index = $rank - 1;
        $tableData = array('class'=>$this->gettableclassname($hashmap,$value->category),'rank'=>strval($rank),'keyword'=>$value->commodity,'rate'=>$value->changerate,'category'=>$value->category,'index'=>$index);
        array_push($rankingTableData,(object)$tableData);
        $rank = $rank + 1;
      }
      return $rankingTableData;
    }
}
?>
