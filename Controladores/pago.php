<?php
require('../lib/fpdf/fpdf.php');

//Incluimos la libreria del qr
include_once '../lib/phpqrcode/qrlib.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require('../lib/PHPMailer/src/Exception.php');
require('../lib/PHPMailer/src/PHPMailer.php');
require('../lib/PHPMailer/src/SMTP.php');

session_start();

$carritoConciertos = $_SESSION['carritoCompra'];


// Crear instancia de FPDF
$pdf = new FPDF();
$pdf->AddPage();


$pdf->SetFont('Arial', '', 22);
$pdf->Cell(40, 10, "Entradas Sacadas");
$pdf->Ln(20);


$pdf->SetFont('Arial', '', 12);
// Añadir celda con color de fondo para el nombre
$pdf->Cell(0, 10, 'Usuario ' . convertidorString($_POST['nombre']), 0, 1, '', false);
// Añadir celda con color de fondo para el nombre
$pdf->Cell(0, 10, 'Correo electronico: ' . $_POST['email'], 0, 1, '', false);

$precioTotal = 0;

// Recorrer el array de sesiones e imprimir los detalles de cada entrada
foreach ($carritoConciertos as $index => $concierto) {


    $codigoConcierto = $concierto['codigo'];

    // Generar el código QR
    $qrCodeContent = 'Código del concierto: ' . $codigoConcierto;
    //$qrCodePath = '../codigosQR/' . $codigoConcierto . '.png';
    //Guardamos los codigos qr en la propia libreria
    $qrCodePath = '../lib/phpqrcode/codigosQR/' . $codigoConcierto . '.png';

    QRcode::png($qrCodeContent, $qrCodePath);

    $pdf->SetTextColor(0, 0, 0);

    //Añadimos la imagen QR al pdf
    $pdf->Image($qrCodePath, $pdf->GetX() + 150, $pdf->GetY() + 5, 30);


    // Se establece el color del texto
    $pdf->SetTextColor(12, 90, 57);


    $pdf->SetFillColor(67, 229, 160);
    // Añadir celda con color de fondo para el nombre
    $pdf->Cell(140, 10, 'Nombre: ' . convertidorString($concierto['nombreArtistico']), 0, 1, '', true);

    $pdf->SetFillColor(67, 229, 160);
    $pdf->Cell(140, 10, 'Lugar: ' . convertidorString($concierto['lugar']), 0, 1, '', true);

    $pdf->SetFillColor(67, 229, 160);
    $pdf->Cell(140, 10, 'Entradas: ' . convertidorString($concierto['entrada']), 0, 1, '', true);

    $pdf->SetFillColor(67, 229, 160);
    $pdf->Cell(140, 10, 'Precio de entrada/s: ' .   convertidorString(($concierto['precio'] * $concierto['entrada']) . " " . "€"), 0, 1, '', true);

    // Restablecer el color del texto
    $pdf->SetTextColor(0, 0, 0);

    $precioTotal += $concierto['entrada'] * $concierto['precio'];

    // Salto de línea entre entradas
    $pdf->Ln(10);
}

$pdf->SetFont('Arial', '', 30);

$importeTotal = 'IMPORTE TOTAL: ' . $precioTotal . "€";
$pdf->Cell(0, 10, convertidorString($importeTotal), 0, 1, '', true);


$facturaPath = '../reportes/factura.pdf';

// Guarda el PDF en el servidor
$pdf->Output('F', $facturaPath);

//Le decimos que el contenido es Pdf
//header('Content-Type: application/pdf');
//donde nos lo va  a guardar
//header('Content-Disposition: inline; filename="factura.pdf"');
//Hacemos que el navegador lea el pdf creado
//readfile($facturaPath);

crearEmail($facturaPath);


function convertidorString($string)
{

    $stringFormateado = iconv('UTF-8', 'iso-8859-1//TRANSLIT//IGNORE', $string);
    //$stringFormateado = mb_convert_encoding($string, 'UTF-8', 'ISO-8859-1');

    return $stringFormateado;
}


/* Envio del mail   */
function crearEmail($facturaPath)
{

    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Por si queremos depurar el envio del email descomentar
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;

        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->Username = "sergiopruebasfernandez@gmail.com";
        $mail->Password = "xygf ebgj gway hjtd";


        $mail->setFrom('sergiopruebasfernandez@gmail.com', 'Sergio Fdez');
        $mail->addAddress($_POST['email'], "Sergio");
        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Factura Entradas';
        $mail->Body    = 'Muchísimas gracias por comprar las entras para los eventos de verano en nuestra web!.' .
            'Aquí le adjuntamos la entrada/s para el concierto que ha comprado.s';

        //Adjuntamos el pdf al correo
        $mail->addAttachment($facturaPath, 'factura.pdf');

        $mail->send();
        echo 'El correo ha sido enviado correctamente';

        ob_end_clean();

        //Le decimos que el contenido es Pdf
        header('Content-Type: application/pdf');
        //donde nos lo va  a guardar
        header('Content-Disposition: inline; filename="factura.pdf"');
        //Hacemos que el navegador lea el pdf creado
        //readfile($facturaPath);

        header("Location: cerrarSesion.php");

        exit();
    } catch (Exception $e) {
        echo "El mensanje no pudo ser enviado. Tipo de Error: {$mail->ErrorInfo}";
    }
}
