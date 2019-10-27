<?php
$username = $_GET['username'];
$role = $_GET['role'];
?>
<div class="modal-header text-center">
    <h4 class="modal-title w-100 font-weight-bold">Atualizar utilizador</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<form action="update.php?user=<?php echo $username; ?>" method="post">
    <div class="modal-body mx-3">
        <div class="md-form mb-5">
            <i class="fas fa-user prefix grey-text"></i>
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo $username; ?>" required>
        </div>
        <div class="md-form mb-4">
            <i class="fas fa-lock prefix grey-text"></i>
            <label for="password">Password</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">
        </div>
        <div class="md-form mb-4">
            <i class="fas fa-user-shield grey-text"></i>
            <label for="role">Tipo</label>
            <select class="form-control" name="role" id="userPermissionsList">
                <script>
                    var opt = "<?php echo $role; ?>";
                    switch (opt) {
                        case 'Admin':
                        case 'admin':
                            document.getElementById("userPermissionsList").selectedIndex = "0";
                            break;
                        case 'user_cru':
                            document.getElementById("userPermissionsList").selectedIndex = "1";
                            break;
                        case 'user_R':
                            document.getElementById("userPermissionsList").selectedIndex = "2";
                            break;
                    }
                </script> 
                <option value="admin">Admin</option>
                <option value="user_cru">User-CRU</option>
                <option value="user_R">User-R</option>
            </select>
        </div>
    </div>
    <div class="modal-footer d-flex justify-content-center">
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </div>
</form>