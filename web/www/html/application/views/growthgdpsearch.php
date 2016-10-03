<tr>
  <form action="/index.php/indicator/searchyear/<?=$id?>/<?=$ordering?>" method="post">
     <td style="width: 2%;">
     </td>
      <td style="width: 48%;" colspan="2">Start Year&nbsp;
        <select name="startyear" size="1">
        <option value="" selected="selected"></option>
        <?php foreach ($yearData as $value) {
        ?>
         <option value="<?=$value->year?>"><?=$value->year?></option>
        <?php
          }
        ?>
        </select>
      </td>
    	 <td style="width: 48%;" colspan="2">End Year
    	    <select name="endyear" size="1">
          <option value="" selected="selected"></option>
            <?php foreach ($yearData as $value) {
            ?>
      	     <option value="<?=$value->year?>"><?=$value->year?></option>
            <?php
              }
            ?>
    	    </select>
       </td>
        <?php foreach ($countryList as $value) {
          if($value->country == "")
            continue;
        ?>

         <div class="checkbox">
           <label>
             <input type="checkbox" name="country[]" value="<?=$value->country?>">
             <?=$value->country?>
           </label>
         </div>
        <?php
          }
        ?>
   <input type="submit" class="btn btn-info" value="Submit">
  </form>
</tr>
