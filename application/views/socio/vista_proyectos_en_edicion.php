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
            <?php if($proyectos): ?>
                <div class="table-responsive">
                <h4>Lista de proyectos en edición</h4>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripcion</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proyectos as $proyecto) : ?>
                            <tr>
                                <td><?= $proyecto->nombre_proyecto ?></td>
                                <td><?= $proyecto->descripcion_proyecto ?></td>
                                <td>
                                    <?= anchor(base_url() . 'socio/editar_proyecto/' . $proyecto->id_proyecto, 'Editar proyecto',Array('class'=>'btn btn-default btn-xs btn-block')) ?>
                                    <?= anchor('socio/terminar_edicion_proyecto/'.$proyecto->id_proyecto, 'Activar proyecto', Array('class' => 'btn btn-primary btn-xs btn-block'))?>
                                    <?= anchor(base_url() . 'socio/eliminar_proyecto/' . $proyecto->id_proyecto, 'Eliminar proyecto',Array('class'=>'btn btn-danger btn-xs btn-block')) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <h4>No existen proyectos en edición actualmente.</h4>
            <?php endif; ?>
            <?= anchor(base_url() . 'socio/registrar_nuevo_proyecto', 'Registrar nuevo proyecto', Array('class' => 'btn btn-primary')) ?>
        </div>	
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    </body>
</html>