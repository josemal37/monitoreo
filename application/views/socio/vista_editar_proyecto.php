<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Editar proyecto</title>
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido socio</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <div>
                <h4><?= $datos_proyecto->nombre_proyecto ?> (Bs. <?= $datos_proyecto->presupuesto_proyecto ?>)</h4>
                <p class="text-justify"><?= $datos_proyecto->descripcion_proyecto ?></p>
                <p class="text-left"><a href="<?= base_url() . 'socio/modificar_proyecto/' . $datos_proyecto->id_proyecto ?>" class="btn btn-default">Modificar datos generales</a></p>
            </div>
            <div>
                <?php if (sizeof($datos_actividades) > 0): ?>
                    <h4>Actividades</h4>
                    <?php foreach ($datos_actividades as $actividad): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#collapse_<?= $actividad->id_actividad ?>"><strong><?= $actividad->nombre_actividad ?></strong></a>
                            </div>
                            <div class="panel-collapse collapse in" id="collapse_<?= $actividad->id_actividad ?>">
                                <div class="panel-body">
                                    <p class="text-justify"><strong>Descripción: </strong><?= $actividad->descripcion_actividad ?></p>
                                    <p><strong>Fecha de inicio: </strong><?= $actividad->fecha_inicio_actividad ?></p>
                                    <p><strong>Fecha de fin: </strong><?= $actividad->fecha_fin_actividad ?></p>
                                    <p><strong>Presupuesto: </strong><?= $actividad->presupuesto_actividad ?></p>
                                    <div>
                                        <a href="<?= base_url() . 'socio/modificar_actividad/' . $actividad->id_actividad ?>" class="btn btn-default">Modificar actividad</a>
                                        <a href="<?= base_url() . 'socio/eliminar_actividad/' . $datos_proyecto->id_proyecto . '/' . $actividad->id_actividad ?>" class="btn btn-danger">Eliminar actividad</a>
                                    </div>
                                    <br>
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
                                                            <th>Nombre del indicador</th>
                                                            <th>Tipo de indicador</th>
                                                            <th>Meta del indicador</th>
                                                            <th>Valor aceptable</th>
                                                            <th>Valor limitado</th>
                                                            <th>Valor no aceptable</th>
                                                            <th>Fecha limite</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($indicadores_actividad as $indicador_actividad): ?>
                                                            <tr>
                                                                <td><?= $indicador_actividad->nombre_indicador_op ?></td>
                                                                <td><?= $indicador_actividad->nombre_tipo_indicador_op ?></td>
                                                                <td><?= $indicador_actividad->meta_op ?></td>
                                                                <td><?= $indicador_actividad->aceptable_op ?></td>
                                                                <td><?= $indicador_actividad->limitado_op ?></td>
                                                                <td><?= $indicador_actividad->no_aceptable_op ?></td>
                                                                <td><?= $indicador_actividad->fecha_limite_indicador_op ?></td>
                                                                <td>
                                                                    <a href="<?= base_url() . 'socio/modificar_indicador_operativo/' . $datos_proyecto->id_proyecto . '/' . $indicador_actividad->id_indicador_op ?>" class="btn btn-default btn-xs btn-block">Modificar indicador</a>
                                                                    <a href="<?= base_url() . 'socio/eliminar_indicador_operativo/' . $datos_proyecto->id_proyecto . '/' . $indicador_actividad->id_indicador_op ?>" class="btn btn-danger btn-xs btn-block">Eliminar indicador</a>
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
                                    <a href="<?= base_url() . 'socio/registrar_nuevo_indicador/' . $datos_proyecto->id_proyecto . '/' . $id_actividad ?>" class="btn btn-default">Registrar indicador operativo</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h2>No se registraron actividades</h2>
                <?php endif; ?>
            </div>
            <a href="<?= base_url() . 'socio/registrar_nueva_actividad/' . $datos_proyecto->id_proyecto ?>" class="btn btn-default">Registrar actividad</a>
            <div>
                <p class="text-right">
                    <a href="<?= base_url() . 'socio/terminar_edicion_proyecto/' . $datos_proyecto->id_proyecto ?>" class="btn btn-primary">Activar proyecto</a>
                    <a href="<?= base_url() . 'socio/eliminar_proyecto/' . $datos_proyecto->id_proyecto ?>" class="btn btn-danger">Eliminar proyecto</a>
                </p>
            </div>
        </div>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
    </body>
</html>
