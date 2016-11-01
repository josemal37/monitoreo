<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

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
            <h4 class="text-primary">Avances del indicador</h4>
            <p class="text-justify"><strong>Indicador:</strong> <?= $hito_cuantitativo->nombre_hito_cn ?></p>
            <p class="text-justify"><strong>Meta:</strong> <span class="number_integer"><?= $hito_cuantitativo->meta_hito_cn ?></span> <?= $hito_cuantitativo->unidad_hito_cn ?></p>
            <p class="text-justify"><strong>Avance:</strong> <span class="number_integer"><?= $hito_cuantitativo->avance_hito_cn ?></span> <?= $hito_cuantitativo->unidad_hito_cn ?></p>
            <p class="text-justify"><strong>Descripción:</strong> <?= $hito_cuantitativo->descripcion_hito_cn ?></p>
            <div>
                <?php if (sizeof($avances_hito_cuantitativo) > 0): ?>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <strong>Avances</strong>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fecha</th>
                                        <th>Cantidad de avance</th>
                                        <th width="50%">Descripción</th>
                                        <th>Estado</th>
                                        <th width="15%">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 0; ?>
                                    <?php foreach ($avances_hito_cuantitativo as $avance): ?>
                                        <tr>
                                            <td><?= $avance->fecha_avance_hito_cn ?></td>
                                            <td><span class="number_integer"><?= $avance->cantidad_avance_hito_cn ?></span> <?= $hito_cuantitativo->unidad_hito_cn ?></td>
                                            <td><?= $avance->descripcion_avance_hito_cn ?></td>
                                            <td>
                                                <?php if ($avance->en_revision_avance_hito_cn): ?>
                                                    <label class="text-primary">En revisión</label>
                                                <?php else: ?>
                                                    <?php if ($avance->aprobado_avance_hito_cn): ?>
                                                        <label class="text-success">Aprobado</label>
                                                    <?php else: ?>
                                                        <label class="text-danger">No aprobado</label>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php if ($avance->en_revision_avance_hito_cn): ?>
                                                    <button type="button" class="btn btn-success btn-xs btn-block" data-toggle="modal" data-target="#modal_revision_<?= $i ?>">Revisar</button>
                                                    <div id="modal_revision_<?= $i ?>" class="modal fade" role="dialog">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                                    <h4 class="modal-title text-primary">Revisar avance</h4>
                                                                </div>
                                                                <form action="<?= base_url() . 'coordinador/revisar_avance_hito_cuantitativo/' . $id_proyecto . '/' . $id_hito . '/' . $avance->id_avance_hito_cn ?>" role="form" method="post" accept-charset="utf-8">
                                                                    <div class="modal-body text-justify">
                                                                        <p><strong>Fecha del avance:</strong> <?= $avance->fecha_avance_hito_cn ?></p>
                                                                        <p><strong>Cantidad del avance:</strong> <span class="number_integer"><?= $avance->cantidad_avance_hito_cn ?></span> <?= $hito_cuantitativo->unidad_hito_cn ?></p>
                                                                        <p><strong>Descripción:</strong> <?= $avance->descripcion_avance_hito_cn ?></p>
                                                                        <?php if ($documentos[$i]): ?>
                                                                            <div class="table-responsive">
                                                                                <table class="table table-bordered table-condensed">
                                                                                    <thead>
                                                                                        <tr>
                                                                                            <th>Título</th>
                                                                                            <th>Descripción</th>
                                                                                            <th>Archivo</th>
                                                                                        </tr>
                                                                                    </thead>
                                                                                    <tbody>
                                                                                        <?php foreach ($documentos[$i] as $documento): ?>
                                                                                            <tr>
                                                                                                <td><?= $documento->titulo_documento_avance_hito_cn ?></td>
                                                                                                <td><?= $documento->descripcion_documento_avance_hito_cn ?></td>
                                                                                                <td width="10%"><a href="<?= base_url() . 'coordinador/descarga/' . $id_institucion . '/' . $documento->archivo_documento_avance_hito_cn ?>" title="<?= $documento->archivo_documento_avance_hito_cn ?>" class="btn btn-success btn-xs btn-block">Descargar</a></td>
                                                                                            </tr>
                                                                                        <?php endforeach; ?>
                                                                                    </tbody>
                                                                                </table>
                                                                            </div>
                                                                        <?php else: ?>
                                                                            <label class="text-warning">Sin documentos</label>
                                                                        <?php endif; ?>
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
                                                                    <h4 class="modal-title text-primary">Detalle</h4>
                                                                </div>
                                                                <div class="modal-body text-justify">
                                                                    <p><strong>Fecha del avance:</strong> <?= $avance->fecha_avance_hito_cn ?></p>
                                                                    <p><strong>Cantidad del avance:</strong> <span class="number_integer"><?= $avance->cantidad_avance_hito_cn ?></span> <?= $hito_cuantitativo->unidad_hito_cn ?></p>
                                                                    <p><strong>Descripción:</strong> <?= $avance->descripcion_avance_hito_cn ?></p>
                                                                    <?php if ($documentos[$i]): ?>
                                                                        <div class="table-responsive">
                                                                            <table class="table table-bordered table-condensed">
                                                                                <thead>
                                                                                    <tr>
                                                                                        <th>Título</th>
                                                                                        <th>Descripción</th>
                                                                                        <th>Archivo</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody>
                                                                                    <?php foreach ($documentos[$i] as $documento): ?>
                                                                                        <tr>
                                                                                            <td><?= $documento->titulo_documento_avance_hito_cn ?></td>
                                                                                            <td><?= $documento->descripcion_documento_avance_hito_cn ?></td>
                                                                                            <td width="10%"><a href="<?= base_url() . 'coordinador/descarga/' . $id_institucion . '/' . $documento->archivo_documento_avance_hito_cn ?>" title="<?= $documento->archivo_documento_avance_hito_cn ?>" class="btn btn-success btn-xs btn-block">Descargar</a></td>
                                                                                        </tr>
                                                                                    <?php endforeach; ?>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <p><label class="text-warning">No tiene documentos.</label></p>
                                                                    <?php endif; ?>
                                                                    <p>
                                                                        <Strong>Estado: </Strong>
                                                                        <?php if ($avance->aprobado_avance_hito_cn): ?>
                                                                            <label class="text-success">Aprobado</label>
                                                                        <?php else: ?>
                                                                            <label class="text-danger">No aprobado</label>
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
        <script type="text/javascript">
            $(document).ready(function() {
                $('.number_decimal').number(true, 2);
                $('.number_integer').number(true);
            });
        </script>
    </body>
</html>
