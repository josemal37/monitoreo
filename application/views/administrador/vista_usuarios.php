<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Usuarios</title>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">Bienvenido administrador</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Usuarios";
            $this->load->view('administrador/nav', $datos);
            ?>
            <h4>Usuarios</h4>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre completo</th>
                            <th>Rol del usuario</th>
                            <th>Telefono</th>
                            <th>E-mail</th>
                            <th>Institución</th>
                            <th>Administrar</th>
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
                                    <a href="<?= base_url() . 'administrador/modificar_usuario/' . $usuario->id_usuario ?>" class="btn btn-success btn-xs btn-block">Modificar usuario</a>
                                    <a href="<?= base_url() . 'administrador/eliminar_usuario/' . $usuario->id_usuario ?>" class="btn btn-danger btn-xs btn-block">Eliminar usuario</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="<?= base_url() . 'administrador/nuevo_usuario' ?>" class="btn btn-primary">Nuevo usuario</a>
            </div>
        </div>
    </body>
</html>