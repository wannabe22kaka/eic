<?php echo validation_errors(); ?>
<tr>
     <td style="width: 2%;">
     </td>
	 <td style="width: 48%;" colspan="2">Year&nbsp;
	    <select name="sy" size="1">
        <?php foreach ($yearData as $value) {
        ?>
         <option value="<?=$value->year?>"><?=$value->year?></option>
        <?php
          }
        ?>
	    </select>
	 </td>
</tr>
