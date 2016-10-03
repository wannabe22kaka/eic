<?php echo validation_errors(); ?>
<tr>
    <form action="/index.php/indicator/G20Search/<?=$id?>" method="post">
       <td style="width: 2%;">
       </td>
  	   <td style="width: 48%;" colspan="2">Year&nbsp;
  	    <select name="selectyear" size="1">
        <option value="" selected="selected"></option>
        <?php foreach ($yearData as $value) {
        ?>
  	     <option value="<?=$value->year?>"><?=$value->year?></option>
        <?php
          }
        ?>
  	    </select>
  	   </td>
       <input type="submit" class="btn btn-info" value="Submit">
     </form>
</tr>
