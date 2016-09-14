<?php
$nombre_usuario = $this->session->userdata('nombre_usuario');
$apellido_usuario = $this->session->userdata('apellido_usuario');
$nombre_institucion = $this->session->userdata('nombre_institucion');
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
                <li <?php if ($activo == "Inicio"): ?>class="active"<?php endif; ?>><?= anchor(base_url() . 'socio', 'Inicio') ?></li>
                <li <?php if ($activo == "Proyectos activos"): ?>class="active"<?php endif; ?>><?= anchor(base_url() . 'socio/proyectos_activos', 'Proyectos activos') ?></li>
                <li <?php if ($activo == "Registrar proyecto"): ?>class="active"<?php endif; ?>><?= anchor(base_url() . 'socio/proyectos_en_edicion', 'Proyectos en edición') ?></li>
                <li <?php if ($activo == "Reportes"): ?>class="active"<?php endif; ?>><?= anchor(base_url() . 'socio/ver_reportes', 'Ver reportes') ?></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"><?= $nombre_usuario . " " . $apellido_usuario ?><span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><?= anchor(base_url() . 'login/cerrar_sesion', 'Cerrar sesión') ?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>