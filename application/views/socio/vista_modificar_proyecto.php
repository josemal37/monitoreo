<!DOCTYPE html>
<html>
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
                <?php
                $nombre_proyecto = array('name' => 'nombre_proyecto', 'placeholder' => 'Nombre del proyecto', 'class' => 'form-control');
                $descripcion_proyecto = array('name' => 'descripcion_proyecto', 'placeholder' => 'Descripción', 'class' => 'form-control', 'rows' => '4');
                $presupuesto_proyecto = array('name' => 'presupuesto_proyecto', 'placeholder' => 'Presupuesto', 'type' => 'number', 'class' => 'form-control');
                $submit = array('name' => 'submit', 'value' => 'Modificar proyecto', 'title' => 'Modificar proyecto', 'class' => 'btn btn-primary');
                ?>
                <?= form_open(base_url() . 'socio/modificar_proyecto/'.$proyecto->id_proyecto, Array('role' => 'form')) ?>
                <div class="form-group">
                    <label for="nombre_proyecto">Nombre del proyecto</label>
                    <?= form_input($nombre_proyecto, $proyecto->nombre_proyecto) ?><p><?= form_error('nombre_proyecto') ?></p>
                </div>
                <div class="form-group">
                    <label for="descripcion_proyecto">Descripción</label>
                    
                    <?= form_textarea($descripcion_proyecto, $proyecto->descripcion_proyecto) ?><p><?= form_error('descripcion_proyecto') ?></p>
                </div>
                <div class="form-group">
                    <label for="presupuesto_proyecto">Presupuesto (Bs.)</label>
                    <?= form_input($presupuesto_proyecto, $proyecto->presupuesto_proyecto) ?><p><?= form_error('presupuesto_proyecto') ?></p>
                </div>
                <?= form_input(Array('type' => 'hidden', 'name' => 'id_proyecto', 'id' => 'id_proyecto', 'value' => $proyecto->id_proyecto)) ?>
                <?= form_submit($submit) ?>
                <?= form_close() ?>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    </body>
</html>