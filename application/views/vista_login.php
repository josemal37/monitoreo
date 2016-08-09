<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
        <link rel="stylesheet" href="<?php echo base_url("assets/css/signin.css"); ?>" />
    </head>
    <body>
        <?php
        $login_usuario = array('name' => 'login_usuario', 'placeholder' => 'nombre de usuario', 'class' => 'form-control');
        $password_usuario = array('name' => 'password_usuario', 'placeholder' => 'password', 'class' => 'form-control');
        $submit = array('name' => 'submit', 'value' => 'Iniciar sesión', 'title' => 'Iniciar sesión', 'class' => 'btn btn-primary');
        ?>
        <div class="container">
            <div class="form-signin">
                <h1 class="form-signin-heading">Inicio de sesión</h1>
                <?= form_open(base_url() . 'login/iniciar_sesion') ?>
                <label for="login_usuario" class="sr-only">Nombre de usuario:</label>
                <?= form_input($login_usuario) ?><p><?= form_error('login_usuario') ?></p>
                <label for="password_usuario" class="sr-only">Introduce tu password:</label>
                <?= form_password($password_usuario) ?><p><?= form_error('password_usuario') ?></p>
                <?= form_hidden('token', $token) ?>
                <?= form_submit($submit) ?>
                <?= form_close() ?>
                <?php
                if ($this->session->flashdata('usuario_incorrecto')) {
                    ?>
                    <p><?= $this->session->flashdata('usuario_incorrecto') ?></p>
                    <?php
                }
                ?>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    </body>
</html>