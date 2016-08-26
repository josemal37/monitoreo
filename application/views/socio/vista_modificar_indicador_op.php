<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />
        <title>Modificar indicador operativo</title>
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido socio</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            if (!$indicador) {
                redirect(base_url() . 'socio');
            }
            ?>
            <div>
                <h4><?= $actividad->nombre_actividad . ' (' . $actividad->fecha_inicio_actividad . ' - ' . $actividad->fecha_fin_actividad . ')' ?></h4>
                <form action="<?= base_url() . 'socio/modificar_indicador_operativo/' . $id_proyecto . '/' . $indicador->id_indicador_op ?>" id="modificar_indicador_operativo" role="form" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="nombre_indicador_op">Nombre del indicador</label>
                        <input type="text" name="nombre_indicador_op" value="<?= $indicador->nombre_indicador_op ?>" placeholder="Nombre del indicador" class="form-control" required>
                        <p><?= form_error('nombre_indicador_op') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="tipos_indicador_op">Tipo de indicador</label>
                        <select name="id_tipo_indicador_op" id="id_tipo_indicador_op" class="form-control" required>
                            <?php foreach ($tipos_indicador_op as $tipo_indicador_op): ?>
                                <option value="<?= $tipo_indicador_op->id_tipo_indicador_op ?>" <?php if ($tipo_indicador_op->id_tipo_indicador_op == $indicador->id_tipo_indicador_op) echo('selected'); ?>><?= $tipo_indicador_op->nombre_tipo_indicador_op ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p><?= form_error('tipo_indicador_op') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="fecha_limite_indicador_op">Fecha limite</label>
                        <input type="text" name="fecha_limite_indicador_op" id="fecha_limite_indicador_op" class="form-control" required>
                        <p><?= form_error('fecha_limite_indicador_op') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="meta_op">Meta del indicador</label>
                        <input type="number" name="meta_op" id="meta_op" value="<?= $indicador->meta_op ?>" placeholder="Meta del indicador" class="form-control" required>
                        <p><?= form_error('meta_op') ?></p>
                    </div>
                    <div id="slider_indicador"></div>
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="no_aceptable_op">Valor no aceptable</label>
                            <input type="text" name="no_aceptable_rango" id="no_aceptable_rango" value="<?= '0 - ' . $indicador->no_aceptable_op ?>" readonly placeholder="Valor no aceptable" class="form-control" required>
                            <input type="hidden" name="no_aceptable_op" id="no_aceptable_op" value="<?= $indicador->no_aceptable_op ?>">
                            <p><?= form_error('no_aceptable_op') ?></p>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="limitado_op">Valor limitado</label>
                            <input type="text" name="limitado_rango" id="limitado_rango" value="<?= $indicador->no_aceptable_op . ' - ' . $indicador->limitado_op ?>" readonly placeholder="Valor limitado" class="form-control" required>
                            <input type="hidden" name="limitado_op" id="limitado_op" value="<?= $indicador->limitado_op ?>">
                            <p><?= form_error('limitado_op') ?></p>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="aceptable_op">Valor aceptable</label>
                            <input type="text" name="aceptable_rango" id="aceptable_rango" value="<?= $indicador->limitado_op . ' - ' . $indicador->aceptable_op ?>" readonly placeholder="Valor aceptable" class="form-control" required>
                            <input type="hidden" name="aceptable_op" id="aceptable_op" value="<?= $indicador->aceptable_op ?>">
                            <p><?= form_error('aceptable_op') ?></p>
                        </div>
                    </div>
                    <input type="hidden" name="id_indicador_op" value="<?= $indicador->id_indicador_op ?>" id="id_indicador_op">
                    <input type="submit" name="submit" value="Modificar indicador" title="Modificar indicador" class="btn btn-primary">
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
                $('#modificar_indicador_operativo').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_indicador_op: {
                            required: true,
                            minlength: 5,
                            maxlength: 128
                        },
                        fecha_limite_indicador_op: {
                            required: true,
                            date: true
                        },
                        meta_op: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        aceptable_op: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        limitado_op: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        no_aceptable_op: {
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
            var fecha_fin_ini = new Date($.datepicker.parseDate('yy-mm-dd', '<?= $actividad->fecha_fin_actividad ?>'));
            var fecha_inicio_ini = new Date($.datepicker.parseDate('yy-mm-dd', '<?= $actividad->fecha_inicio_actividad ?>'));
            var fecha_limite_ini = new Date($.datepicker.parseDate('yy-mm-dd', '<?= $indicador->fecha_limite_indicador_op ?>'));
            $("#fecha_limite_indicador_op").datepicker({dateFormat: 'yy-mm-dd'}).datepicker('setDate', fecha_limite_ini);
            $("#fecha_limite_indicador_op").datepicker("option", "maxDate", fecha_fin_ini);
            $("#fecha_limite_indicador_op").datepicker("option", "minDate", fecha_inicio_ini);
        </script>
        <script type="text/javascript">
            $(function() {
                $('#slider_indicador').slider({
                    range: true,
                    min: 0,
                    max: <?= $indicador->aceptable_op?>,
                    values: [<?= $indicador->no_aceptable_op?>, <?= $indicador->limitado_op?>],
                    slide: function(event, ui) {
                        $('#aceptable_rango').val(ui.values[1] + " - " + $('#aceptable_op').val());
                        $('#limitado_rango').val(ui.values[0] + " - " + ui.values[1]);
                        $('#no_aceptable_rango').val("0 - " + ui.values[0]);
                    },
                    stop: function(event, ui) {
                        $('#limitado_op').val(ui.values[1]);
                        $('#no_aceptable_op').val(ui.values[0]);
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $(function() {
                $('#meta_op').change(function() {
                    var meta_op = $('#meta_op').val();
                    $('#slider_indicador').slider('option', 'max', meta_op);
                    $('#aceptable_op').val(meta_op);
                    $('#aceptable_rango').val($('#slider_indicador').slider('option', 'values')[1] + " - " + meta_op);
                });
            });
        </script>
    </body>
</html>