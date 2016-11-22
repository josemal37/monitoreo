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
        
        <title>Datos contacto</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Usuario";
            $this->load->view('financiador/nav', $datos);
            ?>
            <h4 class="text-primary">Datos de contacto</h4>
            <form action="<?= base_url() . 'financiador/modificar_datos_contacto/' . $usuario->id_usuario ?>" id="formulario_contacto" role="form" method="post" accept-charset="utf-8" autocomplete="off">
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
                <input type="hidden" name="id_usuario" id="id_usuario" value="<?= $usuario->id_usuario ?>">
                <input type="submit" name="submit" value="Modificar datos" title="Modificar datos" class="btn btn-primary">
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_contacto').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        telefono_usuario: {
                            number: true,
                            min: 0
                        },
                        correo_usuario: {
                            minlength: 5,
                            maxlength: 64,
                            email: true
                        }
                    }
                });
            });
        </script>
    </body>
</html>