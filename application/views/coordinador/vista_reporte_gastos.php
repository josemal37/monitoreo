<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

        <title>Reporte de gastos</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Reportes";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <?php if ($proyectos): ?>
                <?php foreach ($proyectos as $proyecto): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" id="heading_<?= $proyecto->id_proyecto ?>">
                            <strong class="text-primary"><?= $proyecto->nombre_proyecto ?><span class="pull-right hidden-sm hidden-xs"><?= $proyecto->nombre_institucion ?></span></strong>
                        </div>
                        <div class="panel-body" id="body_<?= $proyecto->id_proyecto ?>">
                            <label class="hidden-lg hidden-md"><?= $proyecto->nombre_institucion ?></label>
                            <?php if (sizeof($proyecto->actividades) > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="15%">Fecha</th>
                                                <th>Concepto</th>
                                                <th width="15%">Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($proyecto->actividades as $actividad): ?>
                                                <tr>
                                                    <th colspan="3">Actividad: <?= $actividad->nombre_actividad ?></th>
                                                </tr>
                                                <?php if (sizeof($actividad->gastos) > 0): ?>
                                                    <?php foreach ($actividad->gastos as $gasto): ?>
                                                        <tr>
                                                            <td><?= $gasto->fecha_gasto_actividad ?></td>
                                                            <td><?= $gasto->concepto_gasto_actividad ?><a href="<?= base_url() . 'coordinador/descarga/' . $proyecto->id_institucion . '/' . $gasto->respaldo_gasto_actividad ?>" class="btn btn-success btn-xs pull-right">Ver respaldo</a></td>
                                                            <td>Bs. <span class="number_decimal"><?= $gasto->importe_gasto_actividad ?></span></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                <?php else: ?>
                                                    <tr>
                                                        <td colspan="3">No se registraron gastos</td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php else: ?>
                                No se registraron actividades.
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Advertencia
                    </div>
                    <div class="panel-body">
                        Actualmente no existen proyectos activos.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <script type="text/javascript">
            $('.number_decimal').number(true, 2);
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.panel-heading').click(function(){
                    var id_heading = $(this).attr('id');
                    var num_id = id_heading.substring(id_heading.length - 1, id_heading.length);
                    $('#body_' + num_id).toggle('swing');
                });
            });
        </script>
    </body>
</html>