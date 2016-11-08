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

        <title>Registrar POA</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Registrar proyecto";
            $this->load->view('socio/nav', $datos);
            ?>
            <h4 class="text-primary">Registro POA</h4>
            <div>
                <form action="<?= base_url() . 'socio/registrar_nuevo_proyecto' ?>" id="proyecto" role="form" method="post" accept-charset="utf-8">
                    <?php if ($this->session->flashdata('poa_gestion_registrado')): ?>
                        <div class="alert alert-danger alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <strong>¡Error de registro!</strong> <?= $this->session->flashdata('poa_gestion_registrado') ?>
                        </div>
                    <?php endif; ?>
                    <div class="form-group">
                        <label for="nombre_proyecto">Nombre del POA</label>
                        <input type="text" name="nombre_proyecto" id="nombre_proyecto" placeholder="Nombre" class="form-control">
                        <p><?= form_error('nombre_proyecto') ?></p>
                    </div>
                    <div id="load" style="display: none">
                        asd
                    </div>
                    <div class="form-group">
                        <label for="id_anio">Gestión</label>
                        <select name="id_anio" id="id_anio" class="form-control">
                            <?php foreach ($anios as $anio): ?>
                                <option value="<?= $anio->id_anio ?>" <?php if ($anio->activo_anio == true): ?>selected<?php endif; ?>><?= $anio->valor_anio ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_proyecto">Descripción</label>
                        <textarea name="descripcion_proyecto" rows="4" placeholder="Descripción" class="form-control vresize"></textarea>
                        <p><?= form_error('descripcion_proyecto') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="presupuesto_proyecto_vista">Presupuesto del POA (Bs.)</label>
                        <input type="text" name="presupuesto_proyecto_vista" id="presupuesto_proyecto_vista" placeholder="Presupuesto" class="form-control">
                        <input type="hidden" name="presupuesto_proyecto" id="presupuesto_proyecto">
                        <p><?= form_error('presupuesto_proyecto') ?></p>
                        <label>Presupuesto disponible: Bs. <span class="number_decimal"><?= $presupuesto_disponible->presupuesto_disponible_institucion ?></span></label>
                    </div>
                    <input type="submit" name="submit" id="submit" value="Registrar POA" title="Registrar POA" class="btn btn-primary">
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
                            maxlength: 1024
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