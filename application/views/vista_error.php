<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
        
        <title>Error</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <div class="panel panel-danger">
                <div class="panel-heading">
                    Error en el sistema
                </div>
                <div class="panel-body">
                    <p>Ocurrió un error al intentar realizar la última acción en el sistema.</p>
                </div>
            </div>
            <p><a href="<?= base_url() ?>" class="btn btn-primary">Volver al inicio</a></p>
        </div>
    </body>
</html>