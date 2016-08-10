<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">Bienvenido socio</h1>
            <?php
            $nombre_usuario = $this->session->userdata('nombre_usuario');
            $apellido_usuario = $this->session->userdata('apellido_usuario');
            $nombre_institucion = $this->session->userdata('nombre_institucion');
            $data = Array();
            $data['nombre_usuario'] = $nombre_usuario;
            $data['apellido_usuario'] = $apellido_usuario;
            $data['nombre_institucion'] = $nombre_institucion;
            $data['activo'] = "Ver proyectos";
            $this->load->view('socio/nav', $data);
            ?>
            <?php if($proyectos): ?>
                <div class="table-responsive">
                    <h4>Lista de proyectos activos</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Descripción</th>
                                <th>Ver proyecto</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($proyectos as $proyecto) : ?>
                                <tr>
                                    <td><?= $proyecto->nombre_proyecto ?></td>
                                    <td><?= $proyecto->descripcion_proyecto ?></td>
                                    <td class="text-center"><?= anchor(base_url() . 'socio/ver_proyecto/' . $proyecto->id_proyecto, 'Ver proyecto',Array('class' => 'btn btn-default btn-xs')) ?></td>
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