<?php echo validation_errors(); ?>
<div>
  <form class="navbar-search pull-left" action="/index.php/base/serach/<?=$id?>" method="post">
  <input type="text" name="search" id="search" value="<?php echo set_value('search'); ?>"  placeholder="ex)20160810,금리,채권">
  </form>
</div>
