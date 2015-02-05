<?php
/* @var $this BayesController */
/* @var $quest string */
/* @var $answers array */

$url = $this->createAbsoluteUrl('bayes/addanswer');
?>
<form class="answers" method='get' action='<?=$url?>'>
    <p>
        <?=$quest?>
        <input type='hidden' name='id_quest' value='<?=$id_quest?>' />
    </p>
	<p>
    <?php foreach ($answers as $answer): ?>
    
    <div class='answer'>
        <input type='radio' name='id_answer' value='<?=$answer['id']?>' id='answer-<?=$answer['id']?>' />
        <label for='answer-<?=$answer['id']?>'><span></span><?=$answer['name']?></label>
    </div>
    
    <?php endforeach; ?>
	</p>
    <p>
		<input class="btn btn-success" type="submit" value="Ответить" />
	</p>
</form>