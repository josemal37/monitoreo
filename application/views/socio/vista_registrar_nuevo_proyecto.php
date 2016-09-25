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

        <title>Registrar proyecto</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <div>
                <form action="<?= base_url() . 'socio/registrar_nuevo_proyecto' ?>" id="proyecto" role="form" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="nombre_proyecto">Nombre del proyecto</label>
                        <input type="text" name="nombre_proyecto" id="nombre_proyecto" placeholder="Nombre del proyecto" class="form-control">
                        <p><?= form_error('nombre_proyecto') ?></p>
                    </div>
                    <div id="load" style="display: none">
                        asd
                    </div>
                    <div class="form-group">
                        <label for="descripcion_proyecto">Descripción</label>
                        <textarea name="descripcion_proyecto" rows="4" placeholder="Descripción" class="form-control vresize"></textarea>
                        <p><?= form_error('descripcion_proyecto') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="presupuesto_proyecto_vista">Presupuesto (Bs.)</label>
                        <input type="text" name="presupuesto_proyecto_vista" id="presupuesto_proyecto_vista" placeholder="Presupuesto" class="form-control">
                        <input type="hidden" name="presupuesto_proyecto" id="presupuesto_proyecto">
                        <p><?= form_error('presupuesto_proyecto') ?></p>
                        <label>Disponible: Bs. <span class="number_decimal"><?= $presupuesto_disponible->presupuesto_disponible_institucion ?></span></label>
                    </div>
                    <input type="submit" name="submit" id="submit" value="Registrar proyecto" title="Registrar proyecto" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#proyecto').validate({
                    ignore: [],
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_proyecto: {
                            required: true,
                            minlength: 5,
                            maxlength: 128,
                            remote: {
                                url: '<?= base_url() . 'socio/existe_nombre_proyecto_institucion_ajax' ?>',
                                method: 'POST',
                                cache: false,
                                dataType: "json",
                                data: {
                                    nombre_proyecto: function() {
                                        return $('#nombre_proyecto').val();
                                    }
                                }
                            }
                        },
                        descripcion_proyecto: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        presupuesto_proyecto: {
                            required: true,
                            number: true,
                            min: 0,
                            max: <?= $presupuesto_disponible->presupuesto_disponible_institucion ?>
                        }
                    },
                    messages: {
                        nombre_proyecto: {
                            remote: 'Este nombre de proyecto ya se encuentra registrado.'
                        },
                        presupuesto_proyecto: {
                            max: 'Por favor, escribe un valor menor o igual al disponible.'
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#presupuesto_proyecto_vista').number(true, 2);
                $('.number_decimal').number(true, 2);
            });
            $('#presupuesto_proyecto_vista').keyup(function() {
                $('#presupuesto_proyecto').val($('#presupuesto_proyecto_vista').val());
            });
        </script>
    </body>
</html>