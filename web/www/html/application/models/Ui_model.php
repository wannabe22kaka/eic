<?php
class Ui_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('Ui_model',TRUE);
    }

    function getTopTopic(){
        return $this->db->query("SELECT * FROM topic")->result();
    }

    function getSideBar($cid){
        return $this->db->query("select * from sidebar_topic where cid='$cid'")->result();
    }

    function getIDfromCid($id){
        return $this->db->query("select * from sidebar_topic where id='$id'")->row();
    }

    function getTopicInfo($id){
        return $this->db->get_where('topic', array('id'=>$id))->row();
    }
}
?>
