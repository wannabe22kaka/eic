<div class="article list">
  <h3>Relation Article</h3>
</div>
<div class="article list">
  <ul>
    <?php foreach ($result as $value){
      ?>
      <li>
         <dl>
           <dt>
             <a href="<?=$value->link?>">
  								 <?=$value->title?>
  								</a>
           </dt>
           <dd>
             <span class="writing">Writing : <?=$value->writing?></span>
             <span class="date">Day : <?=$value->uploadtime?></span>
           </dd>
         </dl>
      </li>
    <?php
      }
    ?>
  </ul>
</div>
