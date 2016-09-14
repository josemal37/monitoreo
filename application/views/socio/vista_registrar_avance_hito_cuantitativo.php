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
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('socio/nav', $datos);
            ?>
            <div>
                <h4><?= $actividad->nombre_actividad . ': ' . $hito->nombre_hito_cn ?></h4>
                <form action="<?= base_url() . 'socio/registrar_avance_hito_cuantitativo/' . $id_proyecto . '/' . $id_hito ?>" id="formulario_avance_hito" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group">
                        <label for="cantidad_avance_hito">Cantidad de avance</label>
                        <input type="number" name="cantidad_avance_hito" id="cantidad_avance_hito" placeholder="Cantidad de avance" class="form-control" required>
                        <p><?= form_error('cantidad_avance_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="fecha_avance_hito">Fecha del avance</label>
                        <input type="text" name="fecha_avance_hito" id="fecha_avance_hito" class="form-control" required>
                        <p><?= form_error('fecha_avance_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_avance_hito">Descripción</label>
                        <textarea name="descripcion_avance_hito" id="descripcion_avance_hito" rows="4" placeholder="Descripción" class="form-control"></textarea>
                        <p><?= form_error('descripcion_avance_hito') ?></p>
                    </div>
                    <div class="form-group" id="respaldos">
                        <div class="checkbox">
                            <label for="con_respaldos">
                                <input type="checkbox" name="con_respaldos" id="con_respaldos"><strong>Añadir documentos</strong>
                            </label>
                        </div>
                        <p><?= form_error('con_respaldos') ?></p>
                    </div>
                    <input type="hidden" name="id_hito" value="<?= $id_hito ?>" id="id_hito">
                    <input type="submit" name="submit" value="Registrar avance" title="Registrar avance" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.html.array.extend.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.file-input.js' ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_avance_hito').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        cantidad_avance_hito: {
                            required: true,
                            number: true,
                            min: 0
                        },
                        fecha_avance_hito: {
                            required: true,
                            date: true
                        },
                        descripcion_avance_hito: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        'titulo_documento_avance[]': {
                            required: true,
                            minlength: 5,
                            maxlength: 64
                        },
                        'descripcion_documento_avance[]': {
                            required: true,
                            minlength: 5,
                            maxlength: 512
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $("#fecha_avance_hito").datepicker({dateFormat: 'yy-mm-dd'});
        </script>
        <script type="text/javascript">
            $('#con_respaldos').change(function() {
                if ($(this).is(':checked')) {
                    
                    $('#respaldos').append("<div class='form-group' id='documentos_avance' style='display: none'>"+
                                                "<div class='table-responsive'>"+
                                                    "<table class='table table-bordered' id='tabla-respaldos'>"+
                                                        "<thead>"+
                                                            "<tr>"+
                                                                "<th>Titulo</th>"+
                                                                "<th>Descripción</th>"+
                                                                "<th width='25%'>Documento</th>"+
                                                                "<th width='15%'>Acciones</th>"+
                                                            "</tr>"+
                                                        "</thead>"+
                                                        "<tbody>"+
                                                            "<tr>"+
                                                                "<td><div class='form-group'><input type='text' name='titulo_documento_avance[]' id='titulo_documento_avance_1' class='form-control' required></div></td>"+
                                                                "<td><div class='form-group'><textarea name='descripcion_documento_avance[]' id='descripcion_documento_avance_1' class='form-control vresize' required></textarea></div></td>"+
                                                                "<td>"+
                                                                    "<div class='form-group'><input type='file' name='documento_avance_1' id='documento_avance_1' title='Seleccionar archivo' required></div>"+
                                                                "</td>"+
                                                                "<td><button type='button' name='eliminar_fila' id='eliminar_fila' class='btn btn-danger btn-block btn-xs'>Eliminar fila</button></td>"+
                                                            "</tr>"+
                                                        "</tbody>"+
                                                    "</table>"+
                                                "</div>"+
                                                "<button type='button' name='nueva_fila' id='nueva_fila' class='btn btn-success'>Añadir fila</button>"+
                                            "</div>");
                    $('#documentos_avance').show('swing');
                    $('#documento_avance_1').bootstrapFileInput();
                } else {
                    $('#documentos_avance').hide('swing');
                    $('#documentos_avance').remove();
                }
            });
        </script>
        <script type="text/javascript">
            var num_filas = 2;
            $('#respaldos').on('click', '#nueva_fila', function(){
                $('#tabla-respaldos >tbody').append(
                        "<tr>"+
                            "<td><div class='form-group'><input type='text' name='titulo_documento_avance[]' id='titulo_documento_avance_"+num_filas+"' class='form-control' required></div></td>"+
                            "<td><div class='form-group'><textarea name='descripcion_documento_avance[]' id='descripcion_documento_avance_"+num_filas+"' class='form-control vresize' required></textarea></div></td>"+
                            "<td>"+
                                "<div class='form-group'><input type='file' name='documento_avance_"+num_filas+"' id='documento_avance_"+num_filas+"' title='Seleccionar archivo' required></div>"+
                            "</td>"+
                            "<td><button type='button' name='eliminar_fila' id='eliminar_fila' class='btn btn-danger btn-block btn-xs'>Eliminar fila</button></td>"+
                        "</tr>"
                        );
                $('#documento_avance_'+num_filas).bootstrapFileInput();
                num_filas = num_filas + 1;
            });
            $('#respaldos').on('click', '#eliminar_fila', function(){
                if($('#tabla-respaldos >tbody >tr').length > 1) {
                    $(this).closest('tr').remove();
                }
            });
        </script>
    </body>
</html>