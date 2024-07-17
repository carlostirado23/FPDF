<?php
// F-GSV-11_ Ver 01_ Inspección kit de Carretera

require('fpdf/fpdf.php');

class PDF extends FPDF
{
    // Cabecera de página
    function Header()
    {
    }

    // Pie de página
    function Footer()
    {
    }

    // Crear un marco para el formulario con bordes redondeados
    function RoundedRect($x, $y, $w, $h, $r, $style = '')
    {
        $k = $this->k;
        $hp = $this->h;
        if ($style == 'F')
            $op = 'f';
        elseif ($style == 'FD' || $style == 'DF')
            $op = 'B';
        else
            $op = 'S';
        $MyArc = 4 / 3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

        $xc = $x + $w - $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
        $this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);

        $xc = $x + $w - $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
        $this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);

        $xc = $x + $r;
        $yc = $y + $h - $r;
        $this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
        $this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);

        $xc = $x + $r;
        $yc = $y + $r;
        $this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
        $this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
    {
        $h = $this->h;
        $this->_out(sprintf(
            '%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1 * $this->k,
            ($h - $y1) * $this->k,
            $x2 * $this->k,
            ($h - $y2) * $this->k,
            $x3 * $this->k,
            ($h - $y3) * $this->k
        ));
    }

    // Función para pintar el fondo de las letras con bordes redondeados
    function PaintTextBackground($x, $y, $text, $backgroundColor = [35, 175, 216], $textColor = [0, 0, 0], $radius = 2, $customWidth = null, $customHeight = null)
    {
        $textWidth = $this->GetStringWidth($text) + 4; // Añadir un poco de padding
        $width = $customWidth ? $customWidth : $textWidth;
        $height = $customHeight ? $customHeight : 8; // Usar la altura proporcionada o el valor predeterminado de 8

        // Establecer el color de relleno para el fondo
        $this->SetFillColor($backgroundColor[0], $backgroundColor[1], $backgroundColor[2]);

        // Dibujar el rectángulo redondeado
        $this->RoundedRect($x, $y, $width, $height, $radius, 'F');

        // Calcular la posición para centrar el texto horizontal y verticalmente
        $textX = $x + ($width - $textWidth) / 2;
        $textY = $y + ($height - 8) / 2; // Ajustar la posición Y según la altura

        // Establecer la posición del texto
        $this->SetXY($textX, $textY);
        // Establecer el color del texto
        $this->SetTextColor(
            $textColor[0],
            $textColor[1],
            $textColor[2]
        );
        $this->Cell($textWidth, 8, $text, 0, 0, 'C', false);

        // Restablecer el color del texto a negro
        $this->SetTextColor(
            0,
            0,
            0
        );
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('LANDSCAPE', '');

// Dibujar el marco del formulario con bordes redondeados
$pdf->RoundedRect(5, 10, 287, 140, 0, 'D');

$pdf->Image('./servicer.jpeg', 6, 11, 50, 15);

// INSPECCION DE EXTINTORES
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(105, 14);
$pdf->MultiCell(90, 3, utf8_decode("INSPECCION DE EXTINTORES"), 0, 'C');
$pdf->Line(60, 20, 292, 20); //HORIZONTAL

// PROCESO SEGURIDAD Y SALUD EN EL TRABAJO
$pdf->SetXY(113, 25);
$pdf->Cell(0, 0, utf8_decode("PROCESO SEGURIDAD Y SALUD EN EL TRABAJO"));

// TEXTO CON FONDE DE COLOR
$pdf->SetFont('Arial', 'B', 7);
$pdf->PaintTextBackground(5, 27, utf8_decode('ELABORÓ / ACTUALIZÓ'), [35, 175, 216], [255, 255, 255], 0, 55, 4);
$pdf->PaintTextBackground(60, 27, utf8_decode('REVISÓ'), [35, 175, 216], [255, 255, 255], 0, 192, 4);

// APROBO
$pdf->PaintTextBackground(252, 27, utf8_decode('APROBÓ'), [35, 175, 216], [255, 255, 255], 0, 40, 4);

// LINEAL VERTICAL QUE ESTA AL LADO DE LA IMAGEN
$pdf->Line(60, 10, 60, 35); // VERTICAL

// codigo
$pdf->Line(252, 10, 252, 35); //VERTICAL
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(258, 13);
$pdf->Cell(0, 0, utf8_decode("Código:"));

//  F-SST-30
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(272, 13);
$pdf->Cell(0, 0, utf8_decode("F-SST-30"));

$pdf->Line(252, 15, 292, 15); //HORIZONTAL

// FECHA
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(258, 13);
$pdf->Cell(0, 10, "Fecha:");

// 31/05/2023
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(272, 13);
$pdf->Cell(0, 10, "31/05/2023");

// VERSION
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(258, 13);
$pdf->Cell(0, 18, utf8_decode("Versión:"));

// 00
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(272, 13);
$pdf->Cell(0, 18, " 00");
$pdf->Line(252, 23.5, 292, 23.5); //HORIZAONTAL

// VERSION
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(258, 13);
$pdf->Cell(0, 25, utf8_decode("Página:"));

// 1 de 1
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(272, 13);
$pdf->Cell(0, 25, "1 de 1");
$pdf->Line(5, 27, 292, 27); //HORIZAONTAL
$pdf->Line(5, 31, 292, 31); //HORIZAONTAL

// auxiliar de calidad
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(20, 33);
$pdf->Cell(0, 0, "Auxiliar de calidad");

// LIDER SGI
$pdf->SetXY(150, 33);
$pdf->Cell(0, 0, "Lider SGI");

// GERENTE
$pdf->SetXY(266, 33);
$pdf->Cell(0, 0, "Gerente");
$pdf->Line(5, 35, 292, 35); //HORIZONTAL

$pdf->Line(5, 38, 292, 38); //HORIZONTAL

// SEDE, OFICINA O PROYECTO:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(5, 40);
$pdf->Cell(0, 0, utf8_decode("SEDE, OFICINA O PROYECTO:"));

//  SEDE, OFICINA O PROYECTO:
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(50, 40);
$pdf->Cell(0, 0, utf8_decode("SEDE"));


// FECHA:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(235, 40);
$pdf->Cell(0, 0, utf8_decode("FECHA:"));

// 31/05/2023
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(250, 40);
$pdf->Cell(0, 0, "31/05/2023");
$pdf->Line(235, 38, 235, 42); //VERTICAL
$pdf->Line(5, 42, 292, 42); //HORIZONTAL

// C: Cumple NC: No Cumple N/A: No Aplica
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(220, 44);
$pdf->Cell(0, 0, utf8_decode("C: Cumple     NC: No Cumple     N/A: No Aplica"));
$pdf->Line(220, 45.5, 285, 45.5); //HORIZONTAL
$pdf->Line(5, 46, 292, 46); //HORIZONTAL

// CONVENCIONES
$pdf->PaintTextBackground(5, 46, utf8_decode('CONVENCIONES'), [184, 183, 183], [0, 0, 0], 0, 287, 5);
$pdf->Line(5, 51, 292, 51); //HORIZONTAL

// AC: Acceso
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(30, 53);
$pdf->Cell(0, 0, utf8_decode("AC: Acceso"));
$pdf->Line(71, 51, 71, 59); //VERTICAL

// BO: Boquilla o Corneta
$pdf->SetXY(85, 53);
$pdf->Cell(0, 0, utf8_decode("BO: Boquilla o Corneta"));
$pdf->Line(131, 51, 131, 59); //VERTICAL

// SE: Señalización
$pdf->SetXY(145, 53);
$pdf->Cell(0, 0, utf8_decode("SE: Señalización"));
$pdf->Line(180, 51, 180, 59); //VERTICAL

// S: Instalación en el sitio  
$pdf->SetXY(195, 53);
$pdf->Cell(0, 0, utf8_decode("S: Instalación en el sitio"));
$pdf->Line(236, 51, 236, 55); //VERTICAL

// IN: Instrucciones
$pdf->SetXY(238, 53);
$pdf->Cell(0, 0, utf8_decode("IN: Instrucciones"));
$pdf->Line(264, 51, 264, 59); //VERTICAL

// AM: Amarre
$pdf->SetXY(270, 53);
$pdf->Cell(0, 0, utf8_decode("AM: Amarre"));
$pdf->Line(5, 55, 292, 55); //HORIZONTAL

// MT: Manija de Transporte 
$pdf->SetXY(23, 57);
$pdf->Cell(0, 0, utf8_decode('MT: Manija de Transporte '));

// MA: Manguera 
$pdf->SetXY(90, 57);
$pdf->Cell(0, 0, utf8_decode('MA: Manguera '));

// PA: Pasador
$pdf->SetXY(145, 57);
$pdf->Cell(0, 0, utf8_decode('PA: Pasador'));

// AN: Anillo
$pdf->SetXY(185, 57);
$pdf->Cell(0, 0, utf8_decode('AN: Anillo'));
$pdf->Line(208, 55, 208, 59);

// PM: Presión Manometro
$pdf->SetXY(220, 57);
$pdf->Cell(0, 0, utf8_decode('PM: Presión Manometro'));

// PI: Pintura
$pdf->SetXY(270, 57);
$pdf->Cell(0, 0, utf8_decode('PI: Pintura'));

$pdf->Line(5, 59, 292, 59); //HORIZONTAL

// No.
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(5, 59, utf8_decode('No.'), [103, 103, 103], [255, 255, 255], 0, 6, 8);
$pdf->Line(11, 59, 11, 127); //VERTICAL

// LOCALIZACIÓN
$pdf->PaintTextBackground(11, 59, utf8_decode('LOCALIZACIÓN'), [103, 103, 103], [255, 255, 255], 0, 95, 4);
$pdf->Line(11, 63, 106, 63); //HORIZONTAL

// Proceso
$pdf->PaintTextBackground(11, 63, utf8_decode('Proceso'), [103, 103, 103], [255, 255, 255], 0, 30, 4);
$pdf->Line(41, 63, 41, 127); //VERTICAL

// Brigada / Ubicación 
$pdf->PaintTextBackground(41, 63, utf8_decode('Brigada / Ubicación '), [103, 103, 103], [255, 255, 255], 0, 30, 4);
$pdf->Line(71, 63, 71, 127); //VERTICAL

// Responsable 
$pdf->PaintTextBackground(71, 63, utf8_decode('Responsable'), [103, 103, 103], [255, 255, 255], 0, 35, 4);
$pdf->Line(106, 59, 106, 127); //VERTICAL

// Placa de vehiculo
$pdf->PaintTextBackground(106, 59, utf8_decode('Placa de vehiculo'), [103, 103, 103], [255, 255, 255], 0, 25, 8);
$pdf->Line(131, 59, 131, 127); //VERTICAL

// Tipo de extintor y 
$pdf->PaintTextBackground(131, 59, utf8_decode('Tipo de extintor y '), [103, 103, 103], [255, 255, 255], 0, 25, 5);
// capacidad 
$pdf->PaintTextBackground(131, 63, utf8_decode('Fecha'), [103, 103, 103], [255, 255, 255], 0, 25, 4);
$pdf->Line(156, 59, 156, 127); //VERTICAL

// FECHA RECARGA
$pdf->PaintTextBackground(156, 59, utf8_decode('FECHA RECARGA'), [103, 103, 103], [255, 255, 255], 0, 24, 8);
$pdf->Line(180, 59, 180, 127); //VERTICAL

// FECHA VENCIMIENTO
$pdf->PaintTextBackground(180, 59, utf8_decode('FECHA VENCIMIENTO'), [103, 103, 103], [255, 255, 255], 0, 28, 8);
$pdf->Line(208, 59, 208, 127); //VERTICAL

// ASPECTOS A REVISAR
$pdf->PaintTextBackground(208, 59, utf8_decode('ASPECTOS A REVISAR'), [103, 103, 103], [255, 255, 255], 0, 84, 4);
$pdf->Line(208, 63, 292, 63); //HORIZONTAL

// AC
$pdf->PaintTextBackground(208, 63, utf8_decode('AC'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(215,63, 215, 127); //VERTICAL

// BO
$pdf->PaintTextBackground(215, 63, utf8_decode('BO'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(222, 63, 222, 127); //VERTICAL

// SE
$pdf->PaintTextBackground(222, 63, utf8_decode('SE'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(229, 63, 229, 127); //VERTICAL

// IS
$pdf->PaintTextBackground(229, 63, utf8_decode('IS'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(236, 63, 236, 127); //VERTICAL

// IN
$pdf->PaintTextBackground(236, 63, utf8_decode('IN'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(243, 63, 243, 127); //VERTICAL

// AM
$pdf->PaintTextBackground(243, 63, utf8_decode('AM'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(250, 63, 250, 127); //VERTICAL

// MT
$pdf->PaintTextBackground(250, 63, utf8_decode('MT'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(257, 63, 257, 127); //VERTICAL

// MA
$pdf->PaintTextBackground(257, 63, utf8_decode('MA'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(264, 63, 264, 127); //VERTICAL

// PA
$pdf->PaintTextBackground(264, 63, utf8_decode('PA'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(271, 63, 271, 127); //VERTICAL

// AN
$pdf->PaintTextBackground(271, 63, utf8_decode('AN'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(278, 63, 278, 127); //VERTICAL

// PM
$pdf->PaintTextBackground(278, 63, utf8_decode('PM'), [103, 103, 103], [255, 255, 255], 0, 7, 4);
$pdf->Line(285, 63, 285, 127); //VERTICAL

// PI
$pdf->PaintTextBackground(285, 63, utf8_decode('PI'), [103, 103, 103], [255, 255, 255], 0, 7, 4);

$pdf->Line(5, 67, 292, 67); //HORIZONTAL

// 1
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(6, 72);
$pdf->Cell(0, 0, "1");

// PROCESO
$pdf->SetXY(12, 69);
$pdf->MultiCell(26, 3, utf8_decode("Proceso"));

// BRIGADA
$pdf->SetXY(42, 69);
$pdf->MultiCell(26, 3, utf8_decode("Brigada"));

// RESPONSABLE
$pdf->SetXY(72, 69);
$pdf->MultiCell(31, 3, utf8_decode("Responsable"));

// PLACA DE VEHICULO
$pdf->SetXY(107, 69);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// TIPO DE EXTINTOR Y CAPACIDAD
$pdf->SetXY(131, 69);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// FECHA RECARGA
$pdf->SetXY(159, 71);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// FECHA VENCIMIENTO
$pdf->SetXY(183, 71);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// AC
$pdf->SetXY(209, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// BO
$pdf->SetXY(216, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// SE
$pdf->SetXY(223, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// IS
$pdf->SetXY(230, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// IN
$pdf->SetXY(237, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// AM
$pdf->SetXY(244, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// MT
$pdf->SetXY(251, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// MA
$pdf->SetXY(258, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// PA
$pdf->SetXY(265, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// AN
$pdf->SetXY(272, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// PM
$pdf->SetXY(279, 72);
$pdf->Cell(0, 0, utf8_decode("X"));

// PI
$pdf->SetXY(286, 72);
$pdf->Cell(0, 0, utf8_decode("X"));
$pdf->Line(5, 77, 292, 77); //HORIZONTAL

// 2
$pdf->SetXY(6, 82);
$pdf->Cell(0, 0, "2");

// PROCESO
$pdf->SetXY(12, 79);
$pdf->MultiCell(26, 3, utf8_decode("Proceso"));

// BRIGADA
$pdf->SetXY(42, 79);
$pdf->MultiCell(26, 3, utf8_decode("Brigada"));

// RESPONSABLE
$pdf->SetXY(72, 79);
$pdf->MultiCell(31, 3, utf8_decode("Responsable"));

// PLACA DE VEHICULO
$pdf->SetXY(107, 79);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// TIPO DE EXTINTOR Y CAPACIDAD
$pdf->SetXY(131, 79);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// FECHA RECARGA
$pdf->SetXY(159, 81);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// FECHA VENCIMIENTO
$pdf->SetXY(183, 81);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// AC
$pdf->SetXY(209, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// BO
$pdf->SetXY(216, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// SE
$pdf->SetXY(223, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// IS
$pdf->SetXY(230, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// IN
$pdf->SetXY(237, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// AM
$pdf->SetXY(244, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// MT
$pdf->SetXY(251, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// MA
$pdf->SetXY(258, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// PA
$pdf->SetXY(265, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// AN
$pdf->SetXY(272, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// PM
$pdf->SetXY(279, 82);
$pdf->Cell(0, 0, utf8_decode("X"));

// PI
$pdf->SetXY(286, 82);
$pdf->Cell(0, 0, utf8_decode("X"));
$pdf->Line(5, 87, 292, 87); //HORIZONTAL

// 3
$pdf->SetXY(6, 92);
$pdf->Cell(0, 0, "3");

// PROCESO
$pdf->SetXY(12, 89);
$pdf->MultiCell(26, 3, utf8_decode("Proceso"));

// BRIGADA
$pdf->SetXY(42, 89);
$pdf->MultiCell(26, 3, utf8_decode("Brigada"));

// RESPONSABLE
$pdf->SetXY(72, 89);
$pdf->MultiCell(31, 3, utf8_decode("Responsable"));

// PLACA DE VEHICULO
$pdf->SetXY(107, 89);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// TIPO DE EXTINTOR Y CAPACIDAD
$pdf->SetXY(131, 89);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// FECHA RECARGA
$pdf->SetXY(159, 91);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// FECHA VENCIMIENTO
$pdf->SetXY(183, 91);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// AC
$pdf->SetXY(209, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// BO
$pdf->SetXY(216, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// SE
$pdf->SetXY(223, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// IS
$pdf->SetXY(230, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// IN
$pdf->SetXY(237, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// AM
$pdf->SetXY(244, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// MT
$pdf->SetXY(251, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// MA
$pdf->SetXY(258, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// PA
$pdf->SetXY(265, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// AN
$pdf->SetXY(272, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// PM
$pdf->SetXY(279, 92);
$pdf->Cell(0, 0, utf8_decode("X"));

// PI
$pdf->SetXY(286, 92);
$pdf->Cell(0, 0, utf8_decode("X"));
$pdf->Line(5, 97, 292, 97); //HORIZONTAL

// 4
$pdf->SetXY(6, 102);
$pdf->Cell(0, 0, "4");

// PROCESO
$pdf->SetXY(12, 99);
$pdf->MultiCell(26, 3, utf8_decode("Proceso"));

// BRIGADA
$pdf->SetXY(42, 99);
$pdf->MultiCell(26, 3, utf8_decode("Brigada"));

// RESPONSABLE
$pdf->SetXY(72, 99);
$pdf->MultiCell(31, 3, utf8_decode("Responsable"));

// PLACA DE VEHICULO
$pdf->SetXY(107, 99);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// TIPO DE EXTINTOR Y CAPACIDAD
$pdf->SetXY(131, 99);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// FECHA RECARGA
$pdf->SetXY(159, 101);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// FECHA VENCIMIENTO
$pdf->SetXY(183, 101);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// AC
$pdf->SetXY(209, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// BO
$pdf->SetXY(216, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// SE
$pdf->SetXY(223, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// IS
$pdf->SetXY(230, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// IN
$pdf->SetXY(237, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// AM
$pdf->SetXY(244, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// MT
$pdf->SetXY(251, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// MA
$pdf->SetXY(258, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// PA
$pdf->SetXY(265, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// AN
$pdf->SetXY(272, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// PM
$pdf->SetXY(279, 102);
$pdf->Cell(0, 0, utf8_decode("X"));

// PI
$pdf->SetXY(286, 102);
$pdf->Cell(0, 0, utf8_decode("X"));
$pdf->Line(5, 107, 292, 107); //HORIZONTAL

// 5
$pdf->SetXY(6, 112);
$pdf->Cell(0, 0, "5");

// PROCESO
$pdf->SetXY(12, 109);
$pdf->MultiCell(26, 3, utf8_decode("Proceso"));

// BRIGADA
$pdf->SetXY(42, 109);
$pdf->MultiCell(26, 3, utf8_decode("Brigada"));

// RESPONSABLE
$pdf->SetXY(72, 109);
$pdf->MultiCell(31, 3, utf8_decode("Responsable"));

// PLACA DE VEHICULO
$pdf->SetXY(107, 109);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// TIPO DE EXTINTOR Y CAPACIDAD
$pdf->SetXY(131, 109);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// FECHA RECARGA
$pdf->SetXY(159, 111);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// FECHA VENCIMIENTO
$pdf->SetXY(183, 111);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// AC
$pdf->SetXY(209, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// BO
$pdf->SetXY(216, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// SE
$pdf->SetXY(223, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// IS
$pdf->SetXY(230, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// IN
$pdf->SetXY(237, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// AM
$pdf->SetXY(244, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// MT
$pdf->SetXY(251, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// MA
$pdf->SetXY(258, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// PA
$pdf->SetXY(265, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// AN
$pdf->SetXY(272, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// PM
$pdf->SetXY(279, 112);
$pdf->Cell(0, 0, utf8_decode("X"));

// PI
$pdf->SetXY(286, 112);
$pdf->Cell(0, 0, utf8_decode("X"));
$pdf->Line(5, 117, 292, 117); //HORIZONTAL

// 6
$pdf->SetXY(6, 122);
$pdf->Cell(0, 0, "6");

// PROCESO
$pdf->SetXY(12, 119);
$pdf->MultiCell(26, 3, utf8_decode("Proceso"));

// BRIGADA
$pdf->SetXY(42, 119);
$pdf->MultiCell(26, 3, utf8_decode("Brigada"));

// RESPONSABLE
$pdf->SetXY(72, 119);
$pdf->MultiCell(31, 3, utf8_decode("Responsable"));

// PLACA DE VEHICULO
$pdf->SetXY(107, 119);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// TIPO DE EXTINTOR Y CAPACIDAD
$pdf->SetXY(131, 119);
$pdf->MultiCell(24, 3, utf8_decode("ABC123"));

// FECHA RECARGA
$pdf->SetXY(159, 121);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// FECHA VENCIMIENTO
$pdf->SetXY(183, 121);
$pdf->MultiCell(24, 3, utf8_decode("31/05/2023"));

// AC
$pdf->SetXY(209, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// BO
$pdf->SetXY(216, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// SE
$pdf->SetXY(223, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// IS
$pdf->SetXY(230, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// IN
$pdf->SetXY(237, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// AM
$pdf->SetXY(244, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// MT
$pdf->SetXY(251, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// MA
$pdf->SetXY(258, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// PA
$pdf->SetXY(265, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// AN
$pdf->SetXY(272, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// PM
$pdf->SetXY(279, 122);
$pdf->Cell(0, 0, utf8_decode("X"));

// PI
$pdf->SetXY(286, 122);
$pdf->Cell(0, 0, utf8_decode("X"));
$pdf->Line(5, 127, 292, 127); //HORIZONTAL

// OBSERVACIONES: 
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(5, 130);
$pdf->Cell(0, 0, utf8_decode("OBSERVACIONES:"));

// DATOS OBSERVACION
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(7, 132);
$pdf->MultiCell(280, 3, utf8_decode("OBSERVACION"));
$pdf->Line(5, 138, 292, 138); //HORIZONTAL

// INSPECCIONADO POR:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(5, 141);
$pdf->Cell(0, 0, utf8_decode("INSPECCIONADO POR:"));

// DATOS INSPECCIONADO POR
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(7, 143);
$pdf->MultiCell(280, 3, utf8_decode("INSPECCIONADO POR"));


// Salida del archivo PDF
$pdf->Output();
