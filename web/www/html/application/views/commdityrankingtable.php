<div class="row-fluid">
  <div class="span12">
    <h2>Commodity current Ranking</h2>
      <table class="table">
        <td>RANK</td>
        <td>COMMODITY</td>
        <td>CLASSFICATION</td>
        <td>CHANGE RATE</td>
        </tr>
        <?php foreach ($rankingdata as $value){?>
        <tr class=<?=$value->class?>>
        <td><?=$value->rank?></td>
        <td><?=$value->keyword?></td>
        <td><?=$value->category?></td>
        <td><?=$value->rate?></td>
        </tr>
        <?php
          }
        ?>
      </table>
  </div>
</div>
