<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>
<script>
  $(function() {
    $('.chzn-select').chosen();
    $('.chzn-select-deselect').chosen({ allow_single_deselect: true });
  });
</script>

<form action="<?php echo url_for('users/'.($form->getObject()->isNew() ? 'create' : 'update').(!$form->getObject()->isNew() ? '?id='.$form->getObject()->getId() : '')) ?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>
<?php if (!$form->getObject()->isNew()): ?>
<input type="hidden" name="sf_method" value="put" />
<?php endif; ?>
  <table>
    <tfoot>
      <tr>
        <td colspan="2">
          <?php echo $form->renderHiddenFields(false) ?>
          &nbsp;<a class = "btn btn-default" href="<?php echo url_for('users/index') ?>">Все пользователи</a>
          <?php if (!$form->getObject()->isNew()): ?>
            &nbsp;<?php echo link_to('Удалить', 'users/delete?id='.$form->getObject()->getId(), array('method' => 'delete', 'confirm' => 'Are you sure?')) ?>
          <?php endif; ?>
          <input class = "btn btn-default" type="submit" value="Сохранить"/>
        </td>
      </tr>
    </tfoot>
    <tbody>
      <?php echo $form->renderGlobalErrors() ?>
      <tr>
        <th><?php echo $form['first_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['first_name']->renderError() ?>
          <?php echo $form['first_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['last_name']->renderLabel() ?></th>
        <td>
          <?php echo $form['last_name']->renderError() ?>
          <?php echo $form['last_name'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['email_address']->renderLabel() ?></th>
        <td>
          <?php echo $form['email_address']->renderError() ?>
          <?php echo $form['email_address'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['username']->renderLabel() ?></th>
        <td>
          <?php echo $form['username']->renderError() ?>
          <?php echo $form['username'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['password']->renderLabel() ?></th>
        <td>
          <?php echo $form['password']->renderError() ?>
          <?php echo $form['password'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['groups_list']->renderLabel() ?></th>
        <td>
          <?php echo $form['groups_list']->renderError() ?>
          <?php echo $form['groups_list'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['permissions_list']->renderLabel() ?></th>
        <td>
          <?php echo $form['permissions_list']->renderError() ?>
          <?php echo $form['permissions_list'] ?>
        </td>
      </tr>
      <tr>
        <th><?php echo $form['responsible_for_company_list']->renderLabel() ?></th>
        <td>
          <?php echo $form['responsible_for_company_list']->renderError() ?>
          <?php echo $form['responsible_for_company_list'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</form>
