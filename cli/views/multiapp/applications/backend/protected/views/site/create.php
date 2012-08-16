<?php if ($this->editable)
{ ?>
    <h1>Create <?php echo $this->modelAlias; ?> </h1>

    <?php
    $this->renderForm();
//
    ?>
    <?php
}
else
    echo "<h1>Modifying this entity is forbidden!</h1>";
?>