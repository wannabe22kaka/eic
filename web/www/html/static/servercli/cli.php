<?php
  putenv("LANG=ko_KR.UTF-8");
  function operationdaycli($file, $day){
    var_dump($day);
    $filename = "./".$file.".sh ".$day;
    $output = shell_exec($filename);
    //var_dump($output);
    return $output;
  }

  function operationSplitFactorcli($file, $factor){
    $factorarray = explode(',', $factor);
    $str = "";
    foreach ($factorarray as $value) {
      //var_dump($str);
      $str = $str." ".$value;
    }
    $filename = "./".$file.".sh ".$str." ";
    $output = shell_exec($filename);

    return $output;
  }

  function operationcli($file){

    $filename = "./".$file.".sh";
    $output = shell_exec($filename);
    //var_dump($output);
    return $output;
  }
?>
