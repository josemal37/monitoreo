<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?= base_url().'assets/css/bootstrap.css' ?>" />
        <title>Detalle de avance indicador</title>
    </head>
    <body>
        <div class="container">
            <h1 style="text-align: center">Bienvenido coordinador</h1>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos activos";
            $this->load->view('coordinador/nav', $datos);
            ?>
            <div>
                <div>
                    <h4><?= $indicador->nombre_indicador_op ?></h4>
                    <div>
                        <?php if (!$avances_indicador): ?>
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    Advertencia
                                </div>
                                <div class="panel-body">
                                    Todavia no se registraron avances.
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Detalle de avances
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="15%">Fecha de avance</th>
                                                    <th width="70%">Descripción</th>
                                                    <th width="15%">Avance</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($avances_indicador as $avance) : ?>
                                                    <tr>
                                                        <td><?= $avance->fecha_avance_op ?></td>
                                                        <td><?= $avance->descripcion_avance_op ?></td>
                                                        <td><?= $avance->avance_op ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="15%">Fecha del gasto</th>
                                                    <th width="50%">Concepto</th>
                                                    <th width="12%">Importe (Bs.)</th>
                                                    <th width="23%">Respaldo</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($gastos_avances as $gasto_avance) : ?>
                                                    <tr>
                                                        <td><?= $gasto_avance->fecha_gasto_avance ?></td>
                                                        <td><?= $gasto_avance->concepto_gasto_avance ?></td>
                                                        <td><?= $gasto_avance->importe_gasto_avance ?></td>
                                                        <td><?= anchor('coordinador/descarga/'. $gasto_avance->respaldo_gasto_avance, $gasto_avance->respaldo_gasto_avance) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    </body>
</html>