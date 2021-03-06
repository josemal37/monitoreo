<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        
        <title>Modificar usuario</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Usuarios";
            $this->load->view('administrador/nav', $datos);
            ?>
            <h4  class="text-primary">Nuevo usuario</h4>
            <form action="<?= base_url() . 'administrador/modificar_usuario/' . $usuario->id_usuario ?>" id="formulario_usuario" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                <div style="display: none">
                    <input type="text">
                    <input type="password">
                </div>
                <div class="form-group">
                    <label for="nombre_usuario">Nombre</label>
                    <input type="text" name="nombre_usuario" id="nombre_usuario" value="<?= $usuario->nombre_usuario ?>" placeholder="Nombre" class="form-control">
                    <p><?= form_error('nombre_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="apellido_paterno_usuario">Apellido paterno</label>
                    <input type="text" name="apellido_paterno_usuario" id="apellido_paterno_usuario" value="<?= $usuario->apellido_paterno_usuario ?>" placeholder="Apellido paterno" class="form-control">
                    <p><?= form_error('apellido_paterno_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="apellido_materno_usuario">Apellido materno</label>
                    <input type="text" name="apellido_materno_usuario" id="apellido_materno_usuario" value="<?= $usuario->apellido_materno_usuario ?>" placeholder="Apellido materno" class="form-control">
                    <p><?= form_error('apellido_materno_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="id_institucion">Institución</label>
                    <select name="id_institucion" id="id_institucion" class="form-control">
                        <?php foreach ($instituciones as $institucion): ?>
                            <option value="<?= $institucion->id_institucion ?>" <?php if ($usuario->id_institucion == $institucion->id_institucion){echo('selected');} ?>><?= $institucion->nombre_institucion ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p><?= form_error('id_institucion') ?></p>
                </div>
                <div class="form-group">
                    <label for="id_rol">Rol del usuario</label>
                    <select name="id_rol" id="id_rol" class="form-control">
                        <?php foreach ($roles as $rol): ?>
                            <option value="<?= $rol->id_rol ?>" <?php if($usuario->id_rol == $rol->id_rol): ?>selected<?php endif; ?>><?= $rol->nombre_rol ?></option>
                        <?php endforeach; ?>
                    </select>
                    <p><?= form_error('id_rol') ?></p>
                </div>
                <div class="form-group">
                    <label for="telefono_usuario">Número de telefono</label>
                    <input type="number" name="telefono_usuario" id="telefono_usuario" value="<?= $usuario->telefono_usuario ?>" placeholder="Telefono de referencia" class="form-control">
                    <p><?= form_error('telefono_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="correo_usuario">Correo electrónico</label>
                    <input type="text" name="correo_usuario" id="correo_usuario" value="<?= $usuario->correo_usuario ?>" placeholder="Correo electronico" class="form-control">
                    <p><?= form_error('correo_usuario') ?></p>
                </div>
                <div class="form-group">
                    <label for="login_usuario">Login</label>
                    <input type="text" name="login_usuario" id="login_usuario" value="<?= $usuario->login_usuario?>" placeholder="Login" class="form-control" autocomplete="off">
                    <p><?= form_error('login_usuario') ?></p>
                </div>
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $usuario->id_usuario ?>">
                <input type="submit" name="submit" value="Modificar usuario" title="Modificar usuario" class="btn btn-primary">
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
                            email: true,
                            remote: {
                                url: '<?= base_url() . 'administrador/existe_correo_usuario_ajax' ?>',
                                method: 'POST',
                                cache: false,
                                dataType: "json",
                                data: {
                                    correo_usuario: function() {return $('#correo_usuario').val();},
                                    id_usuario: function() {return $('#id_usuario').val();}
                                }
                            }
                        },
                        login_usuario: {
                            required: true,
                            minlength: 5,
                            maxlength: 32,
                            remote: {
                                url: '<?= base_url() . 'administrador/existe_login_usuario_ajax' ?>',
                                method: 'POST',
                                cache: false,
                                dataType: "json",
                                data: {
                                    login_usuario: function() {return $('#login_usuario').val();},
                                    id_usuario: function() {return $('#id_usuario').val();}
                                }
                            }
                        }
                    },
                    messages: {
                        correo_usuario: {
                            remote: 'Este correo ya se encuentra registrado.'
                        },
                        login_usuario: {
                            remote: 'Este login ya se encuentra registrado.'
                        }
                    }
                });
            });
        </script>
    </body>
</html>