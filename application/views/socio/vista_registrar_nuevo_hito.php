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
        
        <title>Registrar hito</title>
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
                <form action="<?= base_url() . 'socio/registrar_nuevo_hito/' . $id_proyecto . '/' . $id_actividad ?>" id="formulario_hito" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                    <div class="form-group">
                        <label for="tipo_hito">Tipo de hito</label>
                        <div class="radio">
                            <label><input type="radio" name="tipo_hito" id="tipo_hito" value="cuantitativo">Cuantitativo</label><br>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="tipo_hito" id="tipo_hito" value="cualitativo" checked>Cualitativo</label><br>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nombre_hito">Nombre del hito</label>
                        <input type="text" name="nombre_hito" id="nombre_hito" placeholder="Nombre del hito" class="form-control" required>
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
                    <input type="submit" name="submit" value="Registrar hito" title="Registrar hito" class="btn btn-primary">
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
                            "<label for='meta_hito_vista'>Meta del hito</label>"+
                            "<input type='text' name='meta_hito_vista' id='meta_hito_vista' placeholder='meta del hito' class='form-control'>"+
                            "<input type='hidden' name='meta_hito' id='meta_hito'>"+
                            "<p><?= form_error('meta_hito') ?></p>"+
                        "</div>"+
                        "<div class='form-group'>"+
                            "<label for='unidad_hito'>Unidad</label>"+
                            "<input type='text' name='unidad_hito' id='unidad_hito' placeholder='Unidad del hito' class='form-control' required>"+
                            "<p><?= form_error('unidad_hito') ?></p>"+
                        "</div>");
                        $('#datos_cuantitativos').slideDown('swing');
                }
                else if (this.value == 'cualitativo') {
                    $('#datos_cuantitativos').slideUp('swing');
                    $('#datos_cuantitativos').empty();
                }
            });
        </script>
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