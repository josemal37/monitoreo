<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <title>Nueva institución</title>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center">Bienvenido administrador</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Instituciones";
            $this->load->view('administrador/nav', $datos);
            ?>
            <h4>Modificar institución</h4>
            <form action="<?= base_url() . 'administrador/modificar_institucion/' . $institucion->id_institucion ?>" id="formulario_institucion" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                <div class="form-group">
                    <label for="nombre_institucion">Nombre de la institución</label>
                    <input type="text" name="nombre_institucion" id="nombre_institucion" value="<?= $institucion->nombre_institucion ?>" placeholder="Nombre" class="form-control">
                    <p><?= form_error('nombre_institucion') ?></p>
                </div>
                <div class="form-group">
                    <label for="sigla_institucion">Sigla</label>
                    <input type="text" name="sigla_institucion" id="sigla_institucion" value="<?= $institucion->sigla_institucion ?>" placeholder="Sigla" class="form-control">
                    <p><?= form_error('sigla_institucion') ?></p>
                </div>
                <div class="form-group">
                    <label for="presupuesto_institucion">Presupuesto (Bs.)</label>
                    <input type="text" name="presupuesto_institucion" id="presupuesto_institucion" value="<?= $institucion->presupuesto_institucion ?>" placeholder="Presupuesto" class="form-control">
                    <p><?= form_error('presupuesto_institucion') ?></p>
                </div>
                <input type="hidden" name="id_institucion" id="id_institucion" value="<?= $institucion->id_institucion?>">
                <input type="submit" name="submit" value="Modificar institución" title="Modificar institución" class="btn btn-primary">
            </form>
        </div>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_institucion').validate({
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_institucion: {
                            required: true,
                            minlength: 3,
                            maxlength: 128
                        },
                        sigla_institucion: {
                            required: true,
                            minlength: 2,
                            maxlength: 8
                        },
                        presupuesto_institucion: {
                            required: true,
                            number: true,
                            min: 0
                        }
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).parent('div').addClass(errorClass).removeClass(validClass);
                        $(element).addClass('control-label');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).parent('div').removeClass(errorClass).addClass(validClass);
                    },
                    errorPlacement: function(error, element) {
                        $(error).addClass('control-label');
                        error.insertAfter(element);
                    }
                });
            });
        </script>
    </body>
</html>