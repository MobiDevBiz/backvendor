
<?php if ($this->editable) {?>
<h1>Update <?php echo $this->modelAlias;?> <?php echo $this->getModelTitle($this->model); ?></h1>
<?php $this->renderForm();
}
else echo "<h1>Modifying this entity is forbidden!</h1>";
?>