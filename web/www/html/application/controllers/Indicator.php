<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "/var/www/html/application/controllers/Base.php";

class Indicator extends Base {
  public $odering;
  public $id;
  function __construct(){
    parent::__construct();
    $this->odering ="";
    $this->id = "";
  }

   function _indicatorcommon($id){
    $this->_setcategory($id);
    $category = $this->Ui_model->getIDfromCid($id);
    $topic    = $this->Ui_model->getTopicInfo($id);
    $this->_common($category->cid);
    $this->load->view('footer');
    }

    function _createShareData($data){
      $total = $data['amount'];
      $countryarray = $data['result'];
      $totalamount = intval($total->currencyamount);
      $sharearray = array();

      foreach ($countryarray as $object) {
        if($object->country == "")
          continue;
        $value = intval($object->currencyamount);
        if($value == 0)
          continue;
        $rate = $value / $totalamount;
        $share = round($rate,2) * 100;
        if( $share == 0)
          continue;

        $shareobject = array('name'=>$object->country,'y'=>$share);
        array_push($sharearray,(object)$shareobject);
      }
      return $sharearray;
    }


    function _getGraphBigtitle($id){
      $title = "";
      switch($id){
        case 19:
          $title = "GDP growth rate";
          break;
        case 20:
          $title = "Import rate";
          break;
        case 21:
          $title = "Export rate";
          break;
      }
      return $title;
    }

    function _getratedataCategory($countryname,$timestamp,$id){
      $rate = 0;
      switch($id){
        case 19:{
          $data = $this->Imfdata_model->getGDPgrowratebyContryname($countryname,$timestamp);
          if($data != null)
            $rate = floatval($data->growrate);
          else
            $rate = 0;
          }
          break;
        case 20:
         {
          $info = $this->Imfdata_model->getTradebyYearAndCountry($countryname,$timestamp,"import");
          if($info != null)
            $rate = floatval($info[0]->percent);
          else
            $rate = 0;
          }
          break;
        case 21:
          {
           $info = $this->Imfdata_model->getTradebyYearAndCountry($countryname,$timestamp,"export");
           if($info != null)
             $rate = floatval($info[0]->percent);
           else
             $rate = 0;
           }
          break;

      }
      return $rate;
    }

    function _createIncreaseGrowthDataForComparing($startindex,$endindex,$yearlist,$countryarray,$id){

        $Returndata = array();
        $timestamp = array();
        $yearcmp = "";
        for($i = 0; $i < count($yearlist); $i++){

          $year = date('Y', strtotime($yearlist[$i]));
          if(strcmp($year,$yearcmp))
          {
            array_push($timestamp,$year);
            $yearcmp = $year;
          }
        }

        $Bigtitle = $this->_getGraphBigtitle($id);

        foreach ($countryarray as $countryname) {
            $gdparray = array();
            $yearcmp = "";
            for($i = 0; $i < count($timestamp); $i++){
                $rate = $this->_getratedataCategory($countryname,$timestamp[$i],$id);
                array_push($gdparray,$rate);

            }
            $ReturndataObject = array('Bigtitle'=>$Bigtitle,'keyword'=>$countryname,'sum' => $gdparray,'timestamp'=>$timestamp);
            array_push($Returndata,$ReturndataObject);
          }

          return $Returndata;
      }

    function _IncreaseGrowthDataCombineCommoditydata($startindex,$endindex,$yearlist,$countryarray,$commodity,$id){
       $ReturnArray = array();
       $Returndata = array();
       $ReturndataIncreaseGrowthData = $this->_createIncreaseGrowthDataForComparing($startindex,$endindex,$yearlist,$countryarray,$id);
       $ReturndataCommodity = $this->IncreaseCommodityArraydata($startindex,$endindex,$commodity,$yearlist,$Returndata);
       //$ReturndataObject = array('keyword'=>$commodity,'sum' => $commodityarray,'timestamp'=>$yearlist);
       array_push($ReturnArray,$ReturndataIncreaseGrowthData);
       array_push($ReturnArray,$ReturndataCommodity);
       return $ReturnArray;

    }

    function _createIncreaseGrowthData($startyear,$endyear,$countryarray){
      $intstartyear = intval($startyear);
      $intendyear = intval($endyear) + 1;
      $timestamp = array();
      $Returndata = array();

      for($i = $intstartyear; $i < $intendyear; $i++){
        array_push($timestamp,$i);
      }

      $lastyeargdp = 0;

      foreach ($countryarray as $value) {
        $gdparray = array();

        for($i = $intstartyear; $i < $intendyear; $i++){
          $data = $this->Imfdata_model->getGDPgrowratebyContryname($value,$i);

          if($data != null)
            $rategrowth = floatval($data->growrate);
          else
            $rategrowth = 0;


          array_push($gdparray,$rategrowth);
        }
        $ReturndataObject = array('keyword'=>$value,'sum' => $gdparray,'timestamp'=>$timestamp);
        array_push($Returndata,$ReturndataObject);
      }

      return $Returndata;
  }

    function _createIncreaseTradeData($startyear,$endyear,$countryarray,$tradename){
      $intstartyear = intval($startyear);
      $intendyear = intval($endyear) + 1;
      $timestamp = array();
      $Returndata = array();

      for($i = $intstartyear; $i < $intendyear; $i++){
        array_push($timestamp,$i);
      }

      $lastyeargdp = 0;

      foreach ($countryarray as $value) {
        $tradearray = array();

        for($i = $intstartyear; $i < $intendyear; $i++){
          $info = $this->Imfdata_model->getTradebyYearAndCountry($value,$i,$tradename);
          $realgrowth = floatval($info[0]->percent);
          array_push($tradearray,$realgrowth);
        }

          $ReturndataObject = array('keyword'=>$value,'sum' => $tradearray,'timestamp'=>$timestamp);
          array_push($Returndata,$ReturndataObject);
      }

      return $Returndata;
  }

  function comparecommodity($id ,$countrystring){
    $this->_indicatorcommon($id);
    $ListArray = $this->getOderingtitle($id);
    $this->load->view('orderingbutton',array('titleList'=>$ListArray));

    $decodecountrystring =urldecode($countrystring);
    $countryArray = explode("|",$decodecountrystring);

    $Yearlist = $this->Commoditydata_model->getCommodityDataofYearlist();
    $startyear = $this->input->post("startyear");
    $endyear = $this->input->post("endyear");
    $commodity = $this->input->post("category");

    $startindex = 0;
    $endindex = 0;

    $timestamp = array();
    for($i = 0; $i < count($Yearlist);$i++){
      if(strcmp($Yearlist[$i]->time,$startyear) == 0)
        $startindex = $i;
      if(strcmp($Yearlist[$i]->time,$endyear) == 0)
        $endindex = $i;

    }

    for($i = $startindex; $i < $endindex;$i++){
      array_push($timestamp,$Yearlist[$i]->time);
    }



    $increasechartdataArray = $this->_IncreaseGrowthDataCombineCommoditydata($startindex,$endindex,$timestamp,$countryArray,$commodity,$id);
    $this->load->view('increasechartcompare',array('increasechartdataArray'=>$increasechartdataArray,'unit'=>"%"));


    //$this->load->view('increasechart',array('title'=>"GDP Growth Rate",'increasechartdata'=>$increasechartdata,'unit'=>"%"));

  }


  function G20Search($id){
    $year = $this->input->post("selectyear");
    $this->_indicatorcommon($id);
    $yearData = $this->Imfdata_model->getYearList("GDP");
    $this->load->view("g20search",array('id'=>$id,'yearData'=>$yearData));
    $result = $this->Imfdata_model->getG20dataExportAndImport($year);
    $amount = $this->Imfdata_model->getSUMGDPbyYear($year);
    $this->load->view('g20table',array('result'=>$result,'amount'=>$amount));
  }

  function searchyear($id,$ordering){
    $this->_indicatorcommon($id);
    $ListArray = $this->getOderingtitle($id);
    $this->load->view('orderingbutton',array('titleList'=>$ListArray));


      switch($ordering){
        case 1:{
            $data = $this->getIndicatorData($id,$this->input->post("selectyear"),$ordering);
              if($id == 19)
                $this->load->view('gdptable',array('result'=>$data['result'],'amount'=>$data['amount']));
              else if($id == 20)
                $this->load->view('tradetable',array('result'=>$data['result'],'year'=>$this->input->post("selectyear"),'country'=>"country",'unit'=>"unit",'value'=>"import"));
              else if($id == 21)
                $this->load->view('tradetable',array('result'=>$data['result'],'year'=>$this->input->post("selectyear"),'country'=>"country",'unit'=>"unit",'value'=>"export"));
            }
            break;
        case 2:{
                $countryArray = $_POST['country'];
                //var_dump($countryArray);
                $countrystring = implode("|", $countryArray);

                if($id == 19){
                  $increasechartdata = $this->_createIncreaseGrowthData($this->input->post("startyear"),$this->input->post("endyear"),$countryArray);
                  //var_dump($increasechartdata);
                  $this->load->view('increasechart',array('title'=>"GDP Growth Rate",'increasechartdata'=>$increasechartdata,'unit'=>"%"));
                }
                else if($id == 20 || $id == 21){
                  if($id == 20)
                    $trade = "import";
                  else if($id == 21)
                    $trade = "export";

                  $increasechartdata = $this->_createIncreaseTradeData($this->input->post("startyear"),$this->input->post("endyear"),$countryArray,$trade);
                  $this->load->view('increasechart',array('title'=> $trade." rate",'increasechartdata'=>$increasechartdata,'unit'=>"%"));

                }
                $Commoditylist = $this->Commoditydata_model->getCommoditylist();
                $yearData = $this->Commoditydata_model->getCommodityDataofYearlist();
                $this->load->view('searchcomparecommodity',array('countrystring'=>$countrystring,'yearData'=>$yearData,'commoditydata'=>$Commoditylist));
              }
            break;
        case 3:{
              $data = $this->getIndicatorData($id,$this->input->post("selectyear"),$ordering);
              $sharechartdata = $this->_createShareData($data);
              $this->load->view('sharechart',array('title'=>$this->input->post("selectyear")." Country GDP Share",'sharechartdata'=>$sharechartdata));
              }
            break;
      }

    }



  function setViewofTradeSearch($id,$ordering){

    $yearData = $this->Imfdata_model->getYearList("GDP");

    switch($ordering){
      case 1:
        $this->load->view("allcountrygdpsearch",array('id'=>$id,'ordering'=>$ordering,'yearData'=>$yearData));
        break;
      case 2:
        $today = date("Ymd");
        $year = substr($today,0,4);
        $countryList = $this->Imfdata_model->getCountryList($year);
        $this->load->view("growthgdpsearch",array('id'=>$id,'ordering'=>$ordering,'yearData'=>$yearData,'countryList'=>$countryList));
        break;
    }
  }
  function setViewofGDPSearch($id, $ordering){
    $yearData = $this->Imfdata_model->getYearList("GDP");
    switch($ordering){
      case 1:
        $this->load->view("allcountrygdpsearch",array('id'=>$id,'ordering'=>$ordering,'yearData'=>$yearData));
        break;
      case 2:
        $today = date("Ymd");
        $year = substr($today,0,4);
        $countryList = $this->Imfdata_model->getCountryList($year);
        $this->load->view("growthgdpsearch",array('id'=>$id,'ordering'=>$ordering,'yearData'=>$yearData,'countryList'=>$countryList));
        break;
      case 3:
        $this->load->view("allcountrygdpsearch",array('id'=>$id,'ordering'=>$ordering,'yearData'=>$yearData));
        break;
    }
  }



  function getodering($id,$ordering){
    $this->_indicatorcommon($id);

    switch($id){
      case 19:
        $this->setViewofGDPSearch($id,$ordering);
        break;
      case 20:
      case 21:
        $this->setViewofTradeSearch($id,$ordering);
        break;
    }
    //switch()
    //$this->load->view('indicatorsearch',array('id'=>$id,'ordering'=>$ordering));
    //$this->load->view('orderingbutton');

  }
  function getOderingtitle($id){
    $ListArray = array();
    switch($id){
      case 19:{
            array_push($ListArray,"Total GDP");
            array_push($ListArray,"Growth GDP");
            array_push($ListArray,"Share GDP");
          }
          break;
      case 20:{
            array_push($ListArray,"ABC..");
            array_push($ListArray,"Increase rate");
          }
          break;
      case 21:{
            array_push($ListArray,"ABC..");
            array_push($ListArray,"Increase rate");
          }
          break;
    }

    return $ListArray;
  }

  function getCategory($id){
    $this->_indicatorcommon($id);
    if($id == 22){
      $yearData = $this->Imfdata_model->getYearList("GDP");
      $this->load->view("g20search",array('id'=>$id,'yearData'=>$yearData));
    }
    else{
      $ListArray = $this->getOderingtitle($id);

      $this->load->view('orderingbutton',array('titleList'=>$ListArray));
    }
  }

  function getGDPDataOrdering($search,$id){

      switch($id){
        case 1:
          $amount = $this->Imfdata_model->getSUMGDPbyYear($search);
          $result = $this->Imfdata_model->getGDPbyodering($search,False);
          break;
        case 2:
          $array = $this->refinekeyword($search);
          //var_dump($array);
          //$result = $this->Imfdata_model->getGDPbyodering($search,False);
          break;
        case 3:
          $amount = $this->Imfdata_model->getSUMGDPbyYear($search);
          $result = $this->Imfdata_model->getGDPbyodering($search,True);
          break;
      }

      $data = array('result'=>$result,'amount'=>$amount);
      return $data;
  }

#수출 관련 데이터
  function getExportDataOrdering($search,$id){

      switch($id){
        case 1:
          $result = $this->Imfdata_model->getExportbyYear($search);
          break;
        case 2:
          $result = $this->Imfdata_model->getExportbyodering($search,False);
          break;
        case 3:
          $result = $this->Imfdata_model->getExportbyodering($search,True);
          break;
      }

      $data = array('result'=>$result);
      return $data;
  }
#수입 관련 데이터
  function getImportDataOrdering($search,$id){
      switch($id){
        case 1:
          $result = $this->Imfdata_model->getImportbyYear($search);
          break;
        case 2:
          $result = $this->Imfdata_model->getImportbyodering($search,False);
          break;
        case 3:
          $result = $this->Imfdata_model->getImportbyodering($search,True);
          break;
      }

      $data = array('result'=>$result);
      return $data;
  }

  function getIndicatorData($id,$search,$ordering){
    $result = null;
    $amount = 0;
    $data   = null;
    switch($id){
      case 19:
          $data = $this->getGDPDataOrdering($search,$ordering);
          break;
      case 20:
          $data = $this->getImportDataOrdering($search,$ordering);
          break;
      case 21:
          $data = $this->getExportDataOrdering($search,$ordering);
          break;
     }

    return $data;
  }

  function createDataCountryGrowth($firstYear,$endYear,$country){
    $inityear = intval($firstYear);
    $endyear = intval($endYear) + 1;
    for($i = $inityear; $i < $endyear; $i++){
      $result = $this->Imfdata_model->getGDPbyContryname($country,$i);

    }

  }





}
?>
