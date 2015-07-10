<?php

/**
 * @author    Julie Engel <entwicklung@metanet.ch>
 * @copyright Copyright (c) 2015, METANET AG
 */

function tracks_get(DateTime $date)
{
	$previous_day = clone $date;
	$previous_day = $previous_day->sub(DateInterval::createfromdatestring('+1 day'));
	
	$next_day = clone $date;
	$next_day = $next_day->sub(DateInterval::createfromdatestring('-1 day'));
	
	//var_dump("-----------------------");
	//var_dump($date);
	//var_dump($previous_day);
	//var_dump($next_day);

	$LASTFM_API_URL = 'http://ws.audioscrobbler.com/2.0/';
	$LASTFM_API_METHOD = 'user.getrecenttracks';
	$LASTFM_API_KEY = '2f0adc911189735abf17a53a4b34707b';
	$LASTFM_USER = 'tandouri';
	$params = array(
		'method'    => $LASTFM_API_METHOD,
		'user'      => $LASTFM_USER,
		'api_key'   => $LASTFM_API_KEY,
		//'from'      => $date->getTimestamp(),
		'from'      => $previous_day->getTimestamp(),
		'to'        => $next_day->getTimestamp(),
		//'limit'     => 200,
		'format'    => 'json',
	);
	$query  = http_build_query($params);
	$call   = $LASTFM_API_URL.'?'.$query;

	$curl = curl_init($call);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$curl_response = curl_exec($curl);
	if ($curl_response === false) {
		$info = curl_getinfo($curl);
		curl_close($curl);
		die('error occured during curl exec. Additioanl info: ' . var_export($info));
	}
	curl_close($curl);

	$decoded = json_decode($curl_response);
	if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
		die('error occured: ' . $decoded->response->errormessage);
	} else {
		//echo 'response ok!';
	}
	//var_dump($decoded);
	if (isset($decoded->recenttracks->track)) {
		$tracks = $decoded->recenttracks->track;
		if (is_array($tracks)) {
			$tracks = array_reverse($tracks);
		} else {
			$track_item = $tracks;
			$tracks = array($track_item);
		}
		return $tracks;
	} else return null;
}

function tracks_render($tracks)
{
	$html = '<ul>';

	foreach ($tracks as $track) {
		$html .= '<li>';
		foreach ($track->date as $trackdate) {
			$html .= '<b>'.$trackdate.':</b> ';
			break;
		}
		// workaround, because one parameter is named '#title' -.-
		foreach ($track->artist as $artist_info) {
			$html .= '['.$artist_info.'] ';
			break;
		}
		$html .= $track->name;
	}
	$html .= '</ul>';
	
	return $html;
}

//$years_active = $lastm_user->getRegYear();
$html = '';
$reg_year = 2006;
$current_year = 2015;
$years_active = $current_year - $reg_year;

$begin  = new DateTime('- '.$years_active.' year');
$end    = new DateTime('+ 1 day');

$period = new DatePeriod($begin, DateInterval::createFromDateString('1 year'), $end);
foreach ($period as $date) {
	if ($tracks = tracks_get($date)) {
		$html .= '<h3>'.$date->format('Y').'</h3>';
		$html .= tracks_render($tracks);
	};
}
//echo $html;
