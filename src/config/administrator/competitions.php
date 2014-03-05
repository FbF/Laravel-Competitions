<?php

return array(

	/**
	 * Model title
	 *
	 * @type string
	 */
	'title' => 'Competitions',

	/**
	 * The singular name of your model
	 *
	 * @type string
	 */
	'single' => 'competition',

	/**
	 * The class name of the Eloquent model that this config represents
	 *
	 * @type string
	 */
	'model' => 'Fbf\LaravelCompetitions\Competition',

	/**
	 * The columns array
	 *
	 * @type array
	 */
	'columns' => array(
		'title' => array(
			'title' => 'Title'
		),
		'published_date' => array(
			'title' => 'Published'
		),
		'closing_date' => array(
			'title' => 'Closing date'
		),
		'status' => array(
			'title' => 'Status',
			'select' => "CASE (:table).status WHEN '".Fbf\LaravelCompetitions\Competition::APPROVED."' THEN 'Approved' WHEN '".Fbf\LaravelCompetitions\Competition::DRAFT."' THEN 'Draft' END",
		),
		'updated_at' => array(
			'title' => 'Last Updated'
		),
	),

	/**
	 * The edit fields array
	 *
	 * @type array
	 */
	'edit_fields' => array(
		'title' => array(
			'title' => 'Title',
			'type' => 'text',
		),
		'main_image' => array(
			'title' => 'Main Image',
			'type' => 'image',
			'naming' => 'random',
			'location' => public_path() . Config::get('laravel-competitions::images.main_image.original.dir'),
			'size_limit' => 5,
			'sizes' => array(
				array(
					Config::get('laravel-competitions::images.main_image.sizes.thumbnail.width'),
					Config::get('laravel-competitions::images.main_image.sizes.thumbnail.height'),
					Config::get('laravel-competitions::images.main_image.sizes.thumbnail.method'),
					public_path() . Config::get('laravel-competitions::images.main_image.sizes.thumbnail.dir'),
					100
				),
				array(
					Config::get('laravel-competitions::images.main_image.sizes.resized.width'),
					Config::get('laravel-competitions::images.main_image.sizes.resized.height'),
					Config::get('laravel-competitions::images.main_image.sizes.resized.method'),
					public_path() . Config::get('laravel-competitions::images.main_image.sizes.resized.dir'),
					100
				),
			),
		),
		'main_image_alt' => array(
			'title' => 'Image ALT text',
			'type' => 'text',
		),
		'summary' => array(
			'title' => 'Summary',
			'type' => 'wysiwyg',
		),
		'content' => array(
			'title' => 'Content',
			'type' => 'wysiwyg',
		),
		'question' => array(
			'title' => 'Question',
			'type' => 'text',
		),
		'correct_answer' => array(
			'title' => 'Correct Answer',
			'type' => 'text',
		),
		'incorrect_answer_1' => array(
			'title' => 'Incorrect Answer 1',
			'type' => 'text',
		),
		'incorrect_answer_2' => array(
			'title' => 'Incorrect Answer 2',
			'type' => 'text',
		),
		'incorrect_answer_3' => array(
			'title' => 'Incorrect Answer 3',
			'type' => 'text',
		),
		'incorrect_answer_4' => array(
			'title' => 'Incorrect Answer 4',
			'type' => 'text',
		),
		'requires_login' => array(
			'title' => 'Users must be logged in to enter?',
			'type' => 'bool',
		),
		'multiple_entries' => array(
			'title' => 'Users can enter more than once (there is no restriction if the competition does not require a user to be logged in)',
			'type' => 'bool',
		),
		'is_sticky' => array(
			'title' => 'Is sticky?',
			'type' => 'bool',
		),
		'slug' => array(
			'title' => 'Slug',
			'type' => 'text',
			'visible' => function($model)
				{
					return $model->exists;
				},
		),
		'page_title' => array(
			'title' => 'Page Title',
			'type' => 'text',
		),
		'meta_description' => array(
			'title' => 'Meta Description',
			'type' => 'textarea',
		),
		'meta_keywords' => array(
			'title' => 'Meta Keywords',
			'type' => 'textarea',
		),
		'published_date' => array(
			'title' => 'Published Date',
			'type' => 'datetime',
			'date_format' => 'yy-mm-dd', //optional, will default to this value
			'time_format' => 'HH:mm',    //optional, will default to this value
		),
		'closing_date' => array(
			'title' => 'Closing Date',
			'type' => 'datetime',
			'date_format' => 'yy-mm-dd', //optional, will default to this value
			'time_format' => 'HH:mm',    //optional, will default to this value
		),
		'status' => array(
			'type' => 'enum',
			'title' => 'Status',
			'options' => array(
				Fbf\LaravelCompetitions\Competition::DRAFT => 'Draft',
				Fbf\LaravelCompetitions\Competition::APPROVED => 'Approved',
			),
		),
		'created_at' => array(
			'title' => 'Created',
			'type' => 'datetime',
			'editable' => false,
		),
		'updated_at' => array(
			'title' => 'Updated',
			'type' => 'datetime',
			'editable' => false,
		),
	),

	/**
	 * The filter fields
	 *
	 * @type array
	 */
	'filters' => array(
		'title' => array(
			'title' => 'Title',
			'type' => 'text',
		),
		'summary' => array(
			'title' => 'Summary',
			'type' => 'text',
		),
		'content' => array(
			'title' => 'Content',
			'type' => 'text',
		),
		'question' => array(
			'title' => 'Question',
			'type' => 'text',
		),
		'published_date' => array(
			'title' => 'Published Date',
			'type' => 'date',
		),
		'status' => array(
			'type' => 'enum',
			'title' => 'Status',
			'options' => array(
				Fbf\LaravelCompetitions\Competition::DRAFT => 'Draft',
				Fbf\LaravelCompetitions\Competition::APPROVED => 'Approved',
			),
		),
	),

	/**
	 * The width of the model's edit form
	 *
	 * @type int
	 */
	'form_width' => 500,

	/**
	 * The validation rules for the form, based on the Laravel validation class
	 *
	 * @type array
	 */
	'rules' => array(
		'title' => 'required|max:255',
//		'main_image' => 'required|max:255',
//		'main_image_alt' => 'max:255|required',
		'summary' => 'required',
		'content' => 'required',
		'question' => 'required|max:255',
		'correct_answer' => 'required|max:255',
		'incorrect_answer_1' => 'required|max:255',
		'incorrect_answer_2' => 'required|max:255',
		'incorrect_answer_3' => 'max:255',
		'incorrect_answer_4' => 'max:255',
		'page_title' => 'max:255',
		'status' => 'required|in:'.Fbf\LaravelCompetitions\Competition::DRAFT.','.Fbf\LaravelCompetitions\Competition::APPROVED,
		'published_date' => 'required|date_format:"Y-m-d H:i:s"|date',
		'closing_date' => 'required|date_format:"Y-m-d H:i:s"|date',
	),

	/**
	 * The sort options for a model
	 *
	 * @type array
	 */
	'sort' => array(
		'field' => 'updated_at',
		'direction' => 'desc',
	),

	/**
	 * If provided, this is run to construct the front-end link for your model
	 *
	 * @type function
	 */
	'link' => function($model)
		{
			return $model->getUrl();
		},

	/**
	 * This is where you can define the model's custom actions
	 */
	'actions' => array(
		'download_entries' => array(
			'title' => 'Download Entries',
			'messages' => array(
				'active' => 'Downloading...',
				'success' => 'Download finished',
				'error' => 'There was an error while downloading the entries',
			),
			'permission' => function($model)
				{
					return true;
				},
			//the model is passed to the closure
			'action' => function($model)
				{
					$fields = array(
						'users.email' => 'Email',
						'fbf_competition_entries.is_correct' => 'Correct answer?',
						'fbf_competition_entries.created_at' => 'Created',
					);

					//get all the rows for this query
					$entryClass = $model->entries()->getModel();

					$result = $entryClass::select(array_keys($fields))
						->join('users', 'fbf_competition_entries.user_id', '=', 'users.id')
						->where('competition_id', '=', $model->id)
						->get()
						->toArray();

					$filePath = storage_path() . DIRECTORY_SEPARATOR . 'entries_'.$model->id.'_'.date('YmdHis').'.csv';

					$fp = fopen($filePath, 'w');

					$separator = "\t";
					$endofline = "\n";

					fwrite($fp, chr(255) . chr(254));
					$fields = '"'.implode("\"$separator\"",$fields).'"'.$endofline;
					$fields = mb_convert_encoding($fields, 'UTF-16LE', 'UTF-8');
					fwrite($fp, $fields);

					foreach ($result as $row)
					{
						// Convert all " and make them ""
						array_walk($row, function(&$value) {
							$value = str_replace('"', '""', $value);
						});
						// Add each row, fields enclosed by ", terminated by \t as per M$ Excel
						$line = '"'.implode("\"$separator\"",$row).'"'.$endofline;
						$line = mb_convert_encoding($line, 'UTF-16LE', 'UTF-8');
						fwrite($fp, $line);
					}

					//return a download response
					return Response::download($filePath);

				}
		),

	),

);