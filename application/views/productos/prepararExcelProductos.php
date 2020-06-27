<?php
$hoja = 0;
$this->excel->createSheet($hoja);
$this->excel->setActiveSheetIndex($hoja);
//name the worksheet
$this->excel->getActiveSheet()->setTitle(substr("Listado Productos", 0, 30));
$this->excel->getActiveSheet()->setCellValue('A1', "Productos catalogados ");
$this->excel->getActiveSheet()->getStyle("A1:E1")->getFont()->setBold(true);
$hoy = date('d/m/Y');
$this->excel->getActiveSheet()->setCellValue('A2', "Fecha: $hoy");

$l=4;
// criterios búsqueda globales (todos los campos)
if($buscar!="_"){
$this->excel->getActiveSheet()->getCell("b$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("c$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("d$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("e$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("h$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("i$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("k$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("m$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("n$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("o$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("j$l")->setValueExplicit($buscar, PHPExcel_Cell_DataType::TYPE_STRING);

$this->excel->getActiveSheet()->getStyle("A$l:p$l")->getFont()->setItalic(true);
$this->excel->getActiveSheet()->getStyle("e$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("h$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("k$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("m$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("n$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("o$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$l++;
}

// criterios búsqueda cabeceras"
$this->excel->getActiveSheet()->getCell("b$l")->setValueExplicit($codigo_producto, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("c$l")->setValueExplicit($id_producto, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("d$l")->setValueExplicit($producto, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("e$l")->setValueExplicit($peso_real, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("h$l")->setValueExplicit($precio_compra, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("i$l")->setValueExplicit($tipo_unidad, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("k$l")->setValueExplicit($tarifa_venta, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("m$l")->setValueExplicit($stock_total, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("n$l")->setValueExplicit($valoracion, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("o$l")->setValueExplicit($margen, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("j$l")->setValueExplicit($proveedor, PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getStyle("A$l:p$l")->getFont()->setItalic(true);
$this->excel->getActiveSheet()->getStyle("A$l:p$l")->getFont()->setItalic(true);
$this->excel->getActiveSheet()->getStyle("e$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("h$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("k$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("m$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("n$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("o$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$l++;
// encabezados
$this->excel->getActiveSheet()->getCell("A$l")->setValueExplicit('Núm', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("B$l")->setValueExplicit('Código 13', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("C$l")->setValueExplicit('C. Báscula', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("D$l")->setValueExplicit('Producto', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("E$l")->setValueExplicit('Peso (Kg)', PHPExcel_Cell_DataType::TYPE_STRING); 
$this->excel->getActiveSheet()->getCell("F$l")->setValueExplicit('Grupo', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("G$l")->setValueExplicit('Familia', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("H$l")->setValueExplicit('Precio compra (€)', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("I$l")->setValueExplicit('Tipo unidad', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("J$l")->setValueExplicit('Proveedor', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("K$l")->setValueExplicit('Tarifa venta (€)', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("L$l")->setValueExplicit('Control stock', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("M$l")->setValueExplicit('Stock total', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("N$l")->setValueExplicit('Valoracion', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("O$l")->setValueExplicit('Margen (%)', PHPExcel_Cell_DataType::TYPE_STRING);
$this->excel->getActiveSheet()->getCell("P$l")->setValueExplicit('url_imagen_portada', PHPExcel_Cell_DataType::TYPE_STRING);

$this->excel->getActiveSheet()->getStyle("A$l:P$l")->getFont()->setBold(true);
$this->excel->getActiveSheet()->getStyle("e$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("h$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("k$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("m$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("n$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
$this->excel->getActiveSheet()->getStyle("o$l")->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

$l++;
// resultados obtenidos
foreach ($result as $k => $v) {
    $this->excel->getActiveSheet()->getCell("A". ($k + $l))->setValueExplicit($v->id, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("b". ($k + $l))->setValueExplicit($v->codigo_producto, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("c". ($k + $l))->setValueExplicit($v->id_producto, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("d". ($k + $l))->setValueExplicit($v->nombre, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("e". ($k + $l))->setValueExplicit($v->peso_real, PHPExcel_Cell_DataType::TYPE_NUMERIC); 
    $this->excel->getActiveSheet()->getCell("f". ($k + $l))->setValueExplicit($v->nombre_grupo, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("g". ($k + $l))->setValueExplicit($v->nombre_familia, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("h". ($k + $l))->setValueExplicit($v->precio_compra, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("i". ($k + $l))->setValueExplicit($v->tipo_unidad, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("j". ($k + $l))->setValueExplicit($v->nombre_proveedor, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("k". ($k + $l))->setValueExplicit($v->tarifa_venta, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("l". ($k + $l))->setValueExplicit($v->control_stock, PHPExcel_Cell_DataType::TYPE_STRING);
    $this->excel->getActiveSheet()->getCell("m". ($k + $l))->setValueExplicit($v->stock_total, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("n". ($k + $l))->setValueExplicit($v->valoracion, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("o". ($k + $l))->setValueExplicit($v->margen, PHPExcel_Cell_DataType::TYPE_NUMERIC);
    $this->excel->getActiveSheet()->getCell("p". ($k + $l))->setValueExplicit($v->url_imagen_portada, PHPExcel_Cell_DataType::TYPE_STRING);

    $this->excel->getActiveSheet()->getStyle("e". ($k + $l))->getNumberFormat()->setFormatCode('###0.000');
    $this->excel->getActiveSheet()->getStyle("h". ($k + $l))->getNumberFormat()->setFormatCode('###0.000');
    $this->excel->getActiveSheet()->getStyle("k". ($k + $l))->getNumberFormat()->setFormatCode('###0.000');
    $this->excel->getActiveSheet()->getStyle("m". ($k + $l))->getNumberFormat()->setFormatCode('###0');
    $this->excel->getActiveSheet()->getStyle("n". ($k + $l))->getNumberFormat()->setFormatCode('###0.00');
}    


$this->excel->getActiveSheet()->getColumnDimension("A")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("b")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("c")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("d")->setWidth(80);
$this->excel->getActiveSheet()->getColumnDimension("e")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("f")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("g")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("h")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("i")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("j")->setWidth(80);
$this->excel->getActiveSheet()->getColumnDimension("k")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("l")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("m")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("n")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("o")->setWidth(15);
$this->excel->getActiveSheet()->getColumnDimension("p")->setWidth(15);
    
$this->excel->removeSheetByIndex(-1);
$filename = "Productos.xls";
header('Content-Type: application/vnd.ms-excel'); //mime type
header('Content-Disposition: attachment;filename="' . $filename . '"'); //tell browser what's the file name
header('Cache-Control: max-age=0'); //no cache

//save it to Excel5 format (excel 2003 .XLS file), change this to 'Excel2007' (and adjust the filename extension, also the header mime type)
//if you want to save it as .XLSX Excel 2007 format
$objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');


//force user to download the Excel file without writing it to server's HD
//
//$objWriter->save(str_replace('.php', '.xls', __FILE__));
$objWriter->save('php://output');
