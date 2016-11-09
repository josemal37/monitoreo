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

        <title>Registrar efecto</title>
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
                <h4 class="text-primary">Registro de efecto</h4>
                <p><strong>PRODOC:</strong> <?= $prodoc->nombre_prodoc ?></p>
                <p><strong>Descripcion del PRODOC:</strong> <?= $prodoc->descripcion_prodoc ?></p>
                <form action="<?= base_url() . 'coordinador/registrar_efecto/' . $id_prodoc ?>" id="efecto" role="form" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="nombre_efecto">Nombre del efecto</label>
                        <input type="text" name="nombre_efecto" id="nombre_efecto" placeholder="Nombre" class="form-control">
                        <p><?= form_error('nombre_efecto') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_efecto">Descripción</label>
                        <textarea name="descripcion_efecto" id="descripcion_efecto" rows="4" placeholder="Descripción" class="form-control vresize"></textarea>
                        <p><?= form_error('descripcion_efecto') ?></p>
                    </div>
                    <input type="submit" name="submit" id="submit" value="Registrar efecto" title="Registrar efecto" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#efecto').validate({
                    ignore: [],
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_efecto: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        descripcion_efecto: {
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