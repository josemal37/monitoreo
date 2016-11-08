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
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery.ui.touch-punch.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>
        
        <title>Registrar comparador</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <div>
                <h4 class="text-primary">Registro de comparador</h4>
                <p class="text-justify"><strong>Actividad:</strong> <?= $actividad->nombre_actividad ?></p>
                <p class="text-justify"><strong>Indicador:</strong> <?= $hito->nombre_hito_cn ?></p>
                <p class="text-justify"><strong>Meta del indicador:</strong> <span class="number_integer"><?= $hito->meta_hito_cn ?></span> <?= $hito->unidad_hito_cn ?></p>
                <p class="text-justify"><strong>Descripción del indicador:</strong> <?= $hito->descripcion_hito_cn ?></p>
                <form action="<?= base_url() . 'coordinador/registrar_indicador_cuantitativo/' . $id_proyecto . '/' . $id_hito ?>" id="formulario_indicador_cuantitativo" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre_indicador">Nombre del comparador</label>
                        <input type="text" name="nombre_indicador" id="nombre_indicador" placeholder="Nombre" class="form-control" required>
                        <p><?= form_error('nombre_indicador') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="tipo_indicador">Tipo de comparador</label>
                        <select name="tipo_indicador" id="tipo_indicador" class="form-control">
                            <?php foreach ($tipos_indicador_cuantitativo as $tipo_indicador): ?>
                                <option value="<?= $tipo_indicador->id_tipo_indicador_cn ?>"><?= $tipo_indicador->nombre_tipo_indicador_cn ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p><?= form_error('tipo_indicador_cuantitativo') ?></p>
                    </div>
                    <div id="slider_indicador"></div>
                    <div class="row">
                        <div class="form-group col-lg-4">
                            <label for="no_aceptable_indicador">No aceptable</label>
                            <input type="text" name="no_aceptable_indicador_vista" id="no_aceptable_indicador_vista" value="<?= 0 . ' - ' . round($hito->meta_hito_cn / 3) ?>" class="form-control">
                            <input type="hidden" name="no_aceptable_indicador" id="no_aceptable_indicador" value="<?= round($hito->meta_hito_cn / 3)?>">
                            <p><?= form_error('no_aceptable_indicador') ?></p>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="limitado_indicador">Limitado</label>
                            <input type="text" name="limitado_indicador_vista" id="limitado_indicador_vista" value="<?= round($hito->meta_hito_cn / 3) . ' - ' . round(($hito->meta_hito_cn / 3) * 2) ?>" class="form-control">
                            <input type="hidden" name="limitado_indicador" id="limitado_indicador" value="<?= round($hito->meta_hito_cn / 3) * 2?>">
                            <p><?= form_error('limitado_indicador') ?></p>
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="aceptable_indicador">Aceptable</label>
                            <input type="text" name="aceptable_indicador_vista" id="aceptable_indicador_vista" value="<?= round(($hito->meta_hito_cn / 3) * 2) . ' - ' . $hito->meta_hito_cn ?>" class="form-control">
                            <input type="hidden" name="aceptable_indicador" id="aceptable_indicador" value="<?= $hito->meta_hito_cn ?>">
                            <p><?= form_error('aceptable_indicador') ?></p>
                        </div>
                    </div>
                    <input type="hidden" name="id_hito" value="<?= $id_hito ?>" id="id_hito">
                    <input type="submit" name="submit" value="Registrar indicador" title="Registrar indicador" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_indicador_cuantitativo').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_indicador: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        tipo_indicador: {
                            required: true
                        },
                        aceptable_indicador: {
                            required: true,
                            number: true,
                            min: 0,
                            max: <?= $hito->meta_hito_cn ?>
                        },
                        limitado_indicador: {
                            required: true,
                            number: true,
                            min: 0,
                            max: <?= $hito->meta_hito_cn ?>
                        },
                        no_aceptable_indicador: {
                            required: true,
                            number: true,
                            min: 0,
                            max: <?= $hito->meta_hito_cn ?>
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
            $(function() {
                $('#slider_indicador').slider({
                    range: true,
                    min: 0,
                    max: <?= $hito->meta_hito_cn ?>,
                    values: [<?= round($hito->meta_hito_cn / 3) ?>, <?= round(($hito->meta_hito_cn / 3) * 2) ?>],
                    slide: function(event, ui) {
                        $('#aceptable_indicador_vista').val(ui.values[1] + " - " + $('#aceptable_indicador').val());
                        $('#limitado_indicador_vista').val(ui.values[0] + " - " + ui.values[1]);
                        $('#no_aceptable_indicador_vista').val("0 - " + ui.values[0]);
                    },
                    stop: function(event, ui) {
                        $('#limitado_indicador').val(ui.values[1]);
                        $('#no_aceptable_indicador').val(ui.values[0]);
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.number_integer').number(true, 0);
            });
        </script>
    </body>
</html>