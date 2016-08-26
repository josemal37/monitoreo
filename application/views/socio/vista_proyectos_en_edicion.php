<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Proyectos en edición</title>
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido socio</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <?php if ($proyectos): ?>
                <div class="table-responsive">
                    <h4>Lista de proyectos en edición</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="20%">Nombre</th>
                                <th width="65%">Descripción</th>
                                <th width="15%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proyectos as $proyecto) : ?>
                                <tr>
                                    <td><?= $proyecto->nombre_proyecto ?></td>
                                    <td><?= $proyecto->descripcion_proyecto ?></td>
                                    <td>
                                        <a href="<?= base_url() . 'socio/editar_proyecto/' . $proyecto->id_proyecto ?>" class="btn btn-success btn-xs btn-block">Editar proyecto</a>
                                        <a href="<?= base_url() . 'socio/terminar_edicion_proyecto/' . $proyecto->id_proyecto ?>" class="btn btn-primary btn-xs btn-block">Activar proyecto</a>
                                        <a href="<?= base_url() . 'socio/eliminar_proyecto/' . $proyecto->id_proyecto ?>" class="btn btn-danger btn-xs btn-block">Eliminar proyecto</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <h4>No existen proyectos en edición actualmente.</h4>
            <?php endif; ?>
            <a href="<?= base_url() . 'socio/registrar_nuevo_proyecto' ?>" class="btn btn-primary">Registrar nuevo proyecto</a>
        </div>	
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
    </body>
</html>