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
            $datos['activo'] = "Usuarios";
            $this->load->view('administrador/nav', $datos);
            ?>
            <h4>Nuevo usuario</h4>
            <form action="<?= base_url() . 'administrador/nuevo_usuario' ?>" id="formulario_usuario" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                <div style="display: none">
                    <input type="text">
                    <input type="password">
                </div>
                <div class="form-group">
                    <label for="nombre_usuario">Nombre</label>
                    <input type="text" name="nombre_usuario" id="nombre_usuario" placeholder="Nombre" class="form-control">
                    <p><?= form_error('nombre_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="apellido_paterno_usuario">Apellido paterno</label>
                    <input type="text" name="apellido_paterno_usuario" id="apellido_paterno_usuario" placeholder="Apellido paterno" class="form-control">
                    <p><?= form_error('apellido_paterno_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="apellido_materno_usuario">Apellido materno</label>
                    <input type="text" name="apellido_materno_usuario" id="apellido_materno_usuario" placeholder="Apellido materno" class="form-control">
                    <p><?= form_error('apellido_materno_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="id_institucion">Institución</label>
                    <select name="id_institucion" id="id_institucion" class="form-control">
                        <?php foreach ($instituciones as $institucion): ?>
                            <option value="<?= $institucion->id_institucion ?>"><?= $institucion->nombre_institucion ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p><?= form_error('id_institucion') ?></p>
                </div>
                <div class="form-group">
                    <label for="id_rol">Rol del usuario</label>
                    <select name="id_rol" id="id_rol" class="form-control">
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol->id_rol ?>"><?= $rol->nombre_rol ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p><?= form_error('id_rol') ?></p>
                </div>
                <div class="form-group">
                    <label for="telefono_usuario">Número de telefono</label>
                    <input type="number" name="telefono_usuario" id="telefono_usuario" placeholder="Telefono de referencia" class="form-control">
                    <p><?= form_error('telefono_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="correo_usuario">Correo electrónico</label>
                    <input type="text" name="correo_usuario" id="correo_usuario" placeholder="Correo electronico" class="form-control">
                    <p><?= form_error('correo_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="login_usuario">Login</label>
                    <input type="text" name="login_usuario" id="login_usuario" placeholder="Login" class="form-control" autocomplete="off">
                    <p><?= form_error('login_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="password_usuario">Password</label>
                    <input type="text" name="password_usuario" id="password_usuario" placeholder="Password" class="form-control" autocomplete="off">
                    <p><?= form_error('password_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="password_usuario_confirmacion">Confirmación del password</label>
                    <input type="text" name="password_usuario_confirmacion" id="password_usuario_confirmacion" placeholder="Confirmación del password" class="form-control" autocomplete="off">
                    <p><?= form_error('password_usuario_confirmacion') ?></p>
                </div>
                <div>
                    <input type="submit" name="submit" value="Registrar usuario" title="Registrar usuario" class="btn btn-primary">
                </div>
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_usuario').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_usuario: {
                            required: true,
                            minlength: 1,
                            maxlength: 64
                        },
                        apellido_paterno_usuario: {
                            required: true,
                            minlength: 1,
                            maxlength: 32
                        },
                        apellido_materno_usuario: {
                            minlength: 1,
                            maxlength: 32
                        },
                        id_institucion: {
                            required: true
                        },
                        id_rol: {
                            required: true
                        },
                        telefono_usuario: {
                            number: true,
                            min: 0
                        },
                        correo_usuario: {
                            minlength: 5,
                            maxlength: 64,
                            email: true
                        },
                        login_usuario: {
                            required: true,
                            minlength: 5,
                            maxlength: 32
                        },
                        password_usuario: {
                            required: true,
                            minlength: 5,
                            maxlength: 32
                        },
                        password_usuario_confirmacion: {
                            required: true,
                            minlength: 5,
                            maxlength: 32,
                            equalTo: '#password_usuario'
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#password_usuario').attr('type', "password");
                $('#password_usuario_confirmacion').attr('type', "password");
            });
        </script>
    </body>
</html>