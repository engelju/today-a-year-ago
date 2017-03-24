<?php

class api
{
	public static function doCurlCall($call)
	{
		$curl = curl_init($call);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$curl_response = curl_exec($curl);
		curl_close($curl);

		if ($curl_response === false) {
			die('error occured during curl exec. Additioanl info: ' . var_export(curl_getinfo($curl)));
		}

		return $curl_response;
	}
}
