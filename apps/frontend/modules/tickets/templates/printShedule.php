<table  class="table  table-bordered table_report">
  <thead>
    <tr>
      <th>Время</th>
      <th>Задача</th>
      <th>Компания</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($created_by_me_for_today as $ticket): ?>
      <tr>  
        <td> <?php  echo $ticket->getSheduleTime() ?></td>
        <td> <?php   echo $ticket->getName() ?></td>
        <td> <?php   echo $ticket->getToCompany()?></td>  
      </tr>
      <tr>
        <td>
          <?php  echo "ОПИСАНИЕ:  ".$ticket->getDescription() ?><br><br>
        </td>
      </tr>
   <?php endforeach; ?>
  </tbody>
</table>