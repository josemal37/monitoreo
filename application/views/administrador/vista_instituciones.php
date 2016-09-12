<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Instituciones</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Instituciones";
            $this->load->view('administrador/nav', $datos);
            ?>
            <?php if (sizeof($instituciones) > 0): ?>
                <h4>Instituciones</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Sigla</th>
                                <th>Nombre de la institución</th>
                                <th>Presupuesto (Bs.)</th>
                                <th>Activa</th>
                                <th>Administrar</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($instituciones as $institucion): ?>
                                <tr>
                                    <td><?= $institucion->sigla_institucion ?></td>
                                    <td><?= $institucion->nombre_institucion ?></td>
                                    <td><?= $institucion->presupuesto_institucion ?></td>
                                    <td>
                                        <?php if($institucion->activa_institucion): ?>
                                            SI
                                        <?php else: ?>
                                            NO
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?= base_url() . 'administrador/modificar_institucion/' . $institucion->id_institucion ?>" class="btn btn-primary btn-xs btn-block">Modificar institución</a>
                                        <?php if ($institucion->activa_institucion == true): ?>
                                            <a href="<?= base_url() . 'administrador/desactivar_institucion/' . $institucion->id_institucion ?>" class="btn btn-danger btn-xs btn-block">Desactivar institución</a>
                                        <?php else: ?>
                                            <a href="<?= base_url() . 'administrador/activar_institucion/' . $institucion->id_institucion ?>" class="btn btn-warning btn-xs btn-block">Activar institución</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <a href="<?= base_url() . 'administrador/nueva_institucion' ?>" class="btn btn-primary">Nueva institución</a>
                </div>
            <?php else: ?>
                <div class="panel panel-warning">
                        <div class="panel-heading">
                            Advertencia
                        </div>
                        <div class="panel-body">
                            Todavía no se registraron instituciones.
                        </div>
                    </div>
            <?php endif; ?>
        </div>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
    </body>
</html>