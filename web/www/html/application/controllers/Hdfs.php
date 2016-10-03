<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "/var/www/html/application/controllers/Setting.php";
require_once "/var/www/html/static/servercli/cli.php";

class Hdfs extends Setting {
  function __construct(){
    parent::__construct();
  }

  function serach($id){
    //var_dump($id);
    $search = $this->input->post("search");
    //var_dump($search);
    switch($id){
      case 15:
      case 25:
      case 26:
      case 27:
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
      case 15:
        var_dump(operationdaycli('sortingdata',$day));
        break;
      case 25:
        var_dump(operationSplitFactorcli('selectpreindicator',$day));
        break;
      case 26:
        var_dump(operationSplitFactorcli('topkeyword',$day));
        break;
      case 27:
        var_dump(operationSplitFactorcli('hadoop',$day));
        break;
    }
  }

}
?>
