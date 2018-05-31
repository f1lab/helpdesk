<?php
/**
 * @var \workActions $this
 * @var \Ticket $ticket
 * @var \Work[] $works
 */
?>

<!DOCTYPE html>
<html>
<head>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans&amp;subset=cyrillic" rel="stylesheet">
    <style>
        html {
            font-family: 'Open Sans', sans-serif;
        }
    </style>
</head>
<body>
<div>
    <?php foreach ([0, 1] as $clone): ?>
        <div style="text-align: center;">
            <table style="width: 524.5pt; margin-right: auto; margin-left: auto; border-collapse: collapse;"
                   cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 18.25pt;">
                    <td style="width: 117.5pt; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;"
                        colspan="2">
                        <h1 style="margin-top: 0pt; margin-bottom: 6pt; font-size: 10pt;"><a name="_Toc485074202"></a><a
                                    name="_Toc493081775"></a><img
                                    src="data:image/jpeg;base64,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"
                                    width="74" height="73"/></h1>
                    </td>
                    <td style="width: 401pt; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;"
                        colspan="3">
                        <h1 style="margin-top: 0pt; margin-left: 21.3pt; margin-bottom: 6pt; text-indent: -21.3pt; text-align: center; font-size: 12pt;">
                            <span style="text-transform: uppercase;">&nbsp;</span></h1>
                        <h1 style="margin-top: 0pt; margin-left: 21.3pt; margin-bottom: 6pt; text-indent: -21.3pt; text-align: center; font-size: 12pt;">
                            <span style="text-transform: uppercase;">Лист учета рабочего времени</span></h1>
                    </td>
                </tr>
                <tr style="height: 18.25pt;">
                    <td style="width: 60.8pt; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: bottom;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt;"><span style="">Заказчик</span>
                        </p>
                    </td>
                    <td style="width: 110.4pt; border-bottom-style: solid; border-bottom-width: 0.75pt; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: bottom;"
                        colspan="4">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt;"><strong><span
                                        style="">
                                    <?= $ticket->getCompany()->getName(); ?>
                                </span></strong></p>
                    </td>
                </tr>
                <tr style="height: 18.25pt;">
                    <td style="width: 117.5pt; border-bottom-style: solid; border-bottom-width: 0.75pt; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;"
                        colspan="2">
                        <h1 style="margin-top: 0pt; margin-bottom: 6pt; font-size: 10pt;"><span
                                    style="font-weight: normal; text-transform: uppercase;">&nbsp;</span></h1>
                    </td>
                    <td style="width: 401pt; border-bottom-style: solid; border-bottom-width: 0.75pt; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;"
                        colspan="3">
                        <h1 style="margin-top: 0pt; margin-left: 21.3pt; margin-bottom: 6pt; text-indent: -21.3pt; text-align: center; font-size: 12pt;">
                            <span style="text-transform: uppercase;">&nbsp;</span></h1>
                    </td>
                </tr>
                <tr style="height: 32.25pt;">
                    <td style="width: 60.8pt; border-style: solid; border-width: 0.75pt; padding-right: 1.12pt; padding-left: 1.12pt; vertical-align: top;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                            <strong><span style="">Дата</span></strong></p>
                    </td>
                    <td style="width: 53.7pt; border-style: solid; border-width: 0.75pt; padding-right: 1.12pt; padding-left: 1.12pt; vertical-align: top;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                            <strong><span style="">Номер Заявки</span></strong></p>
                    </td>
                    <td style="width: 53.7pt; border-style: solid; border-width: 0.75pt; padding-right: 1.12pt; padding-left: 1.12pt; vertical-align: top;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                            <strong><span style="">Начало </span></strong></p>
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                            <strong><span style="">работ</span></strong></p>
                    </td>
                    <td style="width: 53.7pt; border-style: solid; border-width: 0.75pt; padding-right: 1.12pt; padding-left: 1.12pt; vertical-align: top;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                            <strong><span style="">Окончание работ</span></strong></p>
                    </td>
                    <td style="width: 287.6pt; border-style: solid; border-width: 0.75pt; padding-right: 1.12pt; padding-left: 1.12pt; vertical-align: top;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 9pt;">
                            <strong><span style="">Содержание работы</span></strong></p>
                    </td>
                </tr>
                <?php foreach ($works as $work): ?>
                    <tr style="height: 20.85pt;">
                        <td style="width: 60.8pt; border-style: solid; border-width: 0.75pt; padding-right: 1.12pt; padding-left: 1.12pt; vertical-align: top;">
                            <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: right; font-size: 10pt;"><span
                                        style=""><?= (new DateTime($work->getStartedAt()))->format('d.m.Y'); ?></span></p>
                        </td>
                        <td style="width: 53.7pt; border-style: solid; border-width: 0.75pt; padding-right: 1.12pt; padding-left: 1.12pt; vertical-align: top;">
                            <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: right; font-size: 10pt;"><span
                                        style=""><?= $ticket->getId(); ?></span></p>
                        </td>
                        <td style="width: 53.7pt; border-style: solid; border-width: 0.75pt; padding-right: 1.12pt; padding-left: 1.12pt; vertical-align: top;">
                            <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: right; font-size: 10pt;"><span
                                        style=""><?= (new DateTime($work->getStartedAt()))->format('H:i'); ?></span></p>
                        </td>
                        <td style="width: 53.7pt; border-style: solid; border-width: 0.75pt; padding-right: 1.12pt; padding-left: 1.12pt; vertical-align: top;">
                            <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: right; font-size: 10pt;"><span
                                        style=""><?= (new DateTime($work->getFinishedAt()))->format('H:i'); ?></span></p>
                        </td>
                        <td style="width: 287.6pt; border-style: solid; border-width: 0.75pt; padding-right: 1.12pt; padding-left: 1.12pt; vertical-align: top;">
                            <?= $work->getDescription(); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 10pt;"><strong><span style="">&nbsp;</span></strong>
        </p>
        <div style="text-align: center;">
            <table style="width: 531.6pt; margin-right: auto; margin-left: auto; border-collapse: collapse;"
                   cellspacing="0" cellpadding="0">
                <tbody>
                <tr style="height: 13.9pt;">
                    <td style="width: 348px; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;"
                        colspan="3">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 10pt;"><strong><span style="">Представитель заказчика:</span></strong>
                        </p>
                    </td>
                    <td style="width: 359px; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;"
                        colspan="3">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 10pt;"><strong><span style="">Представитель исполнителя:</span></strong>
                        </p>
                    </td>
                </tr>
                <tr style="height: 19pt;">
                    <td style="width: 348px; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;"
                        colspan="3">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 10pt;"><span
                                    style="">&nbsp;</span></p>
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 10pt;"><span style="">&nbsp;</span>
                        </p>
                    </td>
                    <td style="width: 359px; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;"
                        colspan="3">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 10pt;"><span
                                    style="">&nbsp;</span></p>
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 10pt;"><span style="">&nbsp;</span>
                        </p>
                    </td>
                </tr>
                <tr style="height: 19pt;">
                    <td style="width: 9px; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: bottom;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 10pt;"><span style="">/</span></p>
                    </td>
                    <td style="width: 329px; border-bottom-style: solid; border-bottom-width: 0.75pt; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 10pt;"><span
                                    style="">&nbsp;</span></p>
                    </td>
                    <td style="width: 10px; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: bottom;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 10pt;"><span style="">/</span></p>
                    </td>
                    <td style="width: 10px; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: bottom;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 10pt;"><span style="">/</span></p>
                    </td>
                    <td style="width: 339px; border-bottom-style: solid; border-bottom-width: 0.75pt; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-align: center; font-size: 10pt;"><span
                                    style="">&nbsp;</span></p>
                    </td>
                    <td style="width: 10px; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: bottom;">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 10pt;"><span style="">/</span></p>
                    </td>
                </tr>
                <tr style="height: 5.8pt;">
                    <td style="width: 348px; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;"
                        colspan="3">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; text-indent: 62pt; font-size: 10pt;"><span
                                    style="">М.П.</span></p>
                    </td>
                    <td style="width: 359px; padding-right: 1.5pt; padding-left: 1.5pt; vertical-align: top;"
                        colspan="3">
                        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 10pt;"><span style="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span><span
                                    style="">М.П.</span></p>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <p style="margin-top: 0pt; margin-bottom: 0pt; font-size: 12pt;"><span style="">&nbsp;</span></p>
        <?php if ($clone === 0): ?>
            <div align="center">
                <hr style="width: 100%;" align="center" size="2"/>
            </div>
            <div style="text-align: center;">&nbsp;</div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<script>window.print();</script>
</body>
</html>
