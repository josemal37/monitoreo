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
                $nombre_actividad = array('name' => 'nombre_actividad', 'placeholder' => 'Nombre de la actividad', 'class' => 'form-control', 'required');
                $descripcion_actividad = array('name' => 'descripcion_actividad', 'placeholder' => 'Descripción', 'class' => 'form-control', 'rows' => '4');
                $fecha_inicio_actividad = array('name' => 'fecha_inicio_actividad', 'placeholder' => 'Fecha de inicio', 'type' => 'date', 'class' => 'form-control');
                $fecha_fin_actividad = array('name' => 'fecha_fin_actividad', 'placeholder' => 'Fecha de fin', 'type' => 'date', 'class' => 'form-control');
                $presupuesto_actividad = array('name' => 'presupuesto_actividad', 'placeholder' => 'Presupuesto', 'type' => 'number', 'class' => 'form-control');
                $submit = array('name' => 'submit', 'value' => 'Modificar actividad', 'title' => 'Modificar actividad', 'class' => 'btn btn-primary');
                ?>
                <?= form_open(base_url() . 'socio/modificar_actividad/' . $actividad->id_actividad, Array('role' => 'form')) ?>
                <div class="form-group">
                    <label for="nombre_actividad">Nombre de la actividad</label>
                    <?= form_input($nombre_actividad, $actividad->nombre_actividad) ?><p><?= form_error('nombre_actividad') ?></p>
                </div>
                <div class="form-group">
                    <label for="descripcion_actividad">Descripción</label>
                    <?= form_textarea($descripcion_actividad, $actividad->descripcion_actividad) ?><p><?= form_error('descripcion_actividad') ?></p>
                </div>
                <div class="form-group">
                    <label for="fecha_inicio_actividad">Fecha de inicio</label>
                    <?= form_input($fecha_inicio_actividad, $actividad->fecha_inicio_actividad) ?><p><?= form_error('fecha_inicio_actividad') ?></p>
                </div>
                <div class="form-group">
                    <label for="fecha_fin_actividad">Fecha de fin</label>
                    <?= form_input($fecha_fin_actividad, $actividad->fecha_fin_actividad) ?><p><?= form_error('fecha_fin_actividad') ?></p>
                </div>
                <div class="form-group">
                    <label for="presupuesto_actividad">Presupuesto</label>
                    <?= form_input($presupuesto_actividad, $actividad->presupuesto_actividad) ?><p><?= form_error('presupuesto_actividad') ?></p>
                </div>
                <?= form_input(Array('type' => 'hidden', 'name' => 'id_actividad', 'id' => 'id_actividad', 'value' => $actividad->id_actividad)) ?>
                <?= form_input(Array('type' => 'hidden', 'name' => 'id_proyecto', 'id' => 'id_proyecto', 'value' => $actividad->id_proyecto)) ?>
                <?= form_submit($submit) ?>
                <?= form_close() ?>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    </body>
</html>