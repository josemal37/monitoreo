<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />

        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

        <title>Proyectos activos</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Gestion actual";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <form action="<?= base_url() . 'coordinador/habilitar_registro_poa_gestion' ?>" id="registro" role="form" method="post" accept-charset="utf-8">
                <div class="form-group">
                    <label for="valor_anio">Ingrese el año de trabajo</label>
                    <input type="text" name="vista_valor_anio" id="vista_valor_anio" placeholder="Año de trabajo" class="form-control number_integer">
                    <input type="hidden" name="valor_anio" id="valor_anio">
                    <p><?= form_error('valor_anio') ?></p>
                    <?php if($this->session->flashdata('anio_registrado')): ?>
                    <label class="text-danger"><?= $this->session->flashdata('anio_registrado') ?></label>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="submit" name="submit" value="Registrar gestión" title="Registrar gestión" class="btn btn-primary">
                </div>
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#registro').validate({
                    ignore: [],
                    rules: {
                        valor_anio: {
                            required: true,
                            number: true,
                            min: 2000
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.number_integer').number(true, 0);
            });
        </script>
        <script type="text/javascript">
            $('#vista_valor_anio').keyup(function() {
                $('#valor_anio').val($('#vista_valor_anio').val());
            });
        </script>
    </body>
</html>