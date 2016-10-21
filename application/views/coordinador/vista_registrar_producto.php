<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

        <title>Registrar producto</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "PRODOC";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <div>
                <h4 class="text-primary"><?= $efecto->nombre_efecto ?></h4>
                <p class="text-justify"><?= $efecto->descripcion_efecto ?></p>
                <h4 class="text-primary">Registro de producto</h4>
                <form action="<?= base_url() . 'coordinador/registrar_producto/' . $id_prodoc . '/' . $id_efecto ?>" id="producto" role="form" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="nombre_producto">Nombre del producto</label>
                        <input type="text" name="nombre_producto" id="nombre_producto" placeholder="Nombre" class="form-control">
                        <p><?= form_error('nombre_producto') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_producto">Descripción</label>
                        <textarea name="descripcion_producto" id="descripcion_producto" rows="4" placeholder="Descripción" class="form-control vresize"></textarea>
                        <p><?= form_error('descripcion_producto') ?></p>
                    </div>
                    <input type="hidden" name="id_efecto" id="id_efecto" value="<?= $id_efecto ?>">
                    <input type="submit" name="submit" id="submit" value="Registrar producto" title="Registrar producto" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#producto').validate({
                    ignore: [],
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_producto: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        descripcion_producto: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        }
                    }
                });
            });
        </script>
    </body>
</html>