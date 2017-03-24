<?php
require_once '../settings/lastfm_credentials.php';

class LastFM
{
	public static function getTracksForDate(DateTime $date)
	{
		$previous_day = clone $date;
		$previous_day = $previous_day->sub(DateInterval::createfromdatestring('+1 day'));

		$next_day = clone $date;
		$next_day = $next_day->sub(DateInterval::createfromdatestring('-1 day'));

		$call = lastfm_credentials::$API_URL.'?'.http_build_query([
			'method'    => 'user.getrecenttracks',
			'user'      => lastfm_credentials::$USERNAME,
			'api_key'   => lastfm_credentials::$API_KEY,
			'from'      => $previous_day->getTimestamp(),
			'to'        => $next_day->getTimestamp(),
			'format'    => 'json',
		]);
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
