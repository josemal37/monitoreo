<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        
        <title>Ver proyecto</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <div>
                <h4 class="text-justify"><?= $datos_proyecto->nombre_proyecto ?> (Bs. <?= $datos_proyecto->presupuesto_proyecto ?>)<span class="pull-right hidden-sm hidden-xs"><?= $datos_proyecto->sigla_institucion ?></span></h4>
                <p class="text-justify"><?= $datos_proyecto->descripcion_proyecto ?></p>
            </div>
            <div>
                <?php if (sizeof($datos_actividades) > 0): ?>
                    <h4>Actividades</h4>
                    <?php foreach ($datos_actividades as $actividad): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#collapse_<?= $actividad->id_actividad ?>"><strong><?= $actividad->nombre_actividad ?></strong></a>
                            </div>
                            <div class="panel-collapse collapse on" id="collapse_<?= $actividad->id_actividad ?>">
                                <div class="panel-body">
                                    <p class="text-justify"><strong>Descripción: </strong><?= $actividad->descripcion_actividad ?></p>
                                    <p><strong>Fecha de inicio: </strong><?= $actividad->fecha_inicio_actividad ?></p>
                                    <p><strong>Fecha de fin: </strong><?= $actividad->fecha_fin_actividad ?></p>
                                    <p><strong>Presupuesto: </strong>Bs. <?= $actividad->presupuesto_actividad ?></p>
                                    <?php
                                    $id_actividad = $actividad->id_actividad;
                                    $hitos_cuantitativos = $datos_hitos_cuantitativos[$actividad->nombre_actividad];
                                    $hitos_cualitativos = $datos_hitos_cualitativos[$actividad->nombre_actividad];
                                    $indicadores_cuantitativos = $datos_indicadores_cuantitativos[$actividad->nombre_actividad];
                                    $indicadores_cualitativos = $datos_indicadores_cualitativos[$actividad->nombre_actividad];
                                    $gastos_actividad = $datos_gastos_actividad[$actividad->nombre_actividad];
                                    ?>
                                    <?php if ((sizeof($hitos_cuantitativos) + sizeof($hitos_cualitativos)) > 0): ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <strong>Hitos</strong>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre del hito</th>
                                                            <th>Descripción</th>
                                                            <th>Meta</th>
                                                            <th>Unidad</th>
                                                            <th width="15%">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php if (sizeof($hitos_cuantitativos) > 0): ?>
                                                            <?php foreach ($hitos_cuantitativos as $hito_cuantitativo): ?>
                                                                <tr>
                                                                    <td><?= $hito_cuantitativo->nombre_hito_cn ?></td>
                                                                    <td><?= $hito_cuantitativo->descripcion_hito_cn ?></td>
                                                                    <td><?= $hito_cuantitativo->meta_hito_cn ?></td>
                                                                    <td><?= $hito_cuantitativo->unidad_hito_cn ?></td>
                                                                    <td>
                                                                        <a href="<?= base_url() . 'coordinador/registrar_indicador_cuantitativo/' . $datos_proyecto->id_proyecto . '/' . $hito_cuantitativo->id_hito_cn ?>" class="btn btn-primary btn-xs btn-block">Registrar indicador</a>
                                                                        <a href="<?= base_url() . 'coordinador/ver_avances_hito_cuantitativo/' . $datos_proyecto->id_institucion . '/' . $datos_proyecto->id_proyecto . '/' . $hito_cuantitativo->id_hito_cn ?>" class="btn btn-success btn-xs btn-block">Ver avances</a>
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
                                                                    <td>
                                                                        <a href="<?= base_url() . 'coordinador/ver_avances_hito_cualitativo/' . $datos_proyecto->id_institucion . '/' . $datos_proyecto->id_proyecto . '/' . $hito_cualitativo->id_hito_cl ?>" class="btn btn-success btn-xs btn-block">Ver avances</a>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <?php if (sizeof($indicadores_cualitativos) + sizeof($indicadores_cuantitativos) > 0): ?>
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <strong>Indicadores operativos</strong>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Nombre del indicador</th>
                                                                <th width="15%">Estado</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($indicadores_cuantitativos as $indicador_cuantitativo): ?>
                                                                <?php
                                                                $color = 'FFFFFF';
                                                                if ($indicador_cuantitativo->estado_indicador_cn == $this->modelo_indicador->get_no_aceptable()) {
                                                                    $color = 'FDBFBF';
                                                                } else {
                                                                    if ($indicador_cuantitativo->estado_indicador_cn == $this->modelo_indicador->get_limitado()) {
                                                                        $color = 'FDFCBF';
                                                                    } else {
                                                                        if ($indicador_cuantitativo->estado_indicador_cn == $this->modelo_indicador->get_aceptable()) {
                                                                            $color = 'CDFDC3';
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <tr bgcolor="#<?= $color ?>">
                                                                    <td><?= $indicador_cuantitativo->nombre_indicador_cn ?></td>
                                                                    <td><?= $indicador_cuantitativo->estado_indicador_cn ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                            <?php foreach ($indicadores_cualitativos as $indicador_cualitativo): ?>
                                                                <?php
                                                                $color = 'FFFFFF';
                                                                if ($indicador_cualitativo['estado_indicador_cualitativo'] == $this->modelo_indicador->get_no_aceptable()) {
                                                                    $color = 'FDBFBF';
                                                                } else {
                                                                    if ($indicador_cualitativo['estado_indicador_cualitativo'] == $this->modelo_indicador->get_limitado()) {
                                                                        $color = 'FDFCBF';
                                                                    } else {
                                                                        if ($indicador_cualitativo['estado_indicador_cualitativo'] == $this->modelo_indicador->get_aceptable()) {
                                                                            $color = 'CDFDC3';
                                                                        }
                                                                    }
                                                                }
                                                                ?>
                                                                <tr bgcolor="#<?= $color ?>">
                                                                    <td><?= $indicador_cualitativo['nombre_indicador_cualitativo'] ?></td>
                                                                    <td><?= $indicador_cualitativo['estado_indicador_cualitativo'] ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php else : ?>
                                            <div class="panel panel-warning">
                                                <div class="panel-heading">
                                                    Advertencia
                                                </div>
                                                <div class="panel-body">
                                                    Todavía no se registraron indicadores.
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <div class="panel panel-warning">
                                            <div class="panel-heading">
                                                Advertencia
                                            </div>
                                            <div class="panel-body">
                                                No se registraron hitos.
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <?php if ($gastos_actividad): ?>
                                        <div class="panel panel-default hidden">
                                            <div class="panel-heading">
                                                <strong>Gastos actividad</strong>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="15%">Fecha</th>
                                                            <th>Concepto</th>
                                                            <th width="15%">Importe (Bs.)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($gastos_actividad as $gasto_actividad): ?>
                                                            <tr>
                                                                <td><?= $gasto_actividad->fecha_gasto_actividad ?></td>
                                                                <td><?= $gasto_actividad->concepto_gasto_actividad ?><a href="<?= base_url() . 'coordinador/descarga/' . $datos_proyecto->id_institucion . '/' . $gasto_actividad->respaldo_gasto_actividad ?>" class="btn btn-success btn-xs pull-right">Ver respaldo</a></td>
                                                                <td><?= $gasto_actividad->importe_gasto_actividad ?></td>
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
            </div>
        </div>
    </body>
</html>
