<?php echo validation_errors(); ?>
<tr>
  <form action="/index.php/Indicator/comparecommodity/<?=$id?>/<?=$countrystring?>" method="post">
     <td style="width: 2%;">
       </td>
    	 <td style="width: 48%;" colspan="2">Compare commodity&nbsp;
         <select name="category" size="1">
         <option value="" selected="selected"></option>
            <?php foreach ($commoditydata as $object) {
              if($object->commodity == '')
                continue;
            ?>
             <option value="<?=$object->commodity?>"><?=$object->commodity?></option>
            <?php
              }
            ?>
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
    <input type="submit" class="btn btn-info" value="Submit">
  </form>
</tr>
