<?php
namespace App\Traits;

use Orhanerday\OpenAi\OpenAi;

trait ChatGPTTrait {

    public function callChatGPT($model = 'gpt-3.5-turbo', $role = 'user', $message, $temperature = 1.0, $maxTokens = 400, $frequency_penalty = 0, $presence_penalty = 0 ) {

        $api_key = env('OPENAI_API_KEY');
        $open_ai = new OpenAi($api_key);
        $open_ai->setORG(env('API_ORG'));

        $chat = $open_ai->chat([
            'model' => $model,
            'messages' => [
                [
                    'role' => $role,
                    'content' => $message
                ]
            ],
            'temperature' => $temperature,
            'max_tokens' => $maxTokens,
            'response_format' => ["type" => "json_object"],
            'frequency_penalty' => $frequency_penalty,
            'presence_penalty' => $presence_penalty,
        ]);
        $respuesta = json_decode($chat);
        if(isset($respuesta->error)) {
            throw new \Exception($respuesta->error->code." - ".$respuesta->error->message);
        }else{
            return json_decode(str_replace('\n','',$respuesta->choices[0]->message->content),1);
        }
    }
}
