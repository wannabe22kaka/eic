<div class="btn-group">

  <?php
    $i = 1;
   foreach ($titleList as $value){
    ?>
    <button class="btn" type="submit">
      <a href="/index.php/indicator/getodering/<?=$id?>/<?=$i?>"><span class="glyphicon glyphicon-plus"></span><?=$value?></a>
    </button>
  <?php
    $i++;
    }
  ?>
</div>
