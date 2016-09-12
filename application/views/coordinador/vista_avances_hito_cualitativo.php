<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Ver avance</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <div>
                <h4><?= $hito_cualitativo->nombre_hito_cl ?></h4>
                <p class="text-justify"><?= $hito_cualitativo->descripcion_hito_cl ?></p>
            </div>
            <div>
                <?php if ($avances_hito_cualitativo): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Título</th>
                                    <th>Descripción</th>
                                    <th>Estado</th>
                                    <th width="15%">Documento</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($avances_hito_cualitativo as $avance): ?>
                                    <tr>
                                        <td><?= $avance->fecha_avance_hito_cl ?></td>
                                        <td><?= $avance->titulo_avance_hito_cl ?></td>
                                        <td><?= $avance->descripcion_avance_hito_cl ?></td>
                                        <td>
                                            <?php if ($avance->en_revision_avance_hito_cl): ?>
                                                <div class="div_popover"> 
                                                    <a href="#" class="nombre_estado">En revisión</a>
                                                    <div id="contenedor_formulario" class="hide">
                                                        <a href="<?= base_url() . 'coordinador/aprobar_avance_hito_cualitativo/' . $id_proyecto . '/' . $id_hito . '/' . $avance->id_avance_hito_cl ?>" class="btn btn-success btn-xs">Aprobado</a>
                                                        <a href="<?= base_url() . 'coordinador/no_aprobar_avance_hito_cualitativo/' . $id_proyecto . '/' . $id_hito . '/' . $avance->id_avance_hito_cl ?>" class="btn btn-danger btn-xs">No aprobado</a>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <?php if ($avance->aprobado_avance_hito_cl): ?>
                                                    <label class="text-success">Aprobado</label>
                                                <?php else: ?>
                                                    <label class="text-danger">No aprobado</label>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td><a href="<?= base_url() . 'coordinador/descarga/' . $id_institucion . '/' . $avance->documento_avance_hito_cl ?>" title="<?= $avance->documento_avance_hito_cl ?>" class="btn btn-success btn-xs btn-block">Ver documento</a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            Advertencia
                        </div>
                        <div class="panel-body">
                            Todavía no se registraron avances.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript">
            $('.nombre_estado').popover({
                html: true,
                content: function() {
                    return $(this).parent().find('#contenedor_formulario').html();
                }
            });
        </script>
    </body>
</html>
