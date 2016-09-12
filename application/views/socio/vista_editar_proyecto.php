<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Editar proyecto</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
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
                                    <p><strong>Presupuesto: </strong>Bs. <?= $actividad->presupuesto_actividad ?></p>
                                    <div>
                                        <a href="<?= base_url() . 'socio/modificar_actividad/' . $actividad->id_actividad ?>" class="btn btn-default">Modificar actividad</a>
                                        <a href="<?= base_url() . 'socio/eliminar_actividad/' . $datos_proyecto->id_proyecto . '/' . $actividad->id_actividad ?>" class="btn btn-danger">Eliminar actividad</a>
                                    </div>
                                    <br>
                                    <?php
                                    $id_actividad = $actividad->id_actividad;
                                    $hitos_cuantitativos = $datos_hitos_cuantitativos[$actividad->nombre_actividad];
                                    $hitos_cualitativos = $datos_hitos_cualitativos[$actividad->nombre_actividad];
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
                                                            <th>Acciones</th>
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
                                                                    <td width="15%">
                                                                        <a href="<?= base_url() . 'socio/modificar_hito_cuantitativo/' . $datos_proyecto->id_proyecto . '/' . $hito_cuantitativo->id_hito_cn ?>" class="btn btn-default btn-xs btn-block">Modificar hito</a>
                                                                        <a href="<?= base_url() . 'socio/eliminar_hito_cuantitativo/' . $datos_proyecto->id_proyecto . '/' . $hito_cuantitativo->id_hito_cn ?>" class="btn btn-danger btn-xs btn-block">Eliminar hito</a>
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
                                                                        <a href="<?= base_url() . 'socio/modificar_hito_cualitativo/' . $datos_proyecto->id_proyecto . '/' . $hito_cualitativo->id_hito_cl ?>" class="btn btn-default btn-xs btn-block">Modificar hito</a>
                                                                        <a href="<?= base_url() . 'socio/eliminar_hito_cualitativo/' . $datos_proyecto->id_proyecto . '/' . $hito_cualitativo->id_hito_cl ?>" class="btn btn-danger btn-xs btn-block">Eliminar hito</a>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        <?php endif; ?>
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
                                                Todavía no se registraron hitos.
                                            </div>
                                        </div>
                                    <?php endif; ?>    
                                    <a href="<?= base_url() . 'socio/registrar_nuevo_hito/' . $datos_proyecto->id_proyecto . '/' . $id_actividad ?>" class="btn btn-default">Registrar nuevo hito</a>
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
                            Todavía no se registraron actividades.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <a href="<?= base_url() . 'socio/registrar_nueva_actividad/' . $datos_proyecto->id_proyecto ?>" class="btn btn-default">Registrar nueva actividad</a>
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
