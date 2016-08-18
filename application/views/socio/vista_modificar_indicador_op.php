<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Modificar indicador operativo</title>
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido socio</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <div>
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
                        <input type="date" name="fecha_limite_indicador_op" value="<?= $indicador->fecha_limite_indicador_op ?>" class="form-control" required>
                        <p><?= form_error('fecha_limite_indicador_op') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="meta_op">Meta del indicador</label>
                        <input type="number" name="meta_op" value="<?= $indicador->meta_op ?>" placeholder="Meta del indicador" class="form-control" required>
                        <p><?= form_error('meta_op') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="aceptable_op">Valor aceptable</label>
                        <input type="number" name="aceptable_op" value="<?= $indicador->aceptable_op ?>" placeholder="Valor aceptable" class="form-control" required>
                        <p><?= form_error('aceptable_op') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="limitado_op">Valor limitado</label>
                        <input type="number" name="limitado_op" value="<?= $indicador->limitado_op ?>" placeholder="Valor limitado" class="form-control" required>
                        <p><?= form_error('limitado_op') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="no_aceptable_op">Valor no aceptable</label>
                        <input type="number" name="no_aceptable_op" value="<?= $indicador->no_aceptable_op ?>" placeholder="Valor no aceptable" class="form-control" required>
                        <p><?= form_error('no_aceptable_op') ?></p>
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
    </body>
</html>