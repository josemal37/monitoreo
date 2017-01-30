<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>
        
        <title>Proyectos registrados</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Reportes";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <h4 class="text-primary">Proyectos registrados</h4>
            <?php if ($proyectos): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>Proyectos</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="15%">Nombre</th>
                                <th width="30%">Descripción</th>
                                <th width="10%">Institución</th>
                                <th width="15%">Presupuesto (Bs.)</th>
                                <th width="15%">Presupuesto disponible (Bs.)</th>
                                <th width="15%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proyectos as $proyecto) : ?>
                                <tr>
                                    <td><?= $proyecto->nombre_proyecto_global ?></td>
                                    <td><?= $proyecto->descripcion_proyecto_global ?></td>
                                    <td><?= $proyecto->nombre_institucion ?></td>
                                    <td><span class="number_decimal"><?= $proyecto->presupuesto_proyecto_global ?></span></td>
                                    <td><span class="number_decimal"><?= $proyecto->presupuesto_disponible ?></span></td>
                                    <td>
                                        <a href="<?= base_url() . 'coordinador/ver_reporte_proyecto_global/' . $proyecto->id_proyecto_global ?>" class="btn btn-success btn-xs btn-block">Ver POA's del proyecto</a>
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
                        Todavía no se registraron proyectos.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.number_decimal').number(true, 2);
            });
        </script>
    </body>
</html>