<?php

class LastFM
{
	private static $API_URL = 'http://ws.audioscrobbler.com/2.0/';
	private static $API_KEY = '7b39c61b04b19b50f5f5155210d7a15a';
	private static $USERNAME = 'tandouri';

	public static function getTracksForDate(DateTime $date)
	{
		// todo: put timerange stuff elsewhere
		$previous_day = clone $date;
		$previous_day = $previous_day->sub(DateInterval::createfromdatestring('+1 day'));

		$next_day = clone $date;
		$next_day = $next_day->sub(DateInterval::createfromdatestring('-1 day'));

		//var_dump("-----------------------");
		//var_dump($date);
		//var_dump($previous_day);
		//var_dump($next_day);

		$params = [
			'method'    => 'user.getrecenttracks',
			'user'      => self::$USERNAME,
			'api_key'   => self::$API_KEY,
			//'from'      => $date->getTimestamp(),
			'from'      => $previous_day->getTimestamp(),
			'to'        => $next_day->getTimestamp(),
			//'limit'     => 200,
			'format'    => 'json',
		];
		$call = self::$API_URL.'?'.http_build_query($params);
		$response = api::doCurlCall($call);

		$decoded = json_decode($response);
		if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
			die('error occured: ' . $decoded->response->errormessage);
		}

		if (!isset($decoded->recenttracks->track)) {
			die('error occured: no tracks found');
		}

		$tracks = $decoded->recenttracks->track;
		if (!is_array($tracks)) {
			// make sure we always return an array
			$track_item = $tracks;
			$tracks = [$track_item];
		}

		return $tracks;
	}
}
