<?php
/**
 * @var Work[] $works
 */
?>
<?php slot('title', 'Tickets List') ?>

<h1 class="page-header">
    Отчёт по работам
</h1>

<?php include_partial('form', ['form' => $form, 'action' => '']) ?>

<?php if (count($works) > 0): ?>
    <table class="table table-condensed table-bordered table-hover tablesorter">
        <thead>
        <tr>
            <th>Номер заявки</th>
            <th>Начало работ</th>
            <th>Окончание работ</th>
            <th>Содержание работ</th>
            <th>Исполниель</th>
            <th>Времени затрачено</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($works as $work): ?>
            <tr>
                <td>
                    <a href="<?= url_for('@tickets-show?id=' . $work->getTicket()->getId()); ?>">
                        <?= $work->getTicket()->getId(); ?>
                    </a>
                </td>
                <td><?= $work->getStartedAt(); ?></td>
                <td><?= $work->getFinishedAt(); ?></td>
                <td><?= $work->getDescription(); ?></td>
                <td><?= $work->getResponsible(); ?></td>
                <td><?= (new DateTime($work->getFinishedAt()))->diff(new DateTime($work->getStartedAt()))->format('%H:%I'); ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="alert alert-info">
        <h4>Всего времени затрачено: ХХ:ХХ</h4>
        <p>
            Работы, выполненные в рабочее время: ХХ:ХХ <br>
            Работы, выполненные в <em>не</em>рабочее время: ХХ:ХХ <br>
        </p>
    </div>
<?php endif; ?>
