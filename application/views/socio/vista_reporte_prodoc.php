<!DOCTYPE html>
<html lang="es">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />

        <link rel="stylesheet" href="<?= base_url() . 'assets/css/bootstrap.css' ?>" />

        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery-3.1.0.min.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/bootstrap.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/jquery.number.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/amcharts/amcharts.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/amcharts/serial.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/amcharts/themes/light.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/amcharts/plugins/export/export.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets/js/Blob.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets\js\amcharts\plugins\export\libs\pdfmake\pdfmake.js' ?>"></script>
        <script type="text/javascript" src="<?= base_url() . 'assets\js\amcharts\plugins\export\libs\pdfmake\vfs_fonts.js' ?>"></script>
        
        <title>Ver PRODOC</title>
    </head>
    <body>
        <div class="container">
            <?php $this->load->view('cabecera') ?>
            <?php
            $datos = Array();
            $datos['activo'] = "PRODOC";
            $this->load->view('socio/nav', $datos);
            $n1 = 1;
            $n2 = 1;
            $n3 = 1;
            $n4 = 1;
            ?>
            <?php if ($prodoc): ?>
            <p class="text-right"><a onclick="generar_pdf()" class="btn btn-success">Generar PDF</a></p>
                <h4 id="titulo_prodoc" class="text-justify text-primary"><?= $prodoc->nombre_prodoc ?></h4>
                <p id="descripcion_prodoc" class="text-justify"><?= $prodoc->descripcion_prodoc ?></p>
                <h4 id="titulo_objetivo_global" class="text-primary"><?php echo($n1); ?>. Objetivo global</h4>
                <p id="objetivo_global" class="text-justify"><?= $prodoc->objetivo_global_prodoc ?></p>
                <h4 id="titulo_objetivo_proyecto" class="text-primary"><?php $n1 += 1; echo($n1); ?>. Objetivo del proyecto</h4>
                <p id="objetivo_proyecto" class="text-justify"><?= $prodoc->objetivo_proyecto_prodoc ?></p>
                <?php if ($prodoc->efectos): ?>
                    <?php foreach ($prodoc->efectos as $efecto): ?>
                        <div class="panel panel-default contenedor_efecto"  id="efecto_<?= $efecto->id_efecto ?>">
                            <div class="panel-heading">
                                <span class="text-primary"><strong class="text-primary titulo_efecto"><?= $n1 . '.' . $n2 ?>. <?= $efecto->nombre_efecto ?></strong></span>
                            </div>
                            <div class="panel-body">
                                <p class="text-justify"><strong>Descripción:</strong> <span class="descripcion_efecto"><?= $efecto->descripcion_efecto ?></span></p>
                                <?php if ($efecto->productos): ?>
                                    <?php $n3 = 1; ?>
                                    <?php foreach ($efecto->productos as $producto): ?>
                                        <div class="panel panel-default contenedor_producto"  id="producto_<?= $producto->id_producto ?>">
                                            <div class="panel-heading">
                                                <span class="text-primary"><strong class="titulo_producto"><?= $n1 . '.' . $n2 . '.' . $n3 ?> <?= $producto->nombre_producto ?></strong></span>
                                            </div>
                                            <div class="panel-body">
                                                <p class="text-justify"><strong>Descripción:</strong> <span class="descripcion_producto"><?= $producto->descripcion_producto ?></span></p>
                                                <?php if ($producto->metas_cuantitativas): ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Indicador</th>
                                                                    <th>Descripción</th>
                                                                    <th width="17%">Avance / Meta</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($producto->metas_cuantitativas as $meta_cuantitativa): ?>
                                                                    <tr>
                                                                        <td><?= $meta_cuantitativa->nombre_meta_producto_cuantitativa ?></td>
                                                                        <td><?= $meta_cuantitativa->descripcion_meta_producto_cuantitativa ?></td>
                                                                        <td><span class="number_integer"><?= $meta_cuantitativa->avance_meta_producto_cuantitativa ?></span> / <span class="number_integer"><?= $meta_cuantitativa->cantidad_meta_producto_cuantitativa ?></span> <?= $meta_cuantitativa->unidad_meta_producto_cuantitativa ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="panel panel-warning">
                                                        <div class="panel-heading">
                                                            Advertencia
                                                        </div>
                                                        <div class="panel-body">
                                                            Todavía no se registraron indicadores para el producto <strong>"<?= $producto->nombre_producto ?>"</strong>.
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php $n3 += 1; ?>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="panel panel-warning">
                                        <div class="panel-heading">
                                            Advertencia
                                        </div>
                                        <div class="panel-body">
                                            Todavía no se registraron productos para el efecto <strong>"<?= $efecto->nombre_efecto ?>"</strong>.
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php $n2 += 1; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="panel panel-warning">
                        <div class="panel-heading">
                            Advertencia
                        </div>
                        <div class="panel-body">
                            Todavía no se registraron efectos.
                        </div>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        Advertencia
                    </div>
                    <div class="panel-body">
                        Todavía no se registro el PRODOC.
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('.number_decimal').number(true, 2);
                $('.number_integer').number(true);
            });
        </script>
        <script type="text/javascript">
            function generar_pdf() {
                var layout = {
                    pageMargins: [40, 50, 40, 50],
                    styles: {
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
                            fontSize: 12,
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
                        parrafoNormal: {
                            fontSize: 10,
                            alignment: 'justify',
                            margin: [0, 0, 0, 5]
                        },
                        header: {
                            fontSize: 10,
                            alignment: 'left',
                            margin: [40, 30, 0, 0],
                            color: '#939393'
                        },
                        footer: {
                            fontSize: 10,
                            alignment: 'right',
                            margin: [0, 0, 40, 30],
                            color: '#939393'
                        }
                    },
                    content: [
                        {
                            text: document.getElementById('titulo_prodoc').innerHTML,
                            style: 'titulo'
                        },
                        {
                            text: document.getElementById('descripcion_prodoc').innerHTML,
                            style: 'parrafoNormal'
                        },
                        {
                            text: document.getElementById('titulo_objetivo_global').innerHTML,
                            style: 'titulo1'
                        },
                        {
                            text: document.getElementById('objetivo_global').innerHTML,
                            style: 'parrafoNormal'
                        },
                        {
                            text: document.getElementById('titulo_objetivo_proyecto').innerHTML,
                            style: 'titulo1'
                        },
                        {
                            text: document.getElementById('objetivo_proyecto').innerHTML,
                            style: 'parrafoNormal'
                        }
                    ],
                    header: {
                        text: 'Sistema de monitoreo del proyecto: Promoción de una cultura de resiliencia',
                        style: 'header'
                    },
                    footer: function(currentPage, pageCount) {
                        return { 
                            text: 'página ' + currentPage,
                            style: 'footer'
                        };
                    }
                };
                //generamos el contenido de cada efecto
                $('.contenedor_efecto').each(function(){
                    var titulo_efecto = $(this).find('.titulo_efecto').text();
                    var descripcion_efecto = $(this).find('.descripcion_efecto').text();
                    layout.content.push({
                        text: titulo_efecto,
                        style: 'titulo2'
                    });
                    layout.content.push({
                        text: descripcion_efecto,
                        style: 'parrafoNormal'
                    });
                    //generamos el contenido de cada producto
                    $(this).find('.contenedor_producto').each(function(){
                        var titulo_producto = $(this).find('.titulo_producto').text();
                        var descripcion_producto = $(this).find('.descripcion_producto').text();
                        layout.content.push({
                            text: titulo_producto,
                            style: 'titulo3'
                        });
                        layout.content.push({
                            text: descripcion_producto,
                            style: 'parrafoNormal'
                        });
                        //generamos la tabla de indicadores
                        $(this).find('table').each(function(){
                            //generamos el cuerpo de la tabla
                            var body = [];
                            //generamos la cabecera
                            var cabecera = [];
                            var thead = $(this).find('thead');
                            $(thead).find('th').each(function(){
                                cabecera.push({
                                    text: $(this).text(),
                                    style: 'parrafoNormal'
                                });
                            });
                            body.push(cabecera);
                            //generamos el cuerpo
                            var tbody = $(this).find('tbody');
                            //generamos cada fila del cuerpo
                            $(tbody).find('tr').each(function(){
                                var fila = [];
                                $(this).find('td').each(function(){
                                    fila.push({
                                        text: $(this).text(),
                                        style: 'parrafoNormal'
                                    });
                                });
                                body.push(fila);
                            });
                            layout.content.push({
                                table: {
                                    headerRows: 1,
                                    widths: ['30%', '*', '30%'],
                                    body: body
                                }
                            });
                            console.log(body);
                        });
                    });
                });
                //generar pdf
                pdfMake.createPdf(layout).open();
            }
        </script>
    </body>
</html>
