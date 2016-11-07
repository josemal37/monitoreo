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
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>
        
        <title>Reformular indicador</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <h4 class="text-primary">Reformular indicador</h4>
            <div>
                <p><strong>Actividad:</strong> <?= $actividad->nombre_actividad ?></p>
                <p><strong>Fecha de inicio:</strong> <?= $actividad->fecha_inicio_actividad ?></p>
                <p><strong>Fecha de fin:</strong> <?= $actividad->fecha_fin_actividad ?></p>
                <p><strong>Descripción:</strong> <?= $actividad->descripcion_actividad ?></p>
                <form action="<?= base_url() . 'socio/reformular_hito_cuantitativo/' . $id_proyecto . '/' . $id_hito ?>" id="formulario_hito" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre_hito">Nombre del indicador</label>
                        <input type="text" name="nombre_hito" id="nombre_hito" value="<?= $hito->nombre_hito_cn ?>" placeholder="Nombre del hito" class="form-control" required>
                        <p><?= form_error('nombre_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_hito">Descripción</label>
                        <textarea name="descripcion_hito" id="descripcion_hito" rows="4" placeholder="Descripción" class="form-control vresize"><?= $hito->descripcion_hito_cn ?></textarea>
                        <p><?= form_error('descripcion_hito') ?></p>
                    </div>
                    <div id="datos_cuantitativos">
                        <div class="form-group">
                            <label for="meta_hito_vista">Meta</label>
                            <input type="text" name="meta_hito_vista" id="meta_hito_vista" value="<?= $hito->meta_hito_cn ?>" placeholder="Meta del hito" class="form-control">
                            <input type="hidden" name="meta_hito" id="meta_hito" value="<?= $hito->meta_hito_cn ?>">
                            <p><?= form_error('meta_hito') ?></p>
                        </div>
                        <div class="form-group">
                            <label for="unidad_hito">Unidad</label>
                            <input type="text" name="unidad_hito" id="unidad_hito" value="<?= $hito->unidad_hito_cn ?>" placeholder="Unidad del hito" class="form-control" required>
                            <p><?= form_error('unidad_hito') ?></p>
                        </div>
                    </div>
                    <div class='form-group' <?php if(!isset($actividad->nombre_producto)):?>style='display: none;'<?php endif; ?>>
                        <label for='tipo_hito'>Aporta al producto (<?= $actividad->nombre_producto ?>)</label>
                        <div class='radio'>
                            <label><input type='radio' name='aporta_producto' id='tipo_hito' value='directo' <?php if(isset($hito->id_meta_producto_cuantitativa)): ?>checked<?php endif; ?>>Aporta directamente</label><br>
                        </div>
                        <div class='radio'>
                            <label><input type='radio' name='aporta_producto' id='tipo_hito' value='indirecto' <?php if(!isset($hito->id_meta_producto_cuantitativa)): ?>checked<?php endif; ?>>Aporta indirectamente</label><br>
                        </div>
                    </div>
                    <?php if(isset($hito->id_meta_producto_cuantitativa)): ?>
                    <div id='div_aporta_producto'>
                        <div class='form-group'>
                            <label for='id_meta_producto'>Meta del producto a la que aporta directamente</label>
                            <select name='id_meta_producto' id='id_meta_producto' class='form-control'>
                                <?php foreach ($metas_cuantitativas as $meta_cuantitativa): ?>
                                    <option value='<?= $meta_cuantitativa->id_meta_producto_cuantitativa ?>'  <?php if($hito->id_meta_producto_cuantitativa == $meta_cuantitativa->id_meta_producto_cuantitativa): ?>selected<?php endif; ?>><?= $meta_cuantitativa->nombre_meta_producto_cuantitativa ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p><?= form_error('id_meta_producto') ?></p>
                        </div>
                    </div>
                    <?php else: ?>
                    <div id='div_aporta_producto' style='display: none;'>

                    </div>
                    <?php endif; ?>
                    <input type="hidden" name="id_hito" value="<?= $hito->id_hito_cn ?>" id="id_hito">
                    <input type="submit" name="submit" value="Modificar indicador" title="Modificar indicador" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_hito').validate({
                    ignore: [],
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
        <script type="text/javascript">
            $(document).on('change', 'input[type=radio][name=aporta_producto]', function() {
                if (this.value == 'directo') {
                    $('#div_aporta_producto').append(
                    <?php if(isset($actividad->nombre_producto)):?>
                     "<div class='form-group'>"+
                            "<label for='id_meta_producto'>Meta del producto a la que aporta directamente</label>"+
                            "<select name='id_meta_producto' id='id_meta_producto' class='form-control'>"+
                                <?php foreach ($metas_cuantitativas as $meta_cuantitativa): ?>
                                    "<option value='<?= $meta_cuantitativa->id_meta_producto_cuantitativa ?>'  <?php if($hito->id_meta_producto_cuantitativa == $meta_cuantitativa->id_meta_producto_cuantitativa): ?>selected<?php endif; ?>><?= $meta_cuantitativa->nombre_meta_producto_cuantitativa ?></option>"+
                                <?php endforeach; ?>
                            "</select>"+
                            "<p><?= form_error('id_meta_producto') ?></p>"+
                        "</div>"+
                        <?php endif; ?>
                        "");
                        $('#div_aporta_producto').slideDown('swing');
                }
                else if (this.value == 'indirecto') {
                    $('#div_aporta_producto').slideUp('swing');
                    $('#div_aporta_producto').empty();
                }
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#meta_hito_vista').number(true);
            });
            $('#meta_hito_vista').keyup(function(){
                $('#meta_hito').val($('#meta_hito_vista').val());
            });
        </script>
    </body>
</html>