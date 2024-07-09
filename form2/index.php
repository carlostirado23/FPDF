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
$pdf->Line(31, 34, 31, 187);

// RIESGOS Marque con una (X)
$pdf->PaintTextBackground(31, 34, utf8_decode('RIESGOS'), [35, 175, 216], 0, 38, 6);
$pdf->PaintTextBackground(31, 38, utf8_decode('Marque con una (X'), [35, 175, 216], 0, 38, 5);
$pdf->Line(69, 34, 69, 187);

// INCIDENCIA / DESCARGO / AVISO
$pdf->PaintTextBackground(69, 34, utf8_decode('INCIDENCIA / DESCARGO / AVISO'), [35, 175, 216], 0, 42, 5.5);
$pdf->PaintTextBackground(69, 39, utf8_decode('1          2          3          4          5'), [35, 175, 216], 0, 42, 4);
$pdf->Line(69, 39, 111, 39); //HORIZONTAL
// 1 
$pdf->Line(77.4, 39, 77.4, 187); //VERTICAL

// 2
$pdf->Line(85.8, 39, 85.8, 187); //VERTICAL

// 3
$pdf->Line(94.2, 39, 94.2, 187); //VERTICAL

// 4
$pdf->Line(102.6, 39, 102.6, 187); //VERTICAL
$pdf->Line(111, 34, 111, 187); //VERTICAL
$pdf->Line(31, 43, 111, 43); //HORIZONTAL

// CONSECUENCIAS
$pdf->PaintTextBackground(111, 34, utf8_decode('CONSECUENCIAS'), [35, 175, 216], 0, 80, 9);
$pdf->Line(191, 34, 191, 187);

// CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES
$pdf->PaintTextBackground(191, 34, utf8_decode('CONTROLES EN EL ENTORNO Y SIMULTANEAS Y POSTERIORES'), [35, 175, 216], 0, 101, 9);



 

// Eléctrico
$pdf->SetXY(11, 70);
$pdf->Cell(0, 0, utf8_decode('Eléctrico'));

// Añadir el texto con ajuste de ancho
$pdf->SetXY(112, 60);
$pdf->MultiCell(75, 3, utf8_decode('Quemaduras superficiales de la piel, quemaduras internas, Tetanizaciones musculares, traumas respiratorios, fisiciologicos, psicologicos, cardiacos, Trauma de variada severidad, perdida material, de organos y miembros del cuerpo, Daños a la propiedad, y muerte'), 0, "C");

// Salida del archivo PDF
$pdf->Output();
