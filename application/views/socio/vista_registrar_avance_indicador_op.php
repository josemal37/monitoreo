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
                <div>
                    <h4><?= $indicador->nombre_indicador_op?></h4>
                    <div>
                        <h4>Registrar avance</h4>
                        
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    </body>
</html>