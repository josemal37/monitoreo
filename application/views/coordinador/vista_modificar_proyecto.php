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

        <title>Modificar proyecto</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <h4 class="text-primary">Modificar proyecto</h4>
            <div>
                <?php if ($this->session->flashdata('error_proyecto_global')): ?>
                    <div class="alert alert-danger alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>¡Error de registro!</strong> <?= $this->session->flashdata('error_proyecto_global') ?>
                    </div>
                <?php endif; ?>
                <form action="<?= base_url() . 'coordinador/modificar_proyecto/' . $proyecto->id_proyecto_global ?>" id="proyecto" role="form" method="post" accept-charset="utf-8">
                    <div class="form-group">
                        <label for="nombre_proyecto">Nombre del proyecto</label>
                        <input type="text" name="nombre_proyecto" id="nombre_proyecto" value="<?= $proyecto->nombre_proyecto_global ?>" placeholder="Nombre del proyecto" class="form-control">
                        <p><?= form_error('nombre_proyecto') ?></p>
                    </div>
                    <div id="load" style="display: none">
                        asd
                    </div>
                    <div class="form-group">
                        <label for="descripcion_proyecto">Descripción</label>
                        <textarea name="descripcion_proyecto" rows="4" placeholder="Descripción" class="form-control vresize"><?= $proyecto->descripcion_proyecto_global ?></textarea>
                        <p><?= form_error('descripcion_proyecto') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="id_institucion">Institución</label>
                        <select name="id_institucion" id="id_institucion" class="form-control">
                            <?php foreach ($instituciones as $institucion): ?>
                            <option value="<?= $institucion->id_institucion ?>" <?php if($institucion->id_institucion == $proyecto->id_institucion):?>selected<?php endif; ?>><?= $institucion->nombre_institucion ?></option>
                            <?php endforeach; ?>
                        </select>
                        <input type="hidden" name="id_institucion_antiguo" id="id_institucion_antiguo" value="<?= $proyecto->id_institucion ?>">
                        <p><?= form_error('id_institucion') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="presupuesto_proyecto_vista">Presupuesto (Bs.)</label>
                        <input type="text" name="presupuesto_proyecto_vista" id="presupuesto_proyecto_vista" value="<?= $proyecto->presupuesto_proyecto_global ?>" placeholder="Presupuesto" class="form-control">
                        <input type="hidden" name="presupuesto_proyecto" id="presupuesto_proyecto" value="<?= $proyecto->presupuesto_proyecto_global ?>">
                        <p><Strong>Presupuesto asignado:</strong> Bs. <span class="number_decimal"><?= $proyecto->presupuesto_asignado ?></span></p>
                        <p><?= form_error('presupuesto_proyecto') ?></p>
                    </div>
                    <input type="hidden" name="id_proyecto" id="id_proyecto" value="<?= $proyecto->id_proyecto_global ?>">
                    <input type="submit" name="submit" id="submit" value="Modificar proyecto" title="modificar proyecto" class="btn btn-primary">
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
                            maxlength: 128
                        },
                        descripcion_proyecto: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        presupuesto_proyecto: {
                            required: true,
                            number: true,
                            min: <?= $proyecto->presupuesto_asignado ?>,
                            max: 9999999999.99
                        }
                    },
                    messages: {
                        presupuesto_proyecto: {
                            min: "Debe asignar un presupuesto mayor al presupuesto ya asignado."
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