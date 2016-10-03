<table class="table">
  <td><?="Country"?></td>
  <td><?="GDP(rate)"?></td>
  <td><?="Import"?></td>
  <td><?="Export"?></td>
  </tr>
  <?php foreach ($result as $value){
    $rate = "(".strval(round(intval($value->currencyamount)/intval($amount->currencyamount),2) * 100)."%".")";
    ?>
  <td><?=$value->country?></td>
  <td><?=$value->currencyamount.$rate?></td>
  <td><?=$value->importpercent?></td>
  <td><?=$value->exportpercent?></td>
  </tr>
  <?php
    }
  ?>
</table>
