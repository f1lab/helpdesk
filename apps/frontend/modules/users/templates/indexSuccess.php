<h1>Пользователи</h1>

<table class = "table ">
  <thead>
    <tr>
      <th>Id</th>
      <th>Имя</th>
      <th>Фамилия</th>
      <th>Электронный адрес</th>
      <th>Имя пользователя</th>
      <th>Активирован</th>
      <th>Суперадмин</th>
      <th>Предыдущий вход</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($sf_guard_users as $sf_guard_user): ?>
    <tr>
      <td><a href="<?php echo url_for('users/edit?id='.$sf_guard_user->getId()) ?>"><?php echo $sf_guard_user->getId() ?></a></td>
      <td><?php echo $sf_guard_user->getFirstName() ?></td>
      <td><?php echo $sf_guard_user->getLastName() ?></td>
      <td><?php echo $sf_guard_user->getEmailAddress() ?></td>
      <td><?php echo $sf_guard_user->getUsername() ?></td>
      <td><?php if($sf_guard_user->getIsActive()){ echo "Да";} else {echo "Нет";} ?></td>
      <td><?php if($sf_guard_user->getIsSuperAdmin()){ echo "Да";} else {echo "Нет";} ?></td>
      <td><?php echo $sf_guard_user->getLastLogin() ?></td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>

  <a href="<?php echo url_for('users/new') ?>">New</a>
