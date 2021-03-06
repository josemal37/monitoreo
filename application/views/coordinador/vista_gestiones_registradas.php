<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />

        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

        <title>Gestiones registradas</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Gestion actual";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <h4 class="text-primary">Gestiones registradas</h4>
            <?php if ($anios): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Gestiones registradas</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Gestión</th>
                                    <th>Activa</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($anios as $anio): ?>
                                    <tr>
                                        <td><?= $anio->valor_anio ?></td>
                                        <td>
                                            <?php if($anio->activo_anio): ?>
                                                SI
                                            <?php else: ?>
                                                NO
                                            <?php endif; ?>
                                        </td>
                                        <td width="15%">
                                            <a href="<?= base_url() . 'coordinador/activar_anio/' . $anio->id_anio?>" class="btn btn-success btn-xs btn-block">Establecer gestión activa</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Advertencia
                    </div>
                    <div class="panel-body">
                        Todavía no se habilitó el registro para ninguna gestión.
                    </div>
                </div>
            <?php endif; ?>
            <a href="<?= base_url() . 'coordinador/habilitar_registro_poa_gestion' ?>" class="btn btn-primary">Registrar nueva gestión</a>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.number_integer').number(true, 0);
            });
        </script>
    </body>
</html>