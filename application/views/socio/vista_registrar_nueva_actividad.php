<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />
        <title>Registrar actividad</title>
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido socio</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <div>
                <form action="<?= base_url() . 'socio/registrar_nueva_actividad/' . $id_proyecto ?>" id="actividad" role="form" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="nombre_actividad">Nombre de la actividad</label>
                        <input type="text" name="nombre_actividad" placeholder="Nombre de la actividad" class="form-control" required>
                        <p><?= form_error('nombre_actividad') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_actividad">Descripción</label>
                        <textarea name="descripcion_actividad" rows="4" placeholder="Descripción" class="form-control" required></textarea>
                        <p><?= form_error('descripcion_actividad') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="fecha_inicio_actividad">Fecha de inicio</label>
                        <input type="text" name="fecha_inicio_actividad" id="fecha_inicio_actividad" class="form-control" required>
                        <p><?= form_error('fecha_inicio_actividad') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="fecha_fin_actividad">Fecha de fin</label>
                        <input type="text" name="fecha_fin_actividad" id="fecha_fin_actividad" class="form-control" required>
                        <p><?= form_error('fecha_fin_actividad') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="presupuesto_actividad">Presupuesto (Bs.)</label>
                        <input type="number" name="presupuesto_actividad" placeholder="Presupuesto" class="form-control" required>
                        <p><?= form_error('presupuesto_actividad') ?></p>
                    </div>
                    <input type="hidden" name="id_proyecto" value="<?= $id_proyecto ?>" id="id_proyecto">
                    <input type="submit" name="submit" value="Registrar actividad" title="Registrar actividad" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#actividad').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_actividad: {
                            required: true,
                            minlength: 5,
                            maxlength: 128
                        },
                        descripcion_actividad: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        fecha_inicio_actividad: {
                            required: true,
                            date: true
                        },
                        fecha_fin_actividad: {
                            required: true,
                            date: true
                        },
                        presupuesto_actividad: {
                            required: true,
                            number: true,
                            min: 0
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
            $("#fecha_inicio_actividad").datepicker({dateFormat: 'yy-mm-dd'});
            $("#fecha_fin_actividad").datepicker({dateFormat: 'yy-mm-dd'});
        </script>
        <script type="text/javascript">
            $(function() {
                $('#fecha_inicio_actividad').change(function() {
                    var fecha_inicio = $("#fecha_inicio_actividad").datepicker("getDate");
                    $("#fecha_fin_actividad").datepicker("option", "minDate", fecha_inicio);
                });
                $('#fecha_fin_actividad').change(function() {
                    var fecha_fin = $("#fecha_fin_actividad").datepicker("getDate");
                    $("#fecha_inicio_actividad").datepicker("option", "maxDate", fecha_fin);
                });
            });
        </script>
    </body>
</html>