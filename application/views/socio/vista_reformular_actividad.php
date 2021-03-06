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
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

        <title>Reformular actividad</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <h4 class="text-primary">Reformular actividad</h4>
            <div>
                <form action="<?= base_url() . 'socio/reformular_actividad/' . $actividad->id_actividad ?>" id="modificar_actividad" role="form" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="nombre_actividad">Nombre de la actividad</label>
                        <input type="text" name="nombre_actividad" value="<?= $actividad->nombre_actividad ?>" placeholder="Nombre de la actividad" class="form-control" required>
                        <p><?= form_error('nombre_actividad') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_actividad">Descripción</label>
                        <textarea name="descripcion_actividad" rows="4" placeholder="Descripción" class="form-control vresize" required><?= $actividad->descripcion_actividad ?></textarea>
                        <p><?= form_error('descripcion_actividad') ?></p>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label for="asociado">
                                <input type="checkbox" name="asociado" id="asociado" <?php if (isset($actividad->id_producto)): ?>checked<?php endif; ?>><strong>Asociado a un producto</strong>
                            </label>
                        </div>
                        <p><?= form_error('asociado') ?></p>
                    </div>
                    <div id="producto_asociado">
                        <?php if (isset($actividad->id_producto)): ?>
                            <div class="form-group"  id='select_producto'>
                                <label for="id_producto">Producto asociado</label>
                                <select name="id_producto" id="id_producto" class="form-control">
                                    <?php foreach ($productos as $producto): ?>
                                        <option value="<?= $producto->id_producto ?>" title="<?= $producto->descripcion_producto ?>" <?php if ($producto->id_producto == $actividad->id_producto): ?>selected<?php $descripcion_producto = $producto->descripcion_producto ?><?php endif; ?>><?= $producto->nombre_producto ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <div id='descripcion_producto'>
                                    <?= $descripcion_producto ?>
                                </div>
                                <p><?= form_error('id_producto') ?></p>
                                <label class="text-warning">Advertencia: </label> <label>Modificar el producto asociado, eliminará las asociaciones existentes en los indicadores de esta actividad.</label>
                            </div>
                        <?php endif; ?>
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
                        <input type="text" name="presupuesto_actividad_vista" id="presupuesto_actividad_vista" value="<?= $actividad->presupuesto_actividad ?>" placeholder="Presupuesto" class="form-control">
                        <input type="hidden" name="presupuesto_actividad" id="presupuesto_actividad" value="<?= $actividad->presupuesto_actividad ?>">
                        <p><?= form_error('presupuesto_actividad') ?></p>
                    </div>
                    <div class="form-group">
                        <div class="checkbox">
                            <label for="contraparte">
                                <input type="checkbox" name="contraparte" id="contraparte" <?php if($actividad->contraparte_actividad): ?>checked<?php endif; ?>><strong>Contraparte</strong>
                            </label>
                        </div>
                        <p><?= form_error('contraparte') ?></p>
                    </div>
                    <div id="presupuesto_disponible" <?php if($actividad->contraparte_actividad): ?>style="display: none;"<?php endif; ?>>
                        <label>Disponible: Bs. <span class="number_decimal"><?= $presupuesto_disponible->presupuesto_disponible_proyecto ?></span></label>
                    </div>
                    <input type="hidden" name="id_actividad" value="<?= $actividad->id_actividad ?>" id="id_actividad">
                    <input type="hidden" name="id_proyecto" value="<?= $actividad->id_proyecto ?>" id="id_proyecto">
                    <input type="submit" name="submit" value="Reformular actividad" title="Reformular actividad" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#modificar_actividad').validate({
                ignore: [],
                errorClass: 'has-error',
                validClass: 'has-success',
                rules: {
                nombre_actividad: {
                required: true,
                        minlength: 5,
                        maxlength: 1024
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
                                max: function() {
                                if($('#contraparte').is(':checked')) {
                                    return 99999999.99;
                                } else {
                                    return <?= $presupuesto_disponible->presupuesto_disponible_proyecto ?>;
                                }
                            }
                        }
                },
                messages: {
                presupuesto_actividad: {
                    max: 'Por favor, escribe un valor menor o igual al disponible.'
                }
                }
            });
            });        </script>
        <script type="text/javascript">
                    var fecha_inicio_ini = new Date($.datepicker.parseDate('yy-mm-dd', '<?= $actividad->fecha_inicio_actividad ?>'));
                    var fecha_fin_ini = new Date($.datepicker.parseDate('yy-mm-dd', '<?= $actividad->fecha_fin_actividad ?>'));
                    $("#fecha_inicio_actividad").datepicker({dateFormat: 'yy-mm-dd'}).datepicker('setDate', fecha_inicio_ini);
                    $("#fecha_fin_actividad").datepicker({dateFormat: 'yy-mm-dd'}).datepicker('setDate', fecha_fin_ini);
                    $("#fecha_inicio_actividad").datepicker("option", "maxDate", fecha_fin_ini);
                    $("#fecha_fin_actividad").datepicker("option", "minDate", fecha_inicio_ini);        </script>
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
                    });        </script>
        <script type="text/javascript">
                    $(document).ready(function() {
            $('#presupuesto_actividad_vista').number(true, 2);
                    $('.number_decimal').number(true, 2);
            });
                    $('#presupuesto_actividad_vista').keyup(function() {
            $('#presupuesto_actividad').val($('#presupuesto_actividad_vista').val());
            });        </script>
        <script type="text/javascript">
                    $('#asociado').change(function(){
            if ($(this).is(':checked')) {
            $('#producto_asociado').append(
                    "<div class='form-group' id='select_producto' style='display:none'>" +
                        "<label for='id_producto'>Producto asociado</label>" +
                        "<select name='id_producto' id='id_producto' class='form-control'>" +
                            <?php foreach ($productos as $producto): ?>
                                "<option value='<?= $producto->id_producto ?>' title='<?= $producto->descripcion_producto ?>'><?= $producto->nombre_producto ?></option>" +
                            <?php endforeach; ?>
                        "</select>" +
                        "<div id='descripcion_producto'></div>" +
                        "<p><?= form_error('id_producto') ?></p>" +
                        "<label class='text-warning'>Advertencia: </label> <label>Modificar el producto asociado, eliminará las asociaciones existentes en los indicadores de esta actividad.</label>"+
                    "</div>"
                    );
                    $('#descripcion_producto').append($('option:selected', '#id_producto').attr('title'));
                    $('#select_producto').show('swing');
            } else {
            $('#select_producto').hide('swing');
                    $('#select_producto').remove();
            }
            });        
        </script>
        <script type="text/javascript">
                    $(document).on('change', '#id_producto', function(){
            var descripcion = $('option:selected', '#id_producto').attr('title');
                    $('#descripcion_producto').empty();
                    $('#descripcion_producto').append(descripcion);
            });
        </script>
        <script type="text/javascript">
            $('#contraparte').change(function(){
                if($(this).is(':checked')) {
                    $('#presupuesto_disponible').hide('swing');
                } else {
                    $('#presupuesto_disponible').show('swing');
                }
            });
        </script>
    </body>
</html>