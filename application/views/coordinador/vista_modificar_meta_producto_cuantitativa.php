<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/js/jquery-ui-1.12.0/jquery-ui.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>

        <title>Modificar indicador producto</title>
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
                <h4 class="text-primary">Modificar indicador</h4>
                <p><strong>Producto:</strong> <?= $producto->nombre_producto ?></p>
                <p><strong>Descripción del producto:</strong> <?= $producto->descripcion_producto ?></p>
                <form action="<?= base_url() . 'coordinador/modificar_meta_producto_cuantitativa/' . $id_prodoc . '/' . $meta_producto_cuantitativa->id_meta_producto_cuantitativa ?>" id="meta_producto_cuantitativa" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                    <div class="form-group">
                        <label for="nombre_meta_producto_cuantitativa">Nombre</label>
                        <input type="text" name="nombre_meta_producto_cuantitativa" id="nombre_meta_producto_cuantitativa" value="<?= $meta_producto_cuantitativa->nombre_meta_producto_cuantitativa ?>" placeholder="Nombre" class="form-control" required>
                        <p><?= form_error('nombre_meta_producto_cuantitativa') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="descripcion_meta_producto_cuantitativa">Descripción</label>
                        <textarea name="descripcion_meta_producto_cuantitativa" id="descripcion_meta_producto_cuantitativa" rows="4" placeholder="Descripción" class="form-control vresize"><?= $meta_producto_cuantitativa->descripcion_meta_producto_cuantitativa ?></textarea>
                        <p><?= form_error('descripcion_meta_producto_cuantitativa') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="cantidad_meta_producto_cuantitativa_vista">Cantidad</label>
                        <input type="text" name="cantidad_meta_producto_cuantitativa_vista" id="cantidad_meta_producto_cuantitativa_vista" value="<?= $meta_producto_cuantitativa->cantidad_meta_producto_cuantitativa ?>" class="form-control">
                        <input type="hidden" name="cantidad_meta_producto_cuantitativa" id="cantidad_meta_producto_cuantitativa" value="<?= $meta_producto_cuantitativa->cantidad_meta_producto_cuantitativa ?>">
                        <p><?= form_error('cantidad_meta_producto_cuantitativa') ?></p>
                    </div>
                    <div class="form-group">
                        <label for="unidad_meta_producto_cuantitativa">Unidad</label>
                        <input type="text" name="unidad_meta_producto_cuantitativa" id="unidad_meta_producto_cuantitativa" value="<?= $meta_producto_cuantitativa->unidad_meta_producto_cuantitativa ?>" placeholder="Unidad" class="form-control" required>
                        <p><?= form_error('unidad_meta_producto_cuantitativa') ?></p>
                    </div>
                    <input type="hidden" name="id_meta_producto_cuantitativa" id="id_meta_producto_cuantitativa" value="<?= $meta_producto_cuantitativa->id_meta_producto_cuantitativa ?>">
                    <input type="submit" name="submit" value="Modificar indicador" title="Modificar indicador" class="btn btn-primary">
                </form>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#meta_producto_cuantitativa').validate({
                    ignore: [],
                    rules: {
                        nombre_meta_producto_cuantitativa: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        descripcion_meta_producto_cuantitativa: {
                            required: true,
                            minlength: 5,
                            maxlength: 1024
                        },
                        cantidad_meta_producto_cuantitativa: {
                            required: true,
                            number: true
                        },
                        unidad_meta_producto_cuantitativa: {
                            required: true,
                            minlength: 5,
                            maxlength: 128
                        }
                    }
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#cantidad_meta_producto_cuantitativa_vista').number(true, 0);
            });
            $('#cantidad_meta_producto_cuantitativa_vista').keyup(function() {
                $('#cantidad_meta_producto_cuantitativa').val($('#cantidad_meta_producto_cuantitativa_vista').val());
            });
        </script>
    </body>
</html>