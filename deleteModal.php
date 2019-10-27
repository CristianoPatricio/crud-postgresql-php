<?php
$username = $_GET['username'];
?>

<form action="delete.php?user=<?php echo $username; ?>" method="post">
<div class="modal-header">
    <h5 class="modal-title">Eliminar o utilizador <?php echo $username; ?>?</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <p>Tem a certeza? Esta operação é irreversível.</p>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn btn-danger">Eliminar</button>
</div>
</form>