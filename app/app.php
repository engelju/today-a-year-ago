<?php

class app
{
	public function run()
	{
		$html = [];

		//$years_active = $lastm_user->getRegYear();
		$reg_year = 2006;
		$years_active = date('Y') - $reg_year;

		$begin  = new DateTime('- '.$years_active.' year');
		$end    = new DateTime('+ 1 day');
		$period = new DatePeriod($begin, DateInterval::createFromDateString('1 year'), $end);
		foreach ($period as $date) {
			if ($tracks = LastFM::getTracksForDate($date)) {
				$html[] = tracks_render_table($date, $tracks);
			};
		}

		return implode('', array_reverse($html));
	}
}

function tracks_render_table(DateTime $date, $tracks)
{
	$html = '<h3>'.$date->format('Y').'</h3>';
	$html .= '<table class="table table-bordered table-hover">';

	$accessor = '#text';
	foreach ($tracks as $track) {
		$html .= '<tr>';
		$html .= '<td class="col-md-2"><b>'.$track->date->{$accessor}.'</b></td>';
		$html .= '<td class="col-md-4">'.$track->artist->{$accessor}.'</td>';
		$html .= '<td>'.(string)$track->name.'</td>';
	}

	$html .= '</table>';

	return $html;
}
