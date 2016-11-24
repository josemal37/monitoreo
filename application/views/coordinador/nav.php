<?php
$nombre_usuario = $this->session->userdata('nombre_usuario');
$apellido_usuario = $this->session->userdata('apellido_usuario');
$nombre_institucion = $this->session->userdata('nombre_institucion');
$id_usuario = $this->session->userdata('id_usuario');
$telefono_usuario = $this->session->userdata('telefono_usuario');
$correo_usuario = $this->session->userdata('correo_usuario');
$nombre_rol = $this->session->userdata('nombre_rol');
?>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Menú</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li <?php if ($activo == "Proyectos activos"): ?>class="active"<?php endif; ?>><?= anchor(base_url() . 'coordinador/ver_proyectos', 'Proyectos') ?></li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="dropdown <?php if ($activo == "PRODOC"): ?>active<?php endif; ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true">PRODOC<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php $id_prodoc = $this->modelo_coordinador->get_id_prodoc(); ?>
                        <?php if ($id_prodoc): ?>
                            <li><?= anchor(base_url() . 'coordinador/ver_prodoc/' . $id_prodoc, 'Ver PRODOC') ?></li>
                            <li><?= anchor(base_url() . 'coordinador/editar_prodoc/' . $id_prodoc, 'Editar PRODOC') ?></li>
                        <?php else: ?>
                            <li><?= anchor(base_url() . 'coordinador/registrar_prodoc', 'Registrar PRODOC') ?></li>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="dropdown <?php if ($activo == "Gestion actual"): ?>active<?php endif; ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true">Gestión actual<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><?= anchor(base_url() . 'coordinador/gestion_actual', 'Ver POA\'s de la gestión actual') ?></li>
                        <li><?= anchor(base_url() . 'coordinador/gestiones_registradas', 'Gestiones registradas') ?></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav">
                <li class="dropdown <?php if($activo == "Reportes"): ?>active<?php endif; ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true">Reportes<span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <?php if($id_prodoc): ?>
                            <li><?= anchor(base_url() . 'coordinador/ver_reporte_prodoc/' . $id_prodoc, 'Reporte PRODOC') ?></li>
                        <?php else: ?>
                            <li class="disabled"><a href="#">Reporte PRODOC</a></li>
                        <?php endif; ?>
                        <li><a href="<?= base_url() . 'coordinador/ver_reportes_gestion_actual'?>">Reporte POA's gestión actual</a></li>
                        <li><a href='<?= base_url() . 'coordinador/ver_reportes_proyectos'?>'>Reporte Proyectos</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"><?= $nombre_usuario . " " . $apellido_usuario ?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li class="navbar-text">
                            <p><strong>Institución</strong><br><?= $nombre_institucion ?></p>
                            <p><strong>Rol</strong><br><?= $nombre_rol ?></p>
                            <p>
                                <strong>Teléfono</strong>
                                <br>
                                <?php if($telefono_usuario != false): ?>
                                    <?= $telefono_usuario ?>
                                <?php else: ?>
                                    Sin registrar
                                <?php endif; ?>
                            </p>
                            <p>
                                <strong>E-mail</strong>
                                <br>
                                <?php if($correo_usuario): ?>
                                    <?= $correo_usuario ?>
                                <?php else: ?>
                                    Sin registrar
                                <?php endif; ?>
                            </p>
                        </li>
                        <li><a href="<?= base_url() . 'coordinador/modificar_datos_contacto/' . $id_usuario ?>">Cambiar datos de contacto</a></li>
                        <li><a href="<?= base_url() . 'coordinador/modificar_password/' . $id_usuario ?>">Cambiar password</a></li>
                        <li><?= anchor(base_url() . 'login/cerrar_sesion', 'Cerrar sesión') ?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>