<?php
$id_sinistro = $_GET['id_sinistro'];
?>

<form action="deleteAccident.php?id_sinistro=<?php echo $id_sinistro; ?>" method="post">
<div class="modal-header">
    <h5 class="modal-title">Eliminar o sinistro #<?php echo $id_sinistro; ?>?</h5>
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