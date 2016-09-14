<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Reportes</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Reportes";
            $this->load->view('socio/nav', $datos);
            ?>
            <div class="panel panel-success">
                <div class="panel-heading">
                    Reportes
                </div>
                <div class="panel-body">
                    Los reportes se encuentran en desarrollo.
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
        <script type="text/javascript">
            $('#avance').popover({
                html: true,
                content: function() {
                    return $(this).parent().find('#contenedor_formulario').html();
                }
            });
        </script>
    </body>
</html>