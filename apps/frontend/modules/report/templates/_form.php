<?php
/**
 * @var \BaseReportForm $form
 */
?>
<form action="<?= isset($action) ? $action : url_for('report/get'); ?>" method="get">
    <?php if (isset($form['type'])): ?>
        <?php echo $form['type']->renderRowUsing('bootstrap') ?>
    <?php endif; ?>

    <div class="control-group<?php if ($form['from']->hasError()): ?> error<?php endif ?>">
        <?php echo $form['from']->renderLabel(null, array('class' => 'control-label')) ?>

        <div class="controls form-horizontal">
            <?php echo $form['from']->render() ?>

            <?php echo $form['to']->render() ?>
            <div class="help-inline">
                <?php if ($form['from']->hasError()): ?><?php echo $form['from']->getError() ?><?php endif ?>
            </div>
        </div>
    </div>

    <?php echo $form['company_id']->renderRowUsing('bootstrap') ?>
    <?php echo $form['category_id']->renderRowUsing('bootstrap') ?>
    <?php echo $form['responsible_id']->renderRowUsing('bootstrap') ?>

    <?php if (isset($form['headers_drawer'])): ?>
        <?php echo $form['headers_drawer']->renderRowUsing('bootstrap') ?>
    <?php endif; ?>

    <button type="submit" class="btn btn-primary">Получить отчёт</button>
</form>
