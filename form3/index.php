<?php
// F-SST-31_Ver 00_Inspección De Equipo Puesta Tierra De Mt, Bt, Y Vehiculos

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

// CIUDAD
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(7, 38);
$pdf->Cell(0, 0, "CIUDAD:");
// CIUDAD INTRODUCIDA
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(25, 38);
$pdf->Cell(0, 0, "Monteria");
$pdf->Line(20, 39, 70, 39); //HORIZONTAL

// FECHA
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(77, 38);
$pdf->Cell(0, 0, "FECHA:");
// FECHA INTRODUCIDA
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(92, 38);
$pdf->Cell(0, 0, "15/07/2021");
$pdf->Line(89, 39, 140, 39); //HORIZONTAL

// SECTOR:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(7, 45);
$pdf->Cell(0, 0, "SECTOR:");
// SECTOR INTRODUCIDA
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(25, 45);
$pdf->Cell(0, 0, "Monteria");
$pdf->Line(21, 46, 75, 46); //HORIZONTAL

// PROYECTO:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(82, 45);
$pdf->Cell(0, 0, "PROYECTO:");
// FECHA INTRODUCIDA
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(103, 45);
$pdf->Cell(0, 0, "Proyecto");
$pdf->Line(100, 46, 150, 46); //HORIZONTAL

// PLACA VEHICULO:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(7, 53);
$pdf->Cell(0, 0, "PLACA VEHICULO:");
// SECTOR INTRODUCIDA
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(37, 53);
$pdf->Cell(0, 0, "ABC123");
$pdf->Line(35, 54, 70, 54); //HORIZONTAL

// TIPO VEHICULO:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(75, 53);
$pdf->Cell(0, 0, "TIPO VEHICULO:");
// FECHA INTRODUCIDA
$pdf->SetFont("Arial", "", 9);
$pdf->SetXY(103, 53);
$pdf->Cell(0, 0, "Proyecto");
$pdf->Line(100, 54, 150, 54); //HORIZONTAL

// TIPO DE EQUIPO:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(7, 61);
$pdf->Cell(0, 0, "TIPO DE EQUIPO:");

// MT
$pdf->SetXY(50, 61);
$pdf->Cell(0, 0, "MT");
// X DE MT
$pdf->SetXY(58, 59);
$pdf->Cell(6, 3.5, "", 1, 0, 'C');

// BT
$pdf->SetXY(67, 61);
$pdf->Cell(0, 0, "BT");
// X DE BT
$pdf->SetXY(74, 59);
$pdf->Cell(6, 3.5, "", 1, 0, 'C');

// AT
$pdf->SetXY(83, 61);
$pdf->Cell(0, 0, "AT");
// X DE AT
$pdf->SetXY(90, 59);
$pdf->Cell(6, 3.5, "", 1, 0, 'C');

// VEHICULO
$pdf->SetXY(99, 61);
$pdf->Cell(0, 0, "VEHICULO");
// X DE VEHICULO
$pdf->SetXY(117, 59);
$pdf->Cell(6, 3.5, "", 1, 0, 'C');

// TENSION
$pdf->SetXY(127, 61);
$pdf->Cell(0, 0, utf8_decode("TENSIÓN"));
// X DE TENSION
$pdf->SetXY(144, 59);
$pdf->Cell(6, 3.5, "", 1, 0, 'C');

// RESPONSABLE DEL EQUIPO:
$pdf->SetXY(7, 70);
$pdf->Cell(0, 0, "RESPONSABLE DEL EQUIPO:");

// RESPONSABLE
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(50, 70);
$pdf->Cell(0, 0, "Juan Martinez");
$pdf->Line(49, 72, 95, 72); //HORIZONTAL

// CARGO:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(101, 70);
$pdf->Cell(0, 0, "CARGO:");

// CARGO 
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(115, 70);
$pdf->Cell(0, 0, "SDFADFASFD");
$pdf->Line(114, 72, 160, 72); //HORIZONTAL

// INSPECTOR
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(7, 78);
$pdf->Cell(0, 0, "INSPECTOR");

// NOMBRE INSPECTOR
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(50, 78);
$pdf->Cell(0, 0, "SDFADFASFD");
$pdf->Line(49, 79, 95, 79); //HORIZONTAL

// CARGO:
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(101, 78);
$pdf->Cell(0, 0, "CARGO:");

// CARGO 
$pdf->SetFont('Arial', '', 9);
$pdf->SetXY(115, 78);
$pdf->Cell(0, 0, "SDFADFASFD");
$pdf->Line(114, 79, 160, 79); //HORIZONTAL

$pdf->Line(5, 82, 211, 82); //HORIZONTAL

// LISTA DE VERIFICACION
$pdf->SetFont("Arial", "B", 8);
$pdf->SetXY(88, 85);
$pdf->Cell(0, 0, "LISTA DE VERIFICACION");
$pdf->Line(5, 88, 211, 88); //HORIZONTAL

// TIPO
$pdf->PaintTextBackground(5, 88, utf8_decode('TIPO'), [35, 175, 216], 0, 69, 4);
$pdf->Line(74, 88, 74, 209); //VERTICAL

// VALORACIÓN
$pdf->PaintTextBackground(74.3, 88, utf8_decode('VALORACIÓN'), [35, 175, 216], 0, 68.4, 4);
$pdf->Line(142.5, 88, 142.5, 209); //VERTICAL

// OBSERVACIONES
$pdf->PaintTextBackground(142.7, 88, utf8_decode('OBSERVACIONES'), [35, 175, 216], 0, 68.4, 4);
$pdf->Line(5, 92, 211, 92); //HORIZONTAL

// 1. EVALUACION DEL ESTADO DEL SPT DE AT, MT
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(8, 96);
$pdf->Cell(0, 0, utf8_decode('1. EVALUACIÓN DEL ESTADO DEL SPT DE AT, MT'));

// BUENO 
$pdf->SetXY(80, 96);
$pdf->Cell(0, 0, utf8_decode('BUENO'));
$pdf->Line(96.8, 92, 96.8, 209); //VERTICAL

// REGULAR
$pdf->SetXY(101, 96);
$pdf->Cell(0, 0, utf8_decode('REGULAR'));
$pdf->Line(119.6, 92, 119.6, 209); //VERTICAL

// MALO
$pdf->SetXY(127, 96);
$pdf->Cell(0, 0, utf8_decode('MALO'));
$pdf->Line(5, 99, 211, 99); //HORIZONTAL

// Juegos De Pinzas
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(6, 102);
$pdf->Cell(0, 0, utf8_decode('Juegos De Pinzas'));

// BUENO
$pdf->SetXY(84, 102);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 102);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 102);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 102);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 104, 211, 104); //HORIZONTAL

// Conductores / Bajantes
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(6, 107);
$pdf->Cell(0, 0, utf8_decode('Conductores / Bajantes'));

// BUENO
$pdf->SetXY(84, 107);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 107);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 107);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 107);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 109, 211, 109); //HORIZONTAL

// Conectores Ponchables
$pdf->SetFont("Arial", "", 8);
$pdf->SetXY(6, 112);
$pdf->Cell(0, 0, utf8_decode('Conectores Ponchables'));

// BUENO
$pdf->SetXY(84, 112);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 112);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 112);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 112);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 114, 211, 114); //HORIZONTAL

// Barrenos
$pdf->SetXY(6, 117);
$pdf->Cell(0, 0, utf8_decode('Barrenos'));

// BUENO
$pdf->SetXY(84, 117);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 117);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 117);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 117);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 119, 211, 119); //HORIZONTAL

// Collarin
$pdf->SetXY(6, 122);
$pdf->Cell(0, 0, utf8_decode('Collarin'));

// BUENO
$pdf->SetXY(84, 122);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 122);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 122);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 122);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 124, 211, 124); //HORIZONTAL

// Pertiga De Escopeta
$pdf->SetXY(6, 127);
$pdf->Cell(0, 0, utf8_decode('Pertiga De Escopeta'));

// BUENO
$pdf->SetXY(84, 127);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 127);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 127);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 127);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 129, 211, 129); //HORIZONTAL

// Puentes
$pdf->SetXY(6, 132);
$pdf->Cell(0, 0, utf8_decode('Puentes'));

// BUENO
$pdf->SetXY(84, 132);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 132);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 132);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 132);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 134, 211, 134); //HORIZONTAL

// Prensas
$pdf->SetXY(6, 137);
$pdf->Cell(0, 0, utf8_decode('Prensas'));

// BUENO
$pdf->SetXY(84, 137);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 137);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 137);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 137);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 139, 211, 139); //HORIZONTAL

// Ausencia DeTension
$pdf->SetXY(6, 142);
$pdf->Cell(0, 0, utf8_decode('Ausencia DeTension'));

// BUENO
$pdf->SetXY(84, 142);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 142);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 142);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 142);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 144, 211, 144); //HORIZONTAL

// Maletin De Transporte
$pdf->SetXY(6, 147);
$pdf->Cell(0, 0, utf8_decode('Maletin De Transporte'));

// BUENO
$pdf->SetXY(84, 147);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 147);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 147);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 147);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 149, 211, 149); //HORIZONTAL

// Guantes primarios Clase Dos
$pdf->SetXY(6, 152);
$pdf->Cell(0, 0, utf8_decode('Guantes primarios Clase Dos'));

// BUENO
$pdf->SetXY(84, 152);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 152);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 152);  
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 152);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 154, 211, 154); //HORIZONTAL


// Limpieza General del Sistema
$pdf->SetXY(6, 157);
$pdf->Cell(0, 0, utf8_decode('Limpieza General del Sistema'));

// BUENO
$pdf->SetXY(84, 157);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 157);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 157);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 157);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 159, 211, 159); //HORIZONTAL

// 2. EVALUACION DEL ESTADO SPT DE BT
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(8, 162);
$pdf->Cell(0, 0, utf8_decode('2. EVALUACIÓN DEL ESTADO SPT DE BT'));

// BUENO
$pdf->SetXY(80, 162);
$pdf->Cell(0, 0, utf8_decode('BUENO'));

// REGULAR
$pdf->SetXY(101, 162);
$pdf->Cell(0, 0, utf8_decode('REGULAR'));

// MALO
$pdf->SetXY(127, 162);
$pdf->Cell(0, 0, utf8_decode('MALO'));
$pdf->Line(5, 164, 211, 164); //HORIZONTAL

// Juegos De Pinzas
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(6, 167);
$pdf->Cell(0, 0, utf8_decode('Juegos De Pinzas'));

// BUENO
$pdf->SetXY(84, 167);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 167);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 167);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 167);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 169, 211, 169); //HORIZONTAL

// Mangos aislados
$pdf->SetXY(6, 172);
$pdf->Cell(0, 0, utf8_decode('Mangos aislados'));

// BUENO
$pdf->SetXY(84, 172);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 172);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 172);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 172);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 174, 211, 174); //HORIZONTAL


// Maletin DeTransporte
$pdf->SetXY(6, 177);
$pdf->Cell(0, 0, utf8_decode('Maletin DeTransporte'));

// BUENO
$pdf->SetXY(84, 177);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 177);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 177);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 177);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 179, 211, 179); //HORIZONTAL

// Limpieza General del Sistema
$pdf->SetXY(6, 182);
$pdf->Cell(0, 0, utf8_decode('Limpieza General del Sistema'));

// BUENO
$pdf->SetXY(84, 182);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 182);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 182);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 182);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 184, 211, 184); //HORIZONTAL

// 3. EVALUACION DEL SPT DE VEHICULO
$pdf->SetFont("Arial", "B", 7);
$pdf->SetXY(8, 187);
$pdf->Cell(0, 0, utf8_decode('3. EVALUACIÓN DEL SPT DE VEHICULO'));

// BUENO
$pdf->SetXY(80, 187);
$pdf->Cell(0, 0, utf8_decode('BUENO'));

// REGULAR
$pdf->SetXY(101, 187);
$pdf->Cell(0, 0, utf8_decode('REGULAR'));

// MALO
$pdf->SetXY(127, 187);
$pdf->Cell(0, 0, utf8_decode('MALO'));
$pdf->Line(5, 189, 211, 189); //HORIZONTAL

// Cable maza de 1/2 Pulga
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(6, 192);
$pdf->Cell(0, 0, utf8_decode('Cable maza de 1/2 Pulga'));

// BUENO
$pdf->SetXY(84, 192);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 192);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 192);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 192);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 194, 211, 194); //HORIZONTAL

// Conectores Tipo Ponchable
$pdf->SetXY(6, 197);
$pdf->Cell(0, 0, utf8_decode('Conectores Tipo Ponchable'));

// BUENO
$pdf->SetXY(84, 197);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 197);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 197);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 197);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 199, 211, 199); //HORIZONTAL

// Barreno
$pdf->SetXY(6, 202);
$pdf->Cell(0, 0, utf8_decode('Barreno'));

// BUENO
$pdf->SetXY(84, 202);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 202);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 202);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 202);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 204, 211, 204); //HORIZONTAL

// Se encuentra localizado en un sitio estrategico
$pdf->SetXY(6, 207);
$pdf->Cell(0, 0, utf8_decode('Se encuentra localizado en un sitio estrategico'));

// BUENO
$pdf->SetXY(84, 207);
$pdf->Cell(0, 0, utf8_decode('X'));

// REGULAR
$pdf->SetXY(107, 207);
$pdf->Cell(0, 0, utf8_decode('X'));

// MALO
$pdf->SetXY(129, 207);
$pdf->Cell(0, 0, utf8_decode('X'));

// OBSERVACIONES
$pdf->SetXY(143, 207);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES'));
$pdf->Line(5, 209, 211, 209); //HORIZONTAL

$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(5, 213);
$pdf->Cell(0, 0, utf8_decode('OBSERVACIONES:'));

// OBSERVACCION GENERAL
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(7, 215);
$pdf->MultiCell(200, 3, utf8_decode('Observaciones generales'));
$pdf->Line(5, 230, 211, 230); //HORIZONTAL

// TECNICO / LINERO
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(35, 248);
$pdf->Cell(0, 0, utf8_decode('TECNICO / LINERO'));

$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(35, 242);
$pdf->Cell(0, 0, utf8_decode('Juan Martinez'));
$pdf->Line(20, 244, 80, 244); //HORIZONTAL

// RESPONSABLE DE INSPECCIÓN
$pdf->SetFont('Arial', 'B', 8);
$pdf->SetXY(135, 248);
$pdf->Cell(0, 0, utf8_decode('RESPONSABLE DE INSPECCIÓN'));

$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(145, 242);
$pdf->Cell(0, 0, utf8_decode('Juan Martinez'));
$pdf->Line(130, 244, 190, 244); //HORIZONTAL


// Salida del archivo PDF
$pdf->Output();
