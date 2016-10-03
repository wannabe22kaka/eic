<?php echo validation_errors(); ?>
<div>
  <form class="navbar-search pull-left" action="/index.php/<?=$title?>/serach/<?=$id?>" method="post">
  <input type="text" name="search" id="search" value="<?php echo set_value('search'); ?>"  placeholder="<?php echo $searchinfo?>">
  </form>
</div>
