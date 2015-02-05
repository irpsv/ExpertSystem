<?php
/* @var $this SiteController */
/* @var $form ListHipothesis */

$this->pageTitle = "Шаг 2";

?>

<div class="page-header">
    <h1>2. Установка граничных значений</h1>
</div>

<form action="" method="POST">
    
<p>
    <table width="700px">
        <thead>
            <tr>
                <th>Гипотеза</th>
                <th>Граниченое значение С</th>
            </tr>
        </thead>
        <tbody>
            <?php
            
            foreach ($form->getHypothesis() as $id_hyp => $name) {
                echo "<tr>";
                echo "<td>{$name}</td>";
                echo "<td><input type='text' name='hypothesis[{$id_hyp}][eps]' /></td>";
                echo "</tr>";
            }
            
            ?>
        </tbody>
    </table>
</p>

<p>
    <input class="btn btn-success" type="submit" value="Далее" />
</p>
    
</form>