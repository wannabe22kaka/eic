<?php

class effectcalculation
{
  private $LimitValue;
  private $RoundofDigit;
  function __construct(){
    $this->RoundofDigit = 2;
    $this->LimitValue = 0.000000001;
  }

  function __destruct() {

  }
  public function Get_Limitvalue(){
    return $this->LimitValue;
  }
  public function GET_ValueOfFunc($x,$slope,$c){
    return ($x * $slope) + $c;
  }

  public function GET_DefinenumOfFuc($x, $y, $slope){
      $C = $y - ($x*$slope);
      return $C;
  }

  public function GET_RateofChange($x1, $x2, $y1, $y2){
      $rateofchagne = ($y2 - $y1) / ($x2 - $x1);
      return round($rateofchagne,$this->RoundofDigit);
  }
  public function GET_RateofShare($share, $LongRate ,$ShortRate){
    return $share * $LongRate * $ShortRate;
  }

  public function GET_RippleEffect($share, $RateofChange){
    return $share * $RateofChange * $RateofChange;
  }

}

?>
