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
        
        <title>Registrar gasto</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <h4 class="text-primary">Registro de gasto</h4>
            <div>
                <p><strong>Actividad:</strong> <?= $actividad->nombre_actividad ?></p>
                <p><strong>Fecha de inicio:</strong> <?= $actividad->fecha_inicio_actividad ?></p>
                <p><strong>Fecha de fin:</strong> <?= $actividad->fecha_fin_actividad ?></p>
                <p><strong>Presupuesto:</strong> Bs. <span class="number_decimal"><?= $actividad->presupuesto_actividad ?></span></p>
                <p><strong>Descripción:</strong> <?= $actividad->descripcion_actividad ?></p>
                <form action="<?= base_url() . 'socio/registrar_gasto_estimado_actividad/' . $id_proyecto . '/' . $id_actividad ?>" id="formulario_gasto" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                    <div class="form-group">
                        <label for="gasto_actividad">Gasto (Bs.)</label>
                        <input type="text" name="gasto_actividad_vista" id="gasto_actividad_vista" placeholder="Gasto estimado" class="form-control">
                        <input type="hidden" name="gasto_actividad" id="gasto_actividad">
                        <p><?= form_error('gasto_actividad') ?></p>
                    </div>
                    <input type="hidden" name="id_actividad" value="<?= $id_actividad ?>" id="id_actividad">
                    <input type="submit" name="submit" value="Registrar gasto" title="Registrar gasto" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_gasto').validate({
                    ignore: [],
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        gasto_actividad: {
                            required: true,
                            number: true,
                            min: 0
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#gasto_actividad_vista').number(true, 2);
                $('.number_decimal').number(true, 2);
            });
            $('#gasto_actividad_vista').keyup(function() {
                $('#gasto_actividad').val($('#gasto_actividad_vista').val());
            });
        </script>
    </body>
</html>