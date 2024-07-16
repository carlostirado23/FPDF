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
$pdf->AddPage('', 'LEGAL');

// Dibujar el marco del formulario con bordes redondeados
$pdf->RoundedRect(5, 10, 206, 240, 0, 'D');

$pdf->Image('./servicer.jpeg', 6, 11, 40);

// INSPECCIÓN DE EQUIPO PUESTA TIERRA DE MT, BT, Y VEHICULOS
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(65, 14);
$pdf->MultiCell(90, 3, utf8_decode("INSPECCIÓN DE EQUIPO PUESTA TIERRA DE MT, BT, Y VEHICULOS"), 0, 'C');
$pdf->Line(47, 20, 211, 20); //HORIZONTAL

// PROCESO SEGURIDAD Y SALUD EN EL TRABAJO
$pdf->SetXY(73, 25);
$pdf->Cell(0, 0, utf8_decode("PROCESO SEGURIDAD Y SALUD EN EL TRABAJO"));

// TEXTO CON FONDE DE COLOR
$pdf->SetFont('Arial', 'B', 7);
$pdf->PaintTextBackground(5, 27, utf8_decode('ELABORÓ / ACTUALIZÓ'), [35, 175, 216], 0, 42, 4);
$pdf->PaintTextBackground(47, 27, utf8_decode('REVISÓ'), [35, 175, 216], 0, 133, 4);

// APROBO
$pdf->PaintTextBackground(180, 27, utf8_decode('APROBÓ'), [35, 175, 216], 0, 31, 4);

// LINEAL VERTICAL QUE ESTA AL LADO DE LA IMAGEN
$pdf->Line(47, 10, 47, 35); // VERTICAL

// codigo
$pdf->Line(180, 10, 180, 35); //VERTICAL
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(181, 13);
$pdf->Cell(0, 0, utf8_decode("Código:"));

//  F-SST-31
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(193, 13);
$pdf->Cell(0, 0, utf8_decode(" F-SST-31"));

$pdf->Line(180, 15, 211, 15); //HORIZONTAL

// FECHA
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(181, 13);
$pdf->Cell(0, 10, "Fecha:");

// 20/01/2024
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(193, 13);
$pdf->Cell(0, 10, "20/01/2024");

// VERSION
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(181, 13);
$pdf->Cell(0, 18, utf8_decode("Versión:"));

// 00
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(193, 13);
$pdf->Cell(0, 18, "00");
$pdf->Line(180, 23.5, 211, 23.5); //HORIZAONTAL

// VERSION
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(181, 13);
$pdf->Cell(0, 25, utf8_decode("Página:"));

// 1 de 1
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(193, 13);
$pdf->Cell(0, 25, "1 de 1");
$pdf->Line(5, 27, 211, 27); //HORIZAONTAL
$pdf->Line(5, 31, 211, 31); //HORIZAONTAL

// auxiliar de calidad
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(10, 33);
$pdf->Cell(0, 0, "Auxiliar de calidad");

// LIDER SGI
$pdf->SetXY(100, 33);
$pdf->Cell(0, 0, "Lider SGI");

// GERENTE
$pdf->SetXY(190, 33);
$pdf->Cell(0, 0, "Gerente");
$pdf->Line(5, 35, 211, 35); //HORIZONTAL


// Salida del archivo PDF
$pdf->Output();
