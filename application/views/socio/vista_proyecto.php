<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Proyecto</title>
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido socio</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('socio/nav', $datos);
            ?>
            <div>
                <h4><?= $datos_proyecto->nombre_proyecto ?> (Bs. <?= $datos_proyecto->presupuesto_proyecto ?>)</h4>
                <p><?= $datos_proyecto->descripcion_proyecto ?></p>
            </div>
            <div>
                <?php if (sizeof($datos_actividades) > 0): ?>
                    <h4>Actividades</h4>
                    <?php foreach ($datos_actividades as $actividad): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a class="text-primary" data-toggle="collapse" href="#collapse_<?= $actividad->id_actividad ?>"><strong><?= $actividad->nombre_actividad ?></strong></a>
                            </div>
                            <div class="panel-collapse collapse in" id="collapse_<?= $actividad->id_actividad ?>">
                                <div class="panel-body">
                                    <p><?= $actividad->descripcion_actividad ?></p>
                                    <?php
                                    $id_actividad = $actividad->id_actividad;
                                    $indicadores_actividad = $datos_indicadores[$actividad->nombre_actividad];
                                    ?>
                                    <?php if (sizeof($indicadores_actividad) > 0): ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <strong>Indicadores operativos</strong>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th width="65%">Nombre del indicador</th>
                                                            <th width="20%">Estado</th>
                                                            <th width="15%">Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($indicadores_actividad as $indicador_actividad): ?>
                                                            <?php
                                                            $color_indicador = 'ffffff';
                                                            switch ($indicador_actividad->estado_indicador_op) {
                                                                case 'Aceptable':
                                                                    $color_indicador = "CDFDC3";
                                                                    break;
                                                                case 'Limitado':
                                                                    $color_indicador = "FDFCBF";
                                                                    break;
                                                                case 'No aceptable':
                                                                    $color_indicador = "FDBFBF";
                                                                    break;
                                                            }
                                                            ?>
                                                            <tr bgColor = "#<?= $color_indicador ?>">
                                                                <td><?= $indicador_actividad->nombre_indicador_op ?></td>
                                                                <td><?= $indicador_actividad->estado_indicador_op ?></td>
                                                                <td>
                                                                    <a href="<?= base_url() . 'socio/ver_avance_indicador_operativo/' . $datos_proyecto->id_proyecto . '/' . $indicador_actividad->id_indicador_op ?>" class="btn btn-success btn-xs btn-block">Ver avances</a>
                                                                    <a href="<?= base_url() . 'socio/registrar_avance_indicador_operativo/' . $datos_proyecto->id_proyecto . '/' . $indicador_actividad->id_indicador_op ?>" class="btn btn-primary btn-xs btn-block">Registrar avance</a>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <h3>No se registraron indicadores</h3>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h4>No se registraron actividades</h4>
                <?php endif; ?>
            </div>
        </div>	
        <script type="text/javascript" src="<?= base_url() . "assets/js/jquery-3.1.0.min.js" ?>"></script>
        <script type="text/javascript" src="<?= base_url() . "assets/js/bootstrap.js" ?>"></script>
    </body>
</html>
