<!DOCTYPE html>
<html lang="es">
    <head>
        <title>Nuevo usuario</title>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>

    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Usuario";
            $this->load->view('observador/nav', $datos);
            ?>
            <h4 class="text-primary">Cambiar password</h4>
            <form action="<?= base_url() . 'observador/modificar_password/' . $id_usuario ?>" id="formulario_password" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                <div class="form-group">
                    <label for="password_antiguo">Password antiguo</label>
                    <input type="text" name="password_antiguo" id="password_antiguo" placeholder="Password antiguo" class="form-control" autocomplete="off">
                    <p><?= form_error('password_antiguo') ?></p>
                    <?php if($this->session->flashdata('error_password_antiguo')): ?>
                        <label class="text-danger"><?= $this->session->flashdata('error_password_antiguo') ?></label>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="password_nuevo">Password nuevo</label>
                    <input type="text" name="password_nuevo" id="password_nuevo" placeholder="Password nuevo" class="form-control" autocomplete="off">
                    <p><?= form_error('password_nuevo') ?></p>
                </div>
                <div class="form-group">
                    <label for="password_confirmacion">Confirmación del password</label>
                    <input type="text" name="password_confirmacion" id="password_confirmacion" placeholder="Confirmación del password" class="form-control" autocomplete="off">
                    <p><?= form_error('password_confirmacion') ?></p>
                    <?php if($this->session->flashdata('error_password_confirmacion')): ?>
                        <label class="text-danger"><?= $this->session->flashdata('error_password_confirmacion') ?></label>
                    <?php endif; ?>
                </div>
                <div>
                    <input type="submit" name="submit" value="Cambiar password" title="Cambiar password" class="btn btn-primary">
                </div>
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_password').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        password_antiguo: {
                            required: true,
                            minlength: 5,
                            maxlength: 32
                        },
                        password_nuevo: {
                            required: true,
                            minlength: 5,
                            maxlength: 32
                        },
                        password_confirmacion: {
                            required: true,
                            minlength: 5,
                            maxlength: 32,
                            equalTo: '#password_nuevo'
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#password_antiguo').attr('type', "password");
                $('#password_nuevo').attr('type', "password");
                $('#password_confirmacion').attr('type', "password");
            });
        </script>
    </body>
</html>