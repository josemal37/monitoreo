<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
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
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <a href="<?= base_url() . 'socio/registrar_nuevo_proyecto' ?>">
                        <div class="btn-div">
                            <div class="div-center">
                                <img src="<?= base_url() . 'assets/images/registro.png' ?>" style="width: 100%" class="img-responsive">
                            </div>
                            <h3 class="text-center hidden-xs hidden-sm">Registrar proyecto</h3>
                        </div>
                    </a>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <a href="#" id="avance" onclick="return false;">
                        <div class="btn-div">
                            <div class="div-center">
                                <img src="<?= base_url() . 'assets/images/avance.png' ?>" style="width: 100%" class="img-responsive">
                            </div>
                            <h3 class="text-center hidden-xs hidden-sm">Registrar avances</h3>
                        </div>
                    </a>
                    <div id="contenedor_formulario" class="hide">
                        <?php if($proyectos):?>
                            <?php foreach ($proyectos as $proyecto): ?>
                                <a href="<?= base_url() . 'socio/ver_proyecto/' . $proyecto->id_proyecto ?>" class="btn btn-success btn-xs"><?= $proyecto->nombre_proyecto?></a>
                            <?php endforeach; ?>
                        <?php else: ?>
                            No existen proyectos activos.
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
                    <a href="<?= base_url() . 'socio/ver_reportes'?>">
                        <div class="btn-div">
                            <div class="div-center">
                                <img src="<?= base_url() . 'assets/images/reporte.png' ?>" style="width: 100%" class="img-responsive">
                            </div>
                            <h3 class="text-center hidden-xs hidden-sm">Ver reportes</h3>
                        </div>
                    </a>
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