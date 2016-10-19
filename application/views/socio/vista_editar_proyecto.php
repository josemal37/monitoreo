<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

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
                <h4><?= $datos_proyecto->nombre_proyecto ?></h4>
                <p class="text-justify"><strong>Año:</strong> <span class="number_integer"><?= $datos_proyecto->valor_anio ?></span></p>
                <p class="text-justify"><strong>Presupuesto:</strong> Bs. <span class="number_decimal"><?= $datos_proyecto->presupuesto_proyecto ?></span></p>
                <p class="text-justify"><strong>Descripción:</strong> <?= $datos_proyecto->descripcion_proyecto ?></p>
                <p class="text-left"><a href="<?= base_url() . 'socio/modificar_proyecto/' . $datos_proyecto->id_proyecto ?>" class="btn btn-default">Modificar datos generales</a></p>
            </div>
            <div>
                <?php if ($this->session->flashdata('error_sin_productos')): ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>¡Error de registro!</strong> <?= $this->session->flashdata('error_sin_productos') ?>
                    </div>
                <?php endif; ?>
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
                                    <p><strong>Presupuesto: </strong>Bs. <span class="number_decimal"><?= $actividad->presupuesto_actividad ?></span></p>
                                    <?php if (isset($actividad->nombre_producto)): ?>
                                        <p><strong>Producto asociado: </strong><?= $actividad->nombre_producto ?></p>
                                    <?php endif; ?>
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
                                                <strong>Metas</strong>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre de la meta</th>
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
                                                                        <a href="<?= base_url() . 'socio/modificar_hito_cuantitativo/' . $datos_proyecto->id_proyecto . '/' . $hito_cuantitativo->id_hito_cn ?>" class="btn btn-default btn-xs btn-block">Modificar meta</a>
                                                                        <a href="<?= base_url() . 'socio/eliminar_hito_cuantitativo/' . $datos_proyecto->id_proyecto . '/' . $hito_cuantitativo->id_hito_cn ?>" class="btn btn-danger btn-xs btn-block">Eliminar meta</a>
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
                                                                        <a href="<?= base_url() . 'socio/modificar_hito_cualitativo/' . $datos_proyecto->id_proyecto . '/' . $hito_cualitativo->id_hito_cl ?>" class="btn btn-default btn-xs btn-block">Modificar meta</a>
                                                                        <a href="<?= base_url() . 'socio/eliminar_hito_cualitativo/' . $datos_proyecto->id_proyecto . '/' . $hito_cualitativo->id_hito_cl ?>" class="btn btn-danger btn-xs btn-block">Eliminar meta</a>
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
                                                Todavía no se registraron metas.
                                            </div>
                                        </div>
                                    <?php endif; ?>    
                                    <a href="<?= base_url() . 'socio/registrar_nuevo_hito/' . $datos_proyecto->id_proyecto . '/' . $id_actividad ?>" class="btn btn-default">Registrar nueva meta</a>
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
            <p>
                <a href="<?= base_url() . 'socio/registrar_nueva_actividad/' . $datos_proyecto->id_proyecto ?>" class="btn btn-default">Registrar nueva actividad</a>
            </p>
            <div>
                <p class="text-right">
                    <a href="<?= base_url() . 'socio/terminar_edicion_proyecto/' . $datos_proyecto->id_proyecto ?>" class="btn btn-primary">Activar proyecto</a>
                    <a href="<?= base_url() . 'socio/eliminar_proyecto/' . $datos_proyecto->id_proyecto ?>" class="btn btn-danger">Eliminar proyecto</a>
                </p>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.number_decimal').number(true, 2);
                $('.number_integer').number(true);
            });
        </script>
    </body>
</html>
