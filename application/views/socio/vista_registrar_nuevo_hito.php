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
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>
        
        <title>Registrar indicador</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <h4 class="text-primary">Registro de indicador</h4>
            <div>
                <p><strong>Actividad:</strong> <?= $actividad->nombre_actividad ?></p>
                <p><strong>Fecha de inicio:</strong> <?= $actividad->fecha_inicio_actividad ?></p>
                <p><strong>Fecha de fin:</strong> <?= $actividad->fecha_fin_actividad ?></p>
                <p><strong>Descripción:</strong> <?= $actividad->descripcion_actividad ?></p>
                <form action="<?= base_url() . 'socio/registrar_nuevo_hito/' . $id_proyecto . '/' . $id_actividad ?>" id="formulario_hito" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                    <div class="form-group">
                        <label for="tipo_hito">Tipo de indicador</label>
                        <div class="radio">
                            <label><input type="radio" name="tipo_hito" id="tipo_hito" value="cuantitativo">Cuantitativo</label><br>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="tipo_hito" id="tipo_hito" value="cualitativo" checked>Cualitativo</label><br>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nombre_hito">Nombre del indicador</label>
                        <input type="text" name="nombre_hito" id="nombre_hito" placeholder="Nombre" class="form-control" required>
                        <p><?= form_error('nombre_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_hito">Descripción</label>
                        <textarea name="descripcion_hito" id="descripcion_hito" rows="4" placeholder="Descripción" class="form-control vresize"></textarea>
                        <p><?= form_error('descripcion_hito') ?></p>
                    </div>
                    <div id="datos_cuantitativos" style="display: none;">
                        
                    </div>
                    <input type="hidden" name="id_actividad" value="<?= $id_actividad ?>" id="id_actividad">
                    <input type="submit" name="submit" value="Registrar indicador" title="Registrar indicador" class="btn btn-primary">
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
                        nombre_hito: {
                            required: true,
                            minlength: 5,
                            maxlength: 128
                        },
                        descripcion_hito: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        meta_hito: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        unidad_hito: {
                            required: true,
                            minlength: 1,
                            maxlength: 32
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $('input[type=radio][name=tipo_hito]').change(function() {
                if (this.value == 'cuantitativo') {
                    $('#datos_cuantitativos').append(
                    
                     "<div class='form-group'>"+
                            "<label for='meta_hito_vista'>Meta</label>"+
                            "<input type='text' name='meta_hito_vista' id='meta_hito_vista' placeholder='Meta' class='form-control'>"+
                            "<input type='hidden' name='meta_hito' id='meta_hito'>"+
                            "<p><?= form_error('meta_hito') ?></p>"+
                        "</div>"+
                        "<div class='form-group'>"+
                            "<label for='unidad_hito'>Unidad</label>"+
                            "<input type='text' name='unidad_hito' id='unidad_hito' placeholder='Unidad' class='form-control' required>"+
                            "<p><?= form_error('unidad_hito') ?></p>"+
                        "</div>"+
                        <?php if(isset($actividad->nombre_producto)): ?>
                        "<div class='form-group'>"+
                            "<label for='tipo_hito'>Aporta al producto (<?= $actividad->nombre_producto ?>)</label>"+
                            "<div class='radio'>"+
                                "<label><input type='radio' name='aporta_producto' id='tipo_hito' value='directo'>Aporta directamente</label><br>"+
                            "</div>"+
                            "<div class='radio'>"+
                                "<label><input type='radio' name='aporta_producto' id='tipo_hito' value='indirecto' checked>Aporta indirectamente</label><br>"+
                            "</div>"+
                        "</div>"+
                        "<div id='div_aporta_producto' style='display: none;'>"+

                        "</div>"+
                        <?php else: ?>
                            
                        <?php endif; ?>
                        "");
                        $('#meta_hito_vista').number(true);
                        $('#datos_cuantitativos').slideDown('swing');
                }
                else if (this.value == 'cualitativo') {
                    $('#datos_cuantitativos').slideUp('swing');
                    $('#datos_cuantitativos').empty();
                }
            });
        </script>
        <?php if(isset($actividad->id_producto)): ?>
        <script type="text/javascript">
            $(document).on('change', 'input[type=radio][name=aporta_producto]', function() {
                if (this.value == 'directo') {
                    $('#div_aporta_producto').append(
                    
                     "<div class='form-group'>"+
                        "<label for='id_meta_producto'>Meta del producto a la que aporta directamente</label>"+
                        "<select name='id_meta_producto' id='id_meta_producto' class='form-control'>"+
                            <?php foreach ($metas_cuantitativas as $meta_cuantitativa): ?>
                                "<option value='<?= $meta_cuantitativa->id_meta_producto_cuantitativa ?>'><?= $meta_cuantitativa->nombre_meta_producto_cuantitativa ?></option>"+
                            <?php endforeach; ?>
                        "</select>"+
                        "<p><?= form_error('id_meta_producto') ?></p>"+
                    "</div>");
                    $('#div_aporta_producto').slideDown('swing');
                }
                else if (this.value == 'indirecto') {
                    $('#div_aporta_producto').slideUp('swing');
                    $('#div_aporta_producto').empty();
                }
            });
        </script>
        <?php endif; ?>
        <script type="text/javascript">
            $(document).ready(function(){
                $('#meta_hito_vista').number(true);
            });
            $('#datos_cuantitativos').on('keyup', '#meta_hito_vista', function(){
                $('#meta_hito').val($('#meta_hito_vista').val());
            });
        </script>
    </body>
</html>