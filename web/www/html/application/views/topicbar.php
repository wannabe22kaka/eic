    <div class="span5">
        <div class="btn-group">
        <?php
        foreach($topics as $entry){
          ?>
            <button class="btn dropdown-toggle" data-toggle="dropdown">
              <a href="/index.php/<?=strtolower($title)?>/getCategory/<?=$entry->id?>"><?=$entry->title?></a>
            </button>
            <ul class="dropdown-menu">
              <!-- dropdown menu links -->
            </ul>
        <?php } ?>
        </div>
    </div>
  </div>
</div>
