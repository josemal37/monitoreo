<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
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
            <h4><?= $usuario->apellido_paterno_usuario . ' ' . $usuario->apellido_materno_usuario . ' ' . $usuario->nombre_usuario ?></h4>
            <p><strong>Institución: </strong><?= $usuario->nombre_institucion . ' (' . $usuario->sigla_institucion . ')' ?></p>
            <p><strong>Rol: </strong><?= $usuario->nombre_rol ?></p>
            <p><strong>Login: </strong><?= $usuario->login_usuario ?></p>
            <p><strong>Password: </strong><?= $usuario->password_usuario ?></p>
            <p><strong>Número de teléfono: </strong><?= $usuario->telefono_usuario ?></p>
            <p><strong>Correo electrónico: </strong><?= $usuario->correo_usuario ?></p>
            <?php if ($usuario->activo_usuario == true) : ?>
                <p><strong>Activo: </strong>SI</p>
            <?php else: ?>
                <p><strong>Activo: </strong>NO</p>
            <?php endif; ?>
            <div>
                <a href="<?= base_url() . 'administrador/modificar_usuario/' . $usuario->id_usuario ?>" class="btn btn-primary">Modificar usuario</a>
                <?php if($usuario->activo_usuario): ?>
                    <a href="<?= base_url() . 'administrador/desactivar_usuario/' . $usuario->id_usuario ?>" class="btn btn-danger">Desactivar usuario</a>
                <?php else: ?>
                    <a href="<?= base_url() . 'administrador/activar_usuario/' . $usuario->id_usuario ?>" class="btn btn-warning">Activar usuario</a>
                <?php endif; ?>
                <a href="<?= base_url() . 'administrador/usuarios' ?>" class="btn btn-success">Volver</a>
            </div>
        </div>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
    </body>
</html>