<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />
        <title>Registrar avance hito</title>
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido socio</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('socio/nav', $datos);
            ?>
            <div>
                <h4><?= $actividad->nombre_actividad . ': ' . $hito->nombre_hito_cl ?></h4>
                <form action="<?= base_url() . 'socio/registrar_avance_hito_cualitativo/' . $id_proyecto . '/' . $id_hito ?>" id="formulario_avance_hito" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group">
                        <label for="titulo_avance_hito">Título del documento</label>
                        <input type="text" name="titulo_avance_hito" id="titulo_avance_hito" placeholder="Título del documento" class="form-control" required>
                        <p><?= form_error('titulo_avance_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="fecha_avance_hito">Fecha de avance</label>
                        <input type="text" name="fecha_avance_hito" id="fecha_avance_hito" class="form-control" required>
                        <p><?= form_error('fecha_avance_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_avance_hito">Descripción</label>
                        <textarea name="descripcion_avance_hito" id="descripcion_avance_hito" rows="4" placeholder="Descripción" class="form-control"></textarea>
                        <p><?= form_error('descripcion_avance_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="nombre_documento_avance_hito">Documento</label>
                        <div class="row">
                            <div class="col-md-3">
                                <span class="btn btn-default btn-file btn-block"><strong>Seleccione un archivo</strong>
                                    <input type="file" name="documento_avance_hito" id="documento_avance_hito" required>
                                </span>
                            </div>
                            <div class="col-md-9">
                                <input type="text" name="nombre_documento_avance_hito" id="nombre_documento_avance_hito" class="form-control" readonly>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="id_hito" value="<?= $id_hito ?>" id="id_hito">
                    <input type="submit" name="submit" value="Registrar avance" title="Registrar avance" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_avance_hito').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        titulo_avance_hito: {
                            required: true,
                            minlength: 5,
                            maxlength: 128
                        },
                        fecha_documento_avance_hito: {
                            required: true,
                            date: true
                        },
                        descripcion_avance_hito: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        documento_avance_hito: {
                            required: true
                        },
                        nombre_documento_avance_hito: {
                            required: true,
                            maxlength: 128
                        }
                    },
                    messages: {
                        documento_avance_hito: {
                            required: "!"
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
        <script type="text/javascript">
            $("#fecha_avance_hito").datepicker({dateFormat: 'yy-mm-dd'});
        </script>
        <script type="text/javascript">
            $(document).on('change', ':file', function() {
                var input = $(this);
                var label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                $('#nombre_documento_avance_hito').attr('value', label);
            });
        </script>
    </body>
</html>