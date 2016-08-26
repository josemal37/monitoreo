<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Proyectos activos</title>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">Bienvenido coordinador</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <?php if ($proyectos): ?>
                <div class="table-responsive">
                    <h4>Lista de proyectos activos</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th width="20%">Nombre</th>
                                <th width="10%">Institución</th>
                                <th width="55%">Descripción</th>
                                <th width="15%">Ver proyecto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proyectos as $proyecto) : ?>
                                <tr>
                                    <td><?= $proyecto->nombre_proyecto ?></td>
                                    <td><?= $proyecto->nombre_institucion ?></td>
                                    <td><?= $proyecto->descripcion_proyecto ?></td>
                                    <td><a href="<?= base_url() . 'coordinador/ver_proyecto/' . $proyecto->id_proyecto ?>" class="btn btn-success btn-xs btn-block">Ver proyecto</a></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <h4>No se existen proyectos activos actualmente.</h4>
            <?php endif; ?>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    </body>
</html>