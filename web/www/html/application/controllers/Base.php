<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "/var/www/html/static/servercli/cli.php";
require_once "/var/www/html/static/lib/function/effectcalculation.php";
require_once "/var/www/html/static/lib/function/viewfunction.php";


class Base extends CI_Controller {
  private $m_gaptime;
  private $m_localtime;
  private $m_categoryid;
  function __construct(){
    parent::__construct();
    $this->load->helper('url');
    $this->load->library('form_validation');


    $this->load->model('Ui_model');
    $this->load->model('Sorting_model');
    $this->load->model('Crawling_model');
    $this->load->model('Ranking_model');
    $this->load->model('Imfdata_model');
    $this->load->model('Commoditydata_model');
    #echo 'new';
    //$this->load->model('Ranking_model');
  }

  function getGapTime(){
    return $this->m_gaptime;
  }

  function getLocalTime(){
    return $this->m_localtime;
  }

  function _setcategory($id){
    $this->m_categoryid = $id;
  }

  function _getcategory(){
    return $this->m_categoryid;
  }

  function timestamptodate($timestamp){
    $date = date("Y-M-D H:i:s",$timestamp);
    return $date;
  }


  function index(){

      $this->_common(2);
      $this->load->view('footer');

  }
  function get($id){

      $this->_setcategory('0');
      $this->_common($id);
      $this->load->view('get');
      $this->load->view('footer');

  }


  function _common($id){
      $this->_head($id);
      $this->_sidebar($id);
  }

  function _head($id){
    $Toptopic = $this->Ui_model->getTopTopic();
    $this->load->view('head',array('topics'=>$Toptopic,'id'=>$id));
  }

  function _sidebar($id){
    switch($id)
    {
      case 6:
        {
          $Commoditylist = $this->Commoditydata_model->getCommoditylist();
          $yearData = $this->Commoditydata_model->getCommodityDataofYearlist();
          $this->load->view('searchcommodity',array('commoditydata'=>$Commoditylist,'yearData'=>$yearData));
        }
        break;
      default:
        {
          $Toptopic = $this->Ui_model->getSideBar($id);
          $Topic = $this->Ui_model->getTopicInfo($id);
          $this->load->view('sidebar',array('title'=>$Topic->title, 'topics'=>$Toptopic,'id'=>$this->_getcategory()));
        }
        break;
    }

  }

  public function _getTitleofDay($day)
  {
      $standardtimestamp = strtotime($day);
      $timetitle= date("Y-m-d" , $standardtimestamp);
      //$string = "'";
      //$stirng = $string.$timetitle."'";
      return $timetitle;
  }


  function serach($id){
    $search = $this->input->post("search");

    switch ($id) {
      case '23':
          if(strlen($search) < 9){
            echo "ex)20160808,-1,1,0,2";
          }
          else{
            $array = $this->refinekeyword($search);
            $topic = $this->Ui_model->getIDfromCid($id);
            $topicInfo = $this->Ui_model->getTopicInfo($topic->cid);
            $data = array('id'=>$id,'title'=>$topicInfo->title,'cid'=>$topic->cid, 'keywordArray'=>$array);
            $dayobject = (object)$data;
            $this->distributeGraphByid($id,$dayobject);

          }
        break;
      default:
          if(strlen($search) < 9){
            echo "ex)20160808,금리 본예제처럼 제대로 키워드를 입력해주세요";
          }
          else {
            $array = $this->refinekeyword($search);

            $topic = $this->Ui_model->getIDfromCid($id);
            $topicInfo = $this->Ui_model->getTopicInfo($topic->cid);
            $data = array('id'=>$id,'title'=>$topicInfo->title,'cid'=>$topic->cid, 'keywordArray'=>$array);
            $dayobject = (object)$data;

            $this->distributeGraphByid($id,$dayobject);
            $this->load->view('footer');
         }
        break;
    }


  }

  function refinekeyword($keyword){
    return $array = explode(",", $keyword);
  }

  function distributeGraphByid($id , $dayobject){
    switch($id){
      case 7:{
          $day = $dayobject->keywordArray[0];
          $totalcount = $this->Crawling_model->getRawdataTotalcount($day);
          $sharechartdata = $this->createShareData($dayobject,$totalcount);
          $this->getCategory($id);
          $this->load->view('sharechart',array('title'=>$day." Keyword Web Share",'sharechartdata'=>$sharechartdata));
        }
        break;
      case 8:{
          $increasechartdata = $this->createIncreaseData($dayobject,1800);
          $day = $dayobject->keywordArray[0];
          $this->getCategory($id);
          $this->load->view('increasechart',array('title'=>$this->_getTitleofDay($day),'increasechartdata'=>$increasechartdata,'unit'=>"sum"));
        }
        break;
      case 9:{
          $this->getCategory($id);
          $day = $dayobject->keywordArray[0];
          $totalcount = $this->Crawling_model->getRawdataTotalcount($day);
          $sharechartdata = $this->createShareData($dayobject,$totalcount);
          $increasechartdata = $this->createIncreaseData($dayobject,1800);
          $gdpdata = $this->createGDPData($dayobject);

          $data = $this->createRippleEffectData($sharechartdata,$increasechartdata,$gdpdata);



          $this->load->view('rippleeffect',array('data'=>$data,'day'=>$day));
        }
        break;
      case 23:{
        //var_dump($id);
        $day = $dayobject->keywordArray[0];
        $totalcount = $this->Crawling_model->getRawdataTotalcount($day);
        //var_dump($totalcount);
        $sharechartdata = $this->createShareMomentumData($dayobject,$totalcount);
        $this->getCategory($id);
        $this->load->view('sharechart',array('title'=>$day." Momentum Web Share",'sharechartdata'=>$sharechartdata));
      }
      break;
      case 24:{
        $increasechartdata = $this->createMomentumIncreaseData($dayobject,1800);
        #var_dump($increasechartdata);
        $day = $dayobject->keywordArray[0];
        $this->getCategory($id);
        $this->load->view('increasechart',array('title'=>$this->_getTitleofDay($day)." momentum",'increasechartdata'=>$increasechartdata,'unit'=>"sum"));
      }
      break;

    }

  }

  function getKeywordGDP($gdpdata ,$keyword){
   $totalamount = $gdpdata[0];
   //var_dump($totalamount);
   $rate = 1;
   #첫번째 인덱스는 총합이다.
   //var_dump($gdpdata);
    for($i = 1; $i < count($gdpdata); $i++){
      $data = $gdpdata[$i];
      if($data->keyword == $keyword){
        if($data->gdp != null){
          $gdpdataarray = $data->gdp[0];
          $rate = round(intval($gdpdataarray->currencyamount)/ intval($totalamount->currencyamount),2)*100;
          return $rate;
          break;
        }
      }
    }

    return $rate;
  }


  function createRippleEffectData($Sharedata,$Increasedata,$gdpdata){
    $index = 0;
    $RippleEffectArray = array();
    $gdptotoalamount = $gdpdata[0];
    $indexgdpdata = 1;
    //var_dump($gdpdata);
    //var_dump($Sharedata);
    //var_dump($Increasedata);

    foreach ($Increasedata as $object) {
      $sumarray = $object['sum'];
      $length = count($sumarray);
      $y1 = $sumarray[0];
      $y2 = $sumarray[$length -1];
      $x1 = 1;
      $x2 = 13;
      $y = 0;

      $ShareObject = $Sharedata[$index];
      $index++;
      $ShareObject->y;

      $cal = new effectcalculation();

      $RateofChange = $cal->GET_RateofChange($x1, $x2, $y1, $y2);

      $y = $cal->GET_RippleEffect($ShareObject->y, $RateofChange);

      $rate = $this->getKeywordGDP($gdpdata,$object['keyword']);



      $y = $y * $rate;

      $RippleEffectObject = array('name'=>$object['keyword'],'y'=>round($y,2));
      array_push($RippleEffectArray,(object)$RippleEffectObject);
    }
    return $RippleEffectArray;
  }


  function createGDPData($data){
      $day = $data->keywordArray[0];
      $year = substr($day,0,4);
      $GDPArray = array();
      $amount = $this->Imfdata_model->getSUMGDPbyYear($year);
      array_push($GDPArray,(object)$amount);
      for($i = 1; $i < count($data->keywordArray); $i++){
        $array = array();


        $result = $this->Imfdata_model->getKeywordMatchCountry($data->keywordArray[$i]);
        if($result != null)
          $result = $this->Imfdata_model->getGDPbyContryname($result->country,$year);
        $array = array('keyword' => $data->keywordArray[$i], 'gdp'=>$result);
        array_push($GDPArray,(object)$array);
      }


      return $GDPArray;

  }
  function getMomentum($direction){
    $name = "";
    switch($direction){
      case -1:
          $name = "down";
          break;
      case  1:
          $name = "up";
          break;
      case  2:
          $name = "warning";
          break;
      default:
          $name = "none";
          break;
    }

    return $name;

  }
  function createShareMomentumData($data,$object){
    $day = $data->keywordArray[0];
    $sharearray= array();
    $totalcount = intval($object->crawlingdatacount);
    $serachshare= 0.0;
    for($i = 1; $i < count($data->keywordArray); $i++){
      $JsonString = $this->Sorting_model->getJsonDirectionData(array('day'=>$day,'keyword'=>$data->keywordArray[$i]));
      $sum  = 0;
      if($JsonString == null)
          continue;

        for($j = 0; $j < count($JsonString); $j++){
         $JsonObject = json_decode($JsonString[$j]->jsondata,TRUE);
         $sum = $sum + $JsonObject['sum'];
        }
        $rate = $sum/$totalcount;
        $share = round($rate,2) * 100;
        $serachshare += $share;
        $shareobject = array('name'=>$this->getMomentum($data->keywordArray[$i]),'y'=>$share);
        array_push($sharearray,(object)$shareobject);

    }
    $othershare = 100 - $serachshare;
    $shareobject = array('name'=>'other','y'=>$othershare);
    array_push($sharearray,(object)$shareobject);

    return $sharearray;
  }
  function createShareData($data,$object){
    $day = $data->keywordArray[0];
    $sharearray= array();
    $totalcount = intval($object->crawlingdatacount);
    $serachshare= 0.0;
    for($i = 1; $i < count($data->keywordArray); $i++){
      $JsonString = $this->Sorting_model->getJsonData(array('day'=>$day,'keyword'=>$data->keywordArray[$i]));
      if($JsonString == null)
          continue;

        $JsonObject = json_decode($JsonString->jsondata,TRUE);

        $Keywordindex = $JsonObject['keywordindex'];

        $rate = count($Keywordindex)/$totalcount;
        $share = round($rate,2) * 100;

        $serachshare += $share;

        $shareobject = array('name'=>$data->keywordArray[$i],'y'=>$share);
        array_push($sharearray,(object)$shareobject);

        $result = $this->Crawling_model->getRawdataFromIndexArrayOrdering($JsonObject['keywordindex'],$day);

    }

    $othershare = 100 - $serachshare;
    $shareobject = array('name'=>'other','y'=>$othershare);

    array_push($sharearray,(object)$shareobject);

    return $sharearray;
  }
  function getIncreaseData($IncreaseData,$endtime,$persecond){
    $Returndata = array();
    foreach ($IncreaseData as $KeywordObject) {
      $UploadAarray = $KeywordObject['uploadtime'];
      $Keyword      = $KeywordObject['keyword'];

      $index = 0 ;
      $sum = 0;
      $SumArray = array();
      $TimestampArray = array();


      $todaytimestamp = $this->getLocalTime();

      $length = count($UploadAarray);

      while($todaytimestamp <$endtime)
      {
        if($index < $length)
        {
          if($UploadAarray[$index] > $todaytimestamp){
              $sum = $index;
              $sum = $sum + 1;
              array_push($SumArray,$sum);

              $time = date("Y-m-d H:i:s", $todaytimestamp);
              $Datetime = new DateTime($time, new DateTimeZone('Asia/Seoul'));
              $day = $Datetime->format('H:i:s');
              array_push($TimestampArray,$day);
              $todaytimestamp += $persecond;

          }
          $index++;
        }
        else{
          array_push($SumArray,$sum);
          $time = date("Y-m-d H:i:s", $todaytimestamp);
          $Datetime = new DateTime($time, new DateTimeZone('Asia/Seoul'));
          $day = $Datetime->format('H:i:s');
          array_push($TimestampArray,$day);
          $todaytimestamp += $persecond;

        }

      }
      /*
      while(true){

        if($todaytimestamp >=$endtime)
        {
            if($index < $length){
              $sum = $length;
              array_push($SumArray,$sum);

              $time = date("Y-m-d H:i:s", $todaytimestamp);
              $Datetime = new DateTime($time, new DateTimeZone('Asia/Seoul'));
              $day = $Datetime->format('H:i:s');
              array_push($TimestampArray,$day);
            }
            break;
        }
        if($index < $length)
        {

            if($UploadAarray[$index] > $todaytimestamp){
                $sum = $index;
                $sum = $sum + 1;
                array_push($SumArray,$sum);

                $time = date("Y-m-d H:i:s", $todaytimestamp);
                $Datetime = new DateTime($time, new DateTimeZone('Asia/Seoul'));
                $day = $Datetime->format('H:i:s');
                array_push($TimestampArray,$day);
                $todaytimestamp += $persecond;

            }
            $index++;
            var_dump($index);
        }
        else{
            var_dump("confirm");
            var_dump($todaytimestamp);
            break;
          }

      }
      */
      $ReturndataObject = array('keyword'=>$Keyword,'sum' => $SumArray,'timestamp'=>$TimestampArray);
      array_push($Returndata,$ReturndataObject);
    }
    return $Returndata;
  }

  function createMomentumIncreaseData($data,$persecond){
    $day = $data->keywordArray[0];
    $sum     = 0;
    $endtime = 0;

    $IncreaseData = array();
    $Returndata = array();
    $daydata = array('local'=>'Asia/Seoul','day'=>$day);
    $this->adjustlocaltime($daydata);

    for($i = 1; $i < count($data->keywordArray); $i++){
        $uploadtimeArray = array();
        $temptime = 0;

        //var_dump($data->keywordArray[$i]);
        $JsonString = $this->Sorting_model->getJsonDirectionData(array('day'=>$day,'keyword'=>$data->keywordArray[$i]));

        if($JsonString == null)
            continue;

            for($j = 0; $j < count($JsonString); $j++){
             $JsonObject = json_decode($JsonString[$j]->jsondata,TRUE);
             $result = $this->Crawling_model->getRawdataFromIndexArrayOrdering($JsonObject['keywordindex'],$day);
             foreach ($result as $object) {

                 $uploadtime = intval($object->uploadtime);

                array_push($uploadtimeArray, $uploadtime);

             }


      }
      $temptime = $uploadtimeArray[count($uploadtimeArray) - 1];
      if($endtime < $temptime)
          $endtime = $temptime;

      $KeywordArray = array('keyword'=>$this->getMomentum($data->keywordArray[$i]),'uploadtime' => $uploadtimeArray);
      array_push($IncreaseData,$KeywordArray);

    }

    $Increasedata = $this->getIncreaseData($IncreaseData,$endtime,$persecond);

    return $Increasedata;
  }

  function createIncreaseData($data,$persecond){

    $day = $data->keywordArray[0];
    $endtime = 0;
    $sum     = 0;

    $IncreaseData = array();
    $Returndata = array();
    $daydata = array('local'=>'Asia/Seoul','day'=>$day);
    $this->adjustlocaltime($daydata);
    for($i = 1; $i < count($data->keywordArray); $i++){
        $uploadtimeArray = array();
        $temptime = 0;

        //var_dump($data->keywordArray[$i]);
        $JsonString = $this->Sorting_model->getJsonData(array('day'=>$day,'keyword'=>$data->keywordArray[$i]));

        if($JsonString == null)
            continue;

        $JsonObject = json_decode($JsonString->jsondata,TRUE);

        $result = $this->Crawling_model->getRawdataFromIndexArrayOrdering($JsonObject['keywordindex'],$day);



        foreach ($result as $object) {

            $uploadtime = intval($object->uploadtime);

            if($temptime < $uploadtime)
                $temptime = $uploadtime;

           array_push($uploadtimeArray, $uploadtime);

        }



        if($endtime < $temptime)
            $endtime = $temptime;


        $KeywordArray = array('keyword'=>$data->keywordArray[$i],'uploadtime' => $uploadtimeArray);
        array_push($IncreaseData,$KeywordArray);
    }

    $Increasedata = $this->getIncreaseData($IncreaseData,$endtime,$persecond);

    return $Increasedata;
}

  function getCategory($id){
    //var_dump($id);

    $category = $this->Ui_model->getIDfromCid($id);
    $topic    = $this->Ui_model->getTopicInfo($id);
    $this->_setcategory($id);
    $this->_common($category->cid);
    $this->load->view('footer');
    $this->load->view('search',array('id'=>$id,'topic'=>$topic));

  }

  function adjustlocaltime($data)
  {
    $local = $data['local'];
    $day   =  $data['day'];
    $utctimestamp = strtotime($day);
  //  $localtime = new DateTime($day, new DateTimeZone('Asia/Seoul'));
    $localtime = new DateTime($day, new DateTimeZone($local));
    $this->m_localtime =$localtime->getTimeStamp();
    $this->m_gaptime = $this->m_localtime - $utctimestamp;
  }


  function IncreaseCommodityArraydata($startindex,$endindex,$commodity,$yearlist,$Returndata){
    $ReturndataObject = $this->IncreaseCommodityRatedata($startindex,$endindex,$commodity,$yearlist);
    array_push($Returndata,$ReturndataObject);
    return $Returndata;
  }

  function IncreaseCommodityRatedata($startindex,$endindex,$commodity,$yearlist){
    $commodityarray = array();
    $data = $this->Commoditydata_model->getCommodityDataRateChange($commodity);
    for($i = intval($startindex); $i < intval($endindex); $i++){
      if($data[$i] != null)
        $ratechange = floatval($data[$i]->ratechange);
      else
        $ratechange = 0;
      array_push($commodityarray,$ratechange);
    }

    return $ReturndataObject = array('title'=>"commodity",'keyword'=>$commodity,'sum' => $commodityarray,'timestamp'=>$yearlist);
  }

  function IncreaseCommodityPricedata($startindex,$endindex,$commodity,$yearlist){
    $commodityarray = array();
    $data = $this->Commoditydata_model->getCommodityDataPrice($commodity);
    for($i = intval($startindex); $i < intval($endindex); $i++){
      if($data[$i] != null)
        $price = floatval($data[$i]->price);
      else
        $price = 0;
      array_push($commodityarray,$price);
    }

    return $ReturndataObject = array('title'=>"commodity",'keyword'=>$commodity,'sum' => $commodityarray,'timestamp'=>$yearlist);
  }










}
?>
