<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

        <title>Proyecto</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos";
            $this->load->view('financiador/nav', $datos);
            ?>
            <h4 class="text-primary"><?= $proyecto_global->nombre_proyecto_global ?></h4>
            <p class="text-justify"><strong>Presupuesto:</strong> Bs. <span class="number_decimal"><?= $proyecto_global->presupuesto_proyecto_global ?></span></p>
            <p class="text-justify"><strong>Institución:</strong> <?= $proyecto_global->nombre_institucion ?></p>
            <p class="text-justify"><strong>Descripción:</strong> <?= $proyecto_global->descripcion_proyecto_global ?></p>
            <?php if($proyecto_global->proyectos): ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong>POA's registrados</strong>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Año</th>
                                <th>Nombre</th>
                                <th>Presupuesto (Bs.)</th>
                                <th width='15%'>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proyecto_global->proyectos as $proyecto): ?>
                                <tr>
                                    <td><span class="number_integer"><?= $proyecto->valor_anio ?></span></td>
                                    <td><?= $proyecto->nombre_proyecto ?></td>
                                    <td><span class="number_decimal"><?= $proyecto->presupuesto_proyecto ?></span></td>
                                    <td><a href="<?= base_url() . 'financiador/ver_poa/' . $proyecto->id_proyecto ?>" class="btn btn-success btn-xs btn-block">Ver POA</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php else: ?>
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <strong>Advertencia</strong>
                </div>
                <div class="panel-body">
                    La institución todavía no registró ningún POA.
                </div>
            </div>
            <?php endif; ?>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.number_decimal').number(true, 2);
                $('.number_integer').number(true, 0);
            });
        </script>
    </body>
</html>