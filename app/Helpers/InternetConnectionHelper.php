<?php

namespace App\Helpers;

/**
* 
*/
class InternetConnectionHelper
{
	/**
	 * Array de mensajes del estado de la conexión
	 * 
	 * @var array
	 */
	public $messages = [];

	/**
	 * Comprueba si hay conexión a internet consultando dos sitios, google.com y
	 * yahoo.com, de último revisa si hay conexión al sitio dado en $url.
	 * 
	 * @param  string $url
	 * @return bool
	 */
	public function testInternetConnection($url = '')
	{
		$isConnected =false;

		// primero intento conectarme a google y yahoo
		if ($this->testURLConnection('www.google.com') && $this->testURLConnection('www.yahoo.com')){
			// mensaje de prueba de conexión
			$this->messages[] = 'Tu conexión a internet está bien.';
			$isConnected = true;
		}else{
			// mensaje de prueba de conexión
			$this->messages[] = 'No tienes conexión a internet.';
		}
		
		// por último reviso la url dada
		if (! empty($url) && $this->testURLConnection($url)){
			// mensaje de conexión
			$this->messages[] = "El acceso a {$url} está bien.";
			$isConnected = true;
		}elseif(! empty($url) && ! $this->testURLConnection($url)){
			// mensaje de conexión
			$this->messages[] = "El host \"{$url}\" no responde.";
			$isConnected = false;
		}
		
		return $isConnected;
	}

	/**
	 * [testURLConnection description]
	 * https://css-tricks.com/snippets/php/check-if-website-is-available/
	 * 
	 * @param  [type] $url [description]
	 * @return [type]      [description]
	 */
	private function testURLConnection($url)
	{
		$agent = "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)";$ch=curl_init();
		curl_setopt ($ch, CURLOPT_URL,$url );
		curl_setopt($ch, CURLOPT_USERAGENT, $agent);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch,CURLOPT_VERBOSE,false);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch,CURLOPT_SSLVERSION,3);
		curl_setopt($ch,CURLOPT_SSL_VERIFYHOST, FALSE);

		$page=curl_exec($ch);
		
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		
		curl_close($ch);

		$this->messages[] = "Respuesta a {$url} = {$httpcode}";

        return $httpcode >= 200 && $httpcode < 400;
	}
}