<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
        
        <title>Proyectos activos</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Gestion actual";
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
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Advertencia
                    </div>
                    <div class="panel-body">
                        Todavía no se registraron proyectos para esta gestión.
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </body>
</html>