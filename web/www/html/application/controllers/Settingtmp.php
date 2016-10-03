<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "/var/www/html/application/controllers/Base.php";
require_once "/var/www/html/static/servercli/cli.php";

class Setting extends Base {
  function __construct(){
    parent::__construct();
  }

  function searchInfo($id){

    switch($id){
      case 10:
      case 15:
          {
            return "ex)20160808";
          }
          break;
      case 11:
          {
            return "ex)1980~2020";
          }
          break;
      case 25:
          {
            return "ex)2015-07-01,2016-08-01";
          }
          break;
      case 26:
          {
            return "ex)20150701,위안화";
          }
          break;
    }
  }

  function getCategory($id){
    //var_dump($id);
    $this->_setcategory($id);
    $category = $this->Ui_model->getIDfromCid($id);
    $topic    = $this->Ui_model->getTopicInfo($id);
    $this->_common($category->cid);
    $this->load->view('footer');
    $searchinfo = $this->searchInfo($id);
    $this->load->view('settingsearch',array('searchinfo'=>$searchinfo,'id'=>$id,'topic'=>$topic));
  }

  function webcrawling($id,$search){
    if(strlen($search) != 8){
      echo "ex)20160808 날짜를 입력해주세요";
    }
    else {
      $this->getCategory($id);
      $this->_SelectTopic($id,$search);
    }
  }

  function gdpdatacrawling($id,$search){
    if(strlen($search) != 4){
      echo "ex)2016년도를 입력해주세요";
    }
    else {
      $this->getCategory($id);
      $this->_SelectTopic($id,$search);
    }
  }


  function serach($id){
    //var_dump($id);
    $search = $this->input->post("search");
    //var_dump($search);
    switch($id){
      case 10:
          {
            $this->webcrawling($id,$search);
          }
          break;
      case 11:
          {
            $splitsearch = explode("~",$search);
            $inityear = intval($splitsearch[0]);
            $endyear = intval($splitsearch[1]) +  1;
            for($i = $inityear; $i < $endyear; $i++){
              $year = $i;
              $crawlingyear = strval($year);
              //print "crawling year";
              //var_dump($crawlingyear);
              $this->gdpdatacrawling($id,$crawlingyear);
            }
          }
      case 15:
      case 25:
      case 26:
          {
            $this->getCategory($id);
            $this->_SelectTopic($id,$search);
          }
          break;
    }
  }

  function _SelectTopic($id,$day){
    switch($id)
    {
      case 10:
        var_dump(operationdaycli('scrapy',$day));
        break;
      case 11:{
        operationdaycli('gdp',$day);
        operationdaycli('gdpgrowrate',$day);
        }
        break;
      case 15:
        var_dump(operationdaycli('sortingdata',$day));
        break;
      case 25:
        var_dump(operationSplitFactorcli('selectpreindicator',$day));
        break;
      case 26:
        var_dump(operationSplitFactorcli('topkeyword',$day));
        break;

    }
  }

}
?>
