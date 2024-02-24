<?php
namespace App\Traits;
use App\Models\Core\Credential;
use App\Models\Core\Endpoint;

trait CredentialsTrait {

    public function callEndpointWithCredentials($credentials_id,$endpoint_id,$params){
        $credentials = Credential::find($credentials_id);
        $endpoint = Endpoint::find($endpoint_id);
        if(!is_null($credentials) && !is_null($endpoint)){

            if($endpoint->method == "GET"){
                //Comprobar si podemos realizar la llamada al API
                if($credentials->daily_usage_limit != 0){
                    if($credentials->daily_usage == $credentials->daily_usage_limit){
                        throw new \Exception('Maximum daily calls for this provider has reached!');
                    }
                }
                if($credentials->monthly_usage != 0){
                    if($credentials->monthly_usage == $credentials->monthly_usage_limit){
                        throw new \Exception('Maximum monthly calls for this provider has reached!');
                    }
                }
                //////////////
                $http_header = explode(';',$credentials->http_header);
                //Realizar la llamada
                $curl = curl_init();
                curl_setopt_array($curl, [
                    CURLOPT_URL => $endpoint->url.$params,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => $endpoint->method,
                    CURLOPT_HTTPHEADER => $http_header
                ]);
                $response = curl_exec($curl);
                $err = curl_error($curl);
                curl_close($curl);
                if ($err) {
                    throw new \Exception('Curl Error!');
                } else {
                    //Contabilizar uso API
                    if($credentials->daily_usage_limit != 0){
                        $credentials->daily_usage++;
                    }
                    if($credentials->monthly_usage_limit != 0){
                        $credentials->monthly_usage++;
                    }
                    $credentials->save();
                    return json_decode($response,1);
                }
            }
        }
    }
}
