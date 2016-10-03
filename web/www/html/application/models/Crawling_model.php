<?php
class Crawling_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('Crawling_model',TRUE);
    }

    function getRawdataTotalcount($day){
        return $this->db->query("select * from ".$day."crawling_rawdatacount")->row();
    }

    function getRawdataFromArrayIndex($index,$array)
    {
        return $this->db->get_where($day."crawling_rawdata", array('cindex'=>$index))->row();
    }


    function getRawdataFromIndex($index,$day){
        return $this->db->get_where($day."crawling_rawdata", array('cindex'=>$index))->row();
    }

    function getRawdataFromIndexArrayOrdering($array,$day){

      $length = count($array);
      $IdlistString = "cindex in (";

      for($i = 0; $i < $length; $i++){
        $object = strval($array[$i]);


        if($i < $length - 1)
          $IdlistString = $IdlistString.$object.",";
        else
          $IdlistString = $IdlistString.$object.")";

      }
      return $this->db->query("select * from ".$day."crawling_rawdata where ".$IdlistString." order by uploadtime")->result();
    }
}
?>
