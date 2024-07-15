<?php
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
    function PaintTextBackground($x, $y, $text, $backgroundColor = [35, 175, 216], $radius = 2, $customWidth = null, $customHeight = null)
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
        $this->SetTextColor(255, 255, 255); // Establecer el color del texto a blanco
        $this->Cell($textWidth, 8, $text, 0, 0, 'C', false);

        // Restablecer el color del texto a negro
        $this->SetTextColor(0, 0, 0);
    }



}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('LANDSCAPE', '');

// Dibujar el marco del formulario con bordes redondeados
$pdf->RoundedRect(5, 10, 287, 177, 0, 'D');

$pdf->Image('./servicer.jpeg', 6, 11, 40);

// ANALISIS SEGURO DE TRABAJO
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($pdf->GetX() - 190);
$pdf->Cell(0, 8, utf8_decode("ANÁLISIS SEGURO DE TRABAJO"));
$pdf->Line(47, 16, 292, 16); //HORIZONTAL

// PROCESO SEGURIDAD Y SALUD EN EL TRABAJO
$pdf->SetX($pdf->GetX() - 180);
$pdf->Cell(0, 24, utf8_decode("PROCESO SEGURIDAD Y SALUD EN EL TRABAJO"));

// TEXTO CON FONDE DE COLOR
$pdf->SetFont('Arial', 'B', 7);
$pdf->PaintTextBackground(5, 27, utf8_decode('ELABORÓ / ACTUALIZÓ'), [35, 175, 216], 0, 42, 4);
$pdf->PaintTextBackground(47, 27, utf8_decode('REVISÓ'), [35, 175, 216], 0, 219, 4);

// APROBO
$pdf->PaintTextBackground(266, 27, utf8_decode('APROBÓ'), [35, 175, 216], 0, 26, 4);

// LINEAL VERTICAL QUE ESTA AL LADO DE LA IMAGEN
$pdf->Line(47, 10, 47, 34); // VERTICAL

// codigo
$pdf->Line(266, 10, 266, 39); //VERTICAL
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(267, 13);
$pdf->Cell(0, 0, utf8_decode("Código:"));

// F-SST-02
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(277, 13);
$pdf->Cell(0, 0, utf8_decode("F-SST-02"));

// FECHA
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 10, "Fecha:");

// 01/04/2024
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 11);
$pdf->Cell(0, 10, "01/04/2024");
$pdf->Line(266, 19.6, 292, 19.6); //HORIZONTAL

// VERSION
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 18, utf8_decode("Versión:"));

// 02
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 9);
$pdf->Cell(0, 18, "02");
$pdf->Line(266, 23.3, 292, 23.3); //HORIZAONTAL

// VERSION
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 25, utf8_decode("Página:"));

// 1 de 4
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 10);
$pdf->Cell(0, 25, "1 de 4");
$pdf->Line(5, 27, 292, 27); //HORIZAONTAL
$pdf->Line(5, 31, 292, 31); //HORIZAONTAL

// GERENTE
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 14);
$pdf->Cell(0, 39.4, "Gerente");


// auxiliar de calidad
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 571);
$pdf->Cell(0, 39.4, "Auxiliar de calidad");

// LIDER SGI
$pdf->SetX($pdf->GetX() - 143);
$pdf->Cell(0, 39.4, "Lider SGI");
$pdf->Line(5, 34, 292, 34); //HORIZAONTAL

// DESCRIPCION DE ACTIVIDAD GENERAL
$pdf->SetFont("Arial", "B", 6);
$pdf->SetX($pdf->GetX() - 577);
$pdf->Cell(0, 45.4, utf8_decode("DESCRIPCION DE ACTIVIDAD"));
$pdf->SetX($pdf->GetX() - 571);
$pdf->Cell(0, 50, "GENERAL");
$pdf->Line(40, 34, 40, 39);

// FECHA INICIAL
$pdf->SetFont("Arial", "B", 6);
$pdf->SetX($pdf->GetX() - 84);
$pdf->Cell(0, 47, "FECHA INICIAL:");
$pdf->Line(225, 34, 225, 39);

// DD/MM/AA
$pdf->SetFont("Arial", "", 6);
$pdf->SetX($pdf->GetX() - 60);
$pdf->Cell(0, 47, "DD/MM/AA");
$pdf->Line(245, 34, 245, 55);

// FICHA FINAL
$pdf->SetFont("Arial", "B", 6);
$pdf->SetX($pdf->GetX() - 40);
$pdf->Cell(0, 47, "FICHA FINAL:");

// FICHA FINAL
$pdf->SetFont("Arial", "", 6);
$pdf->SetX($pdf->GetX() - 15);
$pdf->Cell(0, 47, "DD/MM/AA");
$pdf->Line(5, 39, 292, 39); //HORIZAONTAL

// ACTIVIDAD EN CASO DE EMEGENCIA
$pdf->SetFont("Arial", "B", 6);
$pdf->SetX($pdf->GetX() - 578);
$pdf->Cell(0, 64, "ACTIVIDAD EN CASO");
$pdf->SetX($pdf->GetX() - 576);
$pdf->Cell(0, 69, "DE EMERGENCIA");
$pdf->SetFont("Arial", "", 6);
$pdf->SetX($pdf->GetX() - 577);
$pdf->Cell(0, 75, utf8_decode("Hospital más cercano"));
$pdf->Line(31, 39, 31, 55);

// ACTTIVIDAD 1
$pdf->PaintTextBackground(31, 39, utf8_decode('ACTIVIDAD No 1'), [35, 175, 216], 0, 28, 4);
$pdf->SetXY(31, 44);
$pdf->MultiCell(27, 3, utf8_decode('Actividad #1'));
$pdf->Line(59, 39, 59, 55);

// ACTTIVIDAD 2
$pdf->PaintTextBackground(59, 39, utf8_decode('ACTIVIDAD No 2'), [35, 175, 216], 0, 28, 4);
$pdf->SetXY(59, 44);
$pdf->MultiCell(27, 3, utf8_decode('Actividad #2'));
$pdf->Line(87, 39, 87, 55);

// ACTTIVIDAD 3
$pdf->PaintTextBackground(87, 39, utf8_decode('ACTIVIDAD No 3'), [35, 175, 216], 0, 28, 4);
$pdf->SetXY(87, 44);
$pdf->MultiCell(27, 3, utf8_decode('Actividad #3'));
$pdf->Line(115, 39, 115, 55);

// ACTTIVIDAD 4
$pdf->PaintTextBackground(115, 39, utf8_decode('ACTIVIDAD No 4'), [35, 175, 216], 0, 28, 4);
$pdf->SetXY(115, 44);
$pdf->MultiCell(27, 3, utf8_decode('Actividad #4'));
$pdf->Line(143, 39, 143, 55);

// ACTTIVIDAD 5
$pdf->PaintTextBackground(143, 39, utf8_decode('ACTIVIDAD No 5'), [35, 175, 216], 0, 28, 4);
$pdf->SetXY(143, 44);
$pdf->MultiCell(27, 3, utf8_decode('Actividad #5'));
$pdf->Line(171, 39, 171, 55); //VERTICAL

// INTEGRANTE DE LA BRIGADA
$pdf->SetFont("Arial", "B", 6);
$pdf->SetXY(174, 45);
$pdf->Cell(0, 0, "INTEGRANTE DE LA");
$pdf->SetXY(179, 13);
$pdf->Cell(0, 69, "BRIGADA");
$pdf->Line(200, 34, 200, 55); //VERTICAL

// TRABAJADOR NO1, No 2 y No3
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(210, 43);
$pdf->Cell(0, 0, "TRABAJADOR No 1");
$pdf->SetXY(260, 43);
$pdf->Cell(0, 0, "TRABAJADOR No 2");
$pdf->Line(200, 47, 292, 47); //HORIZAONTAL
$pdf->SetXY(210, 51);
$pdf->Cell(0, 0, "TRABAJADOR No 3");

// No DE BRIGADA
$pdf->SetFont("Arial", "B", 6);
$pdf->SetXY(251, 50);
$pdf->Cell(0, 0, "No");
$pdf->SetXY(248, 53);
$pdf->Cell(0, 0, "BRIGADA");
$pdf->Line(262, 47, 262, 55); //VERTICAL

// NUMERO DE BRIGADA DE TEXTO
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(265, 52);
$pdf->Cell(0, 0, "12334");

$pdf->Line(5, 55, 292, 55); //HORIZAONTAL

// SISTEMA DE ACCESO PARA EJECUTAR LA LABOR
$pdf->SetFont("Arial", "B", 6);
$pdf->PaintTextBackground(5, 55, utf8_decode('SISTEMA DE ACCESO PARA EJECUTAR LA LABOR'), [35, 175, 216], 0, 166, 7);

// DISTANCIA  DEE SEGURIDDAD
$pdf->PaintTextBackground(171, 55, utf8_decode('DISTEANCIAS DE SEGURIDAD'), [35, 175, 216], 0, 48, 7);
$pdf->Line(171, 55, 171, 86); //VERTICAL


// TIPO DE TRABAJO
$pdf->PaintTextBackground(219, 55, utf8_decode('TIPO DE TRABAJO'), [35, 175, 216], 0, 73, 7);
$pdf->Line(219, 55, 219, 86); //VERTICAL DE DISTANCIA DE SEGURIDAD 
$pdf->Line(5, 62, 292, 62); //HORIZAONTAL

// IMAGENES DE LA CUERDA E IMAGENES
$pdf->Image('./cuerda.jpeg', 6, 64, 18);
$pdf->Line(26, 62, 26, 80); //VERTICAL

// INCIDENCIA / DESCARGO / AVISO
$pdf->SetFont("Arial", "B", 6.5);
$pdf->SetXY(31, 65);
$pdf->Cell(0, 0, "Incidencia/ Descargo");
$pdf->SetXY(39, 67.4);
$pdf->Cell(0, 0, "/ Aviso");

$pdf->SetFont("Arial", "B", 7);
// 1
$pdf->PaintTextBackground(26, 69, utf8_decode('1'), [35, 175, 216], 0, 7, 6);
// X DE 1
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(28, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(33, 69, 33, 80); //VERTICAL 1

// 2
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(33, 69, utf8_decode('2'), [35, 175, 216], 0, 7, 6);
// X DE 2
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(35, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(40, 69, 40, 80); //VERTICAL 2

// 3
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(40, 69, utf8_decode('3'), [35, 175, 216], 0, 7, 6);
// X DE 3
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(42, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(47, 69, 47, 80); //VERTICAL 3

// 4
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(47, 69, utf8_decode('4'), [35, 175, 216], 0, 7, 6);
// X DE 4
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(49, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(54, 69, 54, 80); //VERTICAL 4

// 5
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(54, 69, utf8_decode('5'), [35, 175, 216], 0, 7, 6);
// X DE 5
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(56, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(61, 62, 61, 80); //VERTICAL
$pdf->Line(26, 69, 61, 69); //HORIZAONTAL
$pdf->Line(26, 75, 61, 75); //HORIZAONTAL

$pdf->Image('./carro1.jpeg', 62, 65, 18);
$pdf->Line(80, 62, 80, 80); //VERTICAL

// INCIDENCIA / DESCARGO / AVISO
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(84, 65);
$pdf->Cell(0, 0, "Incidencia/ Descargo");
$pdf->SetXY(92, 67.4);
$pdf->Cell(0, 0, "/ Aviso");

// 1
$pdf->PaintTextBackground(80, 69, utf8_decode('1'), [35, 175, 216], 0, 7, 6);
// X DE 1
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(82, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(87, 69, 87, 80); //VERTICAL 1

// 2
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(87, 69, utf8_decode('2'), [35, 175, 216], 0, 7, 6);
// X DE 2
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(89, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(94, 69, 94, 80); //VERTICAL 2

// 3
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(94, 69, utf8_decode('3'), [35, 175, 216], 0, 7, 6);
// X DE 3
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(96, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(101, 69, 101, 80); //VERTICAL 3

// 4
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(101, 69, utf8_decode('4'), [35, 175, 216], 0, 7, 6);
// X DE 4
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(103, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(108, 69, 108, 80); //VERTICAL 4

// 5
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(108, 69, utf8_decode('5'), [35, 175, 216], 0, 7, 6);
// X DE 5
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(110, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(115, 62, 115, 80); //VERTICAL
$pdf->Line(80, 69, 115, 69); //HORIZAONTAL
$pdf->Line(80, 75, 115, 75); //HORIZAONTAL

$pdf->Image('./carro2.jpeg', 117, 63, 17);
$pdf->Line(137, 62, 137, 80); //VERTICAL

// INCIDENCIA / DESCARGO / AVISO
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(140, 65);
$pdf->Cell(0, 0, "Incidencia/ Descargo");
$pdf->SetXY(145, 67.4);
$pdf->Cell(0, 0, "/ Aviso");

// 1
$pdf->PaintTextBackground(137, 69, utf8_decode('1'), [35, 175, 216], 0, 7, 6);
// X DE 1
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(139, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(144, 69, 144, 80); //VERTICAL 1

// 2
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(144, 69, utf8_decode('2'), [35, 175, 216], 0, 7, 6);
// X DE 2
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(145, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(150, 69, 150, 80); //VERTICAL 2

// 3
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(150, 69, utf8_decode('3'), [35, 175, 216], 0, 7, 6);
// X DE 3
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(152, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(157, 69, 157, 80); //VERTICAL 3

// 4
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(157, 69, utf8_decode('4'), [35, 175, 216], 0, 7, 6);
// X DE 4
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(159, 78);
$pdf->Cell(0, 0, "X");

$pdf->Line(163.8, 69, 163.8, 80); //VERTICAL 4

// 5
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(163.8, 69, utf8_decode('5'), [35, 175, 216], 0, 7, 6);
// X DE 5
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(165, 78);
$pdf->Cell(0, 0, "X");
$pdf->SetFont("Arial", "B", 7);
$pdf->Line(137, 69, 171, 69); //HORIZAONTAL
$pdf->Line(137, 75, 171, 75); //HORIZAONTAL


// DISTACIOPA DE SEGURIDAD
$pdf->PaintTextBackground(171.2, 62, utf8_decode('>1 kV: Distancia 0.8 metros'), [35, 175, 216], 0, 47.7, 6);
$pdf->Line(171, 68, 292, 68); //HORIZAONTAL
$pdf->PaintTextBackground(171.2, 68.1, utf8_decode('7.6 Kv - 13.8Kv: Distancia 0.95 mts'), [35, 175, 216], 0, 47.7, 6);
$pdf->Line(171, 74, 292, 74); //HORIZAONTAL
$pdf->PaintTextBackground(171.2, 74, utf8_decode('33Kv - 34.5Kv: Distancia 1.10 metros'), [35, 175, 216], 0, 47.7, 6);
$pdf->PaintTextBackground(171.2, 80, utf8_decode('33Kv - 34.5Kv: Distancia 1.10 metros'), [35, 175, 216], 0, 47.7, 6);

// TIPO DE TRABAJO
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(220, 65);
$pdf->Cell(0, 0, "Altura:");
$pdf->PaintTextBackground(233, 62, utf8_decode(''), [35, 175, 216], 0, 5, 5.8);
$pdf->SetXY(240, 65);
$pdf->Cell(0, 0, "Izaje:");
$pdf->PaintTextBackground(251, 62, utf8_decode(''), [35, 175, 216], 0, 6, 5.8);
$pdf->SetXY(260, 65);
$pdf->Cell(0, 0, "Electrico:");
$pdf->PaintTextBackground(275, 62, utf8_decode(''), [35, 175, 216], 0, 7, 5.8);
$pdf->SetXY(282, 64);
$pdf->Cell(0, 0, "Otro:");
$pdf->SetXY(282, 66);
$pdf->Cell(0, 0, "Cual");
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(219, 68.1, utf8_decode('PROCESO'), [35, 175, 216], 0, 73, 6);
$pdf->Line(233, 62, 233, 68);
$pdf->Line(238, 62, 238, 68);


// PODA
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(222, 77);
$pdf->Cell(0, 0, "Poda");
$pdf->PaintTextBackground(233, 74, utf8_decode(''), [35, 175, 216], 0, 5, 5.8);
$pdf->SetXY(222, 83);
$pdf->Cell(0, 0, "Mtto");
$pdf->PaintTextBackground(233, 80, utf8_decode(''), [35, 175, 216], 0, 5, 5.8);
$pdf->Line(233, 74, 233, 86);
$pdf->Line(238, 74, 238, 86);


// PQR
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(244, 77);
$pdf->Cell(0, 0, "PQR");
$pdf->PaintTextBackground(260, 74, utf8_decode(''), [35, 175, 216], 0, 7, 5.8);
$pdf->SetXY(240, 83);
$pdf->Cell(0, 0, "Adecuaciones");
$pdf->PaintTextBackground(260, 80, utf8_decode(''), [35, 175, 216], 0, 7, 5.8);
$pdf->Line(260, 74, 260, 86);

$pdf->Line(267, 74, 267, 86);
$pdf->Line(219, 74, 292, 74);


// OTRO
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(270, 77);
$pdf->Cell(0, 0, "OTRO");
$pdf->PaintTextBackground(285, 74, utf8_decode(''), [35, 175, 216], 0, 7, 5.8);
$pdf->Line(285, 74, 285, 80);

$pdf->SetFont("Arial", "", 7);
$pdf->Line(5, 80, 292, 80); //HORIZAONTAL
$pdf->SetXY(5, 82);
$pdf->Cell(0, 0, "Otro: cual");
$pdf->SetXY(20, 82);
$pdf->Cell(0, 0, "motivo");
$pdf->Line(5, 86, 292, 86); //HORIZAONTAL

// PROTOCOLO DE SEGURIDAD Y COMUNICACION
$pdf->SetFont("Arial", "B", 8);
$pdf->PaintTextBackground(5, 86, utf8_decode('PROTOCOLO DE SEGURIDAD Y COMUNICACIÓN'), [35, 175, 216], 0, 287, 4.8);
$pdf->Line(5, 91, 292, 91); //HORIZAONTAL

// No 
$pdf->PaintTextBackground(5, 91, utf8_decode('No'), [35, 175, 216], 0, 10, 11);
$pdf->Line(15, 91, 15, 117); //VERTICAL

// INCIDENCIA/ DESCARGO/ AVISO
$pdf->PaintTextBackground(15, 91, utf8_decode('Incidencia/ Descargo/ Aviso'), [35, 175, 216], 0, 40, 11);
$pdf->Line(55, 91, 55, 117); //VERTICAL

// APERTURA
$pdf->PaintTextBackground(55, 91, utf8_decode('Apertura'), [35, 175, 216], 0, 35, 8);
$pdf->PaintTextBackground(55, 97, utf8_decode('Circuito / Lineas'), [35, 175, 216], 0, 35, 5);
$pdf->Line(90, 91, 90, 117); //VERTICAL

// DIRECCION
$pdf->PaintTextBackground(90, 91, utf8_decode('DIRECCIÓN'), [35, 175, 216], 0, 34, 11);


// FECHA
$pdf->PaintTextBackground(124, 91, utf8_decode('FECHA'), [35, 175, 216], 0, 17, 11);
$pdf->Line(124, 91, 124, 117); //VERTICAL

// HORA INICIO
$pdf->PaintTextBackground(141, 91, utf8_decode('HORA INICIO'), [35, 175, 216], 0, 20, 11);
$pdf->Line(141, 91, 141, 117); //VERTICAL

// Nivel de tension BT/MT
$pdf->PaintTextBackground(161, 91, utf8_decode('Nivel de'), [35, 175, 216], 0, 12, 5);
$pdf->PaintTextBackground(161, 95, utf8_decode('tension'), [35, 175, 216], 0, 12, 4);
$pdf->PaintTextBackground(161, 98.7, utf8_decode('BT/MT'), [35, 175, 216], 0, 12, 3.5);
$pdf->Line(161, 91, 161, 117); //VERTICAL

// Desenerigazado (D) Energizada (E)
$pdf->PaintTextBackground(173, 91, utf8_decode('Desenerigazado (D)'), [35, 175, 216], 0, 28, 8);
$pdf->PaintTextBackground(173, 97, utf8_decode('Energizada (E)'), [35, 175, 216], 0, 28, 5);
$pdf->Line(173, 91, 173, 117); //VERTICAL

// DISTANCIA SEGURIDAD
$pdf->PaintTextBackground(201, 91, utf8_decode('Distancia de'), [35, 175, 216], 0, 18, 8);
$pdf->PaintTextBackground(201, 97, utf8_decode('Seguridad'), [35, 175, 216], 0, 18, 5);
$pdf->Line(201, 91, 201, 117); //VERTICAL

// ALTURA
$pdf->PaintTextBackground(219, 91, utf8_decode('Altura'), [35, 175, 216], 0, 12, 4.2);
$pdf->PaintTextBackground(219, 95, utf8_decode('Aproxs'), [35, 175, 216], 0, 12, 4);
$pdf->PaintTextBackground(219, 99, utf8_decode('MTS'), [35, 175, 216], 0, 12, 3);
$pdf->Line(231, 91, 231, 117); //VERTICAL

// FECHA CIERRE
$pdf->PaintTextBackground(231, 91, utf8_decode('FECHA CIERRE'), [35, 175, 216], 0, 30, 11);
$pdf->Line(261, 91, 261, 117); //VERTICAL

// HORA CIERRE
$pdf->PaintTextBackground(261, 91, utf8_decode('HORA CIERRE'), [35, 175, 216], 0, 31, 11);
$pdf->Line(219, 91, 219, 117); //VERTICAL

$pdf->Line(5, 102, 292, 102); //HORIZAONTAL

// 01
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(8, 104);
$pdf->Cell(0, 0, '1');

// INCIDENCIA/DESCARGO/AVISO
$pdf->SetXY(17, 104);
$pdf->Cell(0, 0, 'Incidencia');

// APERTURA
$pdf->SetXY(57, 104);
$pdf->Cell(0, 0, 'Incidencia');

// DIRECCION
$pdf->SetXY(92, 104);
$pdf->Cell(0, 0, 'Cl 14W - 15');

// FECHA
$pdf->SetXY(125, 104);
$pdf->Cell(0, 0, '09/07/2024');

// HORA INICIO
$pdf->SetXY(145, 104);
$pdf->Cell(0, 0, '9:30 AM');

// NIVEL DE TENSION BT/MT
$pdf->SetXY(164, 104);
$pdf->Cell(0, 0, '10');

// DESENERIGAZADO (D) ENERGIZADA (E)
$pdf->SetXY(174, 104);
$pdf->Cell(0, 0, 'Desenerigazada');

// DISTANCIA DE SEGURIDAD
$pdf->SetXY(205, 104);
$pdf->Cell(0, 0, '5');

// ALTURA APROXS MTS
$pdf->SetXY(220, 104);
$pdf->Cell(0, 0, '5 MTS');

// FECHA CIERRE
$pdf->SetXY(237, 104);
$pdf->Cell(0, 0, '09/07/2024');

// HORA CIERRE
$pdf->SetXY(270, 104);
$pdf->Cell(0, 0, '6:00 PM');

$pdf->Line(5, 105, 292, 105); //HORIZAONTAL

// 02
$pdf->SetXY(8, 107);
$pdf->Cell(0, 0, '2');

// INCIDENCIA/DESCARGO/AVISO
$pdf->SetXY(17, 107);
$pdf->Cell(0, 0, 'Incidencia');

// APERTURA
$pdf->SetXY(57, 107);
$pdf->Cell(0, 0, 'Incidencia');

// DIRECCION
$pdf->SetXY(92, 107);
$pdf->Cell(0, 0, 'Cl 14W - 15');

// FECHA
$pdf->SetXY(125, 107);
$pdf->Cell(0, 0, '09/07/2024');

// HORA INICIO
$pdf->SetXY(145, 107);
$pdf->Cell(0, 0, '9:30 AM');

// NIVEL DE TENSION BT/MT
$pdf->SetXY(164, 107);
$pdf->Cell(0, 0, '10');

// DESENERIGAZADO (D) ENERGIZADA (E)
$pdf->SetXY(174, 107);
$pdf->Cell(0, 0, 'Desenerigazada');

// DISTANCIA DE SEGURIDAD
$pdf->SetXY(205, 107);
$pdf->Cell(0, 0, '5');

// ALTURA APROXS MTS
$pdf->SetXY(220, 107);
$pdf->Cell(0, 0, '5 MTS');

// FECHA CIERRE
$pdf->SetXY(237, 107);
$pdf->Cell(0, 0, '09/07/2024');

// HORA CIERRE
$pdf->SetXY(270, 107);
$pdf->Cell(0, 0, '6:00 PM');
$pdf->Line(5, 108, 292, 108); //HORIZAONTAL

// 03
$pdf->SetXY(8, 110);
$pdf->Cell(0, 0, '3');

// INCIDENCIA/DESCARGO/AVISO
$pdf->SetXY(17, 110);
$pdf->Cell(0, 0, 'Incidencia');

// APERTURA
$pdf->SetXY(57, 110);
$pdf->Cell(0, 0, 'Incidencia');

// DIRECCION
$pdf->SetXY(92, 110);
$pdf->Cell(0, 0, 'Cl 14W - 15');

// FECHA
$pdf->SetXY(125, 110);
$pdf->Cell(0, 0, '09/07/2024');

// HORA INICIO
$pdf->SetXY(145, 110);
$pdf->Cell(0, 0, '9:30 AM');

// NIVEL DE TENSION BT/MT
$pdf->SetXY(164, 110);
$pdf->Cell(0, 0, '10');

// DESENERIGAZADO (D) ENERGIZADA (E)
$pdf->SetXY(174, 110);
$pdf->Cell(0, 0, 'Desenerigazada');

// DISTANCIA DE SEGURIDAD
$pdf->SetXY(205, 110);
$pdf->Cell(0, 0, '5');

// ALTURA APROXS MTS
$pdf->SetXY(220, 110);
$pdf->Cell(0, 0, '5 MTS');

// FECHA CIERRE
$pdf->SetXY(237, 110);
$pdf->Cell(0, 0, '09/07/2024');

// HORA CIERRE
$pdf->SetXY(270, 110);
$pdf->Cell(0, 0, '6:00 PM');
$pdf->Line(5, 111, 292, 111); //HORIZAONTAL

// 04
$pdf->SetXY(8, 113);
$pdf->Cell(0, 0, '4');

// INCIDENCIA/DESCARGO/AVISO
$pdf->SetXY(17, 113);
$pdf->Cell(0, 0, 'Incidencia');

// APERTURA
$pdf->SetXY(57, 113);
$pdf->Cell(0, 0, 'Incidencia');

// DIRECCION
$pdf->SetXY(92, 113);
$pdf->Cell(0, 0, 'Cl 14W - 15');

// FECHA
$pdf->SetXY(125, 113);
$pdf->Cell(0, 0, '09/07/2024');

// HORA INICIO
$pdf->SetXY(145, 113);
$pdf->Cell(0, 0, '9:30 AM');

// NIVEL DE TENSION BT/MT
$pdf->SetXY(164, 113);
$pdf->Cell(0, 0, '10');

// DESENERIGAZADO (D) ENERGIZADA (E)
$pdf->SetXY(174, 113);
$pdf->Cell(0, 0, 'Desenerigazada');

// DISTANCIA DE SEGURIDAD
$pdf->SetXY(205, 113);
$pdf->Cell(0, 0, '5');

// ALTURA APROXS MTS
$pdf->SetXY(220, 113);
$pdf->Cell(0, 0, '5 MTS');

// FECHA CIERRE
$pdf->SetXY(237, 113);
$pdf->Cell(0, 0, '09/07/2024');

// HORA CIERRE
$pdf->SetXY(270, 113);
$pdf->Cell(0, 0, '6:00 PM');
$pdf->Line(5, 114, 292, 114); //HORIZAONTAL

// 05
$pdf->SetXY(8, 116);
$pdf->Cell(0, 0, '5');

// INCIDENCIA/DESCARGO/AVISO
$pdf->SetXY(17, 116);
$pdf->Cell(0, 0, 'Incidencia');

// APERTURA
$pdf->SetXY(57, 116);
$pdf->Cell(0, 0, 'Incidencia');

// DIRECCION
$pdf->SetXY(92, 116);
$pdf->Cell(0, 0, 'Cl 14W - 15');

// FECHA
$pdf->SetXY(125, 116);
$pdf->Cell(0, 0, '09/07/2024');

// HORA INICIO
$pdf->SetXY(145, 116);
$pdf->Cell(0, 0, '9:30 AM');

// NIVEL DE TENSION BT/MT
$pdf->SetXY(164, 116);
$pdf->Cell(0, 0, '10');

// DESENERIGAZADO (D) ENERGIZADA (E)
$pdf->SetXY(174, 116);
$pdf->Cell(0, 0, 'Desenerigazada');

// DISTANCIA DE SEGURIDAD
$pdf->SetXY(205, 116);
$pdf->Cell(0, 0, '5');

// ALTURA APROXS MTS
$pdf->SetXY(220, 116);
$pdf->Cell(0, 0, '5 MTS');

// FECHA CIERRE
$pdf->SetXY(237, 116);
$pdf->Cell(0, 0, '09/07/2024');

// HORA CIERRE
$pdf->SetXY(270, 116);
$pdf->Cell(0, 0, '6:00 PM');
$pdf->Line(5, 117, 292, 117); //HORIZAONTAL

// ANÁLISIS PREVIO A LA TAREA
$pdf->SetFont("Arial", "B", 8);
$pdf->PaintTextBackground(5, 117, utf8_decode('ANÁLISIS PREVIO A LA TAREA'), [35, 175, 216], 0, 287, 4.8);
$pdf->Line(5, 122, 292, 122); //HORIZAONTAL

// EPP REQUERIDOS EN LA PREPARACIÓN DE TODAS MIS ACTIVIDADES A REALIZAR
$pdf->PaintTextBackground(5, 122, utf8_decode('EPP REQUERIDOS EN LA PREPARACIÓN DE TODAS'), [35, 175, 216], 0, 75, 6);
$pdf->PaintTextBackground(5, 127, utf8_decode('MIS ACTIVIDADES A REALIZAR'), [35, 175, 216], 0, 75, 5);
$pdf->Line(80, 122, 80, 187); //VERTICAL

// CUMPLIMIENTO 5 REGLAS DE ORO
$pdf->PaintTextBackground(80, 122, utf8_decode('CUMPLIMIENTO'), [35, 175, 216], 0, 30, 6);
$pdf->PaintTextBackground(80, 127, utf8_decode('5 REGLAS DE ORO'), [35, 175, 216], 0, 30, 5);
$pdf->Line(110, 122, 110, 187); //VERTICAL

// ACTIVIDADES DE LA TAREA SIMULTANEAS , DEL ENTORNO Y POSTERIORES
$pdf->PaintTextBackground(110, 122, utf8_decode('ACTIVIDADES DE LA TAREA SIMULTANEAS, DEL ENTORNO Y POSTERIORES'), [35, 175, 216], 0, 182, 10);
$pdf->Line(5, 132, 292, 132); //HORIZAONTAL

// LINEA VERTICAL QUE ESTA ANTES DE CASCO DE SEGURIDAD
$pdf->Line(10, 132, 10, 187); //VERTICAL

// X CASCO DE SEGURIDAD CON BARBUQUEJO
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(6, 137);
$pdf->Cell(0, 0, 'X');

// X GAFAS DE SEGURIDAD
$pdf->SetXY(6, 149);
$pdf->Cell(0, 0, 'X');

// X PROTECCION AUDITIUVA
$pdf->SetXY(6, 159);
$pdf->Cell(0, 0, 'X');

// X ROPA DE SEGURIDAD
$pdf->SetXY(6, 171);
$pdf->Cell(0, 0, 'X');

// X PROTECCION CONTRA CAIDA
$pdf->SetXY(6, 181);
$pdf->Cell(0, 0, 'X');


// Casco de Seguridad con barbuquejo
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(14, 134.5);
$pdf->Cell(0, 0, 'Casco de');
$pdf->SetXY(11, 137);
$pdf->Cell(0, 0, 'Seguridad con');
$pdf->SetXY(13, 140);
$pdf->Cell(0, 0, 'barbuquejo');
$pdf->Line(30, 132, 30, 187); //VERTICAL

// X PROTECCION RESPIRATORIA
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(31, 138);
$pdf->Cell(0, 0, 'X');

// X GUANTES DE SEGURIDAD
$pdf->SetXY(31, 149);
$pdf->Cell(0, 0, 'X');

// X BOTAS DE AUDITOVAS
$pdf->SetXY(31, 159);
$pdf->Cell(0, 0, 'X');

// X PANTALON ANTICORTE
$pdf->SetXY(31, 171);
$pdf->Cell(0, 0, 'X');

// X GUANTES ANTICORTE
$pdf->SetXY(31, 181);
$pdf->Cell(0, 0, 'X');

$pdf->Line(35, 132, 35, 187); //VERTICAL

// Proteccion Respiratoria
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(38, 136);
$pdf->Cell(0, 0, 'Proteccion');
$pdf->SetXY(37, 139);
$pdf->Cell(0, 0, 'Respiratoria');
$pdf->Line(55, 132, 55, 187); //VERTICAL

// X GUANTES DIELECTRICOS 0, II, Y/O IV
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(56, 138);
$pdf->Cell(0, 0, 'X');

// X CARETA ANTI-ARCO ELECTRICO
$pdf->SetXY(56, 148);
$pdf->Cell(0, 0, 'X');

// X MONJA IGNIFUGA
$pdf->SetXY(56, 159);
$pdf->Cell(0, 0, 'X');

// X PANTALON ANTICORTE
$pdf->SetXY(56, 171);
$pdf->Cell(0, 0, 'X');

// X OTROS
$pdf->SetXY(56, 181);
$pdf->Cell(0, 0, 'X');

$pdf->Line(60, 132, 60, 187); //VERTICAL

// Guantes Dielectricos 0, II, y/o IV.
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(64, 134.5);
$pdf->Cell(0, 0, 'Guantes');
$pdf->SetXY(61.5, 137);
$pdf->Cell(0, 0, 'Dielectricos 0,');
$pdf->SetXY(64, 140);
$pdf->Cell(0, 0, 'II, y/o IV.');

// CUADRO QUE ESTA DEBAJO DE CUMPLIMIENTO 5 REGLAS DE ORO
// 1
$pdf->PaintTextBackground(80, 132, utf8_decode('1'), [35, 175, 216], 0, 7.4, 11);
$pdf->Line(87.5, 132, 87.5, 187); //VERTICAL

// SI
$pdf->SetXY(89, 137);
$pdf->Cell(0, 0, 'SI');
$pdf->Line(95, 132, 95, 187); //VERTICAL

// NO
$pdf->SetXY(96, 137);
$pdf->Cell(0, 0, 'NO');
$pdf->Line(102.5, 132, 102.5, 187); //VERTICAL

// N/A
$pdf->SetXY(103.6, 137);
$pdf->Cell(0, 0, 'N/A');

// ACTIVIDADES DE LA TAREA SIMULTANEAS, DEL ENTORNO Y POSTERIORES
$pdf->SetXY(110, 135);
$pdf->MultiCell(178, 3, utf8_decode('Actividad #1'));


$pdf->Line(5, 143, 292, 143); //HORIZAONTAL

// Gafas de Seguridad
$pdf->SetXY(14, 147);
$pdf->Cell(0, 0, 'Gafas de');
$pdf->SetXY(13 , 150);
$pdf->Cell(0, 0, 'Seguridad');

// Guantes de Seguridad 
$pdf->SetXY(37, 147);
$pdf->Cell(0, 0, 'Guantes de');
$pdf->SetXY(37, 150);
$pdf->Cell(0, 0, 'Seguridad');

// Careta Anti-Arco Eléctrico
$pdf->SetXY(62, 147);
$pdf->Cell(0, 0, 'Careta Anti-');
$pdf->SetXY(60.7, 150);
$pdf->Cell(0, 0, utf8_decode('Arco Eléctrico'));

// CUADRO QUE ESTA DEBAJO DE CUMPLIMIENTO 5 REGLAS DE ORO
// 2
$pdf->PaintTextBackground(80, 143, utf8_decode('2'), [35, 175, 216], 0, 7.4, 11);

// SI
$pdf->SetXY(89, 149);
$pdf->Cell(0, 0, 'SI');

// NO
$pdf->SetXY(96, 149);
$pdf->Cell(0, 0, 'NO');

// N/A
$pdf->SetXY(103.6, 149);
$pdf->Cell(0, 0, 'N/A');

// ACTIVIDADES DE LA TAREA SIMULTANEAS, DEL ENTORNO Y POSTERIORES
$pdf->SetXY(110, 146);
$pdf->MultiCell(178, 3, utf8_decode('Actividad #2'));

$pdf->Line(5, 154, 292, 154); //HORIZAONTAL

// Proteccion Auditiva
$pdf->SetXY(14, 158);
$pdf->Cell(0, 0, 'Proteccion');
$pdf->SetXY(14, 161);
$pdf->Cell(0, 0, 'Auditiva');

// Botas de Seguridad
$pdf->SetXY(38.5, 158);
$pdf->Cell(0, 0, 'Botas de');
$pdf->SetXY(38.5, 161);
$pdf->Cell(0, 0, 'Auditiva');

// Monja Ignifuga
$pdf->SetXY(61, 160);
$pdf->Cell(0, 0, 'Monja Ignifuga');

// CUADRO QUE ESTA DEBAJO DE CUMPLIMIENTO 5 REGLAS DE ORO
// 3
$pdf->PaintTextBackground(80, 154, utf8_decode('3'), [35, 175, 216], 0, 7.4, 11);

// SI
$pdf->SetXY(89, 160);
$pdf->Cell(0, 0, 'SI');

// NO
$pdf->SetXY(96, 160);
$pdf->Cell(0, 0, 'NO');

// N/A
$pdf->SetXY(103.6, 160);
$pdf->Cell(0, 0, 'N/A');

// ACTIVIDADES DE LA TAREA SIMULTANEAS, DEL ENTORNO Y POSTERIORES
$pdf->SetXY(110, 155);
$pdf->MultiCell(178, 3, utf8_decode('Actividad #3'));

$pdf->Line(5, 165, 292, 165); //HORIZAONTAL

// Ropa de Seguridad
$pdf->SetXY(14, 169);
$pdf->Cell(0, 0, 'Ropa de');
$pdf->SetXY(13, 172);
$pdf->Cell(0, 0, 'Seguridad');

// Pantalon anticorte
$pdf->SetFont("Arial", "", 6.6);
$pdf->SetXY(34.5, 170);
$pdf->Cell(0, 0, 'Pantalon anticorte');

// Tapete Dielectrico
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(65, 169);
$pdf->Cell(0, 0, 'Tapete');
$pdf->SetXY(63, 172);
$pdf->Cell(0, 0, 'Dielectrico');

// CUADRO QUE ESTA DEBAJO DE CUMPLIMIENTO 5 REGLAS DE ORO
// 4
$pdf->PaintTextBackground(80, 165, utf8_decode('4'), [35, 175, 216], 0, 7.4, 11);


// SI
$pdf->SetXY(89, 170);
$pdf->Cell(0, 0, 'SI');

// NO
$pdf->SetXY(96, 170);
$pdf->Cell(0, 0, 'NO');

// N/A
$pdf->SetXY(103.6, 170);
$pdf->Cell(0, 0, 'N/A');

// ACTIVIDADES DE LA TAREA SIMULTANEAS, DEL ENTORNO Y POSTERIORES
$pdf->SetXY(110, 166);
$pdf->MultiCell(178, 3, utf8_decode('Actividad #4'));

$pdf->Line(5, 176, 292, 176); //HORIZAONTAL

// Proteccion Contra Caida
$pdf->SetXY(14, 179);
$pdf->Cell(0, 0, 'Proteccion');
$pdf->SetXY(12, 182);
$pdf->Cell(0, 0, 'Contra Caida');

// Guantes anticorte
$pdf->SetXY(34.2, 181);
$pdf->Cell(0, 0, 'Guantes anticorte');

// Otros:
$pdf->SetXY(60, 181);
$pdf->Cell(0, 0, 'Otros:');

// CUADRO QUE ESTA DEBAJO DE CUMPLIMIENTO 5 REGLAS DE ORO
// 5
$pdf->PaintTextBackground(80, 176, utf8_decode('5'), [35, 175, 216], 0, 7.4, 11);


// SI
$pdf->SetXY(89, 181);
$pdf->Cell(0, 0, 'SI');

// NO
$pdf->SetXY(96, 181);
$pdf->Cell(0, 0, 'NO');

// N/A
$pdf->SetXY(103.6, 181);
$pdf->Cell(0, 0, 'N/A');

// ACTIVIDADES DE LA TAREA SIMULTANEAS, DEL ENTORNO Y POSTERIORES
$pdf->SetXY(110, 177);
$pdf->MultiCell(178, 3, utf8_decode('ACTIVADA #5'));

// PAGINA NUMERO 2
$pdf->AddPage('LANDSCAPE', '');

// Dibujar el marco del formulario con bordes redondeados
$pdf->RoundedRect(5, 10, 287, 165, 0, 'D');

$pdf->Image('./servicer.jpeg', 6, 11, 40);

// ANALISIS SEGURO DE TRABAJO
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($pdf->GetX() - 190);
$pdf->Cell(0, 8, utf8_decode("ANÁLISIS SEGURO DE TRABAJO"));
$pdf->Line(47, 16, 292, 16); //HORIZONTAL

// PROCESO SEGURIDAD Y SALUD EN EL TRABAJO
$pdf->SetX($pdf->GetX() - 180);
$pdf->Cell(0, 24, utf8_decode("PROCESO SEGURIDAD Y SALUD EN EL TRABAJO"));

// TEXTO CON FONDE DE COLOR
$pdf->SetFont('Arial', 'B', 7);
$pdf->PaintTextBackground(5, 27, utf8_decode('ELABORÓ / ACTUALIZÓ'), [35, 175, 216], 0, 42, 4);
$pdf->PaintTextBackground(47, 27, utf8_decode('REVISÓ'), [35, 175, 216], 0, 219, 4);

// APROBO
$pdf->PaintTextBackground(266, 27, utf8_decode('APROBÓ'), [35, 175, 216], 0, 26, 4);

// LINEAL VERTICAL QUE ESTA AL LADO DE LA IMAGEN
$pdf->Line(47, 10, 47, 34); // VERTICAL

// codigo
$pdf->Line(266, 10, 266, 34); //VERTICAL
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(267, 13);
$pdf->Cell(0, 0, utf8_decode("Código:"));

// F-SST-02
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(277, 13);
$pdf->Cell(0, 0, utf8_decode("F-SST-02"));

// FECHA
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 10, "Fecha:");

// 01/04/2024
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 11);
$pdf->Cell(0, 10, "01/04/2024");
$pdf->Line(266, 19.6, 292, 19.6); //HORIZONTAL

// VERSION
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 18, utf8_decode("Versión:"));

// 02
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 9);
$pdf->Cell(0, 18, "02");
$pdf->Line(266, 23.3, 292, 23.3); //HORIZAONTAL

// VERSION
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 25, utf8_decode("Página:"));

// 1 de 4
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 10);
$pdf->Cell(0, 25, "2 de 4");
$pdf->Line(5, 27, 292, 27); //HORIZAONTAL
$pdf->Line(5, 31, 292, 31); //HORIZAONTAL

// GERENTE
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 14);
$pdf->Cell(0, 39.4, "Gerente");


// auxiliar de calidad
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 571);
$pdf->Cell(0, 39.4, "Auxiliar de calidad");

// LIDER SGI
$pdf->SetX($pdf->GetX() - 143);
$pdf->Cell(0, 39.4, "Lider SGI");
$pdf->Line(5, 34, 292, 34); //HORIZAONTAL

// PELIGROS
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(5, 34, utf8_decode('PELIGROS'), [35, 175, 216], 0, 26, 9);
$pdf->Line(31, 34, 31, 175); //VERTICAL

// RIESGOS Marque con una (X)
$pdf->PaintTextBackground(31, 34, utf8_decode('RIESGOS'), [35, 175, 216], 0, 38, 6);
$pdf->PaintTextBackground(31, 38, utf8_decode('Marque con una (X'), [35, 175, 216], 0, 38, 5);
$pdf->Line(69, 34, 69, 175); //VERTICAL

// INCIDENCIA / DESCARGO / AVISO
$pdf->PaintTextBackground(69, 34, utf8_decode('INCIDENCIA / DESCARGO / AVISO'), [35, 175, 216], 0, 42, 5.5);
$pdf->PaintTextBackground(69, 39, utf8_decode('1          2          3          4          5'), [35, 175, 216], 0, 42, 4);
$pdf->Line(69, 39, 111, 39); //HORIZONTAL
// 1 
$pdf->Line(77.4, 39, 77.4, 175); //VERTICAL

// 2
$pdf->Line(85.8, 39, 85.8, 175); //VERTICAL

// 3
$pdf->Line(94.2, 39, 94.2, 175); //VERTICAL

// 4
$pdf->Line(102.6, 39, 102.6, 175); //VERTICAL
$pdf->Line(111, 34, 111, 175); //VERTICAL
$pdf->Line(31, 43, 111, 43); //HORIZONTAL

// CONSECUENCIAS
$pdf->PaintTextBackground(111, 34, utf8_decode('CONSECUENCIAS'), [35, 175, 216], 0, 80, 9);
$pdf->Line(191, 34, 191, 175); //VERTICAL

// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
$pdf->PaintTextBackground(191, 34, utf8_decode('CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES'), [35, 175, 216], 0, 101, 9);

// Eléctrico
$pdf->SetXY(11, 65);
$pdf->Cell(0, 0, utf8_decode('Eléctrico'));

// DATOS QUE ESTN EN RIEGOS

// Electrización
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 46);
$pdf->Cell(0, 0, utf8_decode('Electrización'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 45);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 45);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 45);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 45);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 45);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 47, 111, 47); //HORIZONTAL

// Electrocución
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 49.5);
$pdf->Cell(0, 0, utf8_decode('Electrocución'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 49);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 49);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 49);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 49);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 49);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 51, 111, 51); //HORIZONTAL

// Arco Eléctrico
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 53);
$pdf->Cell(0, 0, utf8_decode('Arco Eléctrico'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 53);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 53);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 53);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 53);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 53);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 55, 111, 55); //HORIZONTAL

// Perforacion de conductores
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 57.5);
$pdf->Cell(0, 0, utf8_decode('Arco Eléctrico'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 57);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 57);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 57);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 57);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 57);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 59, 111, 59); //HORIZONTAL

// Incendio o explosión
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 61.5);
$pdf->Cell(0, 0, utf8_decode('Incendio o explosión'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 61);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 61);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 61);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 61);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 61);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 63, 111, 63); //HORIZONTAL

// Equipos energizados
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 65.5);
$pdf->Cell(0, 0, utf8_decode('Equipos energizados'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 65);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 65);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 65);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 65);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 65);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 67, 111, 67); //HORIZONTAL

// Realimentaciones de energía
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 69.5);
$pdf->Cell(0, 0, utf8_decode('Realimentaciones de energía'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 69);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 69);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 69);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 69);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 69);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 71, 111, 71); //HORIZONTAL

// Contacto directo e indirecto con redes de energia
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 71);
$pdf->MultiCell(38, 3, utf8_decode('Contacto directo e indirecto con redes de energia'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 74);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 74);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 74);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 74);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 74);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 77, 111, 77); //HORIZONTAL

// Cruce de circuitos paralelos
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 79.5);
$pdf->Cell(0, 0, utf8_decode('Cruce de circuitos paralelos'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 79);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 79);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 79);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 79);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 79);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 81, 111, 81); //HORIZONTAL

// Energización por parte de terceros
$pdf->SetFont("Arial", "", 6.7);
$pdf->SetXY(31, 83.5);
$pdf->Cell(0, 0, utf8_decode('Energización por parte de terceros'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 83);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 83);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 83);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 83);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 83);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 85, 111, 85); //HORIZONTAL

// Exposicion a acometidaas no autorizadas
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 85.5);
$pdf->MultiCell(38, 2.5, utf8_decode('Exposicion a acometidaas no autorizadas'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 88);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 88);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 88);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 88);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 88);
$pdf->Cell(0, 0, 'x');

// DATOS DE CONSECUENCIAS
// Quemaduras superficiales de la piel
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 58);
$pdf->MultiCell(75, 3, utf8_decode('Quemaduras superficiales de la piel, quemaduras internas, Tetanizaciones musculares, traumas respiratorios, fisiciologicos, psicologicos, cardiacos, Trauma de variada severidad, perdida material, de organos y miembros del cuerpo, Daños a la propiedad, y muerte'), 0, "C");

// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Dotación ignífugo, Guantes dielectrico
$pdf->SetXY(191, 58);
$pdf->MultiCell(100, 3, utf8_decode('Dotación ignífugo, Guantes dielectrico, clase 0, II y IV, Uso de careta anti arco electrico, monja o escafandra ignifuga, tapete dielectrico, Pértigas dieléctricas; Desarrollo correcto de los procedimientos de trabajo. Protocolo de Seguridad en riesgo eléctrico, reglas de oro, Capacitación en riesgo eléctrico, inspección a las herramientas, Plan de Emergencia. '), 0, "C");
$pdf->Line(5, 91, 292, 91); //HORIZONTAL

// Transporte
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 103);
$pdf->Cell(0, 0, utf8_decode('Transporte'));

// DATOS DE RIESGOS
// Desplazamiento vehicular y micro sueño
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 92);
$pdf->MultiCell(38, 2.5, utf8_decode('Desplazamiento vehicular y micro sueño'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 94);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 94);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 94);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 94);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 94);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 97, 111, 97); //HORIZONTAL

// Falla de mecanicas y electricas.
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 99);
$pdf->Cell(0, 0, utf8_decode('Falla de mecanicas y electricas.'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 99);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 99);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 99);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 99);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 99);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 101, 111, 101); //HORIZONTAL

// Derrape o Deslizamiento de llantas
$pdf->SetFont("Arial", "", 6.7);
$pdf->SetXY(31, 103);
$pdf->Cell(0, 0, utf8_decode('Derrape o Deslizamiento de llantas'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 103);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 103);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 103);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 103);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 103);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 105, 111, 105); //HORIZONTAL

// Volcamientos, Choques y 
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 107);
$pdf->Cell(0, 0, utf8_decode('Volcamientos, Choques y '));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 107);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 107);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 107);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 107);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 107);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 109, 111, 109); //HORIZONTAL

// Transporte fluvia
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 111);
$pdf->Cell(0, 0, utf8_decode('Transporte fluvia'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 111);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 111);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 111);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 111);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 111);
$pdf->Cell(0, 0, 'x');

// DATOS DE CONSECUENCIAS
// Accidente vehicular en Trabajo
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 95);
$pdf->MultiCell(75, 3, utf8_decode('Accidente vehicular en Trabajo, en áreas o vías públicas, lesiones, contusiones y muerte, '), 0, "C");
$pdf->SetXY(118, 107);
$pdf->Cell(0, 0, utf8_decode('En caso de traslado en transporte Fluvial, ahogamiento '));

// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Dotación ignífugo, Guantes dielectrico
$pdf->SetXY(191, 94);
$pdf->MultiCell(100, 3, utf8_decode('Preoperacional del vehiculo, Manejo defensivo, pausas activas cada dos horas de viaje, tomar 15 min descanso; Documentos vigentes de conduccion y vehicular, Kit de carretera, Botiquin de primeros auxilios, Extintor Multiproposito. Mantenimiento preventivo y correctivo vehicular. En caso de traslado en transporte fluvial utilizar chaleco de Salvavidas'), 0, "C");

$pdf->Line(5, 113, 292, 113); //HORIZONTAL

// Alturas
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 122);
$pdf->Cell(0, 0, utf8_decode('Alturas'));

// Caida a distinto nive
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 117);
$pdf->Cell(0, 0, utf8_decode('Caida a distinto nive'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 116);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 116);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 116);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 116);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 116);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 119, 111, 119); //HORIZONTAL

// Condiciones de la labor por encima de 2 mts de altura
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 122);
$pdf->MultiCell(38, 2.5, utf8_decode('Condiciones de la labor por encima de 2 mts de altura'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 125);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 125);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 125);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 125);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 125);
$pdf->Cell(0, 0, 'x');

// DATOS DE CONSECUENCIAS
// Lesiones, Fracturas,Traumas psicologicos, perdida de organos
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 117);
$pdf->MultiCell(75, 3, utf8_decode('Lesiones, Fracturas,Traumas psicologicos, perdida de organos y miembros del cuerpo. Daños a la propiedad y perdida material de la empresa, Muerte,'), 0, "C");


// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Capacitación, los sistemas de ingeniería para prevención de caídas
$pdf->SetXY(191, 114);
$pdf->MultiCell(100, 3, utf8_decode('Capacitación, los sistemas de ingeniería para prevención de caídas, medidas colectivas de prevención.Permiso de trabajo en alturas.
Sistemas de acceso para trabajo en alturas. uso e implementacion del kit de rescate.Trabajos en suspensión, certificación para trabajo seguro en alturas vigente y cargado en la pagina delministerio de trabajo , Inspeccion tecnica anual de EPCC e inspeccion pre operacional de EPCC.'), 0, "C");
$pdf->Line(5, 132, 292, 132); //HORIZONTAL

// Publico
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 141);
$pdf->Cell(0, 0, utf8_decode('Publico'));

// Orden Publico
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 135);
$pdf->Cell(0, 0, utf8_decode('Orden Publico'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 134);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 134);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 134);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 134);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 134);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 137, 111, 137); //HORIZONTAL

// Atracos / Asaltos / Robo
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 141);
$pdf->Cell(0, 0, utf8_decode('Atracos / Asaltos / Robo'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 140);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 140);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 140);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 140);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 140);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 143, 111, 143); //HORIZONTAL

// Atentados, secuestros
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 148);
$pdf->Cell(0, 0, utf8_decode('Atentados, secuestros'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 146);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 146);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 146);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 146);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 146);
$pdf->Cell(0, 0, 'x');

// DATOS DE CONSECUENCIAS
// zonas de difícil acceso y zonas denominadas rojas
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 135);
$pdf->MultiCell(75, 3, utf8_decode('zonas de difícil acceso y zonas denominadas rojas. retención de trabajadores y bienes, Daños a la propiedad y perdida material de la empresa, Muerte, '), 0, "C");


// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Capacitación al riesgo público.
$pdf->SetXY(191, 134);
$pdf->MultiCell(100, 3, utf8_decode('Capacitación al riesgo público.
Promover el código de convivencia ciudadana entre los trabajadores. Difundir números de emergencia locales y socializar comportamientos que deben tomar ante  casos de atracos, amenazas, situaciones de violencia, etc.'), 0, "C");
$pdf->Line(5, 150, 292, 150); //HORIZONTAL

// Psicosocial
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 158);
$pdf->Cell(0, 0, utf8_decode('Psicosocial'));

// Condiciones de la tarea (Carga mental y laboral)
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 152);
$pdf->MultiCell(38, 2.5, utf8_decode('Condiciones de la tarea (Carga mental y laboral)'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 153);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 153);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 153);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 153);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 153);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 157, 111, 157); //HORIZONTAL

// Características de la organización del trabajo (comunicación, tecnología)
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 159);
$pdf->MultiCell(38, 2.5, utf8_decode('Características de la organización del trabajo (comunicación, tecnología)'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 162);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 162);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 162);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 162);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 162);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 168, 111, 168); //HORIZONTAL

// Jornada del trabajo
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 171);
$pdf->Cell(0, 0, utf8_decode('Jornada del trabajo'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 171);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 171);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 171);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 171);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 171);
$pdf->Cell(0, 0, 'x');

// DATOS DE CONSECUENCIAS
// Estrés, Sindrome de burnout, trombosis, parlisis facial, deserción laboral.
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 158);
$pdf->MultiCell(75, 3, utf8_decode('Estrés, Sindrome de burnout, trombosis, parlisis facial, deserción laboral. '), 0, "C");


// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Capacitación sobre manejo de riesgo psicosocial
$pdf->SetXY(191, 155);
$pdf->MultiCell(100, 3, utf8_decode('Capacitación sobre manejo de riesgo psicosocial, Formacion en el manejo y planifficacion del trabajo, comunicación asertiva.
Bateria riesgo Psicosocial, Encuesta de clima laboral, hidratacion, pausas activas, descansos. Programa de bienestar laboral, Rotacion del personal.'), 0, "C");


// PAGINA NUMERO 3
$pdf->AddPage('LANDSCAPE', '');

// Dibujar el marco del formulario con bordes redondeados
$pdf->RoundedRect(5, 10, 287, 180, 0, 'D');

$pdf->Image('./servicer.jpeg', 6, 11, 40);

// ANALISIS SEGURO DE TRABAJO
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($pdf->GetX() - 190);
$pdf->Cell(0, 8, utf8_decode("ANÁLISIS SEGURO DE TRABAJO"));
$pdf->Line(47, 16, 292, 16); //HORIZONTAL

// PROCESO SEGURIDAD Y SALUD EN EL TRABAJO
$pdf->SetX($pdf->GetX() - 180);
$pdf->Cell(0, 24, utf8_decode("PROCESO SEGURIDAD Y SALUD EN EL TRABAJO"));

// TEXTO CON FONDE DE COLOR
$pdf->SetFont('Arial', 'B', 7);
$pdf->PaintTextBackground(5, 27, utf8_decode('ELABORÓ / ACTUALIZÓ'), [35, 175, 216], 0, 42, 4);
$pdf->PaintTextBackground(47, 27, utf8_decode('REVISÓ'), [35, 175, 216], 0, 219, 4);

// APROBO
$pdf->PaintTextBackground(266, 27, utf8_decode('APROBÓ'), [35, 175, 216], 0, 26, 4);

// LINEAL VERTICAL QUE ESTA AL LADO DE LA IMAGEN
$pdf->Line(47, 10, 47, 34); // VERTICAL

// codigo
$pdf->Line(266, 10, 266, 34); //VERTICAL
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(267, 13);
$pdf->Cell(0, 0, utf8_decode("Código:"));

// F-SST-02
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(277, 13);
$pdf->Cell(0, 0, utf8_decode("F-SST-02"));

// FECHA
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 10, "Fecha:");

// 01/04/2024
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 11);
$pdf->Cell(0, 10, "01/04/2024");
$pdf->Line(266, 19.6, 292, 19.6); //HORIZONTAL

// VERSION
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 18, utf8_decode("Versión:"));

// 02
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 9);
$pdf->Cell(0, 18, "02");
$pdf->Line(266, 23.3, 292, 23.3); //HORIZAONTAL

// VERSION
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 25, utf8_decode("Página:"));

// 1 de 4
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 10);
$pdf->Cell(0, 25, "3 de 4");
$pdf->Line(5, 27, 292, 27); //HORIZAONTAL
$pdf->Line(5, 31, 292, 31); //HORIZAONTAL

// GERENTE
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 14);
$pdf->Cell(0, 39.4, "Gerente");


// auxiliar de calidad
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 571);
$pdf->Cell(0, 39.4, "Auxiliar de calidad");

// LIDER SGI
$pdf->SetX($pdf->GetX() - 143);
$pdf->Cell(0, 39.4, "Lider SGI");
$pdf->Line(5, 34, 292, 34); //HORIZAONTAL

$pdf->Line(31, 34, 31, 190); //VERTICAL

$pdf->Line(69, 34, 69, 190); //VERTICAL


// 1 
$pdf->Line(77.4, 34, 77.4, 190); //VERTICAL

// 2
$pdf->Line(85.8, 34, 85.8, 190); //VERTICAL

// 3
$pdf->Line(94.2, 34, 94.2, 190); //VERTICAL

// 4
$pdf->Line(102.6, 34, 102.6, 190); //VERTICAL

// 5
$pdf->Line(111, 34, 111, 190); //VERTICAL

$pdf->Line(191, 34, 191, 190); //VERTICAL


// Físicos
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 45);
$pdf->Cell(0, 0, utf8_decode('Físicos'));

// DATOS QUE ESTN EN RIEGOS
// Exposición a Frio / Calor
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 37);
$pdf->Cell(0, 0, utf8_decode('Exposición a Frio / Calor'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 37);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 37);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 37);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 37);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 37);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 40, 111, 40); //HORIZONTAL

// Radiación Solar
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 43);
$pdf->Cell(0, 0, utf8_decode('Radiación Solar'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 43);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 43);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 43);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 43);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 43);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 45, 111, 45); //HORIZONTAL

// Encandilamiento / Oscuridad
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 46);
$pdf->MultiCell(38, 3, utf8_decode('Encandilamiento / Oscuridad'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 47.5);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 47.5);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 47.5);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 47.5);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 47.5);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 50, 111, 50); //HORIZONTAL

// Ruido
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 52);
$pdf->Cell(0, 0, utf8_decode('Ruido'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 52);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 52);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 52);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 52);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 52);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 54, 111, 54); //HORIZONTAL

// Vibración
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 57);
$pdf->Cell(0, 0, utf8_decode('Vibración'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 57);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 57);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 57);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 57);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 57);
$pdf->Cell(0, 0, 'x');

// DATOS DE CONSECUENCIAS
// Fatiga, Cefalea, Disminucion de la capacidad auditiva
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 40);
$pdf->MultiCell(75, 3, utf8_decode('Fatiga, Cefalea, Disminucion de la capacidad auditiva, Quemaduras superficiales de la piel, quemaduras internas, trauma de variada severidad, Daños a la propiedad y perdida material, muerte.'), 0, "C");

// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Dotación ignífugo, Guantes dielectrico
$pdf->SetXY(191, 40);
$pdf->MultiCell(100, 3, utf8_decode('Dotacion de trabajo, Escafandras, pausas activas, Capacitación del personal. Hidratación, Uso de protección personal (casco de seguridad, ropa de trabajo, protección auditiva,bloqueador, guantes, gafas).
Iluminación adecuada para actividades nocturnas o con poca iluminación.'), 0, "C");
$pdf->Line(5, 60, 292, 60); //HORIZONTAL

// Químicos
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 67);
$pdf->Cell(0, 0, utf8_decode('Químicos'));

// Quemaduras
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 62);
$pdf->Cell(0, 0, utf8_decode('Quemaduras'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 62);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 62);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 62);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 62);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 62);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 64, 111, 64); //HORIZONTAL

// Intoxicación / Alergias
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 67);
$pdf->Cell(0, 0, utf8_decode('Intoxicación / Alergias.'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 67);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 67);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 67);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 67);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 67);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 70, 111, 70); //HORIZONTAL

// Gases/Humos/ Polvo/Vapores
$pdf->SetFont("Arial", "", 6.7);
$pdf->SetXY(31, 73);
$pdf->Cell(0, 0, utf8_decode('Gases/Humos/ Polvo/Vapores'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 73);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 73);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 73);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 73);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 73);
$pdf->Cell(0, 0, 'x');



// DATOS DE CONSECUENCIAS
// Irritacion de la pie
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 65);
$pdf->MultiCell(75, 3, utf8_decode('Irritacion de la piel, de los ojos, Desmayos, diarreas '), 0, "C");

// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Uso de protección personal
$pdf->SetXY(191, 62);
$pdf->MultiCell(100, 3, utf8_decode('Uso de protección personal (casco de seguridad, ropa de trabajo, guantes).Protección visual, Tapabocas
Fichas de seguridad del producto quimico, Rotulacion de los productos quimicos, Botiquin de primerosauxilios'), 0, "C");
$pdf->Line(5, 76, 292, 76); //HORIZONTAL

// Biomecánico
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 83);
$pdf->Cell(0, 0, utf8_decode('Biomecánico'));

// Sobre esfuerzo
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 78);
$pdf->Cell(0, 0, utf8_decode('Sobre esfuerzo'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 78);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 78);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 78);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 78);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 78);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 80, 111, 80); //HORIZONTAL

// Posturas Inadecuadas
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 81);
$pdf->MultiCell(38, 2.5, utf8_decode('Posturas Inadecuadas'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 82);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 82);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 82);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 82);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 82);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 84, 111, 84); //HORIZONTAL

// Movimientos Forzosos
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 85);
$pdf->MultiCell(38, 2.5, utf8_decode('Movimientos Forzosos'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 86);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 86);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 86);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 86);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 86);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 88, 111, 88); //HORIZONTAL

// Movimientos Repetitivos
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 89);
$pdf->MultiCell(38, 2.5, utf8_decode('Movimientos Repetitivos'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 90);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 90);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 90);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 90);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 90);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 92, 111, 92); //HORIZONTAL

// Manipulación de Cargas
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 93);
$pdf->MultiCell(38, 2.5, utf8_decode('Manipulación de Cargas'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 94);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 94);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 94);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 94);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 94);
$pdf->Cell(0, 0, 'x');

// DATOS DE CONSECUENCIAS
// Lesiones, Fracturas,Traumas psicologicos, perdida de organos
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 83);
$pdf->MultiCell(75, 3, utf8_decode('Lesiones osteomusculares, desgarres, esguinces, enfermedad osteomusculares '), 0, "C");


// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Capacitación, los sistemas de ingeniería para prevención de caídas
$pdf->SetXY(191, 78);
$pdf->MultiCell(100, 3, utf8_decode('Trabajo en equipo.Pausas activas. Procedimientos seguros para el levantamiento de cargas. Calistenia antes de iniciar labores.
Posturas adecuadas, Capacitación en riesgo biomecánico, manejo de cargas, posturas y rotación de personal.
Ayudas mecanicas para la labor'), 0, "C");
$pdf->Line(5, 96, 292, 96); //HORIZONTAL

// Mecánico
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 103);
$pdf->Cell(0, 0, utf8_decode('Mecánico'));

// Golpes
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 98);
$pdf->Cell(0, 0, utf8_decode('Golpes'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 98);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 98);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 98);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 98);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 98);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 100, 111, 100); //HORIZONTAL

// Atrapamientos
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 102);
$pdf->Cell(0, 0, utf8_decode('Atrapamientos'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 102);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 102);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 102);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 102);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 102);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 104, 111, 104); //HORIZONTAL

// Proyección de Partículas
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 106);
$pdf->Cell(0, 0, utf8_decode('Proyección de Partículas'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 106);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 106);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 106);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 106);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 106);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 108, 111, 108); //HORIZONTAL

// Aplastamiento
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 110);
$pdf->Cell(0, 0, utf8_decode('Aplastamiento'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 110);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 110);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 110);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 110);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 110);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 112, 111, 112); //HORIZONTAL

// Cortes y Heridas
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 114);
$pdf->Cell(0, 0, utf8_decode('Cortes y Heridas'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 114);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 114);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 114);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 114);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 114);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 116, 111, 116); //HORIZONTAL

// Fallas de equipos y herramientas
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 118);
$pdf->Cell(0, 0, utf8_decode('Fallas de equipos y herramientas'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 118);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 118);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 118);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 118);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 118);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 120, 111, 120); //HORIZONTAL

// Caída de Objetos
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 122);
$pdf->Cell(0, 0, utf8_decode('Caída de Objetos'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 122);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 122);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 122);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 122);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 122);
$pdf->Cell(0, 0, 'x');


// DATOS DE CONSECUENCIAS
// zonas de difícil acceso y zonas denominadas rojas
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 107);
$pdf->MultiCell(75, 3, utf8_decode('Lesiones, heridas superficiales, amputaciones, fracturas, Muerte. '), 0, "C");


// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Capacitación al riesgo público.
$pdf->SetXY(191, 99);
$pdf->MultiCell(100, 3, utf8_decode('Uso de EPP (Gafas Guantes de seguridady otros)Casco de seguridad, botas de seguridad.
Dotacion de trabajo. para proceso poda (pantalon anticorte, guantes anticorte) Cuerda de servicio de 20 mts, procedimiento de trabajo seguro Capacitación en manejo de objetos, equipos, maquinas y herramientas para desempeñar labores inspección de equipos y herramientas. Bolso portaherramientas.'), 0, "C");
$pdf->Line(5, 124, 292, 124); //HORIZONTAL

// zaje de carga
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 130);
$pdf->Cell(0, 0, utf8_decode('zaje de carga'));

// Desplome de la carga / Caída de objetos
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 125);
$pdf->MultiCell(38, 2.5, utf8_decode('Desplome de la carga / Caída de objetos'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 127);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 127);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 127);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 127);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 127);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 131, 111, 131); //HORIZONTAL

// Daños a grúas, equipos y demás infraestructura
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 132);
$pdf->MultiCell(38, 2.5, utf8_decode('Daños a grúas, equipos y demás infraestructura'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 134);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 134);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 134);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 134);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 134);
$pdf->Cell(0, 0, 'x');

// DATOS DE CONSECUENCIAS
// Lesiones, golpes, heridas superficiales
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 128);
$pdf->MultiCell(75, 3, utf8_decode('Lesiones, golpes, heridas superficiales, amputaciones, fracturas, Muerte '), 0, "C");


// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Preoperacionales, Plan y permiso de Izaje de carga
$pdf->SetXY(191, 125);
$pdf->MultiCell(100, 3, utf8_decode('Preoperacionales, Plan y permiso de Izaje de carga (critico, no critico), Competencias del aparejador, operador de grua, Capacitacion del personal; Epp ( casco, botas guantes, gafas de segruidad), Epp colectivos para izaje, Pruebas de Izaje certtificada por la ONAC.
Mantenimiento preventivo y correctivo de vehiculos pesados de izaje'), 0, "C");
$pdf->Line(5, 140, 292, 140); //HORIZONTAL

// Tecnológico
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 144);
$pdf->Cell(0, 0, utf8_decode('Tecnológico'));

// Incendio o Explosión
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 141);
$pdf->MultiCell(38, 2.5, utf8_decode('Incendio o Explosión'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 142);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 142);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 142);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 142);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 142);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 144, 111, 144); //HORIZONTAL

// fuga, derrame, 
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 145);
$pdf->MultiCell(38, 2.5, utf8_decode('fuga, derrame,'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 146);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 146);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 146);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 146);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 146);
$pdf->Cell(0, 0, 'x');

// DATOS DE CONSECUENCIAS
// Lesiones, golpes, heridas superficiales
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 141);
$pdf->MultiCell(75, 3, utf8_decode('Efectos sobre la salud del trabajador, Trauma de variada severidad, Daños a la propiedad y perdida material'), 0, "C");


// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Preoperacionales, Plan y permiso de Izaje de carga
$pdf->SetXY(191, 141);
$pdf->MultiCell(100, 3, utf8_decode('Kit antiderrame, plan de emergencias, Capacitación al personal en atención de emergencias'), 0, "C");
$pdf->Line(5, 148, 292, 148); //HORIZONTAL

// Locativos
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 153);
$pdf->Cell(0, 0, utf8_decode('Locativos'));

// Superficies no Uniformes
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 149);
$pdf->MultiCell(38, 2.5, utf8_decode('Superficies no Uniformes'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 150);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 150);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 150);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 150);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 150);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 152, 111, 152); //HORIZONTAL

// Tropiezo  
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 153);
$pdf->MultiCell(38, 2.5, utf8_decode('Tropiezo '));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 154);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 154);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 154);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 154);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 154);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 156, 111, 156); //HORIZONTAL

// Caídas del mismo nive
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 157);
$pdf->MultiCell(38, 2.5, utf8_decode('Caídas del mismo nive '));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 158);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 158);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 158);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 158);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 158);
$pdf->Cell(0, 0, 'x');


// DATOS DE CONSECUENCIAS
// Lesiones, golpes, heridas superficiales
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(112, 152);
$pdf->MultiCell(75, 3, utf8_decode('Lesiones, golpes, heridas superficiales, fracturas'), 0, "C");


// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Preoperacionales, Plan y permiso de Izaje de carga
$pdf->SetXY(191, 151);
$pdf->MultiCell(100, 3, utf8_decode('Jornadas de Orden y aseo, capacitaciones e inspecciones periódicas y acompañamientos a labores de campo'), 0, "C");
$pdf->Line(5, 160, 292, 160); //HORIZONTAL

// Biológico
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(11, 167);
$pdf->Cell(0, 0, utf8_decode('Biológico'));

// Picaduras y mordeduras
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 162);
$pdf->MultiCell(38, 2.5, utf8_decode('Picaduras y mordeduras'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 163);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 163);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 163);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 163);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 163);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 165, 111, 165); //HORIZONTAL

// Virus / Bacterias / Hongos  
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 168);
$pdf->MultiCell(38, 2.5, utf8_decode('Virus / Bacterias / Hongos'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 168);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 168);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 168);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 168);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 168);
$pdf->Cell(0, 0, 'x');


// DATOS DE CONSECUENCIAS
// Afectaciones por enfermedad temporal que produce malestar
$pdf->SetFont("Arial", "", 6.7);
$pdf->SetXY(112, 161);
$pdf->MultiCell(75, 3, utf8_decode('Afectaciones por enfermedad temporal que produce malestar, contagio de enfermedades zoonoticas. Afectaciones por picaduras, mordeduras de insectos, anfibios, caninos y/u otros'), 0, "C");


// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Capacitaciones sobre riesgo biológico
$pdf->SetXY(191, 161);
$pdf->MultiCell(100, 3, utf8_decode('Capacitaciones sobre riesgo biológico, repelente, inspecciones de seguridad, orden y aseo, primeros auxilios.Protocolos de bioseguridad adaptados a la normatividad vigente, vacunas vigentes, traje de apicultura, Botiquin de primeros auxilios, Dotación, EPP, guantes gafas, Botas'), 0, "C");
$pdf->Line(5, 173, 292, 173); //HORIZONTAL

// Fenomenos Naturales
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(4.5, 180);
$pdf->Cell(0, 0, utf8_decode('Fenomenos Naturales'));

// Sismo
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 173);
$pdf->MultiCell(38, 2.5, utf8_decode('Sismo'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 174);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 174);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 174);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 174);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 174);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 176, 111, 176); //HORIZONTAL

// Terremoto / Derrumbe
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 176.5);
$pdf->MultiCell(38, 2.5, utf8_decode('Terremoto / Derrumbe'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 177);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 177);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 177);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 177);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 177);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 179, 111, 179); //HORIZONTAL

// Inundaciones
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 180);
$pdf->MultiCell(38, 2.5, utf8_decode('Inundaciones'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 181);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 181);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 181);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 181);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 181);
$pdf->Cell(0, 0, 'x');
$pdf->Line(31, 183, 111, 183); //HORIZONTAL

// Precipitaciones, (lluvias, vendavales)
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 184);
$pdf->MultiCell(38, 2.5, utf8_decode('Precipitaciones, (lluvias, vendavales)'));

// X 1
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(72, 185);
$pdf->Cell(0, 0, 'x');

// X 2
$pdf->SetXY(80, 185);
$pdf->Cell(0, 0, 'x');

// X 3
$pdf->SetXY(88, 185);
$pdf->Cell(0, 0, 'x');

// X 4
$pdf->SetXY(97, 185);
$pdf->Cell(0, 0, 'x');

// X 5
$pdf->SetXY(105, 185);
$pdf->Cell(0, 0, 'x');

// DATOS DE CONSECUENCIAS
// Afectaciones por enfermedad temporal que produce malestar
$pdf->SetFont("Arial", "", 6.7);
$pdf->SetXY(112, 174);
$pdf->MultiCell(75, 3, utf8_decode('Efectos sobre la salud del trabajador, Trauma de variada severidad, Daños a la propiedad y perdida material'), 0, "C");


// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
// Capacitaciones sobre riesgo biológico
$pdf->SetXY(191, 173);
$pdf->MultiCell(100, 3, utf8_decode('Impermeable, Botiquín de primeros Auxilios, Camilla, ExtintorSocializaciones de Planes de Emergencia, como actuar antes, durante y después de un emergencia, Capacitaciones de manejo de extintores y Primeros Auxilios , Inspecciones de seguridad y Botiquín, brigada de emergencia.
Dependiendo de la labor no se debe trabajar en lluvias.'), 0, "C");


// PAGINA NUMERO 4
$pdf->AddPage('LANDSCAPE', '');

// Dibujar el marco del formulario con bordes redondeados
$pdf->RoundedRect(5, 10, 287, 152, 0, 'D');

$pdf->Image('./servicer.jpeg', 6, 11, 40);

// ANALISIS SEGURO DE TRABAJO
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetX($pdf->GetX() - 190);
$pdf->Cell(0, 8, utf8_decode("ANÁLISIS SEGURO DE TRABAJO"));
$pdf->Line(47, 16, 292, 16); //HORIZONTAL

// PROCESO SEGURIDAD Y SALUD EN EL TRABAJO
$pdf->SetX($pdf->GetX() - 180);
$pdf->Cell(0, 24, utf8_decode("PROCESO SEGURIDAD Y SALUD EN EL TRABAJO"));

// TEXTO CON FONDE DE COLOR
$pdf->SetFont('Arial', 'B', 7);
$pdf->PaintTextBackground(5, 27, utf8_decode('ELABORÓ / ACTUALIZÓ'), [35, 175, 216], 0, 42, 4);
$pdf->PaintTextBackground(47, 27, utf8_decode('REVISÓ'), [35, 175, 216], 0, 219, 4);

// APROBO
$pdf->PaintTextBackground(266, 27, utf8_decode('APROBÓ'), [35, 175, 216], 0, 26, 4);

// LINEAL VERTICAL QUE ESTA AL LADO DE LA IMAGEN
$pdf->Line(47, 10, 47, 34); // VERTICAL

// codigo
$pdf->Line(266, 10, 266, 34); //VERTICAL
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(267, 13);
$pdf->Cell(0, 0, utf8_decode("Código:"));

// F-SST-02
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(277, 13);
$pdf->Cell(0, 0, utf8_decode("F-SST-02"));

// FECHA
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 10, "Fecha:");

// 01/04/2024
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 11);
$pdf->Cell(0, 10, "01/04/2024");
$pdf->Line(266, 19.6, 292, 19.6); //HORIZONTAL

// VERSION
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 18, utf8_decode("Versión:"));

// 02
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 9);
$pdf->Cell(0, 18, "02");
$pdf->Line(266, 23.3, 292, 23.3); //HORIZAONTAL

// VERSION
$pdf->SetFont("Arial", "B", 7);
$pdf->SetX($pdf->GetX() - 20);
$pdf->Cell(0, 25, utf8_decode("Página:"));

// 1 de 4
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 10);
$pdf->Cell(0, 25, "3 de 4");
$pdf->Line(5, 27, 292, 27); //HORIZAONTAL
$pdf->Line(5, 31, 292, 31); //HORIZAONTAL

// GERENTE
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 14);
$pdf->Cell(0, 39.4, "Gerente");


// auxiliar de calidad
$pdf->SetFont("Arial", "", 7);
$pdf->SetX($pdf->GetX() - 571);
$pdf->Cell(0, 39.4, "Auxiliar de calidad");

// LIDER SGI
$pdf->SetX($pdf->GetX() - 143);
$pdf->Cell(0, 39.4, "Lider SGI");
$pdf->Line(5, 34, 292, 34); //HORIZAONTAL


// Identificación de aspectos e impactos ambientales
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(4, 60);
$pdf->MultiCell(28, 3, utf8_decode('Identificación de aspectos e impactos ambientales'), 0, "C");
$pdf->Line(31, 34, 31, 100); //VERTICAL

// Aspectos 
$pdf->PaintTextBackground(31, 34, utf8_decode('Aspectos'), [35, 175, 216], 0, 35, 7);
$pdf->Line(66, 34, 66, 41); //VERTICAL

// Impactos
$pdf->PaintTextBackground(66, 34, utf8_decode('Impactos'), [35, 175, 216], 0, 27, 7);
$pdf->Line(31, 41, 292, 41);//HORIZONTAL
$pdf->Line(93, 34, 93, 100); //VERTICAL

// 1
$pdf->PaintTextBackground(93, 34, utf8_decode('1'), [35, 175, 216], 0, 4, 6.8);
$pdf->Line(97, 34, 97, 100); //VERTICAL

// 2
$pdf->PaintTextBackground(97, 34, utf8_decode('2'), [35, 175, 216], 0, 4, 6.8);
$pdf->Line(101, 34, 101, 100); //VERTICAL

// 3
$pdf->PaintTextBackground(101, 34, utf8_decode('3'), [35, 175, 216], 0, 4, 6.8);
$pdf->Line(105, 34, 105, 100); //VERTICAL

// 4
$pdf->PaintTextBackground(105, 34, utf8_decode('4'), [35, 175, 216], 0, 4, 6.8);
$pdf->Line(109, 34, 109, 100); //VERTICAL

// 5
$pdf->PaintTextBackground(109, 34, utf8_decode('5'), [35, 175, 216], 0, 4, 6.8);
$pdf->Line(113, 34, 113, 100); //VERTICAL

// Aptitud y competencia del personal y Analisis antes de realizar la labor
$pdf->PaintTextBackground(113, 34, utf8_decode('Aptitud y competencia del personal y Analisis antes de realizar la labor'), [35, 175, 216], 0, 140, 6.8);
$pdf->Line(253, 34, 253, 100); //VERTICAL

// # Trab 1
$pdf->PaintTextBackground(253, 34, utf8_decode('# Trab 1'), [35, 175, 216], 0, 13, 5);
$pdf->PaintTextBackground(253, 38.8, utf8_decode('SI    NO'), [35, 175, 216], 0, 13, 2);
$pdf->Line(266, 34, 266, 100); //VERTICAL

// # Trab 2
$pdf->PaintTextBackground(266, 34, utf8_decode('# Trab 2'), [35, 175, 216], 0, 13, 5);
$pdf->PaintTextBackground(266, 38.8, utf8_decode('SI    NO'), [35, 175, 216], 0, 13, 2);
$pdf->Line(279, 34, 279, 100); //VERTICAL

// # Trab 3
$pdf->PaintTextBackground(279, 34, utf8_decode('# Trab 3'), [35, 175, 216], 0, 13, 5);
$pdf->PaintTextBackground(279, 38.8, utf8_decode('SI    NO'), [35, 175, 216], 0, 13, 2);


// ¿se realizo la Identificación de los aspectos e impactos ambientales potenciales para realizar el trabajo?
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(29.5, 41);
$pdf->MultiCell(65, 3, utf8_decode('¿se realizo la Identificación de los aspectos e impactos ambientales potenciales para realizar el trabajo?'), 0, "C");
$pdf->Line(31, 47, 292, 47); //HORIZONTAL
$pdf->Line(66, 47, 66, 100); //VERTICAL

// X 1
$pdf->SetXY(93, 44);
$pdf->Cell(0, 0, 'X');

// X 2
$pdf->SetXY(97, 44);
$pdf->Cell(0, 0, 'X');

// X 3
$pdf->SetXY(101, 44);
$pdf->Cell(0, 0, 'X');

// X 4
$pdf->SetXY(105, 44);
$pdf->Cell(0, 0, 'X');

// X 5
$pdf->SetXY(109, 44);
$pdf->Cell(0, 0, 'X');

// ME ENCUENTRO EN BUENAS CONDICIONES DE SALUD: ¿El personal notifica alguna condición del estado de salud?
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(115, 44);
$pdf->Cell(0, 0, utf8_decode('ME ENCUENTRO EN BUENAS CONDICIONES DE SALUD:'), 0, "C");
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(183, 44);
$pdf->Cell(0, 0, utf8_decode('¿El personal notifica alguna condición del estado de salud?'), 0, "C");

// Trab 1 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(254, 44);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(259.5, 41, 259.5, 100); //VERTICAL

// Trab 1 NO
$pdf->SetXY(260, 44);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 2 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(267, 44);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(272.5, 41, 272.5, 100); //VERTICAL

// Trab 2 NO
$pdf->SetXY(273, 44);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 3 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(280, 44);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(285.5, 41, 285.5, 100); //VERTICAL

// Trab 3 NO
$pdf->SetXY(286, 44);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Consumo de agua y energía: 
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(16, 49);
$pdf->MultiCell(65, 3, utf8_decode('Consumo de agua y energía:'), 0, "C");
$pdf->Line(31, 53, 292, 53); //HORIZONTAL

// Agotamiento de los recursos naturales
$pdf->SetXY(65, 47);
$pdf->MultiCell(30, 3, utf8_decode('Agotamiento de los recursos naturales'), 0, "C");

// X 1
$pdf->SetXY(93, 50);
$pdf->Cell(0, 0, 'X');

// X 2
$pdf->SetXY(97, 50);
$pdf->Cell(0, 0, 'X');

// X 3
$pdf->SetXY(101, 50);
$pdf->Cell(0, 0, 'X');

// X 4
$pdf->SetXY(105, 50);
$pdf->Cell(0, 0, 'X');

// X 5
$pdf->SetXY(109, 50);
$pdf->Cell(0, 0, 'X');

// ME ENCUENTRO CALIFICADO PARA REALIZAR LA LABOR:
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(113, 48.5);
$pdf->Cell(0, 0, utf8_decode('ME ENCUENTRO CALIFICADO PARA REALIZAR LA LABOR:'));
//   ¿he recibido entrenamiento para la labor y cuento con las
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(185, 48.5);
$pdf->Cell(0, 0, utf8_decode(' ¿he recibido entrenamiento para la labor y cuento con las'));
// competencia requeridas?
$pdf->SetXY(165, 51);
$pdf->Cell(0, 0, utf8_decode('competencia requeridas?'));

// Trab 1 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(254, 50);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(259.5, 41, 259.5, 100); //VERTICAL

// Trab 1 NO
$pdf->SetXY(260, 50);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 2 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(267, 50);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(272.5, 41, 272.5, 100); //VERTICAL

// Trab 2 NO
$pdf->SetXY(273, 50);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 3 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(280, 50);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(285.5, 41, 285.5, 100); //VERTICAL

// Trab 3 NO
$pdf->SetXY(286, 50);
$pdf->Cell(0, 0, utf8_decode('NO'));


// Generación de residuos sólidos y líquidos:
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(34, 53);
$pdf->MultiCell(30, 3, utf8_decode('Generación de residuos sólidos y líquidos:'), 0, "C");

// Contaminación del suelo y fuentes de agua
$pdf->SetXY(65, 53);
$pdf->MultiCell(29, 3, utf8_decode('Contaminación del suelo y fuentes de agua'), 0, "C");

// X 1
$pdf->SetXY(93, 56);
$pdf->Cell(0, 0, 'X');

// X 2
$pdf->SetXY(97, 56);
$pdf->Cell(0, 0, 'X');

// X 3
$pdf->SetXY(101, 56);
$pdf->Cell(0, 0, 'X');

// X 4
$pdf->SetXY(105, 56);
$pdf->Cell(0, 0, 'X');

// X 5
$pdf->SetXY(109, 56);
$pdf->Cell(0, 0, 'X');

// IDENTIFICACION DE PELIGROS Y VALORACION DE RIESGOS POTENCIALES:
$pdf->SetFont("Arial", "B", 6.5);
$pdf->SetXY(113, 54.8);
$pdf->Cell(0, 0, utf8_decode('IDENTIFICACION DE PELIGROS Y VALORACION DE RIESGOS POTENCIALES:'), 0, "C");
// ¿se realizo la Identificación de los circuitos y/o
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(200, 54.5);
$pdf->Cell(0, 0, utf8_decode('¿se realizo la Identificación de los circuitos y/o'), 0, "C");
// equipos a trabajar y los peligros y riesgos potenciales para realizar el trabajo?
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(140, 57.5);
$pdf->Cell(0, 0, utf8_decode('equipos a trabajar y los peligros y riesgos potenciales para realizar el trabajo?'), 0, "C");

// Trab 1 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(254, 56);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(259.5, 41, 259.5, 100); //VERTICAL

// Trab 1 NO
$pdf->SetXY(260, 56);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 2 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(267, 56);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(272.5, 41, 272.5, 100); //VERTICAL

// Trab 2 NO
$pdf->SetXY(273, 56);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 3 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(280, 56);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(285.5, 41, 285.5, 100); //VERTICAL

// Trab 3 NO
$pdf->SetXY(286, 56);
$pdf->Cell(0, 0, utf8_decode('NO'));
$pdf->Line(31, 59, 292, 59); //HORIZONTAL

// Intervención arbórea:
$pdf->SetXY(36, 62);
$pdf->Cell(0, 0, utf8_decode('Intervención arbórea:'));

// Agotamiento de los recursos naturales
$pdf->SetXY(67, 59);
$pdf->MultiCell(25, 3, utf8_decode('Agotamiento de los recursos naturales'));

// X 1
$pdf->SetXY(93, 62);
$pdf->Cell(0, 0, 'X');

// X 2
$pdf->SetXY(97, 62);
$pdf->Cell(0, 0, 'X');

// X 3
$pdf->SetXY(101, 62);
$pdf->Cell(0, 0, 'X');

// X 4
$pdf->SetXY(105, 62);
$pdf->Cell(0, 0, 'X');

// X 5
$pdf->SetXY(109, 62);
$pdf->Cell(0, 0, 'X');

// EQUIPOS Y HTAS:
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(115, 61);
$pdf->Cell(0, 0, 'EQUIPOS Y HTAS:');
//  ¿Se realizo la Selección de materiales, equipos y herramientas de trabajo adecuados y cuento con los
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(138, 61);
$pdf->Cell(0, 0, utf8_decode('¿Se realizo la Selección de materiales, equipos y herramientas de trabajo adecuados y cuento con los'));
// necesarios para laborar?
$pdf->SetXY(165, 63.5);
$pdf->Cell(0, 0, 'necesarios para laborar?');

// Trab 1 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(254, 62);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(259.5, 41, 259.5, 100); //VERTICAL

// Trab 1 NO
$pdf->SetXY(260, 62);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 2 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(267, 62);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(272.5, 41, 272.5, 100); //VERTICAL

// Trab 2 NO
$pdf->SetXY(273, 62);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 3 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(280, 62);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(285.5, 41, 285.5, 100); //VERTICAL

// Trab 3 NO
$pdf->SetXY(286, 62);
$pdf->Cell(0, 0, utf8_decode('NO'));
$pdf->Line(31, 65, 292, 65); //HORIZONTAL

// Manejo de productos químicos:
$pdf->SetXY(31, 70);
$pdf->Cell(0, 0, utf8_decode('Manejo de productos químicos:'));

// Generación de residuos peligrosos
$pdf->SetXY(67, 67);
$pdf->MultiCell(24, 3, utf8_decode('Generación de residuos peligrosos'));

// X 1
$pdf->SetXY(93, 70);
$pdf->Cell(0, 0, 'X');

// X 2
$pdf->SetXY(97, 70);
$pdf->Cell(0, 0, 'X');

// X 3
$pdf->SetXY(101, 70);
$pdf->Cell(0, 0, 'X');

// X 4
$pdf->SetXY(105, 70);
$pdf->Cell(0, 0, 'X');

// X 5
$pdf->SetXY(109, 70);
$pdf->Cell(0, 0, 'X');

// PLANIFICACION DE TRABAJO:
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(113, 66.5);
$pdf->Cell(0, 0, 'PLANIFICACION DE TRABAJO:');
//  ¿Se hizo Revisión y verificación del buen estado de los materiales, equipos y herramientas 
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(151, 66.5);
$pdf->Cell(0, 0, utf8_decode('¿Se hizo Revisión y verificación del buen estado de los materiales, equipos y herramientas'));
// de trabajo seleccionadas y se realizo la revisión de condiciones de la instalación (estructura, postes, arboles, equipos, etc. y hubo coordinación de operación con el centro de control CLD?
$pdf->SetXY(113, 68);
$pdf->MultiCell(140, 3, utf8_decode('de trabajo seleccionadas y se realizo la revisión de condiciones de la instalación (estructura, postes, arboles, equipos, etc. y hubo coordinación de operación con el centro de control CLD?'), 0, 'C');

// Trab 1 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(254, 70);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(259.5, 41, 259.5, 100); //VERTICAL

// Trab 1 NO
$pdf->SetXY(260, 70);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 2 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(267, 70);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(272.5, 41, 272.5, 100); //VERTICAL

// Trab 2 NO
$pdf->SetXY(273, 70);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 3 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(280, 70);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(285.5, 41, 285.5, 100); //VERTICAL

// Trab 3 NO
$pdf->SetXY(286, 70);
$pdf->Cell(0, 0, utf8_decode('NO'));
$pdf->Line(31, 74, 292, 74); //HORIZONTAL

// Remoción del suelo
$pdf->SetXY(35, 77);
$pdf->Cell(0, 0, utf8_decode('Remoción del suelo'));

// Deterioro a daño a la capa del suelo
$pdf->SetXY(67, 74);
$pdf->MultiCell(25, 3, utf8_decode('Deterioro a daño a la capa del suelo'));

// X 1
$pdf->SetXY(93, 77);
$pdf->Cell(0, 0, 'X');

// X 2
$pdf->SetXY(97, 77);
$pdf->Cell(0, 0, 'X');

// X 3
$pdf->SetXY(101, 77);
$pdf->Cell(0, 0, 'X');

// X 4
$pdf->SetXY(105, 77);
$pdf->Cell(0, 0, 'X');

// X 5
$pdf->SetXY(109, 77);
$pdf->Cell(0, 0, 'X');

// PERMISOS DE TRABAJO Y OTROS DOCUMENTOS:
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(113, 75.7);
$pdf->Cell(0, 0, utf8_decode('PERMISOS DE TRABAJO Y OTROS DOCUMENTOS:'));
// ¿Cuento con todos los permisos de trabajo seguro, preoperaicionales
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(175, 75.7);
$pdf->Cell(0, 0, utf8_decode('¿Cuento con todos los permisos de trabajo seguro, preoperaicionales'));
// firmados y autorizados para la ejecucion de la labor?
$pdf->SetXY(155, 78.5);
$pdf->Cell(0, 0, utf8_decode('firmados y autorizados para la ejecucion de la labor?'));

// Trab 1 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(254, 77);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(259.5, 41, 259.5, 100); //VERTICAL

// Trab 1 NO
$pdf->SetXY(260, 77);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 2 SI
$pdf->SetXY(267, 77);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(272.5, 41, 272.5, 100); //VERTICAL

// Trab 2 NO
$pdf->SetXY(273, 77);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 3 SI
$pdf->SetXY(280, 77);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(285.5, 41, 285.5, 100); //VERTICAL

// Trab 3 NO
$pdf->SetXY(286, 77);
$pdf->Cell(0, 0, utf8_decode('NO'));
$pdf->Line(31, 80, 292, 80); //HORIZONTAL

// OTRO:
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(31, 82);
$pdf->Cell(0, 0, utf8_decode('OTRO:'));
// respuesta de otro
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(31, 84);
$pdf->MultiCell(35, 3, utf8_decode('datos de aspecto'));


// respuesta de impactos, OTRO
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(66, 81);
$pdf->MultiCell(26, 3, utf8_decode('datos de impacto'));

// X 1
$pdf->SetXY(93, 90);
$pdf->Cell(0, 0, 'X');

// X 2
$pdf->SetXY(97, 90);
$pdf->Cell(0, 0, 'X');

// X 3
$pdf->SetXY(101, 90);
$pdf->Cell(0, 0, 'X');

// X 4
$pdf->SetXY(105, 90);
$pdf->Cell(0, 0, 'X');

// X 5
$pdf->SetXY(109, 90);
$pdf->Cell(0, 0, 'X');

// APLICA PARA PROCESO DE PODA:
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(113, 83);
$pdf->Cell(0, 0, utf8_decode('APLICA PARA PROCESO DE PODA:'));
// ¿Se diligencia el permiso a predios privados en los casos que se requiera para le ejecución
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(157, 83);
$pdf->Cell(0, 0, utf8_decode('¿Se diligencia el permiso a predios privados en los casos que se requiera para le'));
// ejecución de la actividad, se revisan las condiciones fitosanitarias
$pdf->SetXY(113, 84);
$pdf->MultiCell(140, 3, utf8_decode('ejecución de la actividad, se revisan las condiciones fitosanitarias, identificando la especie y estructura física del árbol; Se realiza corte por debajo de la rama a 30 o 60 cm hacia afuera del tronco y luego realizar corte por encima con el fin de evitar cortes mal hechos y al final se realiza el corte definitivo para que el labio cicatrizador pueda hacer su proceso natural de sellar el corte; Se repica y se dispone adecuada del material vegetal resultante?'), 0, 'C');

// Trab 1 SI
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(254, 90);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(259.5, 41, 259.5, 100); //VERTICAL

// Trab 1 NO
$pdf->SetXY(260, 90);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 2 SI
$pdf->SetXY(267, 90);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(272.5, 41, 272.5, 100); //VERTICAL

// Trab 2 NO
$pdf->SetXY(273, 90);
$pdf->Cell(0, 0, utf8_decode('NO'));

// Trab 3 SI
$pdf->SetXY(280, 90);
$pdf->Cell(0, 0, utf8_decode('SI'));
$pdf->Line(285.5, 41, 285.5, 100); //VERTICAL

// Trab 3 NO
$pdf->SetXY(286, 90);
$pdf->Cell(0, 0, utf8_decode('NO'));
$pdf->Line(5, 100, 292, 100); //HORIZONTAL

// Confirmo que el lugar de trabajo y la información diligenciada han sido revisados y examinados, adicionalmente las precauciones señaladas han sido cumplidas; soy responsable de darle cumplimiento a las controles establecidos anteriormente
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetXY(70, 101);
$pdf->MultiCell(180, 3, utf8_decode('Confirmo que el lugar de trabajo y la información diligenciada han sido revisados y examinados, adicionalmente las precauciones señaladas han sido cumplidas; soy responsable de darle cumplimiento a las controles establecidos anteriormente'), 0, 'C');
$pdf->Line(5, 107, 292, 107); //HORIZONTAL

// PARTICIPANTES
$pdf->PaintTextBackground(5, 107, utf8_decode('PARTICIPANTES'), [35, 175, 216], 0, 287, 4);
$pdf->Line(5, 111, 292, 111); //HORIZONTAL

// #
$pdf->PaintTextBackground(5, 111, utf8_decode('#'), [35, 175, 216], 0, 4, 6);
$pdf->Line(9, 111, 9, 144); //VERTICAL
$pdf->Line(5, 117, 292, 117); //HORIZONTAL

// Nombre
$pdf->PaintTextBackground(9, 111, utf8_decode('Nombre'), [35, 175, 216], 0, 50, 5.8);
$pdf->Line(59, 111, 59, 162); //VERTICAL

// Cargo
$pdf->PaintTextBackground(59, 111, utf8_decode('Cargo'), [35, 175, 216], 0, 40, 5.8);
$pdf->Line(99, 111, 99, 162); //VERTICAL

// Firma
$pdf->PaintTextBackground(99, 111, utf8_decode('Firma'), [35, 175, 216], 0, 35, 5.8);
$pdf->Line(134, 111, 134, 144); //VERTICAL

// OBSERVACIONES y/o NOVEDADES ( si aplica)
$pdf->PaintTextBackground(134, 111, utf8_decode('OBSERVACIONES y/o NOVEDADES ( si aplica)'), [35, 175, 216], 0, 158, 5.8);

// 1
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(5, 122);
$pdf->Cell(0, 0, '1');

// NOMBRE
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(14, 118);
$pdf->MultiCell(33, 4, utf8_encode('juan david martinez ordosgoitia'));

// CARGO
$pdf->SetXY( 60, 118);
$pdf->MultiCell(33, 4, utf8_encode('BRIGADA'));

// FIRMA
$pdf->SetXY(104, 120);
$pdf->MultiCell(33, 4, utf8_encode('Juan Martinez'));
$pdf->Line(5, 126, 134, 126); //HORIZONTAL

// 2
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(5, 131);
$pdf->Cell(0, 0, '2');

// NOMBRE
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(14, 127);
$pdf->MultiCell(33, 4, utf8_encode('juan david martinez ordosgoitia'));

// CARGO
$pdf->SetXY(60, 128);
$pdf->MultiCell(33, 4, utf8_encode('BRIGADA'));

// FIRMA
$pdf->SetXY(104, 129);
$pdf->MultiCell(33, 4, utf8_encode('Juan Martinez'));
$pdf->Line(5, 135, 134, 135); //HORIZONTAL

// 3
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(5, 140);
$pdf->Cell(0, 0, '3');

// NOMBRE
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(14, 136);
$pdf->MultiCell(33, 4, utf8_encode('juan david martinez ordosgoitia'));

// CARGO
$pdf->SetXY(60, 136);
$pdf->MultiCell(33, 4, utf8_encode('BRIGADA'));

// FIRMA
$pdf->SetXY(104, 138);
$pdf->MultiCell(33, 4, utf8_encode('Juan Martinez'));
$pdf->Line(5, 144, 292, 144); //HORIZONTAL


// FIRMA DEL TECNICO O TRABAJADOR ENCARGADO No DE IDENTEFICACION 
$pdf->SetFont('Arial', 'B', 7);
$pdf->PaintTextBackground(5, 144, utf8_decode('FIRMA DEL TECNICO O TRABAJADOR'), [35, 175, 216], 0, 53.8, 13);
// ENCARGADO
$pdf->PaintTextBackground(5, 152, utf8_decode('ENCARGADO'), [35, 175, 216], 0, 53.8, 5.8);
// No DE IDENTEFICACION 
$pdf->PaintTextBackground(5, 156, utf8_decode('No DE IDENTEFICACION'), [35, 175, 216], 0, 53.8, 5.8);


// Firma:
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(59, 149);
$pdf->MultiCell(33, 4, utf8_encode('Firma:'));
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(70, 149);
$pdf->MultiCell(33, 4, utf8_encode('Juan Martinez'));
$pdf->Line(59, 153, 99, 153); //HORIZONTAL

// CC
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(59, 158);
$pdf->MultiCell(33, 4, utf8_encode('CC:'));
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(70, 158);
$pdf->MultiCell(33, 4, utf8_encode('123456732'));

// FIRMA DEL SUPERVISOR / CAPATAZ  
$pdf->SetFont('Arial', 'B', 7);
$pdf->PaintTextBackground(99, 144, utf8_decode('FIRMA DEL SUPERVISOR / CAPATAZ'), [35, 175, 216], 0, 53.8, 13);
// (OPCIONAL)
$pdf->PaintTextBackground(99, 152, utf8_decode('(OPCIONAL)'), [35, 175, 216], 0, 53.8, 5.8);
// No DE IDENTIFICACION
$pdf->PaintTextBackground(99, 156, utf8_decode('No DE IDENTIFICACION'), [35, 175, 216], 0, 53.8, 5.8);
$pdf->Line(153, 144, 153, 162); //VERTICAL
// Firma:
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(153, 149);
$pdf->MultiCell(33, 4, utf8_encode('Firma:'));
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(162, 149);
$pdf->MultiCell(33, 4, utf8_encode('Juan Martinez'));
$pdf->Line(153, 153, 195, 153); //HORIZONTAL

// CC
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(153, 158);
$pdf->MultiCell(33, 4, utf8_encode('CC:'));
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(162, 158);
$pdf->MultiCell(33, 4, utf8_encode('123456732'));
$pdf->Line(195, 144, 195, 162); //VERTICAL

// FIRMA DEL SST (OPCIONAL)
$pdf->SetFont('Arial', 'B', 7);
$pdf->PaintTextBackground(195, 144, utf8_decode('FIRMA DEL SST (OPCIONAL)'), [35, 175, 216], 0, 53.8, 14);
// No DE IDENTEFICACION 
$pdf->PaintTextBackground(195, 152, utf8_decode('No DE IDENTEFICACION '), [35, 175, 216], 0, 53.8, 10);
$pdf->Line(249, 117, 249, 162); //VERTICAL

// Firma:
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(249, 149);
$pdf->MultiCell(33, 4, utf8_encode('Firma:'));
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(259, 149);
$pdf->MultiCell(33, 4, utf8_encode('Juan Martinez'));
$pdf->Line(249, 153, 292, 153); //HORIZONTAL

// CC
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(249, 158);
$pdf->MultiCell(33, 4, utf8_encode('CC:'));
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(259, 158);
$pdf->MultiCell(33, 4, utf8_encode('123456732'));

// Firma del Resp. Brigada
$pdf->SetFont('Arial', '', 7);
$pdf->Line(234, 117, 234, 144); //VERTICAL
$pdf->SetXY(234, 124);
$pdf->MultiCell(15, 4, utf8_encode('Firma del Resp. Brigada'), 0, 'C');

// Firma responsabe brigaada
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(259, 125);
$pdf->MultiCell(33, 4, utf8_encode('Juan Martinez'));
$pdf->Line(249, 135, 292, 135); //HORIZONTAL
// CC
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(249, 140);
$pdf->MultiCell(33, 4, utf8_encode('CC:'));
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(259, 140);
$pdf->MultiCell(33, 4, utf8_encode('123456732'));

// DATOS DE OBSERVACIONES y/o NOVEDADES ( si aplica)
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(137, 120);
$pdf->MultiCell(90, 4, utf8_encode('OBSERVACIONES'), 0, 'C');


// Salida del archivo PDF
$pdf->Output();
