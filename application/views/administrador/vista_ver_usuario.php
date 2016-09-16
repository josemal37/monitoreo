<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>

        <title>Ver usuario</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Usuarios";
            $this->load->view('administrador/nav', $datos);
            ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <strong><?= $usuario->apellido_paterno_usuario . ' ' . $usuario->apellido_materno_usuario . ' ' . $usuario->nombre_usuario ?></strong>
                </div>
                <div class="panel-body">
                    <p>
                        <strong>Institución: </strong>
                        <?= $usuario->nombre_institucion . ' (' . $usuario->sigla_institucion . ')' ?>
                    </p>
                    <p>
                        <strong>Rol: </strong>
                        <?= $usuario->nombre_rol ?>
                    </p>
                    <p>
                        <strong class="hidden-sm hidden-xs">Número de teléfono: </strong>
                        <strong class="hidden-lg hidden-md">Teléfono: </strong>
                        <?= $usuario->telefono_usuario ?>
                    </p>
                    <p>
                        <strong class="hidden-sm hidden-xs">Correo electrónico: </strong>
                        <strong class="hidden-lg hidden-md">E-mail: </strong>
                        <?= $usuario->correo_usuario ?>
                    </p>
                    <?php if ($usuario->activo_usuario == true) : ?>
                        <p><strong>Activo: </strong>SI</p>
                    <?php else: ?>
                        <p><strong>Activo: </strong>NO</p>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <a href="<?= base_url() . 'administrador/modificar_usuario/' . $usuario->id_usuario ?>" class="btn btn-primary">Modificar usuario</a>
                <?php if ($usuario->activo_usuario): ?>
                    <a href="<?= base_url() . 'administrador/desactivar_usuario/' . $usuario->id_usuario ?>" class="btn btn-danger">Desactivar usuario</a>
                <?php else: ?>
                    <a href="<?= base_url() . 'administrador/activar_usuario/' . $usuario->id_usuario ?>" class="btn btn-warning">Activar usuario</a>
                <?php endif; ?>
            </div>
        </div>
    </body>
</html>