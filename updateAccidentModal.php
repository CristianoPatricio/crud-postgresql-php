<?php
$id_sinistro = $_GET['id_sinistro'];
$id_distrito = $_GET['id_distrito'];
$id_concelho = $_GET['id_concelho'];
$datahora = $_GET['datahora'];
$nMortos = $_GET['m'];
$nFeridos = $_GET['fg'];
$km = $_GET['km'];
$via = $_GET['via'];
$natureza = $_GET['natureza'];
$lat = $_GET['lat'];
$lon = $_GET['lon'];
?>
<div class="modal-header text-center">
    <h4 class="modal-title w-100 font-weight-bold">Atualizar sinistro #<?php echo $id_sinistro; ?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<br>
<form id="formUpdate" action="updateAccident.php?id=<?php echo $id_sinistro; ?>" method="post">
    <div class="form-row">
        <div class="form-group col-md-4">
            <i class="fas fa-city grey-text"></i>
            <label for="role">Distrito</label>
            <select class="form-control" id="selectListDistritos" name="distrito" required disabled>
                <script>
                    var itemSelectedDistritos = "<?php echo $id_distrito; ?>";
                    document.getElementById("selectListDistritos").selectedIndex = itemSelectedDistritos;                   
                </script> 
                <?php
                // connect to database
                $conn = pg_pconnect("host=52.47.199.255 dbname=teste user=ubuntu password=1234");
                if (!$conn) {
                    echo "An error occurred.\n";
                    exit;
                }
                // get all the uid from the uid column in users
                $result = pg_query($conn, "SELECT id_distrito,nome_distrito FROM distritos");
                if (!$result) {
                    // error message  
                    echo "An error occurred.\n";
                    exit;
                }
                // dispaly on screen all uid data from users
                while ($row = pg_fetch_row($result)) {
                    echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-4">
            <i class="fas fa-university grey-text"></i>
            <label for="role"> Concelho </label>
            <select class="form-control" name="concelho" id="selectListConcelhos" disabled>
                <script>
                    var itemSelectedConcelhos = "<?php echo $id_concelho; ?>";
                    document.getElementById("selectListConcelhos").selectedIndex = itemSelectedConcelho;                   
                </script> 
                <?php

                // get all the uid from the uid column in users
                $result = pg_query($conn, "SELECT id_concelho,nome_concelho FROM concelhos;");
                if (!$result) {
                    // error message  
                    echo "An error occurred.\n";
                    exit;
                }
                // dispaly on screen all uid data from users
                while ($row = pg_fetch_row($result)) {
                    echo '<option value="' . $row[0] . '">' . $row[1] . '</option>';
                }
                ?>
            </select>
            <script>
                document.querySelector("#selectListDistritos").selectedIndex = <?php echo $item; ?>;
            </script>
        </div>
        <div class='form-group col-md-4'>
            <i class="fas fa-calendar grey-text"></i>
            <label for="role"> Data/Hora </label>
            <div class='input-group date' id='datetimepicker1'>
                <input name="datahora" type='text' class="form-control" value="<?php echo $datahora; ?>" id="input-picker" readonly />
            </div>
        </div>
        <script type="text/javascript">
            var d = new Date();

            $("#input-picker").datetimepicker({
                format: 'DD-MM-YYYY HH:mm',
            });

            $(function() {
                $('#input-picker').datetimepicker();
            });
        </script>
    </div>
    <div class="form-row">
        <div class="form-group col-md-2">
            <i class="fas fa-skull-crossbones grey-text"></i>
            <label for="role">Nº Mortos</label>
            <input id="inputNMortos" class="form-control" type="number" name="nMortos" value="<?php echo $nMortos; ?>" min="0" max="20" />
        </div>
        <div class="form-group col-md-2">
            <i class="fas fa-user-injured grey-text"></i>
            <label for="role">Nº F. Graves</label>
            <input id="inputNFeridos" class="form-control" type="number" name="nFGraves" value="<?php echo $nFeridos; ?>" min="0" max="20" />
        </div>
        <div class="form-group col-md-2">
            <i class="fas fa-tachometer-alt grey-text"></i>
            <label for="role">Quilómetro</label>
            <input class="form-control" type="number" name="quilometro" value="<?php echo $km; ?>" min="0" max="1000" step="0.001" />
        </div>
        <div class="form-group col-md-6">
            <i class="fas fa-road grey-text"></i>
            <label for="role">Via</label>
            <select class="form-control" name="via" id="selectListVias" disabled>
                <script>
                    var itemSelectedVias = "<?php echo $via; ?>";
                    document.getElementById("selectListVias").value = itemSelectedVias;                   
                </script> 
                <?php

                // get all the uid from the uid column in users
                $result = pg_query($conn, "SELECT DISTINCT via FROM sinistros WHERE via IS NOT NULL");
                if (!$result) {
                    // error message  
                    echo "An error occurred.\n";
                    exit;
                }
                // dispaly on screen all uid data from users
                while ($row = pg_fetch_row($result)) {
                    echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                }
                ?>-->
            </select>
        </div>
        <div class="form-group col-md-6">
            <i class="fas fa-car-crash grey-text"></i>
            <label for="role">Natureza</label>
            <select class="form-control" name="natureza" id="selectListNatureza" required disabled>
                <script>
                    var itemSelectedNatureza = "<?php echo $natureza; ?>";
                    document.getElementById("selectListNatureza").value = itemSelectedNatureza;                   
                </script> 
                <?php

                // get all the uid from the uid column in users
                $result = pg_query($conn, "SELECT DISTINCT natureza FROM sinistros WHERE natureza IS NOT NULL");
                if (!$result) {
                    // error message  
                    echo "An error occurred.\n";
                    exit;
                }
                // dispaly on screen all uid data from users
                while ($row = pg_fetch_row($result)) {
                    echo '<option value="' . $row[0] . '">' . $row[0] . '</option>';
                }
                ?>
            </select>
        </div>
        <div class="form-group col-md-3">
            <i class="fas fa-location-arrow grey-text"></i>
            <label for="role">Latitude</label>
            <input class="form-control" type="number" name="lat" value="<?php echo $lat; ?>" min="-90" max="90" step="0.001" />
        </div>
        <div class="form-group col-md-3">
            <i class="fas fa-location-arrow grey-text"></i>
            <label for="role">Longitude</label>
            <input class="form-control" type="number" name="lon" value="<?php echo $lon; ?>" min="-180" max="180" step="0.001" />
        </div>
    </div>
    <div class="modal-footer d-flex justify-content-center">
        <button type="submit" class="btn btn-primary">Atualizar</button>
    </div>
</form>

<script>
    // Validação do formulário
    $('#formUpdate').submit(function() {
        nMortos = <?php echo $nMortos; ?>;
        nFeridos = <?php echo $nFeridos; ?>;
        
        nMortosUpdate = document.querySelector("#inputNMortos").value;
        nFeridosUpdate = document.querySelector("#inputNFeridos").value;

        // Manda mensagem se o n.º de mortos for inferior ao numero apresentado 
        if (nMortosUpdate < nMortos){
            alert("Atenção! O n.º de mortos tem de se manter igual ou superior ao valor atual.");
            // Repor valor
            document.querySelector("#inputNMortos").value = nMortos;
            return false;
        }

        // Calcula a diferença entre o n de fg atual e o n de fg p/ alteração
        difFeridos =  nFeridos - nFeridosUpdate;

        if (difFeridos >= 0){
            // Se há baixa de um FG, então tem de somar ao n de mortos
            if (nMortosUpdate == nMortos + difFeridos) {
                return true;
            } else {
                alert("Atenção! Dados inconsistentes na relação n.º mortos/n.º feridos. Verifique novamente os valores antes de continuar.");
                // Repor valores
                document.querySelector("#inputNMortos").value = nMortos;
                document.querySelector("#inputNFeridos").value = nFeridos;
                return false;
            }
        } else {
            alert("Atenção! O nº de feridos graves não pode ser superior ao valor atual.");
            // Repor valor
            document.querySelector("#inputNFeridos").value = nFeridos;
            return false;
        }

        return true;
    });
</script>