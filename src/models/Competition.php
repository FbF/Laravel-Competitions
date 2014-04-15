<?php namespace Fbf\LaravelCompetitions;

use \Config,
	\Lang,
	\Auth,
	\Crypt,
	\Session,
	\URL,
	\App;

class Competition extends \Eloquent {

	/**
	 * Status values for the database
	 */
	const DRAFT = 'DRAFT';
	const APPROVED = 'APPROVED';

	const CLOSED = 'closed';
	const REQUIRES_LOGIN = 'requires_login';
	const ALREADY_ENTERED = 'already_entered';
	const INVALID = 'invalid';

	/**
	 * Name of the table to use for this model
	 * @var string
	 */
	protected $table = 'fbf_competitions';

	/**
	 * The prefix string for config options.
	 *
	 * Defaults to the package's config prefix string
	 *
	 * @var string
	 */
	protected $configPrefix = 'laravel-competitions::';

	/**
	 * Used for Cviebrock/EloquentSluggable
	 * @var array
	 */
	public static $sluggable = array(
		'build_from' => 'title',
		'save_to' => 'slug',
		'separator' => '-',
		'unique' => true,
		'include_trashed' => true,
	);

	/**
	 * Defines the Competition hasMany Entries relationship
	 *
	 * @return mixed
	 */
	public function entries()
	{
		return $this->hasMany('Fbf\LaravelCompetitions\Entry');
	}

	/**
	 * Query scope for "live" items, adds conditions for status = APPROVED and published date is in the past
	 *
	 * @param $query
	 * @return mixed
	 */
	public function scopeLive($query)
	{
		return $query->where($this->getTable().'.status', '=', Competition::APPROVED)
			->where($this->getTable().'.published_date', '<=', \Carbon\Carbon::now());
	}

	/**
	 * Query scope for "open" items, adds conditions for closing date is in the future
	 *
	 * @param $query
	 * @return mixed
	 */
	public function scopeOpen($query)
	{
		return $query->where($this->getTable().'.closing_date', '>=', \Carbon\Carbon::now());
	}

	/**
	 * Query scope for "open" items, adds conditions for closing date is in the future
	 *
	 * @param $query
	 * @return mixed
	 */
	public function scopeClosed($query)
	{
		return $query->where($this->getTable().'.closing_date', '<=', \Carbon\Carbon::now());
	}

	/**
	 * Returns the HTML img tag for the requested image type and size for this item
	 *
	 * @param $type
	 * @param $size
	 * @return null|string
	 */
	public function getImage($type, $size)
	{
		if (empty($this->$type))
		{
			return null;
		}
		$html = '<img src="' . $this->getImageSrc($type, $size) . '"';
		$html .= ' alt="' . $this->{$type.'_alt'} . '"';
		$html .= ' width="' . $this->getImageWidth($type, $size) . '"';
		$html .= ' height="' . $this->getImageHeight($type, $size) . '" />';
		return $html;
	}

	/**
	 * Returns the value for use in the src attribute of an img tag for the given image type and size
	 *
	 * @param $type
	 * @param $size
	 * @return null|string
	 */
	public function getImageSrc($type, $size)
	{
		if (empty($this->$type))
		{
			return null;
		}
		return $this->getImageConfig($type, $size, 'dir') . $this->$type;
	}

	/**
	 * Returns the value for use in the width attribute of an img tag for the given image type and size
	 *
	 * @param $type
	 * @param $size
	 * @return null|string
	 */
	public function getImageWidth($type, $size)
	{
		if (empty($this->$type))
		{
			return null;
		}
		$method = $this->getImageConfig($type, $size, 'method');

		// Width varies for images that are 'portrait', 'auto', 'fit', 'crop'
		if (in_array($method, array('portrait', 'auto', 'fit', 'crop')))
		{
			list($width) = $this->getImageDimensions($type, $size);
			return $width;
		}
		return $this->getImageConfig($type, $size, 'width');
	}

	/**
	 * Returns the value for use in the height attribute of an img tag for the given image type and size
	 *
	 * @param $type
	 * @param $size
	 * @return null|string
	 */
	public function getImageHeight($type, $size)
	{
		if (empty($this->$type))
		{
			return null;
		}
		$method = $this->getImageConfig($type, $size, 'method');

		// Height varies for images that are 'landscape', 'auto', 'fit', 'crop'
		if (in_array($method, array('landscape', 'auto', 'fit', 'crop')))
		{
			list($width, $height) = $this->getImageDimensions($type, $size);
			return $height;
		}
		return $this->getImageConfig($type, $size, 'height');
	}

	/**
	 * Returns an array of the width and height of the current instance's image $type and $size
	 *
	 * @param $type
	 * @param $size
	 * @return array
	 */
	protected function getImageDimensions($type, $size)
	{
		$pathToImage = public_path($this->getImageConfig($type, $size, 'dir') . $this->$type);
		if (is_file($pathToImage) && file_exists($pathToImage))
		{
			list($width, $height) = getimagesize($pathToImage);
		}
		else
		{
			$width = $height = false;
		}
		return array($width, $height);
	}

	/**
	 * Returns the config setting for an image
	 *
	 * @param $imageType
	 * @param $size
	 * @param $property
	 * @internal param $type
	 * @return mixed
	 */
	public function getImageConfig($imageType, $size, $property)
	{
		$config = $this->getConfigPrefix().'images.' . $imageType . '.';
		if ($size == 'original')
		{
			$config .= 'original.';
		}
		elseif (!is_null($size))
		{
			$config .= 'sizes.' . $size . '.';
		}
		$config .= $property;
		return Config::get($config);
	}

	/**
	 * Returns the locale formatted closing date, in the locale's timezone, both of which can be overridden in the language file
	 * @return string
	 */
	public function getFormattedClosingDate()
	{
		$date = $this->getClosingDateAsCarbonInstance();
		if (Lang::has('laravel-competitions::messages.closing_date.timezone'))
		{
			$oldTimezone = date_default_timezone_get();
			$newTimezone = Lang::get('laravel-competitions::messages.closing_date.timezone');
			$date->setTimezone($newTimezone);
			date_default_timezone_set($newTimezone);
		}
		$locale = App::getLocale();
		if (Lang::has('laravel-competitions::messages.closing_date.locale'))
		{
			$locale = Lang::get('laravel-competitions::messages.closing_date.locale');
		}
		setlocale(LC_TIME, $locale);
		$dateFormat = trans('laravel-competitions::messages.closing_date.format');
		if ($dateFormat == 'laravel-competitions::messages.closing_date.format')
		{
			$dateFormat = '%e %B %Y at %H:%M';
		}
		$date = $date->formatLocalized($dateFormat);
		if (Lang::has('laravel-competitions::messages.closing_date.timezone'))
		{
			date_default_timezone_set($oldTimezone);
		}
		return $date;
	}

	/**
	 * @return bool
	 */
	public function isClosed()
	{
		$closingDate = $this->getClosingDateAsCarbonInstance();
		return $closingDate->isPast();
	}

	/**
	 * @return \Carbon\Carbon
	 */
	public function getClosingDateAsCarbonInstance()
	{
		$closingDate = $this->closing_date;
		$closingDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $closingDate, 'UTC');
		return $closingDate;
	}

	/**
	 * Returns the URL of the post
	 * @return string
	 */
	public function getUrl()
	{
		return URL::action('Fbf\LaravelCompetitions\CompetitionsController@view', array('slug' => $this->slug));
	}

	/**
	 * Returns the config prefix
	 *
	 * @return string
	 */
	public function getConfigPrefix()
	{
		return $this->configPrefix;
	}

	/**
	 * Sets the config prefix string
	 *
	 * @param $configBase string
	 * @return string
	 */
	public function setConfigPrefix($configBase)
	{
		return $this->configPrefix = $configBase;
	}

	/**
	 * Returns an array of answers for the current competition. The answers are uniquely keyed based on the competition
	 * id and the field name of the answer, i.e. correct_answer, incorrect_answer_1 etc, then encrypted using the
	 * application's key. The answers are then randomised, and finally, prefixed with a string e.g. "A) ", "B) " etc
	 * (managed in the messages translations file).
	 *
	 * @return array
	 */
	public function getAnswers()
	{
		if (Session::has('competitions.'.$this->id.'.answers'))
		{
			return Session::get('competitions.'.$this->id.'.answers');
		}

		$answers = array(
			Crypt::encrypt($this->id.'.correct_answer') => $this->correct_answer,
		);

		foreach (range(1, 4) as $num)
		{
			if (!empty($this->{'incorrect_answer_'.$num}))
			{
				$answers[Crypt::encrypt($this->id.'.incorrect_answer_'.$num)] = $this->{'incorrect_answer_'.$num};
			}
		}

		$tmp = array();

		$keys = array_keys($answers);

		shuffle($keys);

		foreach($keys as $key) {
			$tmp[$key] = $answers[$key];
		}

		$answers = $tmp;

		$i = 1;
		foreach ($answers as $value => $answer)
		{
			$answers[$value] = trans('laravel-competitions::messages.details.answer_prefix.' . $i) . $answer;
			$i++;
		}

		Session::put('competitions.'.$this->id.'.answers', $answers);

		return $answers;

	}

	/**
	 * Determines if the given answer is the correct one. Checking if the answers are correct is done based on the
	 * field, so even if the administrator changes the value of the correct answer in between when a user loads the
	 * page and when they submit the answer, they can still have the correct answer.
	 *
	 * @param $answer
	 * @return bool
	 */
	public function isCorrect($answer)
	{
		return $this->getAnswerField($answer) == 'correct_answer';
	}

	/**
	 * Gets the answer field, i.e.
	 *
	 * @param $answer
	 * @return string
	 */
	public function getAnswerField($answer)
	{
		$decryptedAnswerField = Crypt::decrypt($answer);
		list($competitionId, $answerField) = explode('.', $decryptedAnswerField);
		if ($competitionId != $this->id)
		{
			return false;
		}
		return $answerField;
	}

	/**
	 * Returns the actual answer the user chose
	 *
	 * @param $answer
	 * @return string
	 */
	public function getAnswer($answer)
	{
		$answers = $this->getAnswers();
		if (!array_key_exists($answer, $answers))
		{
			return false;
		}
		return $answers[$answer];
	}

	/**
	 * Returns the validation rules for the competition
	 *
	 * @return array
	 */
	public function getValidationRules()
	{
		return array(
			'answer' => 'required'
		);
	}

	/**
	 * Returns the custom validation messages for the competition
	 *
	 * @return array
	 */
	public function getCustomValidationMessages()
	{
		return array(
			'answer.required' => 'Please select an answer'
		);
	}

	/**
	 * Checks whether the user can enter the competition. Performs a series of checks including:, whether the competition
	 * is closed, whether the competition requires the user is logged to enter, but they are not, and whether the
	 * competition can only be entered once per user and the current logged in user has already entered.
	 *
	 * Throws an exception if any of these situations arise and the user cannot enter the competition. The message of the
	 * exception is also used in the translation key for the error message to display to the user.
	 *
	 * @throws \Exception
	 */
	public function check()
	{
		if ($this->isClosed())
		{
			throw new \Exception(self::CLOSED);
		}
		if ($this->requiresLoginButUserIsNot())
		{
			throw new \Exception(self::REQUIRES_LOGIN);
		}
		if ($this->singleEntryAndUserAlreadyEntered())
		{
			throw new \Exception(self::ALREADY_ENTERED);
		}

	}

	/**
	 * Determines whether the competition requires the user to be logged in, but they are not
	 *
	 * @return bool
	 */
	public function requiresLoginButUserIsNot()
	{
		if (!$this->requires_login)
		{
			return false;
		}
		return !Auth::check();
	}

	/**
	 * Determines whether the competition allows one entry per user and the current logged in user has already entered
	 *
	 * @return bool
	 */
	public function singleEntryAndUserAlreadyEntered()
	{
		if ($this->multiple_entries)
		{
			return false;
		}
		if (!Auth::check())
		{
			return false;
		}
		$entryClass = $this->entries()->getModel();
		return (bool)$entryClass::where('user_id', '=', Auth::user()->id)
			->where('competition_id', '=', $this->id)
			->count();
	}

	/**
	 * Stores the entry
	 *
	 * @param $input array
	 * @return bool
	 */
	public function enter($input)
	{
		$entryClass = $this->entries()->getModel();
		$entry = new $entryClass;
		$entry->answer = $this->getAnswer($input['answer']);
		$entry->is_correct = $this->isCorrect($input['answer']);
		if ($this->requires_login)
		{
			$entry->user_id = Auth::user()->id;
		}
		return $this->entries()->save($entry);
	}

}