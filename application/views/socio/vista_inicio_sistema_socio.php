<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>

        <title>Inicio</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Inicio";
            $this->load->view('socio/nav', $datos);
            ?>
            <?php if ($this->session->flashdata('error_proyecto_global')): ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Error de registro!</strong> <?= $this->session->flashdata('error_proyecto_global') ?>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error_proyecto')): ?>
                <div class="alert alert-warning alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <strong>¡Advertencia!</strong> <?= $this->session->flashdata('error_proyecto') ?>
                </div>
            <?php endif; ?>
            <div class="row hidden-sm hidden-xs">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <a href="<?= base_url() . 'socio/registrar_nuevo_proyecto' ?>">
                        <div class="btn-div">
                            <div class="div-center">
                                <img src="<?= base_url() . 'assets/images/registro.png' ?>" style="width: 100%" class="img-responsive">
                            </div>
                            <h3 class="text-center hidden-xs hidden-sm">Registrar POA</h3>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <a href="<?= base_url() . 'socio/ver_proyecto_gestion_actual'?>" id="avance">
                        <div class="btn-div">
                            <div class="div-center">
                                <img src="<?= base_url() . 'assets/images/avance.png' ?>" style="width: 100%" class="img-responsive">
                            </div>
                            <h3 class="text-center hidden-xs hidden-sm">Registrar avance POA</h3>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <a href="<?= base_url() . 'socio/ver_reporte_gestion_actual' ?>">
                        <div class="btn-div">
                            <div class="div-center">
                                <img src="<?= base_url() . 'assets/images/reporte.png' ?>" style="width: 100%" class="img-responsive">
                            </div>
                            <h3 class="text-center hidden-xs hidden-sm">Ver reportes</h3>
                        </div>
                    </a>
                </div>
            </div>
            <div class="hidden-lg hidden-md">
                <div class="">
                    <a href="<?= base_url() . 'socio/registrar_nuevo_proyecto' ?>" class="btn btn-success btn-lg btn-block">Registrar POA</a>
                </div>
                <br>
                <div>
                    <a href="<?= base_url() . 'socio/ver_proyecto_gestion_actual'?>" data-toggle="collapse" id="avance_mobile" class="btn btn-success btn-lg btn-block">Registrar avance POA</a>
                </div>
                <br>
                <div>
                    <a href="<?= base_url() . 'socio/ver_reportes' ?>" class="btn btn-success btn-lg btn-block">Ver reportes</a>
                </div>
            </div>
        </div>
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