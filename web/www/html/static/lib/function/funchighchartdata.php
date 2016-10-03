<?php


class ChartdataMgr
{


  function __construct(){

  }

  function __destruct() {

  }



  private function ripple_chartstring($keyword, $value) {
    $datastring = "{name:'".$keyword."',
                    y:".$value.",
                    drilldown: null}";
    return $datastring;
   }

   public function ripplechartdatarray($array){
     $i = 0;
     $length = count($array);
     $comastandard = $length - 1;
     for($i = 0; $i < $length; $i++) {
       $object = $array[$i];
       $string = $this->ripple_chartstring($object->name,$object->y);

      if($i < $comastandard)
         $string = $string.",\n";

       echo $string;
     }
   }


  private function circle_chartstring($keyword, $value) {
    $datastring = "{name:'".$keyword."',
                    y:".$value."}";
    return $datastring;
   }

  public function circlechartdatarray($array){
    $i = 0;
    $length = count($array);
    $comastandard = $length - 1;

    for($i = 0; $i < $length; $i++) {
      $object = $array[$i];
      $string = $this->circle_chartstring($object->name,$object->y);

     if($i < $comastandard)
        $string = $string.",\n";

      echo $string;
    }
  }


  private function linear_chartXstring($value) {
    $datastring = "'".$value."'";
    return $datastring;
   }

   public function LinearChartsetXAlies($array){
     $i = 0;
     $length = count($array);
     $comastandard = $length - 1;
     $string = "[";
     for($i = 0; $i < $length; $i++) {
       $object = $array[$i];
       //$time = substr($object,12);
       $string = $string.$this->linear_chartXstring($object);

      if($i < $comastandard)
         $string = $string.",";


     }
     $string = $string."]";
     echo $string;
   }


  private function linear_chartYstring($value) {
    $arraystring = json_encode($value['sum'],true);
    $datastring = "{ name:'".$value['keyword']."',\n
                     data:".$arraystring."}";
    return $datastring;
   }

  public function LinearChartsetYAlies($array){
    $i = 0;
    $length = count($array);
    $comastandard = $length - 1;

    for($i = 0; $i < $length; $i++) {
      $object = $array[$i];
      $string = $this->linear_chartYstring($object);

     if($i < $comastandard)
        $string = $string.",\n";

      echo $string;
    }

  }

  public function getTitleofDay($day)
  {
      $standardtimestamp = strtotime($day);
      $timetitle= date("Y-m-d" , $standardtimestamp);
      //$string = "'";
      //$stirng = $string.$timetitle."'";
      echo $timetitle;
  }


  public function getRippleffctTitleofDay()
  {

      echo "'Ripple Effect'";
  }


}




?>
