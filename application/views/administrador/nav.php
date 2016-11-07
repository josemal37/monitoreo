<?php
$nombre_usuario = $this->session->userdata('nombre_usuario');
$apellido_usuario = $this->session->userdata('apellido_usuario');
$nombre_institucion = $this->session->userdata('nombre_institucion');
$id_usuario = $this->session->userdata('id_usuario');
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
                <li <?php if ($activo == "Usuarios"): ?>class="active"<?php endif; ?>><?= anchor(base_url() . 'administrador/usuarios', 'Administrar usuarios') ?></li>
                <li <?php if ($activo == "Instituciones"): ?>class="active"<?php endif; ?>><?= anchor(base_url() . 'administrador/instituciones', 'Administrar instituciones') ?></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"><?= $nombre_usuario . " " . $apellido_usuario ?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= base_url() . 'administrador/modificar_password/' . $id_usuario ?>">Cambiar password</a></li>
                        <li><?= anchor(base_url() . 'login/cerrar_sesion', 'Cerrar sesión') ?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>