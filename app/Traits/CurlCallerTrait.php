<?php
namespace App\Traits;

trait CurlCallerTrait
{
    /**
     * Realiza una solicitud cURL a una URL dada.
     *
     * @param string $method El método HTTP ('GET', 'POST', etc.).
     * @param string $url La URL a la cual hacer la solicitud.
     * @param array $headers Cualquier cabecera adicional para enviar con la solicitud.
     * @param array|string $postData Datos para enviar en el cuerpo de la solicitud.
     * @return array Respuesta y código de estado HTTP.
     */
    public function curlRequest(string $method, string $url, array $headers = [], $postData = null): array
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        if (!is_null($postData)) {
            if (is_array($postData)) {
                $postData = http_build_query($postData);
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        }

        $response = curl_exec($ch);
        $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return [
            'response' => $response,
            'statusCode' => $httpStatusCode,
        ];
    }
}
