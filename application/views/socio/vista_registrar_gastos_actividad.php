<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />
        
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.file-input.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.html.array.extend.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.file-input.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>
        
        <title>Registrar gastos actividad</title>
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
                <h4><?= $actividad->nombre_actividad . ' (' . $actividad->fecha_inicio_actividad . ' - ' . $actividad->fecha_fin_actividad . ')' ?></h4>
                <form action="<?= base_url() . 'socio/registrar_gastos_actividad/' . $id_proyecto . '/' . $id_actividad ?>" id="formulario_gastos" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="tabla_gastos">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Importe (Bs.)</th>
                                    <th>Concepto</th>
                                    <th>Respaldo</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div class="form-group"><input type="text" name="fecha_gasto[]" id="fecha_gasto_1" class="form-control" required></div></td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="importe_gasto_vista[]" id="importe_gasto_vista_1" class="form-control importe">
                                            <input type="hidden" name="importe_gasto[]" id="importe_gasto_1">
                                        </div>
                                    </td>
                                    <td><div class="form-group"><textarea name="concepto_gasto[]" id="concepto_gasto_1" class="form-control vresize" required></textarea></div></td>
                                    <td>
                                        <div class="form-group">
                                            <input type='file' name='respaldo_1' id='respaldo_1' title="Seleccionar archivo" required class="required" aria-required="true" data-required>
                                        </div>
                                    </td>
                                    <td><button name="eliminar_fila" id="eliminar_fila" class="btn btn-danger btn-block btn-xs">Eliminar fila</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group">
                        <button type='button' name='nueva_fila' id='nueva_fila' class='btn btn-success'>Añadir fila</button>
                    </div>
                    <input type="hidden" name="id_actividad" value="<?= $id_actividad ?>" id="id_actividad">
                    <input type="submit" name="submit" value="Registrar gastos" title="Registrar gastos" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_gastos').validate({
                    ignore: [],
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        'fecha_gasto[]': {
                            required: true,
                            date: true
                        },
                        'importe_gasto[]': {
                            required: true,
                            number: true,
                            min: 0
                        },
                        'concepto_gasto[]': {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $("#fecha_gasto_1").datepicker({dateFormat: 'yy-mm-dd'});
            //$('#respaldo_1').bootstrapFileInput();
            $('#importe_gasto_vista_1').number(true, 2);
        </script>
        <script type="text/javascript">
            var num_filas = 2;
            $('#formulario_gastos').on('click', '#nueva_fila', function() {
                $('#tabla_gastos >tbody').append(
                        "<tr>" +
                        "<td><div class='form-group'><input type='text' name='fecha_gasto[]' id='fecha_gasto_" + num_filas + "' class='form-control' required></div></td>" +
                        "<td><div class='form-group'><input type='text' name='importe_gasto_vista[]' id='importe_gasto_vista_" + num_filas + "' class='form-control importe'>" +
                        "<input type='hidden' name='importe_gasto[]' id='importe_gasto_" + num_filas + "'></div></td>" +
                        "<td><div class='form-group'><textarea name='concepto_gasto[]' id='concepto_gasto_" + num_filas + "' class='form-control vresize' required></textarea></div></td>" +
                        "<td><div class='form-group'><input type='file' name='respaldo_" + num_filas + "' id='respaldo_" + num_filas + "' title='Seleccionar archivo' required  class='required' aria-required='true' data-required></div></td>" +
                        "<td><button name='eliminar_fila' id='eliminar_fila' class='btn btn-danger btn-block btn-xs'>Eliminar fila</button></td>" +
                        "</tr>"
                        );
                $("#fecha_gasto_" + num_filas).datepicker({dateFormat: 'yy-mm-dd'});
                $('#importe_gasto_vista_' + num_filas).number(true, 2);
                $('#respaldo_' + num_filas).bootstrapFileInput();
                num_filas = num_filas + 1;
            });
            $('#formulario_gastos').on('click', '#eliminar_fila', function() {
                if ($('#tabla_gastos >tbody >tr').length > 1) {
                    $(this).closest('tr').remove();
                }
            });
        </script>
        <script type="text/javascript">
            $(document).on('keyup', function(e){
                if(e.target.name == 'importe_gasto_vista[]'){
                    var id_vista = e.target.id;
                    var num_importe = id_vista.substring(id_vista.length - 1, id_vista.length);
                    var id_importe = 'importe_gasto_' + num_importe;
                    $('#'+id_importe).val($('#'+id_vista).val());
                }
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#respaldo_1').bootstrapFileInput();
            });
        </script>
    </body>
</html>