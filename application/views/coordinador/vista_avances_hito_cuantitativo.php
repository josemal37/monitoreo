<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Ver avance</title>
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido coordinador</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <div>
                <h4><?= $hito_cuantitativo->nombre_hito_cn ?></h4>
                <p class="text-justify"><?= $hito_cuantitativo->descripcion_hito_cn ?></p>
            </div>
            <div>
                <?php if (sizeof($avances_hito_cuantitativo) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th width="50%">Descripción</th>
                                    <th>Cantidad de avance</th>
                                    <th>Estado</th>
                                    <th width="15%">Documentos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php foreach ($avances_hito_cuantitativo as $avance): ?>
                                    <tr>
                                        <td><?= $avance->fecha_avance_hito_cn ?></td>
                                        <td><?= $avance->descripcion_avance_hito_cn ?></td>
                                        <td><?= $avance->cantidad_avance_hito_cn . ' ' . $hito_cuantitativo->unidad_hito_cn ?></td>
                                        <td class="text-center">
                                            <?php if ($avance->en_revision_avance_hito_cn): ?>
                                                <div class="div_popover"> 
                                                    <a href="#" class="nombre_estado">En revisión</a>
                                                    <div id="contenedor_formulario" class="hide">
                                                        <a href="<?= base_url() . 'coordinador/aprobar_avance_hito_cuantitativo/' . $id_proyecto . '/' . $id_hito . '/' . $avance->id_avance_hito_cn ?>" class="btn btn-success btn-xs">Aprobado</a>
                                                        <a href="<?= base_url() . 'coordinador/no_aprobar_avance_hito_cuantitativo/' . $id_proyecto . '/' . $id_hito . '/' . $avance->id_avance_hito_cn ?>" class="btn btn-danger btn-xs">No aprobado</a>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <?php if ($avance->aprobado_avance_hito_cn): ?>
                                                    <label class="text-success">Aprobado</label>
                                                <?php else: ?>
                                                    <label class="text-danger">No aprobado</label>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?php if ($documentos[$i]): ?>
                                                <?php foreach ($documentos[$i] as $documento): ?>
                                                    <a href="<?= base_url() . 'coordinador/descarga/' . $id_institucion . '/' . $documento->archivo_documento_avance_hito_cn ?>" title="<?= $documento->archivo_documento_avance_hito_cn ?>" class="btn btn-success btn-xs btn-block">Ver documento</a>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <label class="text-warning">Sin documentos</label>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php $i = $i + 1; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    Todavía no se registraron avances.
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
