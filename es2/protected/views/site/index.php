<?php
/* @var $this SiteController */

$img_dir = Yii::app()->baseUrl ."/img/";

?>
<p>
    Прежде чем преступить к тестированию необходимо выбрать каким из методов будет проводиться тестирование. Методы доступные для тестирования:
</p>

<p>
<table width="700px" style="text-align: center">
    <tbody>
        <tr>
            <td>
                <?php
                    echo CHtml::link(Chtml::image($img_dir .'icon-pro.png'), $this->createAbsoluteUrl("site/start"));
                ?>
            </td>
            <td>
                <?php
                    echo CHtml::link(Chtml::image($img_dir .'icon-bayes.png'), "/es1/bayes/start");
                ?>
            </td>
        </tr>
    </tbody>
</table>
</p>

<p>
    Управление данными производиться через встроенный <?=CHtml::link("менеджер", $this->createAbsoluteUrl("manager/Hypothesis/list"))?>.
</p>