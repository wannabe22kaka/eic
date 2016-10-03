<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "/var/www/html/application/controllers/Base.php";
//require "/var/www/html/static/lib/function/datasingleton.php";

function hashcmp($a, $b)
{
    if ($a->count == $b->count ) {
        return 0;
    }
    return ($a->count  > $b->count ) ? -1 : 1;
}

class Ranking extends Base {
  function __construct(){
    parent::__construct();

  }

  function getSortIuputData($value){
    $editstring = urldecode($value);
    $day = substr($editstring,0,8);
    $keyword = substr($editstring,8,strlen($editstring));
    $info = array('day'=>$day,'keyword'=>$keyword);
    return $info;
  }

  function getArticle($id,$keywordday){
    $this->getArticleCategory($id);
    $info = $this->getSortIuputData($keywordday);
    //var_dump($info);
    $jsondata = $this->Sorting_model->getJsonData($info);
    //var_dump($jsondata);

    $JsonObject = json_decode($jsondata->jsondata,TRUE);
    //var_dump($JsonObject);

    $articlearray = array();

    foreach ($JsonObject['keywordindex'] as $index){
      $articledata = $this->Crawling_model->getRawdataFromIndex($index,$info['day']);
      $articledata->uploadtime = $this->timestamptodate(intval($articledata->uploadtime));
      array_push($articlearray,$articledata);
    }


    //var_dump($articlearray);
    $this->load->view('article',array('result'=>$articlearray));

  }

  function getArticleCategory($id){
    $this->_setcategory($id);
    $category = $this->Ui_model->getIDfromCid($id);
    $topic    = $this->Ui_model->getTopicInfo($id);
    $this->_common($category->cid);
    $this->load->view('footer');
  }

  function getCategory($id){
    $this->_setcategory($id);
    $category = $this->Ui_model->getIDfromCid($id);
    $topic    = $this->Ui_model->getTopicInfo($id);
    $this->_common($category->cid);
    $this->load->view('footer');
    $this->load->view('rankingsearch',array('id'=>$id,'topic'=>$topic));
  }

  function index(){
    $search = "20160927,10";
    $id = 4;
    $this->initpage($search,$id);
  }

  function initpage($search,$id){
    $this->settable($search,$id);
  }

  function settable($search,$id){
    $array = $this->refinekeyword($search);
    $topic = $this->Ui_model->getIDfromCid($id);
    $topicInfo = $this->Ui_model->getTopicInfo($topic->cid);
    $data = array('id'=>$id,'title'=>$topicInfo->title,'cid'=>$topic->cid, 'keywordArray'=>$array);
    $dayobject = (object)$data;
    $this->distributeGraphByid($id,$dayobject);
    $this->load->view('footer');
  }

  function serach($id){
    $search = $this->input->post("search");
    if(strlen($search) < 9){
      echo "ex)20160808,10 날짜와 갯수를 입력해주세요";
    }
    else {
      $this->settable($search,$id);
    }
  }




  function  rankingData($dataarray,$limit){
    $hashMap = array();
    $count = 0;
    $find = False;
    foreach ($dataarray as $value) {
      $find = False;
      if($count >= $limit)
        break;

      foreach ($hashMap as $object) {
        if($value->category == $object->category){
            $object->count = $object->count + 1;
            $find = True;
        }
      }

      if($find == False){
        $obj = (object)array("category" => $value->category, "count"=> 1);
        array_push($hashMap, $obj);
      }
      $count++;
    }
    usort($hashMap, "hashcmp");
    return $hashMap;
  }
  function distributeGraphByid($id , $dayobject){
    switch($id){
      case 4:{
          $day = $dayobject->keywordArray[0];
          $limitcount = $dayobject->keywordArray[1];
          $info = array('day'=>$day, 'limit'=>$limitcount);
          $sortdata = $this->Sorting_model->getRankingJsonData($info);
          $totalcount = $this->Crawling_model->getRawdataTotalcount($day);
          $this->getCategory($id);
          $topic = array('class'=>'ranking', 'func'=>'getArticle','id'=>$id,'day'=>$day);
          $rankingData = $this->Ranking_model->rankingTable($sortdata,$totalcount);
          $this->load->view('rankingtable',array('rankingdata'=>$rankingData,'topic'=>$topic));
        //  var_dump($rankingData[0]);
          $toprate = floatval($rankingData[0]->rate);
          if($toprate >=30){
             //echo "40% up";
             $editday = substr($day,0,6)."01";
             $timestamp = strtotime($editday) - (2592000 + 86400);
             $editday= date("Ymd" ,$timestamp);
             $topic = $editday."commodity";
             $commoidtyData = $this->Commoditydata_model->getCommoditydatachangerateandcategory($editday,"asc");
             //var_dump($commoidtyData);
             $topsharecommodity = $this->rankingData($commoidtyData,20);
             //var_dump($topsharecommodity);
             $commoidtyRankingData = $this->Commoditydata_model->rankingTable($commoidtyData,$topsharecommodity);
             $this->load->view('commdityrankingtable',array('rankingdata'=>$commoidtyRankingData,'topic'=>$topic));

           }


          }
          break;
    }
  }
}
?>
