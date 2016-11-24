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
                <li <?php if($activo == "PRODOC"):?>class="active"<?php endif;?>><a href="<?= base_url() . 'financiador/ver_reporte_prodoc'?>">PRODOC</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li <?php if($activo == "Proyectos"):?>class="active"<?php endif;?>><a href="<?= base_url() . 'financiador/ver_proyectos'?>">Proyectos</a></li>
            </ul>
            <ul class="nav navbar-nav">
                <li <?php if($activo == "Gestion actual"):?>class="active"<?php endif;?>><a href="<?= base_url() . 'financiador/ver_poas_gestion_actual'?>">POA's gestión actual</a></li>
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
                        <li><a href="<?= base_url() . 'financiador/modificar_datos_contacto/' . $id_usuario ?>">Cambiar datos de contacto</a></li>
                        <li><a href="<?= base_url() . 'financiador/modificar_password/' . $id_usuario ?>">Cambiar password</a></li>
                        <li><?= anchor(base_url() . 'login/cerrar_sesion', 'Cerrar sesión') ?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>