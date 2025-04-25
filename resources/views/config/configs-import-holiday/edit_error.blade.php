<?php
  $error_cells = json_decode($this->item->error_cell);
?>
<style>
  table.cell {
      border-collapse: collapse;
  }

  table.cell, table.cell tr th, table.cell tr td {
      border: 1px solid black;
  }

  table.cell tr th, table.cell tr td{
    padding: 15px;
    text-align: left;
  }

  .bg-gray{
    background-color: #dddcdb;
  }

  .bg-yellow{
    background-color: #FFFF00;
  }
</style>

<?php if(count($error_cells)>0){ ?>
  <table class="cell">
    <tr align="center">
      <th>#</th>
      <th>A</th>
      <th>B</th>
      <th>C</th>
      <th>D</th>
      <th>E</th>
    </tr>

    <?php foreach ($error_cells as $key => $value) { ?>
      <tr <?php echo ($key==1)?'class="bg-yellow"':''?>>
        <td class="bg-gray"><?php echo $key; ?></td>
      <?php for($col = 'A'; $col<='E'; $col++){?>
        <td>
          <?php
            if(isset($value->$col)){
              echo '<i class="text-error">'.$value->$col->data.'</i>';
              echo ($value->$col->comment!='')?'<i class="muted">('.$value->$col->comment.')</i>':'';
            }
          ?>
        </td>
      <?php }?>
    </tr>
    <?php }?>

  </table>
<?php }?>

<?php exit; ?>
