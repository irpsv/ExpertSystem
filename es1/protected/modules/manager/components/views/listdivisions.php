<?php
/* @var $this ListDivisions */
?>

<p>
<?php foreach ($this->getListDivisions() as $id => $name) {
    echo "<div class='row-list-divisions'>";
    echo $this->renderInput($id);
    echo "<label for='division-{$id}'>{$name}</label>";
    echo "</div>";
}
?>
</p>