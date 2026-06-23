<?php
//include 'inc/session-proveedor.php';

include 'inc/config.inc.php';
include 'inc/functions.inc.php';

if ($session_user_plan === 'Free') {
  header('location:cotizaciones');
  exit();
};

$data = $_GET['uid'];

if (!$data) {
  header('location:cotizaciones');
  exit();
}

$data = $_GET['uid'];
$quote_data = (array) json_decode(base64_decode($data));

/*echo json_encode($quote_data);
return;*/



$today_date = date('d/m/Y');
$tipo_evento        = cleanStr($quote_data['TipoEvento']);
$paquete            = cleanStr($quote_data['Paquete']);
$precio_paquete     = cleanStr($quote_data['PrecioPaquete']);
$modalidad_paquete  = cleanStr($quote_data['ModalidadPaquete']);
$fecha_cotizacion   = cleanStr($quote_data['FechaCotizacion']);
$folio              = $quote_data['Folio'] ? cleanStr($quote_data['Folio']) : 'M-0000/2022';

$cliente            = cleanStr($quote_data['NombreCompleto']);
$cliente_telefono   = cleanStr($quote_data['Telefono']);
$cliente_correo     = cleanStr($quote_data['Email']);

/* $data = $_GET['uid'];
$quote_data = (array) json_decode(base64_decode($data)); */

/* $data = $_GET['uid'];

$quote_data = (array) json_decode(base64_decode($data));

$id_cotizacion      = cleanStr($quote_data['idCotizacion']);
$proveedor          = cleanStr($quote_data['Proveedor']);
$tel_proveedor      = cleanStr($quote_data['TelefonoProveedor']);
$whats_proveedor    = cleanStr($quote_data['WhatsProveedor']);

$cliente            = cleanStr($quote_data['NombreCompleto']);
$tel_cliente        = cleanStr($quote_data['Telefono']);
$whats_cliente      = cleanStr($quote_data['Telefono']);
$email_cliente      = cleanStr($quote_data['Email']);
$fecha              = cleanStr($quote_data['Fecha']);
$paquete            = cleanStr($quote_data['Paquete']);
$tipo_evento        = cleanStr($quote_data['TipoEvento']);
$precio_paquete     = cleanStr($quote_data['PrecioPaquete']);
$modalidad_paquete  = cleanStr($quote_data['OrientacionPaquete']); */

/*define ('PDF_MARGIN_TOP', 40);
define ('PDF_MARGIN_BOTTOM', 40);
define ('PDF_MARGIN_FOOTER', 40);
define ('PDF_MARGIN_HEADER', 80);*/


// Include the main TCPDF library ( search for installation path ).
require_once('data/lib/TCPDF/tcpdf.php');
/* include('inc/security.php');
  include('inc/settings.inc.php');*/

/*$uid = isset( $_GET['uid'] )? $_GET['uid']:0;
$db->Query( "SELECT       concat(clientes.Calle,' ',clientes.Colonia,' col. ',clientes.Colonia,', ',clientes.Localidad,' ',clientes.Estado,' ',clientes.Pais,' ',clientes.CP  ) as Direccion,
                          clientes.Telefono,
                          clientes.Nombre as Cliente, usuarios.NombreCompleto,
                          clientes.RFC,
                          date_format(cotizaciones.Fecha,'%d-%m-%Y') as Fecha,
                          cotizaciones.Folio,
                          cotizaciones.Total
    FROM cotizaciones
    LEFT JOIN sucursales ON(sucursales.idSucursal = cotizaciones.idSucursal)
    LEFT JOIN usuarios ON(usuarios.idUsuario = cotizaciones.UserCaptura)
    LEFT JOIN clientes ON(cotizaciones.idCliente = clientes.idCliente) 
    WHERE idCotizacion = '".$uid."'" );
$dataCotizaciones = $db->FetchArray();*/





// Extend the TCPDF class to create custom Header and Footer

class MYPDF extends TCPDF
{
  var $top_margin = 80;
  //Page header

  public function Header()
  {
    $data = $_GET['uid'];
    $quote_data = (array) json_decode(base64_decode($data));

    $proveedor          = strtoupper(cleanStr($quote_data['Proveedor']));
    $direccion_negocio  = strtoupper(cleanStr($quote_data['DireccionCompleta']));
    $telefono_proveedor = cleanStr($quote_data['TelefonoProveedor']);
    $whatsapp_proveedor = cleanStr($quote_data['CelularProveedor']);
    $facebook_proveedor = cleanStr($quote_data['Facebook']);
    $instagram_proveedor = cleanStr($quote_data['Instagram']);
    // Logo
    $image_file = 'https://windsoftti.com/wp-content/uploads/2021/07/logo_windsoftti_blanco.png';
    // Image( $file, $x = '', $y = '', $w = 0, $h = 0, $type = '', $link = '', $align = '', $resize = false, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false )
    $this->Image($image_file, 10, 10, 50, 19, 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
    // Set font
    $this->SetFont('helvetica', '', 9);
    // Title
    //Cell( $w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = 0, $link = '', $stretch = 0, $ignore_min_height = false, $calign = 'T', $valign = 'M' )
    $this->SetXY(70, 10);
    $this->Cell(0, 10, $proveedor, 0, false, 'L', 0, '', 0, false, 'T', 'M');
    /*$this->SetXY( 70, 15 );
        $this->Cell( 0, 10, 'R.F.C.: HIMY840518KQ6', 0, false, 'L', 0, '', 0, false, 'T', 'M' );*/
    $this->SetXY(70, 20);
    //MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
    $this->MultiCell(130, 5, $direccion_negocio, 0, 'L', 0, 0, '', '', true);
    //$this->Cell( 0, 10, '8A CALLE SUR ORIENTE 813 BARRIO LAS CHILCAS COMITAN DE DOMINGUEZ, CHIAPAS C.P.: 30036', 0, false, 'L', 0, '', 0, false, 'T', 'M' );
    $this->SetXY(70, 25);

    $tel_proveedor_txt          = !empty($telefono_proveedor)? 'Tel: '.$telefono_proveedor:'';
    $whats_proveedor_txt        = !empty($whatsapp_proveedor)? 'Whatsapp: '.$whatsapp_proveedor:'';
    $facebook_proveedor_txt     = !empty($facebook_proveedor)? 'Facebook: '.$facebook_proveedor:'';
    $instagram_proveedor_txt    = !empty($instagram_proveedor)? 'Instagram: '.$instagram_proveedor:'';


    $this->Cell(0, 10,$whats_proveedor_txt .' '.$tel_proveedor_txt, 0, false, 'L', 0, '', 0, false, 'T', 'M');
    $this->SetXY(70, 30);
    $this->Cell(0, 10, $facebook_proveedor_txt.' '.$instagram_proveedor_txt, 0, false, 'L', 0, '', 0, false, 'T', 'M');
    $this->top_margin = $this->GetY() + 35; // padding for second page
  }

  // Page footer

  public function Footer()
  {
    $data = $_GET['uid'];
    $quote_data = (array) json_decode(base64_decode($data));

    $proveedor          = strtoupper(cleanStr($quote_data['Proveedor']));
    // Position at 15 mm from bottom
    $this->SetY(-23);
    // Set font
    $this->SetFont('helvetica', 'I', 9);
    $this->writeHTML('<table width="100%" border="0">
            <tr>
            <td width="33.3333%" align="left">
            UBICACIÓN: <br>'.$quote_data['DireccionCompleta'].'
            </td>
            <td width="33.3333%" align="left"></td>
            <td width="33.3333%" align="left">
            BBVA:<br>Titular: ' . $proveedor . '<br>Cuenta: 0466685013<br>Clave: 0121 0900 4666 8501 36
            </td>
            </tr>
            </table>', true, false, true, false, '');
    //$this->SetY( -15 );
    // Page number
    //$this->Cell( 0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M' );
  }
}

// create new PDF document
$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Manteles Largos');
$pdf->SetTitle('Cotización');
$pdf->SetSubject('Manteles Largos');
$pdf->SetKeywords('Cotizacion, Manteles Largos');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings ( optional )
if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
  require_once(dirname(__FILE__) . '/lang/eng.php');
  $pdf->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
$pdf->SetFont('helvetica', '', 9);

// add a page
$pdf->AddPage();


/*$db->Query("SELECT Producto, Cantidad, SubTotal, Total, Descuento
FROM cotizacion_detalles WHERE idCotizacion = '".$uid."'" );*/
//$dataDetalles = $db->FetchArray();
//print_r($dataDetalles);


$productos = '
<tr rowspan="3" nobr="true">
  <td align="left" >' . $today_date . '</td>
  <td align="left">' . $tipo_evento . '</td>
  <td align="left">' . $paquete . '/' . $modalidad_paquete . '</td>
  <td align="right">$' . number_format($precio_paquete, 2) . '</td>
  <td align="right">$0.00</td>
  <td align="right">$' . number_format($precio_paquete, 2) . '</td>
</tr>';

/*foreach ($dataDetalles as $key => $value) {
    $productos .= ' key'.$key.' value'.$value.'<br>';
}*/

/*while($dataDetalles = $db->FetchArray()){
    $porcentaje = (100 - $dataDetalles['Descuento']);
    $precioNormal = (($dataDetalles['SubTotal'] * 100)/$porcentaje);
    //$productos .= ' key'.$dataDetalles['Producto'].'<br>';
    $productos .= '<tr rowspan="3"  nobr="true">
        <td width="7%">'.$dataDetalles['Cantidad'].'</td>
         <td width="30%">'.$dataDetalles['Producto'].'</td>
        <td align="right">$'.number_format($precioNormal,2).'</td>
        <td align="right">'.$dataDetalles['Descuento'].'%</td>
        <td align="right">$'.$dataDetalles['SubTotal'].'</td>
        <td align="right">$'.$dataDetalles['Total'].'</td>
    </tr>';
}*/

/*$totalSinIVA    = ($dataCotizaciones['Total']/1.16);
$IVA            = ($dataCotizaciones['Total'] -$totalSinIVA);
$totalConIVA    = $totalSinIVA+$IVA ;*/


// set some text to print
$html = '
<br><br><br><br><br><br>
<table border="0">
<tr>
    <td>
       Cotización: : ' . $folio . '
    </td>
    <td>
       www.manteleslargos.com
    </td>
    <td align="right">
       Fecha de expedición: ' . $fecha_cotizacion . '
    </td>
</tr>
<tr>
    <td colspan="3">
       <hr>
    </td>
</tr>
</table>

<br><br>
<table border="0" width="60%">
<tr>
    <td>
        CLIENTE:
    </td>
    <td>
        ' . $cliente . '
    </td>
</tr>
<br>
<tr>
    <td>
        DATOS DE CONTACTO:
    </td>
    <td>
    Tel: ' . $cliente_telefono . '<br>
    ' . $cliente_correo . ' <br>


    </td>
</tr>
</table>

<br><br><br>


<table width="100%" border="1" cellspacing="0" cellpadding="6" >
    <tr style="background-color:#000;color:white;">
        <th align="left">FECHA</th>
        <th align="left">TIPO</th>
        <th  align="left">PAQUETE</th>
        <th  align="right">PRECIO</th>
        <th  align="right">DESCUENTO</th>
        <th align="right">IMPORTE</th>
    </tr>
    ' . $productos . '
   
</table>

<br><br><br><br><br><br>
<table border="0" cellspacing="0" cellpadding="4" >
    <tr>
        <td colspan="4">' . numtoletras($precio_paquete) . '</td>
        <td colspan="1" align="left">SUBTOTAL</td>
        <td  align="right">$' . number_format($precio_paquete, 2) . '</td>
        
    </tr>
     <tr>
        <td colspan="4">Precios sujetos a cambio sin previo aviso.</td>
        <td colspan="1" align="left">IVA</td>
        <td  align="right">$' . number_format(($precio_paquete * 0.16), 2) . '</td>
    </tr>
      <tr>
        <td colspan="4">Vigencia: 4 días.</td>
       <td colspan="1" align="left">TOTAL</td>
        <td  align="right">$' . number_format(($precio_paquete * 1.16), 2) . '</td>
    </tr>
</table>

';

/* <td colspan="4">TRES MIL PESOS 00/100 M.N</td> */
$pdf->writeHTML($html, true, false, true, false, '');

// print a block of text using Write()
//$pdf->Write( 0, $txt, '', 0, 'C', true, 0, false, false, 0 );

// ---------------------------------------------------------
// move pointer to last page
$pdf->lastPage();

//Close and output PDF document
$pdf->Output('cotizacion_'.$folio.'.pdf', 'I');

//===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  === +
// END OF FILE
//===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  ===  === +


function numtoletras($xcifra)
{
  $xarray = array(
    0 => "Cero",
    1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
    "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
    "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
    100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
  );
  //
  $xcifra = trim($xcifra);
  $xlength = strlen($xcifra);
  $xpos_punto = strpos($xcifra, ".");
  $xaux_int = $xcifra;
  $xdecimales = "00";
  if (!($xpos_punto === false)) {
    if ($xpos_punto == 0) {
      $xcifra = "0" . $xcifra;
      $xpos_punto = strpos($xcifra, ".");
    }
    $xaux_int = substr($xcifra, 0, $xpos_punto); // obtengo el entero de la cifra a covertir
    $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2); // obtengo los valores decimales
  }

  $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); // ajusto la longitud de la cifra, para que sea divisible por centenas de miles (grupos de 6)
  $xcadena = "";
  for ($xz = 0; $xz < 3; $xz++) {
    $xaux = substr($XAUX, $xz * 6, 6);
    $xi = 0;
    $xlimite = 6; // inicializo el contador de centenas xi y establezco el límite a 6 dígitos en la parte entera
    $xexit = true; // bandera para controlar el ciclo del While
    while ($xexit) {
      if ($xi == $xlimite) { // si ya llegó al límite máximo de enteros
        break; // termina el ciclo
      }

      $x3digitos = ($xlimite - $xi) * -1; // comienzo con los tres primeros digitos de la cifra, comenzando por la izquierda
      $xaux = substr($xaux, $x3digitos, abs($x3digitos)); // obtengo la centena (los tres dígitos)
      for ($xy = 1; $xy < 4; $xy++) { // ciclo para revisar centenas, decenas y unidades, en ese orden
        switch ($xy) {
          case 1: // checa las centenas
            if (substr($xaux, 0, 3) < 100) { // si el grupo de tres dígitos es menor a una centena ( < 99) no hace nada y pasa a revisar las decenas

            } else {
              $key = (int) substr($xaux, 0, 3);
              if (TRUE === array_key_exists($key, $xarray)) {  // busco si la centena es número redondo (100, 200, 300, 400, etc..)
                $xseek = $xarray[$key];
                $xsub = subfijo($xaux); // devuelve el subfijo correspondiente (Millón, Millones, Mil o nada)
                if (substr($xaux, 0, 3) == 100)
                  $xcadena = " " . $xcadena . " CIEN " . $xsub;
                else
                  $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                $xy = 3; // la centena fue redonda, entonces termino el ciclo del for y ya no reviso decenas ni unidades
              } else { // entra aquí si la centena no fue numero redondo (101, 253, 120, 980, etc.)
                $key = (int) substr($xaux, 0, 1) * 100;
                $xseek = $xarray[$key]; // toma el primer caracter de la centena y lo multiplica por cien y lo busca en el arreglo (para que busque 100,200,300, etc)
                $xcadena = " " . $xcadena . " " . $xseek;
              } // ENDIF ($xseek)
            } // ENDIF (substr($xaux, 0, 3) < 100)
            break;
          case 2: // checa las decenas (con la misma lógica que las centenas)
            if (substr($xaux, 1, 2) < 10) {
            } else {
              $key = (int) substr($xaux, 1, 2);
              if (TRUE === array_key_exists($key, $xarray)) {
                $xseek = $xarray[$key];
                $xsub = subfijo($xaux);
                if (substr($xaux, 1, 2) == 20)
                  $xcadena = " " . $xcadena . " VEINTE " . $xsub;
                else
                  $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
                $xy = 3;
              } else {
                $key = (int) substr($xaux, 1, 1) * 10;
                $xseek = $xarray[$key];
                if (20 == substr($xaux, 1, 1) * 10)
                  $xcadena = " " . $xcadena . " " . $xseek;
                else
                  $xcadena = " " . $xcadena . " " . $xseek . " Y ";
              } // ENDIF ($xseek)
            } // ENDIF (substr($xaux, 1, 2) < 10)
            break;
          case 3: // checa las unidades
            if (substr($xaux, 2, 1) < 1) { // si la unidad es cero, ya no hace nada

            } else {
              $key = (int) substr($xaux, 2, 1);
              $xseek = $xarray[$key]; // obtengo directamente el valor de la unidad (del uno al nueve)
              $xsub = subfijo($xaux);
              $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub;
            } // ENDIF (substr($xaux, 2, 1) < 1)
            break;
        } // END SWITCH
      } // END FOR
      $xi = $xi + 3;
    } // ENDDO

    if (substr(trim($xcadena), -5, 5) == "ILLON") // si la cadena obtenida termina en MILLON o BILLON, entonces le agrega al final la conjuncion DE
      $xcadena .= " DE";

    if (substr(trim($xcadena), -7, 7) == "ILLONES") // si la cadena obtenida en MILLONES o BILLONES, entoncea le agrega al final la conjuncion DE
      $xcadena .= " DE";

    // ----------- esta línea la puedes cambiar de acuerdo a tus necesidades o a tu país -------
    if (trim($xaux) != "") {
      switch ($xz) {
        case 0:
          if (trim(substr($XAUX, $xz * 6, 6)) == "1")
            $xcadena .= "UN BILLON ";
          else
            $xcadena .= " BILLONES ";
          break;
        case 1:
          if (trim(substr($XAUX, $xz * 6, 6)) == "1")
            $xcadena .= "UN MILLON ";
          else
            $xcadena .= " MILLONES ";
          break;
        case 2:
          if ($xcifra < 1) {
            $xcadena = "CERO PESOS $xdecimales/100 M.N.";
          }
          if ($xcifra >= 1 && $xcifra < 2) {
            $xcadena = "UN PESO $xdecimales/100 M.N. ";
          }
          if ($xcifra >= 2) {
            $xcadena .= " PESOS $xdecimales/100 M.N. "; //
          }
          break;
      } // endswitch ($xz)
    } // ENDIF (trim($xaux) != "")
    // ------------------      en este caso, para México se usa esta leyenda     ----------------
    $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); // quito el espacio para el VEINTI, para que quede: VEINTICUATRO, VEINTIUN, VEINTIDOS, etc
    $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
    $xcadena = str_replace("UN UN", "UN", $xcadena); // quito la duplicidad
    $xcadena = str_replace("  ", " ", $xcadena); // quito espacios dobles
    $xcadena = str_replace("BILLON DE MILLONES", "BILLON DE", $xcadena); // corrigo la leyenda
    $xcadena = str_replace("BILLONES DE MILLONES", "BILLONES DE", $xcadena); // corrigo la leyenda
    $xcadena = str_replace("DE UN", "UN", $xcadena); // corrigo la leyenda
  } // ENDFOR ($xz)
  return trim($xcadena);
}

function subfijo($xx)
{ // esta función regresa un subfijo para la cifra
  $xx = trim($xx);
  $xstrlen = strlen($xx);
  if ($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3)
    $xsub = "";
  //
  if ($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6)
    $xsub = "MIL";
  //
  return $xsub;
}
