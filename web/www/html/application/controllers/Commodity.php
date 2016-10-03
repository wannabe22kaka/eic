<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "/var/www/html/application/controllers/Base.php";

class Commodity extends Base {
  function __construct(){
    parent::__construct();

  }

  function categorysearch($id){
    $this->_common($id);
    /*
    $category = $this->input->post("category");
    $CommodityDatalist = $this->Commoditydata_model->getCommodityData($category);
*/
    $yearlist = $this->Commoditydata_model->getCommodityDataofYearlist();
    $startyear = $this->input->post("startyear");
    $endyear = $this->input->post("endyear");
    $unit = $this->input->post("unit");
    //$commodity = $this->input->post("category");
    $commoidtyArray = $_POST['commoidty'];

    $startindex = 0;
    $endindex = 0;

    $timestamp = array();
    for($i = 0; $i < count($yearlist);$i++){
      if(strcmp($yearlist[$i]->time,$startyear) == 0)
        $startindex = $i;
      if(strcmp($yearlist[$i]->time,$endyear) == 0)
        $endindex = $i;

    }

    for($i = $startindex; $i < $endindex;$i++){
      array_push($timestamp,$yearlist[$i]->time);
    }
    $Returndata = array();
    if($unit == "price"){
      foreach ($commoidtyArray as $commodity) {
        $ReturndataCommodity = $this->IncreaseCommodityPricedata($startindex,$endindex,$commodity,$timestamp,$Returndata);
        array_push($Returndata,$ReturndataCommodity);
      }
    }
    else{
      foreach ($commoidtyArray as $commodity) {
        $ReturndataCommodity = $this->IncreaseCommodityRatedata($startindex,$endindex,$commodity,$timestamp,$Returndata);
        array_push($Returndata,$ReturndataCommodity);
      }
    }
    $this->load->view('increasechart',array('title'=>"commodity",'increasechartdata'=>$Returndata,'unit'=>$unit));

  }


  function _createCommodityIncreasedata($CommodityDatalist){
    $Returndata = array();
    $valuearray = array();
    $timestamp = array();
    $commodity = $CommodityDatalist[0]->commodity;
    foreach ($CommodityDatalist as $value) {
      $jsonarray = array();
      array_push($timestamp,$value->time);
      array_push($valuearray,floatval($value->price));
    }
    $ReturndataObject = array('keyword'=>$commodity,'sum' => $valuearray,'timestamp'=>$timestamp);
    array_push($Returndata,$ReturndataObject);
    return $Returndata;
  }

}
