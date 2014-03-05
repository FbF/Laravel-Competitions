<?php namespace Fbf\LaravelCompetitions;

class Entry extends \Eloquent {

	/**
	 * Name of the table to use for this model
	 * @var string
	 */
	protected $table = 'fbf_competition_entries';

	/**
	 * Defines the belongsTo relationship between a competition entry and a competition
	 * @return mixed
	 */
	public function competition()
	{
		return $this->belongsTo('Fbf\LaravelCompetitions\Competition');
	}

	/**
	 * Defines the belongsTo relationship between a competition entry and a user
	 * @return mixed
	 */
	public function user()
	{
		return $this->belongsTo('User');
	}

}

