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

        <title>Registrar PRODOC</title>
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
                <h4 class="text-primary">Registro de PRODOC</h4>
                <form action="<?= base_url() . 'coordinador/registrar_prodoc' ?>" id="prodoc" role="form" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="nombre_prodoc">Nombre del PRODOC</label>
                        <input type="text" name="nombre_prodoc" id="nombre_prodoc" placeholder="Nombre" class="form-control">
                        <p><?= form_error('nombre_prodoc') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_prodoc">Descripción</label>
                        <textarea name="descripcion_prodoc" id="descripcion_prodoc" rows="4" placeholder="Descripción" class="form-control vresize"></textarea>
                        <p><?= form_error('descripcion_prodoc') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="objetivo_global_prodoc">Objetivo global</label>
                        <textarea name="objetivo_global_prodoc" id="objetivo_global_prodoc" rows="4" placeholder="Objetivo global" class="form-control vresize"></textarea>
                        <p><?= form_error('objetivo_global_prodoc') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="objetivo_proyecto_prodoc">Objetivo del proyecto</label>
                        <textarea name="objetivo_proyecto_prodoc" id="objetivo_proyecto_prodoc" rows="4" placeholder="Objetivo del proyecto" class="form-control vresize"></textarea>
                        <p><?= form_error('objetivo_proyecto_prodoc') ?></p>
                    </div>
                    <input type="submit" name="submit" id="submit" value="Registrar PRODOC" title="Registrar PRODOC" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#prodoc').validate({
                    ignore: [],
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_prodoc: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        descripcion_prodoc: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        objetivo_global_prodoc: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        objetivo_proyecto_prodoc: {
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