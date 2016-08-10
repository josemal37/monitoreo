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
                $nombre_indicador_op = array('name' => 'nombre_indicador_op', 'placeholder' => 'Nombre del indicador', 'class' => 'form-control', 'required');
                $fecha_limite_indicador_op = array('name' => 'fecha_limite_indicador_op', 'placeholder' => 'Fecha limite', 'type' => 'date', 'class' => 'form-control');
                $meta_op = array('name' => 'meta_op', 'placeholder' => 'Meta del indicador', 'type' => 'number', 'class' => 'form-control');
                $aceptable_op = array('name' => 'aceptable_op', 'placeholder' => 'Valor aceptable', 'type' => 'number', 'class' => 'form-control');
                $limitado_op = array('name' => 'limitado_op', 'placeholder' => 'Valor limitado', 'type' => 'number', 'class' => 'form-control');
                $no_aceptable_op = array('name' => 'no_aceptable_op', 'placeholder' => 'Valor no aceptable', 'type' => 'number', 'class' => 'form-control');
                $submit = array('name' => 'submit', 'value' => 'Modificar indicador', 'title' => 'Modificar indicador', 'class' => 'btn btn-primary');
                ?>
                <?= form_open(base_url() . 'socio/modificar_indicador_operativo/' . $id_proyecto.'/'.$indicador->id_indicador_op, Array('role' => 'form')) ?>
                <div class="form-group">
                    <label for="nombre_indicador_op">Nombre del indicador</label>
                    <?= form_input($nombre_indicador_op, $indicador->nombre_indicador_op) ?><p><?= form_error('nombre_indicador_op') ?></p>
                </div>
                <div class="form-group">
                    <label for="tipos_indicador_op">Tipo de indicador</label>
                    <select name="id_tipo_indicador_op" id="id_tipo_indicador_op" class="form-control">
                        <?php foreach ($tipos_indicador_op as $tipo_indicador_op): ?>
                        <option value="<?= $tipo_indicador_op->id_tipo_indicador_op ?>" <?php if($tipo_indicador_op->id_tipo_indicador_op == $indicador->id_tipo_indicador_op) echo('selected');?>><?= $tipo_indicador_op->nombre_tipo_indicador_op ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p><?= form_error('tipo_indicador_op') ?></p>
                </div>
                <div class="form-group">
                    <label for="fecha_limite_indicador_op">Fecha limite</label>
                    <?= form_input($fecha_limite_indicador_op, $indicador->fecha_limite_indicador_op) ?><p><?= form_error('fecha_limite_indicador_op') ?></p>
                </div>
                <div class="form-group">
                    <label for="meta_op">Meta del indicador</label>
                    <?= form_input($meta_op, $indicador->meta_op) ?><p><?= form_error('meta_op') ?></p>
                </div>
                <div class="form-group">
                    <label for="aceptable_op">Valor aceptable</label>
                    <?= form_input($aceptable_op, $indicador->aceptable_op) ?><p><?= form_error('aceptable_op') ?></p>
                </div>
                <div class="form-group">
                    <label for="limitado_op">Valor limitado</label>
                    <?= form_input($limitado_op, $indicador->limitado_op) ?><p><?= form_error('limitado_op') ?></p>
                </div>
                <div class="form-group">
                    <label for="no_aceptable_op">Valor no aceptable</label>
                    <?= form_input($no_aceptable_op, $indicador->no_aceptable_op) ?><p><?= form_error('no_aceptable_op') ?></p>
                </div>
                <?= form_input(Array('type' => 'hidden', 'name' => 'id_indicador_op', 'id' => 'id_indicador_op', 'value' => $indicador->id_indicador_op)) ?>
                <?= form_submit($submit) ?>
                <?= form_close() ?>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    </body>
</html>