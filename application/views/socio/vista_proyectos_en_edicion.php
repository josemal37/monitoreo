<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        
        <title>Proyectos en edición</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <?php if ($proyectos): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Lista de POA's en edición</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="10%">Año</th>
                                    <th width="20%">Nombre</th>
                                    <th width="55%">Descripción</th>
                                    <th width="15%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($proyectos as $proyecto) : ?>
                                    <tr>
                                        <td><?= $proyecto->valor_anio ?></td>
                                        <td><?= $proyecto->nombre_proyecto ?></td>
                                        <td><?= $proyecto->descripcion_proyecto ?></td>
                                        <td>
                                            <a href="<?= base_url() . 'socio/editar_proyecto/' . $proyecto->id_proyecto ?>" class="btn btn-success btn-xs btn-block">Editar POA</a>
                                            <a href="<?= base_url() . 'socio/terminar_edicion_proyecto/' . $proyecto->id_proyecto ?>" class="btn btn-primary btn-xs btn-block">Activar POA</a>
                                            <a href="<?= base_url() . 'socio/eliminar_proyecto/' . $proyecto->id_proyecto ?>" class="btn btn-danger btn-xs btn-block">Eliminar POA</a>
                                        </td>
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
                        No existen POA's en edición actualmente.
                    </div>
                </div>
            <?php endif; ?>
            <a href="<?= base_url() . 'socio/registrar_nuevo_proyecto' ?>" class="btn btn-primary">Registrar nuevo POA</a>
        </div>
    </body>
</html>