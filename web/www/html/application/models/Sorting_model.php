<?php
class Sorting_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('Sorting_model',TRUE);
    }

    function getJsonDirectionData($info){
      return $this->db->query("select * from ".$info['day']."sorting_table where json_search(jsondata,'all','".$info['keyword']."')='$.direction'")->result();
    }

    function getJsonData($info){
      //  return $this->db->query("select * from sidebar_topic where id='$id'")->row();
        return $this->db->query("select * from ".$info['day']."sorting_table where json_search(jsondata,'one','".$info['keyword']."')='$.keyword'")->row();
    }

    function getRankingJsonData($info){
      //  return $this->db->query("select * from sidebar_topic where id='$id'")->row();
        return $this->db->query("select * from ".$info['day']."sorting_table limit ".$info['limit'])->result();
    }
}
?>
