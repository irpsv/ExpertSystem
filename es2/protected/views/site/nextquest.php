<?php
/* @var $this SiteController */
/* @var $quest Quest */
/* @var $answers Answer[] */
$url_action = $this->createAbsoluteUrl("site/addanswer");
?>

<form action="<?=$url_action?>" method="GET">
<p>
    <p>
        <input type="hidden" value="<?=$quest->id?>" name="id_quest" />
        <?=$quest->name?>
    </p>
    <?php foreach ($answers as $a): ?>
    <?php $id = "answer-{$a->id}"; ?>
    <p>
        <input id="<?=$id?>" type="radio" name="c_value" value="<?=$a->c_value?>" />
        <label for="<?=$id?>"><?=$a->name?></label>
    </p>
    <?php endforeach; ?>
</p>
<p>
    <input class="btn btn-success" type="submit" value="Ответить" />
</p>
</form>