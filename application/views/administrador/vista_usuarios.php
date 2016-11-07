<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>

        <title>Usuarios</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Usuarios";
            $this->load->view('administrador/nav', $datos);
            ?>
            <?php if (sizeof($usuarios) > 0): ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>Lista de usuarios</strong>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nombre completo</th>
                                    <th>Rol del usuario</th>
                                    <th>Telefono</th>
                                    <th>E-mail</th>
                                    <th>Institución</th>
                                    <th>Activo</th>
                                    <th width="15%">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td><?= $usuario->apellido_paterno_usuario . ' ' . $usuario->apellido_materno_usuario . ' ' . $usuario->nombre_usuario ?></td>
                                        <td><?= $usuario->nombre_rol ?></td>
                                        <td><?= $usuario->telefono_usuario ?></td>
                                        <td><?= $usuario->correo_usuario ?></td>
                                        <td><?= $usuario->nombre_institucion ?></td>
                                        <td>
                                            <?php if ($usuario->activo_usuario): ?>
                                                SI
                                            <?php else: ?>
                                                NO
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <a href="<?= base_url() . 'administrador/ver_usuario/' . $usuario->id_usuario ?>" class="btn btn-success btn-xs btn-block">Ver usuario</a>
                                            <a href="<?= base_url() . 'administrador/modificar_usuario/' . $usuario->id_usuario ?>" class="btn btn-primary btn-xs btn-block">Modificar datos generales</a>
                                            <a href="<?= base_url() . 'administrador/modificar_password_usuario/' . $usuario->id_usuario ?>" class="btn btn-warning btn-xs btn-block">Modificar password</a>
                                            <?php if ($usuario->activo_usuario): ?>
                                                <a href="<?= base_url() . 'administrador/desactivar_usuario/' . $usuario->id_usuario ?>" class="btn btn-danger btn-xs btn-block">Desactivar usuario</a>
                                            <?php else: ?>
                                                <a href="<?= base_url() . 'administrador/activar_usuario/' . $usuario->id_usuario ?>" class="btn btn-danger btn-xs btn-block">Activar usuario</a>
                                            <?php endif; ?>
                                        </td>
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
                            Todavía no se registraron usuarios.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <a href="<?= base_url() . 'administrador/nuevo_usuario' ?>" class="btn btn-primary">Nuevo usuario</a>
        </div>
    </body>
</html>