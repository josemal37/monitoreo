<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>

        <title>Ver avance</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('coordinador/nav', $datos);
            $estado = 'No aprobado';
            if ($avances_hito_cualitativo) {
                foreach ($avances_hito_cualitativo as $avance) {
                    if ($avance->aprobado_avance_hito_cl) {
                        $estado = 'Aprobado';
                    }
                }
            }
            ?>
            <div>
                <h4 class="text-primary">Avances del indicador</h4>
                <p class="text-justify"><strong>Indicador:</strong> <?= $hito_cualitativo->nombre_hito_cl ?></p>
                <p class="text-justify"><strong>Estado:</strong> <?= $estado ?></p>
                <p class="text-justify"><strong>Descripción:</strong> <?= $hito_cualitativo->descripcion_hito_cl ?></p>
            </div>
            <div>
                <?php if ($avances_hito_cualitativo): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Avances</strong>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Título</th>
                                        <th>Descripción</th>
                                        <th>Estado</th>
                                        <th width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    <?php foreach ($avances_hito_cualitativo as $avance): ?>
                                        <tr>
                                            <td><?= $avance->fecha_avance_hito_cl ?></td>
                                            <td><?= $avance->titulo_avance_hito_cl ?></td>
                                            <td><?= $avance->descripcion_avance_hito_cl ?></td>
                                            <td>
                                                <?php if ($avance->en_revision_avance_hito_cl): ?>
                                                    <label class="text-primary">En revisión</label>
                                                <?php else: ?>
                                                    <?php if ($avance->aprobado_avance_hito_cl): ?>
                                                        <label class="text-success">Aprobado</label>
                                                    <?php else: ?>
                                                        <label class="text-danger">No aprobado</label>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($avance->en_revision_avance_hito_cl): ?>
                                                    <button type="button" class="btn btn-success btn-xs btn-block" data-toggle="modal" data-target="#modal_revision_<?= $i ?>">Revisar</button>
                                                    <div id="modal_revision_<?= $i ?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title text-center text-primary">Revisar avance</h4>
                                                                </div>
                                                                <form action="<?= base_url() . 'coordinador/revisar_avance_hito_cualitativo/' . $id_proyecto . '/' . $id_hito . '/' . $avance->id_avance_hito_cl ?>" role="form" method="post" accept-charset="utf-8">
                                                                    <div class="modal-body text-justify">
                                                                        <p><strong>Fecha del avance:</strong> <?= $avance->fecha_avance_hito_cl ?></p>
                                                                        <p><strong>Título:</strong> <?= $avance->titulo_avance_hito_cl ?></p>
                                                                        <p><strong>Descripción:</strong> <?= $avance->descripcion_avance_hito_cl ?></p>
                                                                        <p><strong>Documento:</strong> <a href="<?= base_url() . 'coordinador/descarga/' . $id_institucion . '/' . $avance->documento_avance_hito_cl ?>" title="<?= $avance->documento_avance_hito_cl ?>" class="btn btn-success btn-xs">Descargar</a></p>
                                                                        <div>
                                                                            <label class="text-success"><input type="radio" name="estado" id="estado" value="aprobado"  style="vertical-align: middle; margin: 0px;">Aprobado</label><br>
                                                                            <label class="text-danger"><input type="radio" name="estado" id="estado" value="no_aprobado"  style="vertical-align: middle; margin: 0px;" checked> No aprobado</label>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <input type="submit" name="submit" id="submit" value="Guardar" title="Guardar" class="btn btn-primary">
                                                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-default btn-xs btn-block" data-toggle="modal" data-target="#modal_revision_<?= $i ?>">Ver detalle</button>
                                                    <div id="modal_revision_<?= $i ?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title text-center text-primary">Detalle</h4>
                                                                </div>
                                                                <div class="modal-body text-justify">
                                                                    <p><strong>Fecha del avance:</strong> <?= $avance->fecha_avance_hito_cl ?></p>
                                                                    <p><strong>Título:</strong> <?= $avance->titulo_avance_hito_cl ?></p>
                                                                    <p><strong>Descripción:</strong> <?= $avance->descripcion_avance_hito_cl ?></p>
                                                                    <p><strong>Documento:</strong> <a href="<?= base_url() . 'coordinador/descarga/' . $id_institucion . '/' . $avance->documento_avance_hito_cl ?>" title="<?= $avance->documento_avance_hito_cl ?>" class="btn btn-success btn-xs">Descargar</a></p>
                                                                    <p>
                                                                        <strong>Estado:</strong> 
                                                                        <?php if ($avance->en_revision_avance_hito_cl): ?>
                                                                            <label class="text-primary">En revisión</label>
                                                                        <?php else: ?>
                                                                            <?php if ($avance->aprobado_avance_hito_cl): ?>
                                                                                <label class="text-success">Aprobado</label>
                                                                            <?php else: ?>
                                                                                <label class="text-danger">No aprobado</label>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php $i = $i + 1; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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
