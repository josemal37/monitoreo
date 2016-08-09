<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido socio</h1>
            <?php
            $nombre_usuario = $this->session->userdata('nombre_usuario');
            $apellido_usuario = $this->session->userdata('apellido_usuario');
            $nombre_institucion = $this->session->userdata('nombre_institucion');
            $data = Array();
            $data['nombre_usuario'] = $nombre_usuario;
            $data['apellido_usuario'] = $apellido_usuario;
            $data['nombre_institucion'] = $nombre_institucion;
            $data['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $data);
            ?>
            <div>
                <h4><?= $datos_proyecto->nombre_proyecto ?> (Bs. <?= $datos_proyecto->presupuesto_proyecto ?>)</h4>
                <p><?= $datos_proyecto->descripcion_proyecto ?></p>
                <p class="text-left"><?= anchor('socio/modificar_proyecto/'.$datos_proyecto->id_proyecto, 'Modificar datos generales', Array('class' => 'btn btn-default'))?></p>
            </div>
            <div>
                <?php if (sizeof($datos_actividades) > 0): ?>
                    <h4>Actividades</h4>
                    <?php foreach ($datos_actividades as $actividad): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#collapse_<?=$actividad->id_actividad?>"><strong><?= $actividad->nombre_actividad ?></strong></a>
                            </div>
                            <div class="panel-collapse collapse in" id="collapse_<?=$actividad->id_actividad?>">
                                <div class="panel-body">
                                    <p><strong>Descripción: </strong><?= $actividad->descripcion_actividad ?></p>
                                    <p><strong>Fecha de inicio: </strong><?= $actividad->fecha_inicio_actividad ?></p>
                                    <p><strong>Fecha de fin: </strong><?= $actividad->fecha_fin_actividad ?></p>
                                    <div>
                                    <?= anchor(base_url('socio/modificar_actividad/'.$actividad->id_actividad), 'Modificar actividad', Array('class' => 'btn btn-default')) ?>
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
                                                            <td><?= anchor(base_url().'socio/modificar_indicador/'.$datos_proyecto->id_proyecto.'/'.$indicador_actividad->id_indicador_op, 'Modificar indicador', Array('class' => 'btn btn-default btn-xs')) ?></td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php else: ?>
                                        <h3>No se registraron indicadores</h3>
                                    <?php endif; ?>
                                    <?= anchor(base_url('socio/registrar_nuevo_indicador/'.$datos_proyecto->id_proyecto.'/'.$id_actividad), 'Registrar indicador operativo', Array('class' => 'btn btn-default')) ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <h2>No se registraron actividades</h2>
                <?php endif; ?>
            </div>
            <?= anchor(base_url('socio/registrar_nueva_actividad/' . $datos_proyecto->id_proyecto), 'Registrar actividad', Array('class' => 'btn btn-default')) ?>
        </div>
        <div class="container footer">
            <p class="text-center text-muted">Sistema de monitoreo</p>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    </body>
</html>
