<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>
        
        <title>Reformular proyecto</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('socio/nav', $datos);
            ?>
            <div>
                <h4 class="text-primary"><?= $proyecto->nombre_proyecto ?></h4>
                <p class="text-justify"><strong>Año:</strong> <span class="number_integer"><?= $proyecto->valor_anio ?></span></p>
                <p class="text-justify"><strong>Presupuesto:</strong> Bs. <span class="number_decimal"><?= $proyecto->presupuesto_proyecto ?></span></p>
                <p class="text-justify"><strong>Descripción:</strong> <?= $proyecto->descripcion_proyecto ?></p>
            </div>
            <div>
                <?php if (sizeof($proyecto->actividades) > 0): ?>
                    <h4 class="text-primary">Actividades en reformulación</h4>
                    <?php foreach ($proyecto->actividades as $actividad): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#collapse_<?= $actividad->id_actividad ?>"><strong><?= $actividad->nombre_actividad ?></strong></a>
                            </div>
                            <div class="panel-collapse collapse on" id="collapse_<?= $actividad->id_actividad ?>">
                                <div class="panel-body">
                                    <p class="text-justify"><strong>Descripción: </strong><?= $actividad->descripcion_actividad ?></p>
                                    <p><strong>Fecha de inicio: </strong><?= $actividad->fecha_inicio_actividad ?></p>
                                    <p><strong>Fecha de fin: </strong><?= $actividad->fecha_fin_actividad ?></p>
                                    <p>
                                        <strong>Presupuesto: </strong>
                                        Bs. <span class="number_decimal"><?= $actividad->presupuesto_actividad ?></span>
                                        <?php if($actividad->contraparte_actividad): ?>
                                            (contraparte)
                                        <?php endif; ?>
                                    </p>
                                    <?php if(isset($actividad->nombre_producto)): ?>
                                        <p><strong>Producto asociado: </strong><?= $actividad->nombre_producto ?></p>
                                    <?php endif; ?>
                                    <p>
                                        <a href="<?= base_url() . 'socio/reformular_actividad/' . $actividad->id_actividad ?>" class="btn btn-default">Modificar actividad</a>
                                    </p>
                                    <?php
                                    $id_actividad = $actividad->id_actividad;
                                    $hitos_cuantitativos = $actividad->hitos_cuantitativos;
                                    $hitos_cualitativos = $actividad->hitos_cualitativos;
                                    //$gastos_actividad = $datos_gastos_actividad[$actividad->nombre_actividad];
                                    $indicadores_cuantitativos = Array();
                                    $indicadores_cualitativos = Array();
                                    $gastos_actividad = false;
                                    $finalizado = true;
                                    ?>
                                    <?php if ((sizeof($hitos_cuantitativos) + sizeof($hitos_cualitativos)) > 0): ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <strong>Indicadores</strong>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre del indicador</th>
                                                            <th>Descripción</th>
                                                            <th>Meta</th>
                                                            <th>Meta asociada</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (sizeof($hitos_cuantitativos) > 0): ?>
                                                            <?php foreach ($hitos_cuantitativos as $hito_cuantitativo): ?>
                                                                <tr>
                                                                    <td><?= $hito_cuantitativo->nombre_hito_cn ?></td>
                                                                    <td><?= $hito_cuantitativo->descripcion_hito_cn ?></td>
                                                                    <td><span class="number_integer"><?= $hito_cuantitativo->meta_hito_cn ?></span> <?= $hito_cuantitativo->unidad_hito_cn ?></td>
                                                                    <td>
                                                                        <?php if (isset($hito_cuantitativo->id_meta_producto_cuantitativa)): ?>
                                                                            <span class="number_integer"><?= $hito_cuantitativo->cantidad_meta_producto_cuantitativa ?></span> <?= $hito_cuantitativo->unidad_meta_producto_cuantitativa ?>
                                                                        <?php else: ?>
                                                                            -----
                                                                        <?php endif; ?>
                                                                    </td>
                                                                    <td width="15%">
                                                                        <a href="<?= base_url() . 'socio/reformular_hito_cuantitativo/' . $proyecto->id_proyecto . '/' . $hito_cuantitativo->id_hito_cn ?>" class="btn btn-default btn-xs btn-block">Modificar indicador</a>
                                                                        <?php if($hito_cuantitativo->numero_avances_cn == 0): ?>
                                                                            <a href="<?= base_url() . 'socio/eliminar_hito_cuantitativo_reformulado/' . $proyecto->id_proyecto . '/' . $hito_cuantitativo->id_hito_cn ?>" class="btn btn-danger btn-xs btn-block">Eliminar indicador</a>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                        <?php if (sizeof($hitos_cualitativos) > 0): ?>
                                                            <?php foreach ($hitos_cualitativos as $hito_cualitativo): ?>
                                                                <tr>
                                                                    <td><?= $hito_cualitativo->nombre_hito_cl ?></td>
                                                                    <td><?= $hito_cualitativo->descripcion_hito_cl ?></td>
                                                                    <td>-----</td>
                                                                    <td>-----</td>
                                                                    <td width="15%">
                                                                        <a href="<?= base_url() . 'socio/reformular_hito_cualitativo/' . $proyecto->id_proyecto . '/' . $hito_cualitativo->id_hito_cl ?>" class="btn btn-default btn-xs btn-block">Modificar indicador</a>
                                                                        <?php if($hito_cualitativo->numero_avances_cl == 0): ?>
                                                                            <a href="<?= base_url() . 'socio/eliminar_hito_cualitativo_reformulado/' . $proyecto->id_proyecto . '/' . $hito_cualitativo->id_hito_cl ?>" class="btn btn-danger btn-xs btn-block">Eliminar indicador</a>
                                                                        <?php endif; ?>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <?php $finalizado = false; ?>
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">
                                                Advertencia
                                            </div>
                                            <div class="panel-body">
                                                No se registraron indicadores.
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <p>
                                        <a href="<?= base_url() . 'socio/registrar_nuevo_hito_reformulado/' . $proyecto->id_proyecto . '/' . $id_actividad ?>" class="btn btn-default">Registrar nuevo indicador</a>
                                    </p>
                                    <?php if ($gastos_actividad): ?>
                                        <div class="panel panel-default hidden">
                                            <div class="panel-heading">
                                                <strong>Gastos actividad</strong>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Concepto</th>
                                                            <th>Importe (Bs.)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($gastos_actividad as $gasto_actividad): ?>
                                                            <tr>
                                                                <td><?= $gasto_actividad->fecha_gasto_actividad ?></td>
                                                                <td><?= $gasto_actividad->concepto_gasto_actividad ?><a href="<?= base_url() . 'socio/descarga/' . $gasto_actividad->respaldo_gasto_actividad ?>" class="btn btn-success btn-xs pull-right">Ver respaldo</a></td>
                                                                <td><span class="number_decimal"><?= $gasto_actividad->importe_gasto_actividad ?></span></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="panel panel-warning hidden">
                                            <div class="panel-heading">
                                                Advertencia
                                            </div>
                                            <div class="panel-body">
                                                Todavía no se registraron gastos.
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <a href="<?= base_url() . 'socio/registrar_gastos_actividad/' . $proyecto->id_proyecto . '/' . $actividad->id_actividad ?>" class="btn btn-default hidden">Registrar gastos actividad</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="panel panel-warning">
                    <div class="panel-heading">
                        Advertencia
                    </div>
                    <div class="panel-body">
                        No se registraron actividades.
                    </div>
                </div>
                <?php endif; ?>
                <?php if(!$finalizado): ?>
                    <div class="alert alert-warning alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>¡Advertencia!</strong> Cada actividad debe tener al menos un indicador para terminar la reformulación del POA.
                    </div>
                <?php endif; ?>
                <p>
                    <a href="<?= base_url() . 'socio/terminar_reformulacion_proyecto/' . $proyecto->id_proyecto ?>" class="btn btn-primary <?php if (!$finalizado): ?>disabled<?php endif; ?>">Terminar reformulación</a>
                </p>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                $('.number_decimal').number(true, 2);
                $('.number_integer').number(true);
            });
        </script>
    </body>
</html>
