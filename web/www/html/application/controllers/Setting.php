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
          return "ex)20160808";
          break;
      case 11:
          return "ex)1980~2020";
          break;
      case 25:
          return "ex)2015-07-01,2016-08-01";
          break;
      case 26:
          return "ex)20150701,위안화";
          break;
    }
  }

  function getCategory($id){
    $this->_setcategory($id);
    $category = $this->Ui_model->getIDfromCid($id);
    $topic    = $this->Ui_model->getTopicInfo($category->cid);
    $this->_common($category->cid);
    $this->load->view('footer');
    $searchinfo = $this->searchInfo($id);
    $this->load->view('settingsearch',array('searchinfo'=>$searchinfo,'id'=>$id,'topic'=>$topic->title));
  }




}
?>
