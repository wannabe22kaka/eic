<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "/var/www/html/application/controllers/Setting.php";
require_once "/var/www/html/static/servercli/cli.php";

class Crawling extends Setting {
  function __construct(){
    parent::__construct();
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
    }
  }

}
?>
