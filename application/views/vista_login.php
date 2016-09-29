<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/signin.css' ?>" />
        
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        
        <title>Sistema de monitoreo</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <div class="form-signin">
                <h2 class="hidden-sm hidden-xs">Inicio de sesión</h2>
                <form action="<?= base_url() . 'login/iniciar_sesion' ?>" id="inicio-sesion" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="login_usuario" class="sr-only">Nombre de usuario</label>
                        <input type="text" name="login_usuario" placeholder="nombre de usuario" class="form-control">
                        <p><?= form_error('login_usuario') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="password_usuario" class="sr-only">Introduce tu password</label>
                        <input type="password" name="password_usuario" placeholder="contraseña" class="form-control">
                        <p><?= form_error('password_usuario') ?></p>
                    </div>
                    <input type="hidden" name="token" value="<?= $token ?>">
                    <?php if ($this->session->flashdata('usuario_incorrecto')): ?>
                        <div class="form-group has-error">
                            <label class="control-label"><?= $this->session->flashdata('usuario_incorrecto') ?></label>
                        </div>
                    <?php endif; ?>
                    <input type="submit" name="submit" value="Iniciar sesión" title="Iniciar sesión" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#inicio-sesion').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        login_usuario: {
                            required: true, 
                            maxlength: 32
                        },
                        password_usuario: {
                            required: true, 
                            maxlength: 32
                        }
                    }
                });
            });
        </script>
    </body>
</html>