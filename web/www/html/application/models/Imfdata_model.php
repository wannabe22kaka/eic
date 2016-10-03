<?php
class Imfdata_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->db = $this->load->database('Imfdata_model',TRUE);
    }

    function getGDPbyYear($year){
        return $this->db->query("SELECT country, currencyamount FROM  ".$year."GDP")->result();
    }

    function getGDPbyodering($year,$isASC){
        if($isASC == TRUE)
           return $this->db->query("SELECT country, currencyamount FROM  ".$year."GDP ORDER BY currencyamount ASC")->result();
        else
           return $this->db->query("SELECT country, currencyamount FROM  ".$year."GDP ORDER BY currencyamount DESC")->result();
    }



    function getSUMGDPbyYear($year){
        $row = $this->db->query("SELECT sum(currencyamount)  as currencyamount FROM  ".$year."GDP")->row();
        return $row;
    }


    function getKeywordMatchCountry($keyword){
        $query = "select * from KeywordMatchGDP where keyword='".$keyword."'";
        var_dump($query);
        return $this->db->query($query)->row();
    }

    function getGDPbyContryname($country,$year){
        return $this->db->query("select * from ".$year."GDP where country='".$country."'")->result();
    }

    function getGDPCurrencyAmountbyContryname($country,$year){
        return $this->db->query("select currencyamount from ".$year."GDP where country='".$country."'")->row();
    }

    function getGDPgrowratebyContryname($country,$year){
        return $this->db->query("select growrate from ".$year."GDP where country='".$country."'")->row();
    }


   ///////Common///////////
   function getYearList($tablename){
      return $this->db->query("SELECT year FROM ".$tablename."List")->result();
   }

   function getCountryList($year){
       return $this->db->query("select country from ".$year."GDP")->result();
   }

   function getTradebyYearAndCountry($country,$year, $tablename){
       return $this->db->query("SELECT percent FROM  ".$year."trade".$tablename." where country='".$country."'")->result();
   }
  ////////////Inflation data ////////////////////
  function getInflation($country,$year){
    return $this->db->query("select percent from ".$year."Inflation where country='".$country."'")->row();
  }

  function getEndofperiodInflation($country,$year){
    return $this->db->query("select percent from ".$year."EndofperiodInflation where country='".$country."'")->row();
  }



  ////////////Export data ///////////////////////

    function getExportbyYear($year){
        return $this->db->query("SELECT country, units,  percent FROM  ".$year."tradeexport")->result();
    }

    function getExportbyodering($year,$isASC){
        if($isASC == TRUE)
           return $this->db->query("SELECT country, units,  percent  FROM  ".$year."tradeexport ORDER BY  percent ASC")->result();
        else
           return $this->db->query("SELECT country, units,  percent  FROM  ".$year."tradeexport ORDER BY  percent DESC")->result();
    }



    ///////////Import data ////////////////////////


    function getImportbyYear($year){
        return $this->db->query("SELECT country, units,  percent  FROM  ".$year."tradeimport")->result();
    }

    function getImportbyodering($year,$isASC){
        if($isASC == TRUE)
           return $this->db->query("SELECT country, units,  percent  FROM  ".$year."tradeimport ORDER BY  percent ASC")->result();
        else
           return $this->db->query("SELECT country, units,  percent  FROM  ".$year."tradeimport ORDER BY  percent DESC")->result();
    }


    ////////G20 JOIN DATA////////

    function getG20data($year){
      return $this->db->query("select * from ".$year."GDP ORDER BY currencyamount DESC LIMIT 20")->result();
    }

    function getG20dataExportAndImport($year){


      $GDPtable = $year."GDP";
      $IMPORTtable = $year."tradeimport";
      $EXPORTtable = $year."tradeexport";

      return $this->db->query("select e.percent as exportpercent, i.percent as importpercent, g.country,g.currency, g.currencyamount, currencyunit from ".$GDPtable." as g join ".$IMPORTtable." as i on g.country = i.country left join ".$EXPORTtable." as e on g.country = e.country ORDER BY g.currencyamount DESC LIMIT 20")->result();
    }


}
?>
