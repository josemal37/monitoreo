<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>

        <title>Reportes</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Reportes";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Reportes</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="hidden">
                                <td>Reporte de gastos</td>
                                <td>Un reporte que muestra los gastos realizados por los socios en el desarrollo de sus proyectos.</td>
                                <td><a href="<?= base_url() . 'coordinador/reporte_gastos' ?>" class="btn btn-success btn-xs btn-block">Ver reporte</a></td>
                            </tr>
                            <tr>
                                <td>Reporte del estado del proyecto</td>
                                <td>Un reporte que muestra el estado actual del proyecto.</td>
                                <td><a href="<?= base_url() . 'coordinador/reporte_estado_actual_proyecto' ?>" class="btn btn-success btn-xs btn-block">Ver reporte</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </body>
</html>