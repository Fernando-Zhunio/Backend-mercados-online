<?php

namespace App\Utils;

use GuzzleHttp\Client;

class Notificaciones
{
	public function send($destinatario, $titulo, $mensaje)
	{
		$client = new Client();
		$json = json_decode(json_encode('{"to":"destinatario","notification":{"body": "mensaje", "title": "titulo", "click_action":".view.Principal"}}', true));
		$json = str_replace("destinatario", $destinatario, $json);
		$json = str_replace("titulo", $titulo, $json);
		$json = str_replace("mensaje", $mensaje, $json);

		$response = $client->post('https://fcm.googleapis.com/fcm/send', [
			'headers' =>
			[
				'Authorization' => 'key=' . env('FCM_KEY'),
				'Content-Type' => 'application/json'
			],
			'body' => $json
		]);
	}
}
