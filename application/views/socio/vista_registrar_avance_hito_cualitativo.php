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
        <script type="text/javascript" src="<?= base_url() . 'assets/js/additional-methods.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.js' ?>"></script>

        <title>Registrar avance</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('socio/nav', $datos);
            ?>
            <h4 class="text-primary">Registrar avance</h4>
            <p class="text-justify"><strong>Actividad:</strong> <?= $actividad->nombre_actividad ?></p>
            <p class="text-justify"><strong>Indicador:</strong> <?= $hito->nombre_hito_cl ?></p>
            <p class="text-justify"><strong>Descripción del indicador:</strong>  <?= $hito->descripcion_hito_cl ?></p>
            <div>
                <form action="<?= base_url() . 'socio/registrar_avance_hito_cualitativo/' . $id_proyecto . '/' . $id_hito ?>" id="formulario_avance_hito" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data" autocomplete="off">
                    <div class="form-group">
                        <label for="titulo_avance_hito">Título del documento</label>
                        <input type="text" name="titulo_avance_hito" id="titulo_avance_hito" placeholder="Título del documento" class="form-control" required>
                        <p><?= form_error('titulo_avance_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="fecha_avance_hito">Fecha de avance</label>
                        <input type="text" name="fecha_avance_hito" id="fecha_avance_hito" class="form-control" required>
                        <p><?= form_error('fecha_avance_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_avance_hito">Descripción</label>
                        <textarea name="descripcion_avance_hito" id="descripcion_avance_hito" rows="4" placeholder="Descripción" class="form-control vresize"></textarea>
                        <p><?= form_error('descripcion_avance_hito') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="documento_avance_hito">Documento</label>
                        <div>
                            <input type="file" name="documento_avance_hito" id="documento_avance_hito" title="Seleccione un archivo" required>
                        </div>
                        <br>
                        <strong>Extensiones validas: </strong> pdf, doc, docx, rar, zip, xls, xlsx, gif, jpg, jpeg, png.
                    </div>
                    <input type="hidden" name="id_hito" value="<?= $id_hito ?>" id="id_hito">
                    <input type="submit" name="submit" value="Registrar avance" title="Registrar avance" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_avance_hito').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        titulo_avance_hito: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        fecha_documento_avance_hito: {
                            required: true,
                            date: true
                        },
                        descripcion_avance_hito: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        documento_avance_hito: {
                            required: true,
                            extension: 'gif|jpg|jpeg|jpe|png|pdf|doc|docx|rar|zip|xls|xlsx'
                        }
                    },
                    messages: {
                        documento_avance_hito: {
                            required: 'Seleccione un archivo.',
                            extension: 'Extensión no valida.'
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#documento_avance_hito').bootstrapFileInput();
            });
        </script>
        <script type="text/javascript">
            $("#fecha_avance_hito").datepicker({dateFormat: 'yy-mm-dd'});
        </script>
    </body>
</html>