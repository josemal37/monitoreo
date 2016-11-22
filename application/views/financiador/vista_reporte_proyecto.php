<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />
        <link rel="stylesheet"  href="<?= base_url() . 'assets/js/amcharts/plugins/export/export.css' ?>" />
        <link rel="stylesheet" href="<?= base_url() . 'assets/css/amcharts-monitoreo.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/amcharts/amcharts.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/amcharts/serial.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/amcharts/themes/light.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/amcharts/plugins/export/export.js' ?>"></script>


        <title>POA</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "Proyectos";
            $this->load->view('financiador/nav', $datos);
            ?>
            <div>
                <h4 id="titulo_poa" class="text-primary"><?= $proyecto->nombre_proyecto ?></h4>
                <p class="text-justify"><strong>Año:</strong> <span id="anio_poa" class="number_integer"><?= $proyecto->valor_anio ?></span></p>
                <p class="text-justify"><strong>Presupuesto:</strong> Bs. <span id="presupuesto_poa" class="number_decimal"><?= $proyecto->presupuesto_proyecto ?></span></p>
                <p class="text-justify"><strong>Gasto actual:</strong> Bs. <span id="gasto_poa" class="number_decimal"></span></p>
                <p class="text-justify"><strong>Descripción:</strong> <span id="descripcion_poa"><?= $proyecto->descripcion_proyecto ?></span></p>
            </div>
            <div>
                <?php if (sizeof($proyecto->actividades) > 0): ?>
                    <h4 class="text-primary">Actividades</h4>
                    <?php foreach ($proyecto->actividades as $actividad): ?>
                        <div class="panel panel-default contenedor_actividad">
                            <div class="panel-heading">
                                <strong class="titulo_actividad text-primary"><?= $actividad->nombre_actividad ?></strong>
                            </div>
                            <div class="panel-body">
                                <p class="text-left"><strong>Descripción: </strong><span class="descripcion_actividad"><?= $actividad->descripcion_actividad ?></span></p>
                                <div class="row">
                                    <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                                        <p><strong>Fecha de inicio: </strong><span class="fecha_inicio_actividad"><?= $actividad->fecha_inicio_actividad ?></span></p>
                                        <p><strong>Fecha de fin: </strong><span class="fecha_fin_actividad"><?= $actividad->fecha_fin_actividad ?></span></p>
                                        <p>
                                            <strong>Presupuesto: </strong>
                                            <span class="presupuesto_actividad">Bs. <span class="number_decimal"><?= $actividad->presupuesto_actividad ?></span><?php if ($actividad->contraparte_actividad): ?> (contraparte)<?php endif; ?></span>
                                        </p>
                                        <span class="hidden valor_presupuesto_actividad"><?= $actividad->presupuesto_actividad ?></span>
                                        <?php if (isset($actividad->gasto_actividad)): ?>
                                            <p>
                                                <strong>Gasto estimado:</strong> 
                                                <span class="gasto_actividad">Bs. <span class="number_decimal"><?= $actividad->gasto_actividad ?></span></span>
                                                <span class="hidden valor_gasto_actividad"><?= $actividad->gasto_actividad ?></span>
                                            </p>
                                        <?php else: ?>
                                            <p>
                                                <strong>Gasto estimado:</strong> 
                                                <span class="gasto_actividad">Bs. <span class="number_decimal">0</span></span>
                                                <span class="hidden valor_gasto_actividad">0</span>
                                            </p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="contenedor_grafico col-lg-6 col-md-6 col-sm-8 col-xs-12 table-responsive">
                                        <label>Avance de los indicadores</label>
                                        <div id="grafico_porcentaje_indicadores_<?= $actividad->id_actividad ?>" class="grafico_porcentaje_indicadores grafico"></div>
                                    </div>
                                    <div class="contenedor_grafico col-lg-3 col-md-3 col-sm-4 col-xs-12 table-responsive">
                                        <label>Presupuesto vs Gasto</label>
                                        <div id="grafico_financiero_actividad_<?= $actividad->id_actividad ?>" class="grafico_financiero_actividad grafico"></div>
                                    </div>
                                </div>
                                <?php
                                $id_actividad = $actividad->id_actividad;
                                $hitos_cuantitativos = $actividad->hitos_cuantitativos;
                                $hitos_cualitativos = $actividad->hitos_cualitativos;
                                $indicadores_cuantitativos = $actividad->indicadores_cuantitativos;
                                $indicadores_cualitativos = $actividad->indicadores_cualitativos;
                                $gastos_actividad = false;
                                ?>
                                <?php if ((sizeof($hitos_cuantitativos) + sizeof($hitos_cualitativos)) > 0): ?>
                                    <div class="panel panel-default contenedor_indicadores">
                                        <div class="panel-heading">
                                            <strong>Indicadores</strong>
                                        </div>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Nombre del indicador</th>
                                                        <th>Descripción</th>
                                                        <th>Avance / Meta</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php if (sizeof($hitos_cuantitativos) > 0): ?>
                                                        <?php foreach ($hitos_cuantitativos as $hito_cuantitativo): ?>
                                                            <tr class="indicador_cuantitativo">
                                                                <td class="nombre_indicador"><?= $hito_cuantitativo->nombre_hito_cn ?></td>
                                                                <td class="descripcion_indicador"><?= $hito_cuantitativo->descripcion_hito_cn ?></td>
                                                                <td><span class="number_integer cantidad_avance_indicador"><?= $hito_cuantitativo->cantidad_avance_cn ?></span><span class="hidden valor_cantidad_avance_indicador"><?= $hito_cuantitativo->cantidad_avance_cn ?></span> / <span class="number_integer meta_indicador"><?= $hito_cuantitativo->meta_hito_cn ?></span><span class="hidden valor_meta_indicador"><?= $hito_cuantitativo->meta_hito_cn ?></span> <span class="unidad_indicador"><?= $hito_cuantitativo->unidad_hito_cn ?></span></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                    <?php if (sizeof($hitos_cualitativos) > 0): ?>
                                                        <?php foreach ($hitos_cualitativos as $hito_cualitativo): ?>
                                                            <tr class="indicador_cualitativo <?php if ($hito_cualitativo->avances): ?>aprobado<?php else: ?>no_aprobado<?php endif; ?>">
                                                                <td class="nombre_indicador"><?= $hito_cualitativo->nombre_hito_cl ?></td>
                                                                <td class="descripcion_indicador"><?= $hito_cualitativo->descripcion_hito_cl ?></td>
                                                                <td>-----</td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php if (sizeof($indicadores_cualitativos) + sizeof($indicadores_cuantitativos) > 0): ?>
                                        <div class="panel panel-default contenedor_estado_indicadores">
                                            <div class="panel-heading">
                                                <strong>Estado de los indicadores</strong>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Nombre del indicador</th>
                                                            <th>Nombre del comparador</th>
                                                            <th>Tipo de comparador</th>
                                                            <th>Estado</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($indicadores_cuantitativos as $indicador_cuantitativo): ?>
                                                            <?php
                                                            $color = 'FFFFFF';
                                                            if ($indicador_cuantitativo->estado_indicador_cn == $this->modelo_indicador->get_no_aceptable()) {
                                                                $color = 'FDBFBF';
                                                            } else {
                                                                if ($indicador_cuantitativo->estado_indicador_cn == $this->modelo_indicador->get_limitado()) {
                                                                    $color = 'FDFCBF';
                                                                } else {
                                                                    if ($indicador_cuantitativo->estado_indicador_cn == $this->modelo_indicador->get_aceptable()) {
                                                                        $color = 'CDFDC3';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <tr bgcolor="#<?= $color ?>">
                                                                <td><?= $indicador_cuantitativo->nombre_hito_cn ?></td>
                                                                <td><?= $indicador_cuantitativo->nombre_indicador_cn ?></td>
                                                                <td><?= $indicador_cuantitativo->nombre_tipo_indicador_cn ?></td>
                                                                <td><?= $indicador_cuantitativo->estado_indicador_cn ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        <?php foreach ($indicadores_cualitativos as $indicador_cualitativo): ?>
                                                            <?php
                                                            $color = 'FFFFFF';
                                                            if ($indicador_cualitativo['estado_indicador_cualitativo'] == $this->modelo_indicador->get_no_aceptable()) {
                                                                $color = 'FDBFBF';
                                                            } else {
                                                                if ($indicador_cualitativo['estado_indicador_cualitativo'] == $this->modelo_indicador->get_limitado()) {
                                                                    $color = 'FDFCBF';
                                                                } else {
                                                                    if ($indicador_cualitativo['estado_indicador_cualitativo'] == $this->modelo_indicador->get_aceptable()) {
                                                                        $color = 'CDFDC3';
                                                                    }
                                                                }
                                                            }
                                                            ?>
                                                            <tr bgcolor="#<?= $color ?>">
                                                                <td><?= $indicador_cualitativo['nombre_indicador_cualitativo'] ?></td>
                                                                <td>Documento aceptado</td>
                                                                <td>Booleano</td>
                                                                <td><?= $indicador_cualitativo['estado_indicador_cualitativo'] ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    <?php else : ?>
                                        <div class="panel panel-warning hidden">
                                            <div class="panel-heading">
                                                Advertencia
                                            </div>
                                            <div class="panel-body">
                                                Todavía no se registraron indicadores.
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            Advertencia
                                        </div>
                                        <div class="panel-body">
                                            No se registraron indicadores.
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            Advertencia
                        </div>
                        <div class="panel-body">
                            No se registraron actividades.
                        </div>
                    </div>
                <?php endif; ?>
                <div id="contenedor_descarga">
                    <p class="text-right"><a id="generarPDF" onclick="generarPDF()" class="btn btn-success">Generar PDF</a></p>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function(){
                var gasto_poa = 0;
                $(document).find('.valor_gasto_actividad').each(function(){
                    var gasto_actividad = parseFloat($(this).text());
                    gasto_poa = gasto_poa + gasto_actividad;
                });
                $('#gasto_poa').append(gasto_poa).html();
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.number_decimal').number(true, 2);
                $('.number_integer').number(true);
            });
            $.fn.ignore = function(sel) {
                return this.clone().find(sel || ">*").remove().end();
            };
            $(document).ready(function() {
                var soportado = false;
                try {
                    var isFileSaverSupported = typeof Uint8Array != 'undefined' && !!new Blob;
                    if(isFileSaverSupported) {
                        soportado = true;
                    }
                } catch (e) {

                }
                if(!soportado) {
                    $('#generarPDF').attr('disabled', 'disabled');
                    $('#contenedor_descarga').append(""+
                            "<div class='alert alert-danger alert-dismissable'>"+
                            "<button type='button' class='close' data-dismiss='alert'>&times;</button>"+
                            "<strong>¡Navegador no compatible!</strong> Su navegador no es compatible con la funcionalidad de generación de PDF's."+
                            "</div>");
                }
            });
        </script>
        <script type="text/javascript">
            //el ultimo grafico generado
            var chart;
            //obtener el color del gasto
            function color_gasto(presupuesto_actividad, gasto_actividad) {
                if (presupuesto_actividad >= gasto_actividad) {
                    return '#04D215';
                } else {
                    return '#FF0F00';
                }
            }

            //colores para calcular el color del porcentaje
            var coloresPorcentaje = [
                {pct: 0.0, color: {r: 0xff, g: 0x00, b: 0}},
                {pct: 0.5, color: {r: 0xff, g: 0xff, b: 0}},
                {pct: 1.0, color: {r: 0x00, g: 0xff, b: 0}}
            ];

            //obtener el color segun porcentaje
            function color_porcentaje(porcentaje) {
                for (var i = 1; i < coloresPorcentaje.length - 1; i++) {
                    if (porcentaje <= coloresPorcentaje[i].pct) {
                        break;
                    }
                }
                var lower = coloresPorcentaje[i - 1];
                var upper = coloresPorcentaje[i];
                var range = upper.pct - lower.pct;
                var rangePct = (porcentaje - lower.pct) / range;
                var pctLower = 1 - rangePct;
                var pctUpper = rangePct;
                var color = {
                    r: Math.floor(lower.color.r * pctLower + upper.color.r * pctUpper),
                    g: Math.floor(lower.color.g * pctLower + upper.color.g * pctUpper),
                    b: Math.floor(lower.color.b * pctLower + upper.color.b * pctUpper)
                };
                return 'rgb(' + [color.r, color.g, color.b].join(',') + ')';
            }

            //obtener el porcetaje de avance
            function porcentaje_avance_indicador(cantidad_avance, meta) {
                var res = 0;
                if (meta != 0) {
                    res = (cantidad_avance / meta) * 100;
                }
                if (res < 0) {
                    res = 0;
                }
                if (res > 100) {
                    res = 100;
                }
                res = res.toFixed(2);
                return res;
            }
            ;

            //texto para indicadores cualitativos
            function ballonTextAvanceCualitativo(porcentaje) {
                if (porcentaje < 100) {
                    return "No completado"
                } else {
                    return "Completado"
                }
            }

            //verificar si un grafico esta vacio
            AmCharts.checkEmptyData = function(chart) {
                if (0 == chart.dataProvider.length) {
                    chart.valueAxes[0].minimum = 0;
                    chart.valueAxes[0].maximum = 100;
                    var dataPoint = {
                        dummyValue: 0
                    };
                    dataPoint[chart.categoryField] = '';
                    chart.dataProvider = [dataPoint];
                    chart.addLabel(0, '50%', 'El gráfico no contiene datos', 'center');
                    chart.chartDiv.style.opacity = 0.5;
                    chart.validateNow();
                }
            }

            //generamos los graficos
            $(document).ready(function() {
                //para cada actividad generamos graficos
                $('.contenedor_actividad').each(function() {
                    //grafico financiero
                    var presupuesto_actividad = parseFloat($(this).find('.valor_presupuesto_actividad').text());
                    var gasto_actividad = parseFloat($(this).find('.valor_gasto_actividad').text());
                    var id_contenedor_grafico_financiero = $(this).find('.grafico_financiero_actividad').attr('id');
                    var chart_financiero = AmCharts.makeChart(id_contenedor_grafico_financiero, {
                        type: 'serial',
                        theme: 'light',
                        dataProvider: [
                            {
                                'nombre': 'Presupuesto',
                                'valor': presupuesto_actividad,
                                'color': '#0D8ECF'
                            },
                            {
                                'nombre': 'Gasto',
                                'valor': gasto_actividad,
                                'color': color_gasto(presupuesto_actividad, gasto_actividad)
                            }
                        ],
                        "valueAxes": [{
                                "axisAlpha": 0,
                                "position": "left",
                                "title": "Bs.",
                                "minimum": 0
                            }],
                        "startDuration": 0,
                        "graphs": [{
                                "balloonText": "<b>[[category]]:</b> [[value]]",
                                "fillColorsField": "color",
                                "fillAlphas": 0.9,
                                "lineAlpha": 0.2,
                                "type": "column",
                                "valueField": "valor",
                                "labelText": "[[value]]"
                            }],
                        "chartCursor": {
                            "categoryBalloonEnabled": false,
                            "cursorAlpha": 0,
                            "zoomable": false
                        },
                        "categoryField": "nombre",
                        "categoryAxis": {
                            "gridPosition": "start"
                        },
                        "export": {
                            "enabled": true,
                            "menu": []
                        }
                    });
                    AmCharts.checkEmptyData(chart_financiero);
                    //grafico porcentaje de indicadores
                    var contenedor_indicadores = $(this).find('.contenedor_indicadores');
                    var datos_grafico_porcentaje = [];
                    var tbody = $(contenedor_indicadores).find('tbody');
                    $(tbody).find('tr').each(function() {
                        if ($(this).hasClass('indicador_cuantitativo')) {
                            var nombre = $(this).find('.nombre_indicador').text();
                            var cantidad_avance = parseFloat($(this).find('.valor_cantidad_avance_indicador').text());
                            var meta = parseFloat($(this).find('.valor_meta_indicador').text());
                            var unidad = $(this).find('.unidad_indicador').text();
                            var porcentaje_avance = porcentaje_avance_indicador(cantidad_avance, meta);
                            datos_grafico_porcentaje.push({
                                "nombre": nombre,
                                "porcentaje_avance": porcentaje_avance,
                                "unidad": unidad,
                                "ballonText": "<br>Avance:</br> " + porcentaje_avance + ' %',
                                "color": color_porcentaje(porcentaje_avance_indicador(cantidad_avance, meta) / 100),
                                "labelText": porcentaje_avance + "%"
                            });
                        } else {
                            if ($(this).hasClass('indicador_cualitativo')) {
                                var nombre = $(this).find('.nombre_indicador').text();
                                var porcentaje_avance = 0;
                                var color = '#ff0000';
                                var labelText = "No completado";
                                if ($(this).hasClass('aprobado')) {
                                    porcentaje_avance = 100;
                                    color = '#00ff00';
                                    labelText = "completado";
                                }

                                datos_grafico_porcentaje.push({
                                    "nombre": nombre,
                                    "porcentaje_avance": porcentaje_avance,
                                    "unidad": '%',
                                    "ballonText": ballonTextAvanceCualitativo(porcentaje_avance),
                                    "color": color,
                                    "labelText": labelText
                                });
                            }
                        }
                    });
                    var id_grafico_porcentaje = $(this).find('.grafico_porcentaje_indicadores').attr('id');
                    var chart_grafico_porcentaje = AmCharts.makeChart(id_grafico_porcentaje, {
                        "type": "serial",
                        "theme": "light",
                        "dataProvider": datos_grafico_porcentaje,
                        "categoryField": "nombre",
                        "rotate": true,
                        "startDuration": 0,
                        "categoryAxis": {
                            "gridPosition": "start",
                            "position": "left"
                        },
                        "graphs": [
                            {
                                "id": "porcentaje_avance",
                                "title": "Porcentaje de avance",
                                "balloonText": "[[ballonText]]",
                                "type": "column",
                                "valueField": "porcentaje_avance",
                                "fillAlphas": 0.9,
                                "lineAlpha": 0.2,
                                "fillColorsField": "color",
                                "labelText": "[[labelText]]"
                            }
                        ],
                        "valueAxes": [{
                                "axisAlpha": 0,
                                "position": "left",
                                "title": "Porcentaje de avance",
                                "minimum": 0,
                                "maximum": 100
                            }],
                        "chartCursor": {
                            "categoryBalloonEnabled": false,
                            "cursorAlpha": 0,
                            "zoomable": false
                        },
                        "export": {
                            "enabled": true,
                            "menu": []
                        }
                    });
                    AmCharts.checkEmptyData(chart_grafico_porcentaje);
                    chart = chart_grafico_porcentaje;
                });
            });

            //generamos el pdf
            function generarPDF() {
                //obtenemos los ids de los graficos
                var ids = [];
                $(document).find('.grafico').each(function() {
                    ids.push($(this).attr('id'));
                });
                //obtenemos los graficos
                var charts = {}
                var numCharts = ids.length;
                var chartsFaltantes = numCharts;
                for (var i = 0; i < numCharts; i++) {
                    for (var j = 0; j < AmCharts.charts.length; j++) {
                        if (AmCharts.charts[j].div.id == ids[i])
                            charts[ids[i]] = AmCharts.charts[j];
                    }
                }
                //guardamos las imagenes de cada grafico
                for (var x in charts) {
                    if (charts.hasOwnProperty(x)) {
                        charts[x]["export"].capture({}, function() {
                            this.toPNG({"multiplier": 2}, function(data) {
                                this.setup.chart.exportedImage = data;
                                chartsFaltantes--;
                                if (chartsFaltantes == 0) {
                                    guardarPDF();
                                }
                            });
                        });
                    }
                }
                function guardarPDF() {
                    //generamos la fecha actual
                    var fecha_actual = new Date();
                    var dia = fecha_actual.getDate();
                    var mes = fecha_actual.getMonth() + 1;
                    var anio = fecha_actual.getFullYear();
                    var fecha = dia + '-' + mes + '-' + anio;
                    //declaramos el contenedor del PDF
                    var layout = {
                        "content": [],
                        "pageMargins": [40, 50, 40, 50],
                        "styles": {
                            titulo: {
                                fontSize: 14,
                                alignment: 'center',
                                color: '#3274AE',
                                bold: true,
                                margin: [0, 10, 0, 10]
                            },
                            titulo1: {
                                fontSize: 14,
                                alignment: 'justify',
                                color: '#3274AE',
                                bold: true,
                                margin: [0, 5, 0, 5]
                            },
                            titulo2: {
                                fontSize: 13,
                                alignment: 'justify',
                                color: '#3274AE',
                                bold: true,
                                margin: [0, 5, 0, 5]
                            },
                            titulo3: {
                                fontSize: 12,
                                alignment: 'justify',
                                color: '#3274AE',
                                bold: true,
                                margin: [0, 5, 0, 5]
                            },
                            titulo4: {
                                fontSize: 11,
                                alignment: 'justify',
                                color: '#3274AE',
                                bold: true,
                                margin: [0, 5, 0, 5]
                            },
                            cabeceraTabla: {
                                fontSize: 10,
                                alignment: 'center',
                                margin: [0, 0, 0, 0],
                                bold: true
                            },
                            parrafoNormal: {
                                fontSize: 10,
                                alignment: 'justify',
                                margin: [0, 0, 0, 5]
                            },
                            parrafoNegrita: {
                                fontSize: 10,
                                alignment: 'justify',
                                margin: [0, 0, 0, 5],
                                bold: true
                            },
                            headerLeft: {
                                fontSize: 10,
                                alignment: 'left',
                                margin: [40, 30, 0, 0],
                                color: '#939393'
                            },
                            headerRight: {
                                fontSize: 10,
                                alignment: 'right',
                                margin: [0, 30, 40, 0],
                                color: '#939393'
                            },
                            footerLeft: {
                                fontSize: 10,
                                alignment: 'left',
                                margin: [40, 0, 0, 30],
                                color: '#939393'
                            },
                            footerRight: {
                                fontSize: 10,
                                alignment: 'right',
                                margin: [0, 0, 40, 30],
                                color: '#939393'
                            }
                        },
                        "header": {
                            columns: [
                                {
                                    text: 'Proyecto: Promoción de una cultura de resiliencia',
                                    style: 'headerLeft'
                                },
                                {
                                    text: 'Reporte POA',
                                    style: 'headerRight'
                                }
                            ]
                        },
                        "footer": function(currentPage, pageCount) {
                            return {
                                columns: [
                                    {
                                        text: fecha,
                                        style: 'footerLeft'
                                    },
                                    {
                                        text: 'página ' + currentPage,
                                        style: 'footerRight'
                                    }
                                ]
                            };
                        }
                    };
                    //generamos el contenido del PDF
                    //titulo del poa
                    var titulo = $(document).find("#titulo_poa").text();
                    layout.content.push({
                        "text": titulo,
                        "style": "titulo"
                    });
                    //anio y presupuesto
                    var anio = $(document).find("#anio_poa").text();
                    var presupuesto = "Bs. " + $(document).find("#presupuesto_poa").text();
                    var gastoActual = "Bs. " + $(document).find("#gasto_poa").text();
                    var descripcion = $(document).find("#descripcion_poa").text();
                    layout.content.push({
                        "text": "Datos generales",
                        "style": "titulo1"
                    });
                    layout.content.push({
                        table: {
                            body: [
                                [
                                    {
                                        "text": "Año:",
                                        "style": "parrafoNegrita"
                                    },
                                    {
                                        "text": anio,
                                        "style": "parrafoNormal"
                                    }
                                ],
                                [
                                    {
                                        "text": "Presupuesto:",
                                        "style": "parrafoNegrita"
                                    },
                                    {
                                        "text": presupuesto,
                                        "style": "parrafoNormal"
                                    }
                                ],
                                [
                                    {
                                        "text": "Gasto actual:",
                                        "style": "parrafoNegrita"
                                    },
                                    {
                                        "text": gastoActual,
                                        "style": "parrafoNormal"
                                    }
                                ],
                                [
                                    {
                                        "text": "Descripción:",
                                        "style": "parrafoNegrita"
                                    },
                                    {
                                        "text": descripcion,
                                        "style": "parrafoNormal"
                                    }
                                ]
                            ]
                        },
                        layout: "noBorders"
                    });
                    //actividades
                    layout.content.push({
                        "text": "Actividades",
                        "style": "titulo1"
                    });
                    $(document).find(".contenedor_actividad").each(function() {
                        //titulo de la actividad
                        var tituloActividad = $(this).find('.titulo_actividad').text();
                        layout.content.push({
                            "text": tituloActividad,
                            "style": "titulo2"
                        });
                        //datos de la actividad
                        layout.content.push({
                            "text": "Datos de la actividad",
                            "style": "titulo3"
                        });
                        var descripcionActividad = $(this).find(".descripcion_actividad").text();
                        var fechaInicio = $(this).find(".fecha_inicio_actividad").text();
                        var fechaFin = $(this).find(".fecha_fin_actividad").text();
                        layout.content.push({
                            table: {
                                body: [
                                    [
                                        {
                                            "text": "Fecha de inicio:",
                                            "style": "parrafoNegrita"
                                        },
                                        {
                                            "text": fechaInicio,
                                            "style": "parrafoNormal"
                                        }
                                    ],
                                    [
                                        {
                                            "text": "Fecha de fin:",
                                            "style": "parrafoNegrita"
                                        },
                                        {
                                            "text": fechaFin,
                                            "style": "parrafoNormal"
                                        }
                                    ],
                                    [
                                        {
                                            "text": "Descripción:",
                                            "style": "parrafoNegrita"
                                        },
                                        {
                                            "text": descripcionActividad,
                                            "style": "parrafoNormal"
                                        }
                                    ]
                                ]
                            },
                            layout: "noBorders"
                        });
                        //descripcion de la actividad
                        var presupuesto = $(this).find(".presupuesto_actividad").text();
                        var gasto = $(this).find(".gasto_actividad").text();
                        //graficos
                        var idGraficoPorcentaje = $(this).find(".grafico_porcentaje_indicadores").attr("id");
                        var idGraficoFinanciero = $(this).find(".grafico_financiero_actividad").attr("id");
                        layout.content.push({
                            table: {
                                body: [
                                    [
                                        {
                                            "text": "Avance de los indicadores",
                                            "style": "titulo3"
                                        },
                                        {
                                            "text": "Presupuesto Vs Gasto",
                                            "style": "titulo3"
                                        }
                                    ],
                                    [
                                        {
                                            "image": charts[idGraficoPorcentaje].exportedImage,
                                            "width": 350
                                        },
                                        {
                                            "image": charts[idGraficoFinanciero].exportedImage,
                                            "width": 150,
                                        }
                                    ]
                                ]
                            },
                            layout: "noBorders"
                        });
                        //indicadores
                        var contenedorIndicadores = $(this).find(".contenedor_indicadores");
                        if (contenedorIndicadores.length > 0) {
                            layout.content.push({
                                "text": "Indicadores",
                                "style": "titulo3"
                            });
                            var body = [];
                            var cabecera = [];
                            $(contenedorIndicadores).find("thead > tr > th").each(function() {
                                cabecera.push({
                                    "text": $(this).text(),
                                    "style": "cabeceraTabla"
                                });
                            });
                            body.push(cabecera);
                            $(contenedorIndicadores).find("tbody > tr").each(function() {
                                var fila = [];
                                $(this).find("td").each(function() {
                                    fila.push({
                                        text: $(this).ignore(".hidden").text(),
                                        style: "parrafoNormal"
                                    });
                                });
                                body.push(fila);
                            });
                            layout.content.push({
                                table: {
                                    headerRows: 1,
                                    widths: ['25%', '*', '25%'],
                                    "body": body
                                }
                            });
                        }
                        //estado de los indicadores
                        var contenedorEstadoIndicadores = $(this).find(".contenedor_estado_indicadores");
                        if(contenedorEstadoIndicadores.length > 0) {
                            layout.content.push({
                                "text": "Estado de los indicadores",
                                "style": "titulo3"
                            });
                            var body2 = [];
                            var cabecera = [];
                            $(contenedorEstadoIndicadores).find("thead > tr > th").each(function(){
                                cabecera.push({
                                    "text": $(this).text(),
                                    "style": "cabeceraTabla"
                                });
                            });
                            body2.push(cabecera);
                            $(contenedorEstadoIndicadores).find("tbody > tr").each(function(){
                                var fila = [];
                                var color = $(this).attr("bgcolor");
                                console.log(color);
                                $(this).find("td").each(function(){
                                    fila.push({
                                        "text": $(this).ignore(".hidden").text(),
                                        "style": "parrafoNormal",
                                        "fillColor": color
                                    });
                                });
                                body2.push(fila);
                            });
                            layout.content.push({
                                table: {
                                    headerRows: 1,
                                    widths: ['25%', '*', '15%', '15%'],
                                    "body": body2
                                }
                            });
                        }
                    });
                    //exportamos el pdf
                    chart["export"].toPDF(layout, function(data) {
                        this.download(data, "application/pdf", "Reporte POA " + anio + " al " + fecha + ".pdf");
                    });
                }
            }
        </script>
    </body>
</html>
