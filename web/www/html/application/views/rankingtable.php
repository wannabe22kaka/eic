<div class="row-fluid">
  <div class="span12">
  <h2>DAY : <?=$topic['day']?></h2>
  <table class="table">
    <td>RANK</td>
    <td>KEYWORD</td>
    <td>SHARE RATE</td>
    <td>ARTICLE</td>
    </tr>
    <?php foreach ($rankingdata as $value){?>
    <td><?=$value->rank?></td>
    <td><?=$value->keyword?></td>
    <td><?=$value->rate?>%</td>
    <td><a href="/index.php/<?=$topic['class']?>/<?=$topic['func']?>/<?=$topic['id']?>/<?=$topic['day'].$value->keyword?>" class="btn btn-link">Article</a></td>
    </tr>
    <?php
      }
    ?>
  </table>
  </div>
</div>
