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

    public function numeroALetras($number)
    {
        $number = trim($number);
        $number = str_replace(',', '', $number);
        $xpos_punto = strpos($number, ".");
        if ($xpos_punto === false) {
            $entero = $number;
            $decimales = "00";
        } else {
            $entero = substr($number, 0, $xpos_punto);
            $decimales = substr($number . "00", $xpos_punto + 1, 2);
        }

        $entero = ltrim($entero, "0");
        if ($entero == "") $entero = "0";

        $unidades = ["", "UN", "DOS", "TRES", "CUATRO", "CINCO", "SEIS", "SIETE", "OCHO", "NUEVE", "DIEZ", "ONCE", "DOCE", "TRECE", "CATORCE", "QUINCE", "DIECISEIS", "DIECISIETE", "DIECIOCHO", "DIECINUEVE", "VEINTE", "VEINTIUN", "VEINTIDOS", "VEINTITRES", "VEINTICUATRO", "VEINTICINCO", "VEINTISEIS", "VEINTISIETE", "VEINTIOCHO", "VEINTINUEVE"];
        $decenas = ["", "DIEZ", "VEINTE", "TREINTA", "CUARENTA", "CINCUENTA", "SESENTA", "SETENTA", "OCHENTA", "NOVENTA"];
        $centenas = ["", "CIENTO", "DOSCIENTOS", "TRESCIENTOS", "CUATROCIENTOS", "QUINIENTOS", "SEISCIENTOS", "SETECIENTOS", "OCHOCIENTOS", "NOVECIENTOS"];

        $convertirGrupo = function($n) use ($unidades, $decenas, $centenas) {
            $n = (int)$n;
            $c = floor($n / 100);
            $d = floor(($n % 100) / 10);
            $u = $n % 10;
            $res = "";
            if ($c == 1 && $d == 0 && $u == 0) $res .= "CIEN";
            else if ($c > 0) $res .= $centenas[$c];

            $du = $n % 100;
            if ($du > 0) {
                if ($res != "") $res .= " ";
                if ($du < 30) {
                    $res .= $unidades[$du];
                } else {
                    $res .= $decenas[$d];
                    if ($u > 0) $res .= " Y " . $unidades[$u];
                }
            }
            return $res;
        };

        if ($entero == "0") {
            $resultado = "CERO";
        } else {
            $entero = str_pad($entero, 18, "0", STR_PAD_LEFT);
            $b2 = substr($entero, 0, 6);
            $m2 = substr($entero, 6, 6);
            $u2 = substr($entero, 12, 6);

            $resultado = "";

            if ((int)$b2 > 0) {
                $bg1 = substr($b2, 0, 3);
                $bg2 = substr($b2, 3, 3);
                if ((int)$bg1 > 0) {
                    $g = $convertirGrupo($bg1);
                    if ($g == "UN") $g = "";
                    $resultado .= trim($g . " MIL") . " ";
                }
                if ((int)$bg2 > 0) {
                    $resultado .= $convertirGrupo($bg2) . " ";
                }
                if ((int)$b2 == 1) $resultado .= "BILLON ";
                else $resultado .= "BILLONES ";
            }

            if ((int)$m2 > 0) {
                $mg1 = substr($m2, 0, 3);
                $mg2 = substr($m2, 3, 3);
                if ((int)$mg1 > 0) {
                    $g = $convertirGrupo($mg1);
                    if ($g == "UN") $g = "";
                    $resultado .= trim($g . " MIL") . " ";
                }
                if ((int)$mg2 > 0) {
                    $resultado .= $convertirGrupo($mg2) . " ";
                }
                if ((int)$m2 == 1) $resultado .= "MILLON ";
                else $resultado .= "MILLONES ";
            }

            if ((int)$u2 > 0) {
                $ug1 = substr($u2, 0, 3);
                $ug2 = substr($u2, 3, 3);
                if ((int)$ug1 > 0) {
                    $g = $convertirGrupo($ug1);
                    if ($g == "UN") $g = ""; 
                    $resultado .= trim($g . " MIL") . " ";
                }
                if ((int)$ug2 > 0) {
                    $resultado .= $convertirGrupo($ug2);
                }
            }
            
            $resultado = trim(preg_replace('/\s+/', ' ', $resultado));
        }

        return "(" . $resultado . " PESOS " . $decimales . "/100 M.N.)";
    }

}
