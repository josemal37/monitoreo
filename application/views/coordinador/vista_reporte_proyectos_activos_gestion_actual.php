<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
        
        <title>POA's gestión actual</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Reportes";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <h4 class="text-primary">POA's gestión actual</h4>
            <?php if ($proyectos): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>POA's gestión actual</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Año</th>
                                <th>Institución</th>
                                <th>Nombre</th>
                                <th width='15%'>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proyectos as $proyecto) : ?>
                                <tr>
                                    <td><?= $proyecto->valor_anio ?></td>
                                    <td><?= $proyecto->sigla_institucion ?></td>
                                    <td><?= $proyecto->nombre_proyecto ?></td>
                                    <td><a href="<?= base_url() . 'coordinador/ver_reporte_poa/' . $proyecto->id_proyecto ?>" class="btn btn-success btn-xs btn-block">Ver reporte POA</a></td>
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
                        No existen POA's activos actualmente.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>