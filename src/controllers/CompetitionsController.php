<?php namespace Fbf\LaravelCompetitions;

class CompetitionsController extends \BaseController {

	protected $competition;

	public function __construct(Competition $competition)
	{
		$this->competition = $competition;
	}

	public function index()
	{
		// Get the competitions
		$competitions = $this->competition->live()
			->orderBy($this->competition->getTable().'.is_sticky', 'desc')
			->orderBy($this->competition->getTable().'.closing_date', 'desc')
			->paginate(\Config::get('laravel-competitions::views.index_page.results_per_page'));

		return \View::make(\Config::get('laravel-competitions::views.index_page.view'), compact('competitions'));
	}

	public function view($slug)
	{
		// Get the selected competition
		$competition = $this->competition->live()
			->where($this->competition->getTable().'.slug', '=', $slug)
			->firstOrFail();

		return \View::make(\Config::get('laravel-competitions::views.view_page.view'), compact('competition'));
	}

	public function enter($slug)
	{
		// Get the selected competition
		$competition = $this->competition->live()
			->where($this->competition->getTable().'.slug', '=', $slug)
			->firstOrFail();

		// Make sure the user can enter the competition
		try {
			$competition->check();
		}
		catch (\Exception $e)
		{
			return \Redirect::action('Fbf\LaravelCompetitions\CompetitionsController@view', array('slug' => $slug))->with('fbf_competitions_error_message', $e->getMessage());
		}

		// Check if submitted data is valid
		$validator = \Validator::make(\Input::all(), $competition->getValidationRules(), $competition->getCustomValidationMessages());
		if ($validator->fails())
		{
			return \Redirect::action('Fbf\LaravelCompetitions\CompetitionsController@view', array('slug' => $slug))->with('fbf_competitions_error_message', $competition::INVALID)->withErrors($validator);
		}

		// Save the entry and redirect back to competition page with a thanks message
		$competition->enter(\Input::all());
		return \Redirect::action('Fbf\LaravelCompetitions\CompetitionsController@view', array('slug' => $slug))->with('fbf_competitions_thanks_message', trans('laravel-competitions::messages.details.entered'));

	}

}