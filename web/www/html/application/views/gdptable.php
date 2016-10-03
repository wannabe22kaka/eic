<table class="table">
  <?php foreach ($result as $value){
    ?>
  <td><?=$value->country?></td>
  <td><?=$value->currencyamount?></td>
  <td><?=strval(round(intval($value->currencyamount)/intval($amount->currencyamount),2) * 100)."%"?></td>
  </tr>
  <?php
    }
  ?>
</table>
