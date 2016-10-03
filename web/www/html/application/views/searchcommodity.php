<?php echo validation_errors(); ?>
<tr>
  <form action="/index.php/commodity/categorysearch/<?=$id?>" method="post">
     <td style="width: 2%;">
       </td>
         <td style="width: 48%;" colspan="2">Unit&nbsp;
          <select name="unit" size="1">
          <option value="" selected="selected"></option>
           <option value="price">price(U.S Dollar)</option>
           <option value="percent">percent(%)</option>
          </select>
         </td>
        <td style="width: 48%;" colspan="2">Start Year&nbsp;
         <select name="startyear" size="1">
         <option value="" selected="selected"></option>
         <?php foreach ($yearData as $value) {
         ?>
          <option value="<?=$value->time?>"><?=$value->time?></option>
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
             <option value="<?=$value->time?>"><?=$value->time?></option>
             <?php
               }
             ?>
          </select>
        </td>
      </td>
       <?php foreach ($commoditydata as $value) {
         if($value->commodity == "")
           continue;
       ?>

        <div class="checkbox">
          <label>
            <input type="checkbox" name="commoidty[]" value="<?=$value->commodity?>">
            <?=$value->commodity?>
          </label>
        </div>
       <?php
         }
       ?>
     </td>
   </td>
    <input type="submit" class="btn btn-info" value="Submit">
  </form>
</tr>
