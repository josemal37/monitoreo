<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />
        <title>Registrar hito</title>
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
                                    <th width="12%">Fecha</th>
                                    <th width="10%">Importe (Bs.)</th>
                                    <th>Concepto</th>
                                    <th width="20%">Respaldo</th>
                                    <th width="15%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><div class="form-group"><input type="text" name="fecha_gasto[]" id="fecha_gasto_1" class="form-control" required></div></td>
                                    <td>
                                        <div class="form-group">
                                            <input type="text" name="importe_gasto_vista[]" id="importe_gasto_vista_1" class="form-control importe" required>
                                            <input type="hidden" name="importe_gasto[]" id="importe_gasto_1">
                                        </div>
                                    </td>
                                    <td><div class="form-group"><textarea name="concepto_gasto[]" id="concepto_gasto_1" class="form-control vresize" required></textarea></div></td>
                                    <td>
                                        <div class="form-group">
                                            <input type='file' name='respaldo_1' id='respaldo_1' title="Archivo" required class="required" aria-required="true" data-required>
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
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.file-input.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>
        <script type="text/javascript">
            $.extend($.validator.prototype, {
                showErrors: function(errors) {
                    if (errors) {
                        // add items to error list and map
                        $.extend(this.errorMap, errors);
                        this.errorList = [];
                        for (var name in errors) {
                            this.errorList.push({
                                message: errors[name],
                                /* NOTE THAT IM COMMENTING THIS OUT
                                 element: this.findByName(name)[0]
                                 */
                                element: this.findById(name)[0]
                            });
                        }
                        // remove items from success list
                        this.successList = $.grep(this.successList, function(element) {
                            return !(element.name in errors);
                        });
                    }
                    this.settings.showErrors
                            ? this.settings.showErrors.call(this, this.errorMap, this.errorList)
                            : this.defaultShowErrors();
                },
                findById: function(id) {
                    // select by name and filter by form for performance over form.find(“[id=…]”)
                    var form = this.currentForm;
                    return $(document.getElementById(id)).map(function(index, element) {
                        return element.form == form && element.id == id && element || null;
                    });
                },
                checkForm: function() {
                    this.prepareForm();
                    for (var i = 0, elements = (this.currentElements = this.elements()); elements[i]; i++) {
                        if (this.findByName(elements[i].name).length != undefined && this.findByName(elements[i].name).length > 1) {
                            for (var cnt = 0; cnt < this.findByName(elements[i].name).length; cnt++) {
                                this.check(this.findByName(elements[i].name)[cnt]);
                            }
                        } else {
                            this.check(elements[i]);
                        }
                    }
                    return this.valid();
                }
            });
            $(document).ready(function() {
                $('#formulario_gastos').validate({
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
                        "<td><div class='form-group'><input type='text' name='importe_gasto_vista[]' id='importe_gasto_vista_" + num_filas + "' class='form-control importe' required>" +
                        "<input type='hidden' name='importe_gasto[]' id='importe_gasto_" + num_filas + "'></div></td>" +
                        "<td><div class='form-group'><textarea name='concepto_gasto[]' id='concepto_gasto_" + num_filas + "' class='form-control vresize' required></textarea></div></td>" +
                        "<td><div class='form-group'><input type='file' class='msg_respaldo' name='respaldo_" + num_filas + "' id='respaldo_" + num_filas + "' required  class='required' aria-required='true' data-required></div></td>" +
                        "<td><button name='eliminar_fila' id='eliminar_fila' class='btn btn-danger btn-block btn-xs'>Eliminar fila</button></td>" +
                        "</tr>"
                        );
                $("#fecha_gasto_" + num_filas).datepicker({dateFormat: 'yy-mm-dd'});
                $('#importe_gasto_vista_' + num_filas).number(true, 2);
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
    </body>
</html>