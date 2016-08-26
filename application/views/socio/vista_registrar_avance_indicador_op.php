<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
        <title>Registrar avance indicador</title>
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido socio</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('socio/nav', $datos);
            ?>
            <div>
                <div>
                    <h4><?= $indicador->nombre_indicador_op ?></h4>
                    <?php
                    $avance_op = array('name' => 'avance_op', 'placeholder' => 'Avance', 'type' => 'number', 'class' => 'form-control', 'required' => 'required');
                    $descripcion_avance_op = array('name' => 'descripcion_avance_op', 'placeholder' => 'Descripción', 'class' => 'form-control', 'rows' => '4', 'required' => 'required');
                    $submit = array('name' => 'submit', 'value' => 'Registrar avance', 'title' => 'Registrar avance', 'class' => 'btn btn-primary');
                    ?>
                    <form action="<?= base_url() . 'socio/registrar_avance_indicador_operativo/' . $id_proyecto . '/' . $indicador->id_indicador_op ?>" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="form-group">
                            <label for="avance_op">Cantidad de avance</label>
                            <input type="number" name="avance_op" placeholder="Avance" class="form-control" required="required">
                            <p><?= form_error('avance_op') ?></p>
                        </div>
                        <div class="form-group">
                            <label for="descripcion_avance_op">Descripción</label>
                            <textarea name="descripcion_avance_op" rows="4" placeholder="Descripción" class="form-control" required="required"></textarea>
                            <?= form_error('descripcion_avance_op') ?>
                        </div>
                        <div class="form-group">
                            <label>Gastos</label>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="tabla_gastos">
                                    <thead>
                                        <tr class="ajaxTitle">
                                            <th width="15%">Fecha del gasto</th>
                                            <th width="50%">Concepto</th>
                                            <th width="13%">Importe (Bs.)</th>
                                            <th width="12%">Respaldo</th>
                                            <th width="10%">Borrar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="vertical-top"><input type="date" name="fecha_gasto_avance[]" class="form-control" required></td>
                                            <td><textarea name="concepto_gasto_avance[]" placeholder="Concepto" class="form-control vresize" rows="2" required></textarea></td>
                                            <td class="vertical-top"><input type="number" name="importe_gasto_avance[]" placeholder="Importe" class="form-control" required></td>
                                            <td class="vertical-top"><input type="file" name="respaldo_gasto_avance_1" required></td>
                                            <td class="vertical-top"><input type="button" id="borrar_fila" value="Borrar"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-default" id="nueva_fila">Nueva fila</button>
                        </div>
                        <input type="hidden" name="id_indicador_op" value="<?= $id_indicador ?>" id="id_indicador_op">
                        <input type="submit" name="submit" value="Registrar avance" title="Registrar avance" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
        <script>
            var num_filas = 2;
            $('#nueva_fila').click(function() {
                var fila = '<tr>' +
                        '<td class="vertical-top"><input type="date" name="fecha_gasto_avance[]" placeholder="Fecha del gasto" class="form-control" required></td>' +
                        '<td><textarea name="concepto_gasto_avance[]" placeholder="Concepto" class="form-control vresize" rows="2" required></textarea></td>' +
                        '<td class="vertical-top"><input type="number" name="importe_gasto_avance[]" placeholder="Importe" class="form-control" required></td>' +
                        '<td class="vertical-top"><input type="file" name="respaldo_gasto_avance_' + num_filas + '" required></td>' +
                        '<td class="vertical-top"><input type="button" id="borrar_fila" value="Borrar" /></td>' +
                        '</tr>';
                $('#tabla_gastos >tbody').append(fila);
                num_filas = num_filas + 1;
            });
        </script>
        <script>
            $(function() {
                $(document).on('click', '#borrar_fila', function(event) {
                    if ($('#tabla_gastos >tbody >tr').length > 1) {
                        event.preventDefault();
                        $(this).closest('tr').remove();
                    }
                });
            });
        </script>
    </body>
</html>