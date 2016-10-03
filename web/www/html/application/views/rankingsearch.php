<?php echo validation_errors(); ?>
<div class="row-fluid">
  <div class="span10">
    <form class="navbar-search pull-left" action="/index.php/ranking/serach/<?=$id?>" method="post">
    <input type="text" name="search" id="search" value="<?php echo set_value('search'); ?>"  placeholder="ex)20160810">
    </form>
  </div>
</div>
