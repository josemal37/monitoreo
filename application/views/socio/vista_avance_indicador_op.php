<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="<?php echo base_url("assets/css/bootstrap.css"); ?>" />
        <title>Ver avance indicador</title>
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
                    <p><strong>Meta del indicador: </strong><?= $indicador->meta_op ?></p>
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
                                                <?php $total_avance = 0; ?>
                                                <?php foreach ($avances_indicador as $avance) : ?>
                                                    <tr>
                                                        <td><?= $avance->fecha_avance_op ?></td>
                                                        <td><?= $avance->descripcion_avance_op ?></td>
                                                        <td><?= $avance->avance_op ?></td>
                                                        <?php $total_avance = $total_avance + $avance->avance_op; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <tr>
                                                    <td></td>
                                                    <td class="text-right"><strong>Total:</strong></td>
                                                    <td><?= $total_avance ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Detalle de gastos
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th width="15%">Fecha del gasto</th>
                                                    <th width="50%">Concepto</th>
                                                    <th width="12%">Importe (Bs.)</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $total_importe = 0; ?>
                                                <?php foreach ($gastos_avances as $gasto_avance) : ?>
                                                    <tr>
                                                        <td width="15%"><?= $gasto_avance->fecha_gasto_avance ?></td>
                                                        <td width="70%">
                                                            <?= $gasto_avance->concepto_gasto_avance ?>
                                                            <a href="<?= base_url() . 'socio/descarga/' . $gasto_avance->respaldo_gasto_avance ?>" class="btn btn-success btn-xs pull-right">Ver respaldo</a>
                                                        </td>
                                                        <td width="15%"><?= $gasto_avance->importe_gasto_avance ?></td>
                                                        <?php $total_importe = $total_importe + $gasto_avance->importe_gasto_avance; ?>
                                                    </tr>
                                                <?php endforeach; ?>
                                                <tr>
                                                    <td></td>
                                                    <td class="text-right"><strong>Total: </strong></td>
                                                    <td><?= $total_importe ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                        <a href="<?= base_url() . 'socio/registrar_avance_indicador_operativo/' . $id_proyecto . '/' . $id_indicador ?>" class="btn btn-primary">Registrar avance</a>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.1.0.min.js"); ?>"></script>
        <script type="text/javascript" src="<?php echo base_url("assets/js/bootstrap.js"); ?>"></script>
    </body>
</html>