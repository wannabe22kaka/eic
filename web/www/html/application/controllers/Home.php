<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once "/var/www/html/application/controllers/Base.php";

class Home extends Base {
  function __construct(){
    parent::__construct();
  }

  function getCategory($id){
    $this->_setcategory($id);
    $category = $this->Ui_model->getIDfromCid($id);
    $this->_common($category->cid);
    $this->load->view('footer');
  }


}
?>
