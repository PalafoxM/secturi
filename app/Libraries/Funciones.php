<?php
namespace App\Libraries;
use App\Libraries\Globals;
date_default_timezone_set('America/Mexico_City');// Zona horaria de Mexico
use DateTime;
use stdClass;

class Funciones {    
    private $secretKey;
    public function __construct()
    {
        $this->globals = new Globals();
        $this->secretKey = 'ORnsLEykJAMTEvacurIPAMAeRvelINclOg';
    }

    function encode($data) {
        return rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');
    }
    
    function decode($data) {
        return json_decode(base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)));
    }

    /**
     * Función que realiza la transformación de numeros arábigos a números romanos
     * 
     * @param int $number
     * @return string
     */
    function number2Roman($number) {
        $number = (int)$number;
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    
    public function encrypt($q)
    {
        $q = json_encode($q);
        $ciphering = "AES-256-CTR";
        $encryption_iv = '1234567891011121';
        $encryption_key = "v5bQr9UhrmzrlMst9WDD6rkp1";
        $encryption = openssl_encrypt($q, $ciphering, $encryption_key, 0, $encryption_iv);
        return rtrim(strtr(base64_encode($encryption), '+/', '-_'), '=');
    }

    public function decrypt($q)
    {
        $ciphering = "AES-256-CTR";
        $decryption_iv = '1234567891011121';
        $decryption_key = "v5bQr9UhrmzrlMst9WDD6rkp1";
        $decryption = openssl_decrypt(base64_decode(str_pad(strtr($q, '-_', '+/'), strlen($q) % 4, '=', STR_PAD_RIGHT)), $ciphering, $decryption_key, 0, $decryption_iv);
        return json_decode($decryption);
    }

    public function dateISOtoEuro($date, $format = "d/m/Y H:i")
    {
        $date = date_create($date);
        return date_format($date,$format);
    }

    public function dateEuroToISO($date, $separador = "/" )
    {
        $date = explode($separador,$date);
        return $date[2]."-".$date[1]."-".$date[0];
    }
    
    public function getEdad($fechaInicial = false, $fechaFinal = false)
    {
        $fechaFinal = (!$fechaFinal)? date("Y-m-d"): $fechaFinal;
        $response = new \stdClass();
        $response->error = true;

        if (!$fechaInicial) {
            $response->respuesta = "Favor de ingresar una fecha inicial";
            $response->idTipoEdad = 8;
            $response->dscTipoEdad = "Se ignora";
            $response->edad = $response->dscTipoEdad;
            return $response;
        }

        $response->dif = date_diff(date_create($fechaInicial), date_create($fechaFinal));

        // Tipo edad
        if ($response->dif->y > 0){
             $response->idTipoEdad = 5;
             $response->dscTipoEdad = ($response->dif->y > 1)? "años":"año";
             $response->edad = $response->dif->y ." ". $response->dscTipoEdad;
        }
        else if ($response->dif->m > 0){
             $response->idTipoEdad = 4;
             $response->dscTipoEdad = ($response->dif->m > 1)? "meses":"mes";
             $response->edad = $response->dif->m ." ". $response->dscTipoEdad;
        }
        else if ($response->dif->d > 0){
            $response->idTipoEdad = 3;
            $response->dscTipoEdad = ($response->dif->d > 1)? "días":"día";
            $response->edad = $response->dif->d ." ". $response->dscTipoEdad;
        }
        else{
            $response->idTipoEdad = 8;
            $response->dscTipoEdad = "Se ignora";
            $response->edad = $response->dscTipoEdad;
        }

        return $response;
        
    }
    public function generateToken($userData)
    {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $payload = json_encode($userData);

        // Codificar a Base64 el header y el payload
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

        // Crear la firma usando HMAC-SHA256
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Retornar el JWT completo (header.payload.signature)
        return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    }

    // Verificación del token manual
    public function verifyToken($jwt)
    {
        // Dividimos el token en sus partes (header, payload, signature)
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        // Verificamos la firma
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secretKey, true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        // Comparamos la firma generada con la proporcionada
        if ($base64UrlSignature === $signatureProvided) {
            return json_decode($payload); // El token es válido, devolver los datos
        } else {
            return false; // El token es inválido
        }
    }
 
    public function getTiempoTranscurrido($fechaInicial = false, $fechaFinal = false)
    {
        $fechaFinal = (!$fechaFinal)? date("Y-m-d H:i:s"): $fechaFinal;
        $response = new \stdClass();
        $response->error = true;
        $response->tiempo = "";

        if (!$fechaInicial) {
            $response->respuesta = "Favor de ingresar una fecha inicial";
            $response->idTipoEdad = 8;
            $response->dscTipoEdad = "Se ignora";
            $response->tiempo = $response->dscTipoEdad;
            return $response;
        }

        $response->dif = date_diff(date_create($fechaInicial), date_create($fechaFinal));

        // Tipo edad
        if ($response->dif->y > 0){
            $dscTipoEdad = ($response->dif->y > 1)? " años ":" año ";
            $response->tiempo .= $response->dif->y . $dscTipoEdad ;
        }
        if ($response->dif->m > 0){
            $dscTipoEdad = ($response->dif->m > 1)? " meses ":" mes ";
            $response->tiempo .= $response->dif->m . $dscTipoEdad;
        }
        if ($response->dif->d > 0){
            $dscTipoEdad = ($response->dif->d > 1)? " días ":" día ";
            $response->tiempo .= $response->dif->d . $dscTipoEdad;
        }
        if ($response->dif->h > 0){
            $dscTipoEdad = ($response->dif->h > 1)? " horas ":" hora ";
            $response->tiempo .= $response->dif->h . $dscTipoEdad;
        }
        if ($response->dif->i > 0){
            $dscTipoEdad = ($response->dif->i > 1)? " minutos ":" minuto ";
            $response->tiempo .= $response->dif->i . $dscTipoEdad;
        }

        return $response;
        
    }

    public function numeroALetras($xcifra)
    {
        $xarray = array(
            0 => "Cero",
            1 => "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE",
            "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE",
            "VEINTI", 30 => "TREINTA", 40 => "CUARENTA", 50 => "CINCUENTA", 60 => "SESENTA", 70 => "SETENTA", 80 => "OCHENTA", 90 => "NOVENTA",
            100 => "CIENTO", 200 => "DOSCIENTOS", 300 => "TRESCIENTOS", 400 => "CUATROCIENTOS", 500 => "QUINIENTOS", 600 => "SEISCIENTOS", 700 => "SETECIENTOS", 800 => "OCHOCIENTOS", 900 => "NOVECIENTOS"
        );
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
            $xaux_int = substr($xcifra, 0, $xpos_punto); 
            $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2);
        }

        $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT); 
        $xcadena = "";
        for ($xz = 0; $xz < 3; $xz++) {
            $xaux = substr($XAUX, $xz * 6, 6);
            $xi = trim(substr($xaux, 0, 3));
            $xbox = "";
            if ($xi > 0) {
                $x3digitos = ($xi % 100);
                $xaux_num = $xi;
                if ($x3digitos > 0 && $x3digitos < 21) $xbox = $xarray[$x3digitos];
                else {
                    $x2digitos = ($xi % 10);
                    if ($x2digitos == 0) $xbox = $xarray[$x3digitos];
                    else $xbox = $xarray[$x3digitos - $x2digitos] . " Y " . $xarray[$x2digitos];
                }
                $x3digitos = $xi; 
                if ($x3digitos > 99) {
                    $xcentenas = floor($x3digitos / 100);
                    $xresto = ($x3digitos % 100);
                    if ($xcentenas == 1 && $xresto == 0) $xbox = "CIEN";
                    else $xbox = $xarray[$xcentenas * 100] . " " . $xbox;
                }
                if ($xz == 0) $xcadena = $xbox . " BILLONES " . $xcadena;
                elseif ($xz == 1) $xcadena = $xbox . " MILLONES " . $xcadena;
                elseif ($xz == 2) $xcadena = $xbox . " MILES " . $xcadena;
            }
            $xbox = "";
            $xaux = substr($xaux, 3, 3);
            $xi = trim($xaux);
            if ($xi > 0) {
                $x3digitos = ($xi % 100);
                $xaux_num = $xi;
                if ($x3digitos > 0 && $x3digitos < 21) $xbox = $xarray[$x3digitos];
                else {
                    $x2digitos = ($xi % 10);
                    if ($x2digitos == 0) $xbox = $xarray[$x3digitos];
                    else $xbox = $xarray[$x3digitos - $x2digitos] . " Y " . $xarray[$x2digitos];
                }
                $x3digitos = $xi; 
                if ($x3digitos > 99) {
                    $xcentenas = floor($x3digitos / 100);
                    $xresto = ($x3digitos % 100);
                    if ($xcentenas == 1 && $xresto == 0) $xbox = "CIEN";
                    else $xbox = $xarray[$xcentenas * 100] . " " . $xbox;
                }
            } else $xbox = ""; 
            if ($xz == 0) {
                if ($xi > 0) $xcadena = $xbox . " MIL MILLONES " . $xcadena;
            } elseif ($xz == 1) {
                if ($xi > 0) {
                    if ($xi == 1) $xbox = "UN"; 
                    $xcadena = $xcadena . " " . $xbox . " MILLONES ";
                }
            } elseif ($xz == 2) {
                if ($xi > 0) {
                    if ($xi == 1) $xbox = "UN"; 
                    $xcadena = $xcadena . " " . $xbox . " MIL ";
                }
            }
        }
        
        $xi = trim(substr($XAUX, -3));
        $xbox = "";
        if ($xi > 0) {
            $x3digitos = ($xi % 100);
            if ($x3digitos > 0 && $x3digitos < 21) $xbox = $xarray[$x3digitos];
            else {
                $x2digitos = ($xi % 10);
                if ($x2digitos == 0) $xbox = $xarray[$x3digitos];
                else $xbox = $xarray[$x3digitos - $x2digitos] . " Y " . $xarray[$x2digitos];
            }
            $x3digitos = $xi; 
            if ($x3digitos > 99) {
                $xcentenas = floor($x3digitos / 100);
                $xresto = ($x3digitos % 100);
                if ($xcentenas == 1 && $xresto == 0) $xbox = "CIEN";
                else $xbox = $xarray[$xcentenas * 100] . " " . $xbox;
            }
        } else $xbox = "CERO";
        $xcadena = $xcadena . " " . $xbox;
        $xcadena = str_replace("VEINTI ", "VEINTI", $xcadena); 
        $xcadena = str_replace("  ", " ", $xcadena); 
        $xcadena = str_replace("UN UN", "UN", $xcadena);
        $xcadena = str_replace("  ", " ", $xcadena);
        $xcadena = str_replace("BILLONES MILLONES MILES", "BILLONES", $xcadena);
        $xcadena = str_replace("MILLONES MILES", "MILLONES", $xcadena);
        $xcadena = str_replace("UN MIL", "MIL", $xcadena); 
        $xcadena = trim($xcadena);
        
        return "(" . $xcadena . " PESOS " . $xdecimales . "/100 M.N.)";
    }

}
