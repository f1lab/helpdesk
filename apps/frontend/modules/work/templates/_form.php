<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<form action="<?php echo url_for('work/'
    . ($form->getObject()->isNew() ? 'create' : 'update')
    . (!$form->getObject()->isNew() ? '?id=' . $form->getObject()->getId() : ''))
?>" method="post" <?php $form->isMultipart() and print 'enctype="multipart/form-data" ' ?>>

    <?php echo $form->renderUsing('bootstrap') ?>

    <?php if (!$form->getObject()->isNew()): ?>
        <input type="hidden" name="sf_method" value="put"/>
    <?php endif ?>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?php echo url_for($sf_request->getParameter('ticket_id') ? '@tickets-show?id='
            . $sf_request->getParameter('ticket_id') : 'work/index') ?>" class="btn">Back to list</a>

        <?php if (!$form->getObject()->isNew()): ?>
            <?php echo link_to('Delete', 'work/delete?id=' . $form->getObject()->getId(), [
                'method' => 'delete',
                'confirm' => 'Are you sure?',
                'class' => 'btn btn-warning pull-right',
            ]) ?>
        <?php endif ?>
    </div>
</form>
