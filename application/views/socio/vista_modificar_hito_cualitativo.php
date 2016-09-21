<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />
        
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        
        <title>Modificar hito</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <div>
                <h4><?= $actividad->nombre_actividad . ' (' . $actividad->fecha_inicio_actividad . ' - ' . $actividad->fecha_fin_actividad . ')' ?></h4>
                <form action="<?= base_url() . 'socio/modificar_hito_cualitativo/' . $id_proyecto . '/' . $id_hito ?>" id="formulario_hito" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre_hito">Nombre del hito</label>
                        <input type="text" name="nombre_hito" id="nombre_hito" value="<?= $hito->nombre_hito_cl ?>" placeholder="Nombre del hito" class="form-control" required>
                        <p><?= form_error('nombre_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_hito">Descripción</label>
                        <textarea name="descripcion_hito" id="descripcion_hito" rows="4" placeholder="Descripción" class="form-control vresize"><?= $hito->descripcion_hito_cl ?></textarea>
                        <p><?= form_error('descripcion_hito') ?></p>
                    </div>
                    <input type="hidden" name="id_hito" value="<?= $hito->id_hito_cl ?>" id="id_hito">
                    <input type="submit" name="submit" value="Modificar hito" title="Modificar hito" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_hito').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_hito_cn: {
                            required: true,
                            minlength: 5,
                            maxlength: 128
                        },
                        descripcion_hito_cn: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        meta_hito_cn: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        unidad_hito_cn: {
                            required: true,
                            minlength: 1,
                            maxlength: 32
                        }
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).parent('div').addClass(errorClass).removeClass(validClass);
                        $(element).addClass('control-label');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).parent('div').removeClass(errorClass).addClass(validClass);
                    },
                    errorPlacement: function(error, element) {
                        $(error).addClass('control-label');
                        error.insertAfter(element);
                    }
                });
            });
        </script>
    </body>
</html>