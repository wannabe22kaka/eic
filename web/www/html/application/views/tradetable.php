<div class="row-fluid">
  <div class="span12">
    <table class="table">
      <h2><?=$year?></h2>
      </tr>
      <td><?=$country?></td>
      <td><?=$unit?></td>
      <td><?=$value?></td>
      </tr>
      <?php foreach ($result as $value){
        if($value->country == "")
          continue;
        ?>
      <td><?=$value->country?></td>
      <td><?=$value->units?></td>
      <td><?=strval($value->percent)."%"?></td>
      </tr>
      <?php
        }
      ?>
    </table>
  </div>
</div>
