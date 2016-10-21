<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

        <title>Editar PRODOC</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "PRODOC";
            $this->load->view('coordinador/nav', $datos);
            $n1 = 1;
            $n2 = 1;
            $n3 = 1;
            $n4 = 1;
            ?>
            <?php if ($prodoc): ?>
                <h4 class="text-justify text-primary"><?= $prodoc->nombre_prodoc ?></h4>
                <p class="text-justify"><?= $prodoc->descripcion_prodoc ?></p>
                <h4 class="text-primary"><?php echo($n1); ?>. Objetivo global</h4>
                <p class="text-justify"><?= $prodoc->objetivo_global_prodoc ?></p>
                <h4 class="text-primary"><?php  $n1 += 1; echo($n1);?>. Objetivo del proyecto</h4>
                <p class="text-justify"><?= $prodoc->objetivo_proyecto_prodoc ?></p>
                <p><a href="<?= base_url() . 'coordinador/modificar_prodoc/' . $prodoc->id_prodoc?>" class="btn btn-default">Modificar datos generales</a></p>
                <?php if ($prodoc->efectos): ?>
                    <?php foreach ($prodoc->efectos as $efecto): ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a data-toggle="collapse" href="#collapse_efecto_<?= $efecto->id_efecto ?>"><strong class="text-primary"><?= $n1.'.'.$n2 ?>. <?= $efecto->nombre_efecto ?></strong></a>
                            </div>
                            <div class="panel-body panel-collapse collapse on" id="collapse_efecto_<?= $efecto->id_efecto ?>">
                                <p class="text-justify"><strong>Descripción:</strong> <?= $efecto->descripcion_efecto ?></p>
                                <p><a href="<?= base_url() . 'coordinador/modificar_efecto/' . $prodoc->id_prodoc . '/' . $efecto->id_efecto?>" class="btn btn-default">Modificar efecto</a></p>
                                <?php if ($efecto->productos): ?>
                                    <?php $n3 = 1; ?>
                                    <?php foreach ($efecto->productos as $producto): ?>
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <a data-toggle="collapse" href="#collapse_producto_<?= $producto->id_producto ?>"><strong><?= $n1.'.'.$n2.'.'.$n3 ?> <?= $producto->nombre_producto ?></strong></a>
                                            </div>
                                            <div class="panel-body panel-collapse collapse on" id="collapse_producto_<?= $producto->id_producto ?>">
                                                <p class="text-justify"><strong>Descripción:</strong> <?= $producto->descripcion_producto ?></p>
                                                <p><a href="<?= base_url() . 'coordinador/modificar_producto/' . $prodoc->id_prodoc . '/' . $producto->id_producto?>" class="btn btn-default">Modificar producto</a></p>
                                                <?php if ($producto->metas_cuantitativas): ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Indicador</th>
                                                                    <th>Descripción</th>
                                                                    <th>Meta</th>
                                                                    <th width="15%">Acciones</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($producto->metas_cuantitativas as $meta_cuantitativa): ?>
                                                                    <tr>
                                                                        <td><?= $meta_cuantitativa->nombre_meta_producto_cuantitativa ?></td>
                                                                        <td><?= $meta_cuantitativa->descripcion_meta_producto_cuantitativa ?></td>
                                                                        <td><span class="number_integer"><?= $meta_cuantitativa->cantidad_meta_producto_cuantitativa ?></span> <?= $meta_cuantitativa->unidad_meta_producto_cuantitativa ?></td>
                                                                        <td><a href="<?= base_url() . 'coordinador/modificar_meta_producto_cuantitativa/' . $prodoc->id_prodoc . '/' .$meta_cuantitativa->id_meta_producto_cuantitativa ?>" class="btn btn-default btn-xs btn-block">Modificar indicador</a></td>
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
                                                            Todavía no se registraron indicadores para el producto <strong>"<?= $producto->nombre_producto ?>"</strong>.
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <p><a href="<?= base_url() . 'coordinador/registrar_meta_producto_cuantitativa/' . $prodoc->id_prodoc . '/' . $producto->id_producto ?>" class="btn btn-default">Registrar indicador</a></p>
                                            </div>
                                        </div>
                                        <?php $n3 += 1; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            Advertencia
                                        </div>
                                        <div class="panel-body">
                                            Todavía no se registraron productos para el efecto <strong>"<?= $efecto->nombre_efecto ?>"</strong>.
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <p><a href="<?= base_url() . 'coordinador/registrar_producto/' . $prodoc->id_prodoc . '/' . $efecto->id_efecto ?>" class="btn btn-default">Registrar producto</a></p>
                            </div>
                        </div>
                        <?php $n2 += 1; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            Advertencia
                        </div>
                        <div class="panel-body">
                            Todavía no se registraron efectos.
                        </div>
                    </div>
                <?php endif; ?>
                <p><a href="<?= base_url() . 'coordinador/registrar_efecto/' . $prodoc->id_prodoc ?>" class="btn btn-default">Registrar efecto</a></p>
            <?php else: ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Advertencia
                    </div>
                    <div class="panel-body">
                        Todavía no se registro el PRODOC.
                    </div>
                </div>
                <a href="<?= base_url() . 'coordinador/registrar_prodoc' ?>" class="btn btn-primary">Registrar PRODOC</a>
            <?php endif; ?>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.number_decimal').number(true, 2);
                $('.number_integer').number(true);
            });
        </script>
    </body>
</html>
