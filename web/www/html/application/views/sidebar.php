    <div class="span5">
        <ul class="nav nav-pills">
        <?php
        foreach($topics as $entry){
          ?>
            <li><a href="/index.php/<?=strtolower($title)?>/getCategory/<?=$entry->id?>"><?=$entry->title?></a></li>
        <?php } ?>

        </ul>
    </div>
  </div>
</div>
