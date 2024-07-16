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
        $this->SetTextColor($textColor[0],
            $textColor[1],
            $textColor[2]
        );
        $this->Cell($textWidth, 8, $text, 0, 0, 'C', false);

        // Restablecer el color del texto a negro
        $this->SetTextColor(0, 0,
            0
        );
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage('', 'LETTER');

// Dibujar el marco del formulario con bordes redondeados
$pdf->RoundedRect(5, 10, 206, 158, 0, 'D');

$pdf->Image('./servicer.jpeg', 5, 10, 42);

// INSPECCIÓN KIT DE CARRETERA
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY(65, 14);
$pdf->MultiCell(90, 3, utf8_decode("INSPECCIÓN KIT DE CARRETERA"), 0, 'C');
$pdf->Line(47, 20, 211, 20); //HORIZONTAL

// GESTIÓN DE SEGURIDAD VIAL
$pdf->SetXY(85, 25);
$pdf->Cell(0, 0, utf8_decode("GESTIÓN DE SEGURIDAD VIAL"));

// TEXTO CON FONDE DE COLOR
$pdf->SetFont('Arial', 'B', 7);
$pdf->PaintTextBackground(5, 27, utf8_decode('ELABORÓ / ACTUALIZÓ'), [35, 175, 216],[255, 255, 255], 0, 42, 4);
$pdf->PaintTextBackground(47, 27, utf8_decode('REVISÓ'), [35, 175, 216],[255, 255, 255], 0, 133, 4);

// APROBO
$pdf->PaintTextBackground(180, 27, utf8_decode('APROBÓ'), [35, 175, 216], [255, 255, 255], 0, 31, 4);

// LINEAL VERTICAL QUE ESTA AL LADO DE LA IMAGEN
$pdf->Line(47, 10, 47, 35); // VERTICAL

// codigo
$pdf->Line(180, 10, 180, 35); //VERTICAL
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(181, 13);
$pdf->Cell(0, 0, utf8_decode("Código:"));

//  F-GSV-11
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(193, 13);
$pdf->Cell(0, 0, utf8_decode("F-GSV-11"));

$pdf->Line(180, 15, 211, 15); //HORIZONTAL

// FECHA
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(181, 13);
$pdf->Cell(0, 10, "Fecha:");

// 16/02/2024
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(193, 13);
$pdf->Cell(0, 10, "16/02/2024");

// VERSION
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(181, 13);
$pdf->Cell(0, 18, utf8_decode("Versión:"));

// 00
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(193, 13);
$pdf->Cell(0, 18, " 01");
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

// CLASIFICACION
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(38, 37);
$pdf->Cell(0, 0, utf8_decode("CLASIFICACIÓN"));
$pdf->Line(93, 35, 93, 55); //VERTICAL

// AREA O CENTRO DE TRABAJO 
$pdf->SetXY(95, 37);
$pdf->Cell(0, 0, utf8_decode("ÁREA O CENTRO DE TRABAJO"));
$pdf->Line(140, 35, 140, 55); //VERTICAL

// FECHA APERTURA H.V
$pdf->SetXY(145, 37);
$pdf->Cell(0, 0, utf8_decode("FECHA APERTURA H.V"));
$pdf->Line(180, 35, 180, 48); //VERTICAL

// RESPONSABLE:
$pdf->SetXY(185, 37);
$pdf->Cell(0, 0, utf8_decode("RESPONSABLE:"));
$pdf->Line(5, 39, 211, 39); //HORIZONTAL

// VEHÍCULO LIVIANO
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(6, 41.3);
$pdf->Cell(6, 4, '', 1, 0, 'C');
$pdf->SetFont("Arial", "", 5.7);
$pdf->SetXY(12, 43.6);
$pdf->Cell(0, 0, utf8_decode("VEHÍCULO LIVIANO"));
$pdf->Line(32.2, 39, 32.2, 48); //VERTICAL

// VEHICULO PESADO 
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(33, 41.3);
$pdf->Cell(6, 4, '', 1, 0, 'C');
$pdf->SetFont("Arial", "", 5.7);
$pdf->SetXY(39, 43.6);
$pdf->Cell(0, 0, utf8_decode("VEHICULO PESADO "));
$pdf->Line(59.6, 39, 59.6, 48); //VERTICAL

// OTRO:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(60.5, 41.3);
$pdf->Cell(6, 4, '', 1, 0, 'C');
$pdf->SetFont("Arial", "", 5.7);
$pdf->SetXY(67, 43.6);
$pdf->Cell(0, 0, utf8_decode("OTRO:"));
$pdf->SetFont("Arial", "", 6);
$pdf->SetXY(74, 43.6);
$pdf->Cell(0, 0, utf8_decode('SDFFGHFGH'));
$pdf->Line(75, 44.5, 93, 44.5); //HORIZONTAL

// DATOS DE AREA O CENTRO DE TRABAJO
$pdf->SetFont("Arial", "", 6);
$pdf->SetXY(95, 40);
$pdf->MultiCell(43, 3, utf8_decode("DFSDFGHFDG"));

// DATOS FECHA APERTURA H.V
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(144, 43);
$pdf->Cell(0, 0, 'DD');
$pdf->Line(153.3, 39, 153.3, 48); //VERTICAL

// MM
$pdf->SetXY(156, 43);
$pdf->Cell(0, 0, 'MM');
$pdf->Line(166.6, 39, 166.6, 48); //VERTICAL

// AA
$pdf->SetXY(170, 43);
$pdf->Cell(0, 0, 'AA');

// DATOS RESPONSABLE
$pdf->SetFont("Arial", "", 6);
$pdf->SetXY(183, 43);
$pdf->Cell(0, 0, utf8_decode("SDFGHJ"));
$pdf->Line(5, 48, 211, 48); //HORIZONTAL

// LUGAR DE UBICACIÓN 
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(6, 52);
$pdf->Cell(0, 0, utf8_decode("LUGAR DE UBICACIÓN"));

// LUGAR
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(35, 52);
$pdf->Cell(0, 0, utf8_decode("LUGAR DE UBICACIÓN"));

// PLACA VEHICULO 
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(93, 52);
$pdf->Cell(0, 0, utf8_decode("PLACA VEHÍCULO"));

// PLACA
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(119, 52);
$pdf->Cell(0, 0, utf8_decode("ABC123"));

// CONDUCTOR DEL VEHÍCULO:
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(140, 52);
$pdf->Cell(0, 0, utf8_decode("CONDUCTOR DEL VEHÍCULO:"));

// CONDUCTOR
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(178, 52);
$pdf->Cell(0, 0, utf8_decode("Juan Martinez"));
$pdf->Line(178, 53, 208, 53); //HORIZONTAL
$pdf->Line(5, 55, 211, 55); //HORIZONTAL

// Los siguientes elementos deben ser evaluacios de acuerdo a los siguientes criterios: 
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(6, 58);
$pdf->Cell(0, 0, utf8_decode("Los siguientes elementos deben ser evaluados de acuerdo a los siguientes criterios:"));

// C: Cumple 
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(107, 58);
$pdf->Cell(0, 0, utf8_decode("C: Cumple"));

// NC: No Cumple 
$pdf->SetXY(125, 58);
$pdf->Cell(0, 0, utf8_decode("NC: No Cumple"));

// NA: No Aplica
$pdf->SetXY(148, 58);
$pdf->Cell(0, 0, utf8_decode("NA: No Aplica"));
$pdf->Line(5, 60, 211, 60); //HORIZONTAL

// DESCRIPCION DEL ELEMENTO
$pdf->SetFont("Arial", "B", 6);
$pdf->PaintTextBackground(5, 60, utf8_decode('DESCRIPCION DEL ELEMENTO'), [184, 183, 183], [0, 0, 0], 0, 50, 8);
$pdf->Line(55, 60, 55, 128); //VERTICCAL

// CANTIDAD
$pdf->PaintTextBackground(55, 60, utf8_decode('CANTIDAD'), [184, 183, 183], [0, 0, 0], 0, 17, 8);
$pdf->Line(71.8, 60, 71.8, 128); //VERTICCAL

// FECHA DE
$pdf->PaintTextBackground(72, 60, utf8_decode('FECHA DE'), [184, 183, 183], [0, 0, 0], 0, 20, 5);
// VENCIMIENTO
$pdf->PaintTextBackground(72, 64, utf8_decode('VENCIMIENTO'), [184, 183, 183], [0, 0, 0], 0, 20, 4);
$pdf->Line(91.7, 60, 91.7, 128); //VERTICCAL

// INSPECCION 1
$pdf->PaintTextBackground(92, 60, utf8_decode('INSPECCION 1'), [184, 183, 183], [0, 0, 0], 0, 20, 4);

// CANT. CRITERIO
$pdf->PaintTextBackground(92, 64, utf8_decode('CANT.  CRITERIO'), [184, 183, 183], [0, 0, 0], 0, 20, 4);
$pdf->Line(100.3, 64, 100.3, 128); //VERTICCAL
$pdf->Line(111.7, 60, 111.7, 128); //VERTICCAL

// INSPECCION 2
$pdf->PaintTextBackground(112, 60, utf8_decode('INSPECCION 2'), [184, 183, 183], [0, 0, 0], 0, 20, 4);

// CANT. CRITERIO
$pdf->PaintTextBackground(112, 64, utf8_decode('CANT.  CRITERIO'), [184, 183, 183], [0, 0, 0], 0, 20, 4);
$pdf->Line(120.4, 64, 120.4, 128); //VERTICCAL
$pdf->Line(131.7, 60, 131.7, 128); //VERTICCAL

// INSPECCION 3
$pdf->PaintTextBackground(132, 60, utf8_decode('INSPECCION 3'), [184, 183, 183], [0, 0, 0], 0, 20, 4);

// CANT. CRITERIO
$pdf->PaintTextBackground(132, 64, utf8_decode('CANT.  CRITERIO'), [184, 183, 183], [0, 0, 0], 0, 20, 4);
$pdf->Line(140.3, 64, 140.3, 128); //VERTICCAL
$pdf->Line(151.7, 60, 151.7, 128); //VERTICCAL

// INSPECCION 4
$pdf->PaintTextBackground(152, 60, utf8_decode('INSPECCION 4'), [184, 183, 183], [0, 0, 0], 0, 20, 4);

// CANT. CRITERIO
$pdf->PaintTextBackground(152, 64, utf8_decode('CANT.  CRITERIO'), [184, 183, 183], [0, 0, 0], 0, 20, 4);
$pdf->Line(160.3, 64, 160.3, 128); //VERTICCAL
$pdf->Line(171.7, 60, 171.7, 128); //VERTICCAL

// INSPECCION 5
$pdf->PaintTextBackground(172, 60, utf8_decode('INSPECCION 5'), [184, 183, 183], [0, 0, 0], 0, 20, 4);

// CANT. CRITERIO
$pdf->PaintTextBackground(172, 64, utf8_decode('CANT.  CRITERIO'), [184, 183, 183], [0, 0, 0], 0, 20, 4);
$pdf->Line(180.3, 64, 180.3, 128); //VERTICCAL
$pdf->Line(191, 60, 191, 128); //VERTICCAL

// INSPECCION 5
$pdf->PaintTextBackground(191, 60, utf8_decode('INSPECCION 5'), [184, 183, 183], [0, 0, 0], 0, 20, 4);
$pdf->Line(92, 64, 211, 64); //HORIZONTAL
// CANT. CRITERIO
$pdf->PaintTextBackground(191, 64, utf8_decode('CANT.  CRITERIO'), [184, 183, 183], [0, 0, 0], 0, 20, 4);
$pdf->Line(199.3, 64, 199.3, 128); //VERTICCAL
$pdf->Line(5, 68, 211, 68); //HORIZONTAL

// Gato
$pdf->SetFont("Arial", "", 7);
$pdf->SetXY(6, 71);
$pdf->Cell(0, 0, utf8_decode("Gato"));

// 1
$pdf->SetXY(62, 71);
$pdf->Cell(0, 0, utf8_decode("1"));

// fecha
$pdf->SetXY(79, 71);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(93, 71);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 71);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 71);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 71);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 71);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 71);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 71);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 71);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 71);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 71);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 71);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 71);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 73, 211, 73); //HORIZONTAL

// Cruceta
$pdf->SetXY(6, 76);
$pdf->Cell(0, 0, utf8_decode("Cruceta"));

// CANTIDAD
$pdf->SetXY(62, 76);
$pdf->Cell(0, 0, utf8_decode("1"));

// FECHA
$pdf->SetXY(79, 76);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(93, 76);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 76);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 76);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 76);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 76);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 76);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 76);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 76);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 76);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 76);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 76);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 76);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 78, 211, 78); //HORIZONTAL

// Señales de carretera reflectiva 
$pdf->SetXY(6, 81);
$pdf->Cell(0, 0, utf8_decode("Señales de carretera reflectiva"));

// CANTIDAD
$pdf->SetXY(62, 81);
$pdf->Cell(0, 0, utf8_decode("1"));

// FECHA
$pdf->SetXY(79, 81);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(93, 81);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 81);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 81);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 81);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 81);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 81);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 81);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 81);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 81);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 81);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 81);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 81);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 83, 211, 83); //HORIZONTAL

// Extintor Multiproposito 
$pdf->SetXY(6, 86);
$pdf->Cell(0, 0, utf8_decode("Extintor Multiproposito"));

// CANTIDAD
$pdf->SetXY(62, 86);
$pdf->Cell(0, 0, utf8_decode("1"));

// FECHA
$pdf->SetXY(74, 86);
$pdf->Cell(0, 0, utf8_decode("DD/MM/AA"));

// CANT
$pdf->SetXY(93, 86);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 86);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 86);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 86);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 86);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 86);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 86);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 86);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 86);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 86);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 86);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 86);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 88, 211, 88); //HORIZONTAL

// Tacos
$pdf->SetXY(6, 91);
$pdf->Cell(0, 0, utf8_decode("Tacos"));

// CANTIDAD
$pdf->SetXY(62, 91);
$pdf->Cell(0, 0, utf8_decode("2"));

// FECHA
$pdf->SetXY(79, 91);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(93, 91);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 91);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 91);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 91);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 91);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 91);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 91);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 91);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 91);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 91);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 91);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 91);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 93, 211, 93); //HORIZONTAL

// Caja de herramientas
$pdf->SetXY(6, 96);
$pdf->Cell(0, 0, utf8_decode("Caja de herramientas"));

// CANTIDAD
$pdf->SetXY(62, 96);
$pdf->Cell(0, 0, utf8_decode("1"));

// FECHA
$pdf->SetXY(79, 96);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(93, 96);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 96);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 96);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 96);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 96);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 96);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 96);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 96);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 96);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 96);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 96);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 96);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 98, 211, 98); //HORIZONTAL

// Alicate
$pdf->SetXY(6, 101);
$pdf->Cell(0, 0, utf8_decode("Alicate"));

// CANTIDAD
$pdf->SetXY(62, 101);
$pdf->Cell(0, 0, utf8_decode("1"));

// FECHA
$pdf->SetXY(79, 101);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(93, 101);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 101);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 101);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 101);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 101);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 101);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 101);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 101);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 101);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 101);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 101);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 101);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 103, 211, 103); //HORIZONTAL

// destornilladores
$pdf->SetXY(6, 106);
$pdf->Cell(0, 0, utf8_decode("Destornilladores"));

// CANTIDAD
$pdf->SetXY(62, 106);
$pdf->Cell(0, 0, utf8_decode("1"));

// FECHA
$pdf->SetXY(79, 106);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(93, 106);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 106);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 106);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 106);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 106);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 106);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 106);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 106);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 106);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 106);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 106);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 106);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 108, 211, 108); //HORIZONTAL

// llave de expansión
$pdf->SetXY(6, 111);
$pdf->Cell(0, 0, utf8_decode("Llave de expansión"));

// CANTIDAD
$pdf->SetXY(62, 111);
$pdf->Cell(0, 0, utf8_decode("1"));

// FECHA
$pdf->SetXY(79, 111);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(93, 111);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 111);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 111);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 111);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 111);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 111);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 111);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 111);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 111);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 111);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 111);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 111);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 113, 211, 113); //HORIZONTAL

// Juego de llaves fijas
$pdf->SetXY(6, 116);
$pdf->Cell(0, 0, utf8_decode("Juego de llaves fijas"));

// CANTIDAD
$pdf->SetXY(62, 116);
$pdf->Cell(0, 0, utf8_decode("1"));

// FECHA
$pdf->SetXY(79, 116);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(93, 116);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 116);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 116);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 116);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 116);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 116);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 116);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 116);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 116);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 116);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 116);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 116);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 118, 211, 118); //HORIZONTAL

// Llanta de respuesto
$pdf->SetXY(6, 121);
$pdf->Cell(0, 0, utf8_decode("Llanta de respuesto"));

// CANTIDAD
$pdf->SetXY(62, 121);
$pdf->Cell(0, 0, utf8_decode("1"));

// FECHA
$pdf->SetXY(79, 121);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(93, 121);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 121);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 121);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 121);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 121);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 121);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 121);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 121);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 121);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 121);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 121);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 121);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 123, 211, 123); //HORIZONTAL

// Linterna Con pilas
$pdf->SetXY(6, 126);
$pdf->Cell(0, 0, utf8_decode("Linterna Con pilas"));

// CANTIDAD
$pdf->SetXY(62, 126);
$pdf->Cell(0, 0, utf8_decode("1"));

// FECHA
$pdf->SetXY(74, 126);
$pdf->Cell(0, 0, utf8_decode("DD/MM/AA"));

// CANT
$pdf->SetXY(93, 126);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(103, 126);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(113, 126);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(123, 126);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(133, 126);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(143, 126);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(153, 126);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(163, 126);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(173, 126);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(183, 126);
$pdf->Cell(0, 0, utf8_decode("NA"));

// CANT
$pdf->SetXY(193, 126);
$pdf->Cell(0, 0, 'NA');

// CRITERIO
$pdf->SetXY(203, 126);
$pdf->Cell(0, 0, utf8_decode("NA"));
$pdf->Line(5, 128, 211, 128); //HORIZONTAL

// No. DE INSPECCION
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(5, 128, utf8_decode('No. DE INSPECCION'), [184, 183, 183], [0, 0, 0], 0, 37, 4);
$pdf->Line(42, 128, 42, 168); //VERTICAL

// FECHA DE INSPECCION
$pdf->PaintTextBackground(42, 128, utf8_decode('FECHA DE INSPECCION'), [184, 183, 183], [0, 0, 0], 0, 32, 4);
$pdf->Line(74, 128, 74, 168); //VERTICAL

// RESPONSABLE DE LA INSPECCION 
$pdf->PaintTextBackground(74, 128, utf8_decode('RESPONSABLE DE LA INSPECCION'), [184, 183, 183], [0, 0, 0], 0, 69, 4);
$pdf->Line(142.5, 128, 142.5, 168); //VERTICAL

// OBSERVACIONES
$pdf->PaintTextBackground(142.5, 128, utf8_decode('OBSERVACIONES'), [184, 183, 183], [0, 0, 0], 0, 68.4, 4);
$pdf->Line(5, 132, 211, 132); //HORIZONTAL


// INSPECCION 1
$pdf->PaintTextBackground(5, 132, utf8_decode('INSPECCION 1'), [184, 183, 183], [0, 0, 0], 0, 36.8, 6);

// FECHA INSPECCION DD/MM/AA
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(50, 135);
$pdf->Cell(0, 0, utf8_decode("DD/MM/AA"));

// RESPONSABLE
$pdf->SetXY(80, 135);
$pdf->Cell(0, 0, utf8_decode("Juan Martinez"));

// OBSERVACIONES
$pdf->SetXY(145, 133);
$pdf->MultiCell(64, 3, utf8_decode("NA"));
$pdf->Line(5, 138, 211, 138); //HORIZONTAL

// INSPECCION 2
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(5, 138, utf8_decode('INSPECCION 2'), [184, 183, 183], [0, 0, 0], 0, 36.8, 6);

// FECHA INSPECCION DD/MM/AA
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(50, 141);
$pdf->Cell(0, 0, utf8_decode("DD/MM/AA"));

// RESPONSABLE
$pdf->SetXY(80, 141);
$pdf->Cell(0, 0, utf8_decode("Juan Martinez"));

// OBSERVACIONES
$pdf->SetXY(145, 139);
$pdf->MultiCell(64, 3, utf8_decode("NA"));
$pdf->Line(5, 144, 211, 144); //HORIZONTAL

// INSPECCION 3
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(5, 144, utf8_decode('INSPECCION 3'), [184, 183, 183], [0, 0, 0], 0, 36.8, 6);

// FECHA INSPECCION DD/MM/AA
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(50, 147);
$pdf->Cell(0, 0, utf8_decode("DD/MM/AA"));

// RESPONSABLE
$pdf->SetXY(80, 147);
$pdf->Cell(0, 0, utf8_decode("Juan Martinez"));

// OBSERVACIONES
$pdf->SetXY(145, 145);
$pdf->MultiCell(64, 3, utf8_decode("NA"));
$pdf->Line(5, 150, 211, 150); //HORIZONTAL

// INSPECCION 4
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(5, 150, utf8_decode('INSPECCION 4'), [184, 183, 183], [0, 0, 0], 0, 36.8, 6);

// FECHA INSPECCION DD/MM/AA
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(50, 153);
$pdf->Cell(0, 0, utf8_decode("DD/MM/AA"));

// RESPONSABLE
$pdf->SetXY(80, 153);
$pdf->Cell(0, 0, utf8_decode("Juan Martinez"));

// OBSERVACIONES
$pdf->SetXY(145, 151);
$pdf->MultiCell(64, 3, utf8_decode("NA"));
$pdf->Line(5, 156, 211, 156); //HORIZONTAL

// INSPECCION 5
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(5, 156, utf8_decode('INSPECCION 5'), [184, 183, 183], [0, 0, 0], 0, 36.8, 6);

// FECHA INSPECCION DD/MM/AA
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(50, 159);
$pdf->Cell(0, 0, utf8_decode("DD/MM/AA"));

// RESPONSABLE
$pdf->SetXY(80, 159);
$pdf->Cell(0, 0, utf8_decode("Juan Martinez"));

// OBSERVACIONES
$pdf->SetXY(145, 157);
$pdf->MultiCell(64, 3, utf8_decode("NA"));
$pdf->Line(5, 162, 211, 162); //HORIZONTAL

// No. DE INSPECCION 6
$pdf->SetFont("Arial", "B", 7);
$pdf->PaintTextBackground(5, 162, utf8_decode('INSPECCION 6'), [184, 183, 183], [0, 0, 0], 0, 36.8, 6);

// FECHA INSPECCION DD/MM/AA
$pdf->SetFont('Arial', '', 7);
$pdf->SetXY(50, 165);
$pdf->Cell(0, 0, utf8_decode("DD/MM/AA"));

// RESPONSABLE
$pdf->SetXY(80, 165);
$pdf->Cell(0, 0, utf8_decode("Juan Martinez"));

// OBSERVACIONES
$pdf->SetXY(145, 163);
$pdf->MultiCell(64, 3, utf8_decode("NA"));





// Salida del archivo PDF
$pdf->Output();
