<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

        <title>Registrar actividad</title>
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
                <form action="<?= base_url() . 'socio/registrar_nueva_actividad/' . $id_proyecto ?>" id="actividad" role="form" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="nombre_actividad">Nombre de la actividad</label>
                        <input type="text" name="nombre_actividad" placeholder="Nombre de la actividad" class="form-control" required>
                        <p><?= form_error('nombre_actividad') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_actividad">Descripción</label>
                        <textarea name="descripcion_actividad" rows="4" placeholder="Descripción" class="form-control vresize" required></textarea>
                        <p><?= form_error('descripcion_actividad') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="id_producto">Producto asociado</label>
                        <select name="id_producto" id="id_producto" class="form-control">
                            <?php foreach ($productos as $producto): ?>
                                <option value="<?= $producto->id_producto ?>"><?= $producto->nombre_producto ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p><?= form_error('id_producto') ?></p>
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
                        <label for="presupuesto_actividad_vista">Presupuesto (Bs.)</label>
                        <input type="text" name="presupuesto_actividad_vista" id="presupuesto_actividad_vista" placeholder="Presupuesto" class="form-control">
                        <input type="hidden" name="presupuesto_actividad" id="presupuesto_actividad">
                        <p><?= form_error('presupuesto_actividad') ?></p>
                        <label>Disponible: Bs. <span class="number_decimal"><?= $presupuesto_disponible->presupuesto_disponible_proyecto ?></span></label>
                    </div>
                    <input type="hidden" name="id_proyecto" value="<?= $id_proyecto ?>" id="id_proyecto">
                    <input type="submit" name="submit" value="Registrar actividad" title="Registrar actividad" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#actividad').validate({
                    ignore: [],
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
                            min: 0,
                            max: <?= $presupuesto_disponible->presupuesto_disponible_proyecto ?>
                        }
                    },
                    messages: {
                        presupuesto_actividad: {
                            max: 'Por favor, escribe un valor menor o igual al disponible.'
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $("#fecha_inicio_actividad").datepicker({
                dateFormat: 'yy-mm-dd',
                inline: true,
            });
            $("#fecha_fin_actividad").datepicker({dateFormat: 'yy-mm-dd'});
        </script>
        <script type="text/javascript">
            if ($(document).width() <= 768) {
                $("#calendario_inicio_actividad").datepicker('hide');
            }
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
        <script type="text/javascript">
            $(document).ready(function() {
                $('#presupuesto_actividad_vista').number(true, 2);
                $('.number_decimal').number(true, 2);
            });
            $('#presupuesto_actividad_vista').keyup(function() {
                $('#presupuesto_actividad').val($('#presupuesto_actividad_vista').val());
            });
        </script>
    </body>
</html>