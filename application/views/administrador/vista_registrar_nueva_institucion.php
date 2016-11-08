<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.validate.bootstrap.defaults.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/localization/messages_es.min.js' ?>"></script>

        <title>Nueva institución</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Instituciones";
            $this->load->view('administrador/nav', $datos);
            ?>
            <h4  class="text-primary">Nueva institución</h4>
            <form action="<?= base_url() . 'administrador/nueva_institucion' ?>" id="formulario_institucion" role="form" method="post" accept-charset="utf-8" autocomplete="off">
                <div class="form-group">
                    <label for="nombre_institucion">Nombre de la institución</label>
                    <input type="text" name="nombre_institucion" id="nombre_institucion" placeholder="Nombre" class="form-control">
                    <p><?= form_error('nombre_institucion') ?></p>
                </div>
                <div class="form-group">
                    <label for="sigla_institucion">Sigla</label>
                    <input type="text" name="sigla_institucion" id="sigla_institucion" placeholder="Sigla" class="form-control">
                    <p><?= form_error('sigla_institucion') ?></p>
                </div>
                <?php if ($this->session->flashdata('existe_institucion')): ?>
                    <p><label class="text-danger">La institución ya se encuentra registrada.</label></p>
                <?php endif; ?>
                <input type="submit" name="submit" value="Registrar institución" title="Registrar institución" class="btn btn-primary">
            </form>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#formulario_institucion').validate({
                    ignore: [],
                    errorClass: 'has-error',
                    validClass: 'has-success',
                    rules: {
                        nombre_institucion: {
                            required: true,
                            minlength: 3,
                            maxlength: 128,
                            remote: {
                                url: '<?= base_url() . 'administrador/existe_nombre_institucion_ajax' ?>',
                                method: 'POST',
                                cache: false,
                                dataType: "json",
                                data: {
                                    nombre_institucion: function() {return $('#nombre_institucion').val();}
                                }
                            }
                        },
                        sigla_institucion: {
                            required: true,
                            minlength: 2,
                            maxlength: 8,
                            remote: {
                                url: '<?= base_url() . 'administrador/existe_sigla_institucion_ajax' ?>',
                                method: 'POST',
                                cache: false,
                                dataType: "json",
                                data: {
                                    nombre_institucion: function() {return $('#sigla_institucion').val();}
                                }
                            }
                        }
                    },
                    messages: {
                        nombre_institucion: {
                            remote: 'Esta institución ya se encuentra registrada.'
                        },
                        sigla_institucion: {
                            remote: 'Esta sigla ya se encuentra registrada.'
                        }
                    }
                });
            });
        </script>
    </body>
</html>