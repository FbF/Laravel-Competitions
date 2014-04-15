<?php

/**
 * Configuration options for the built-in views
 */
return array(

	/**
	 * Configuration options for the index page
	 */
	'index_page' => array(

		/**
		 * The view to use for the index pages. You can change this to a view in your app, and inside your own view you
		 * can @include the various partials in the package if you want to, or you can use this one provided, provided
		 * you have a blade layout called `master`, or you can leave this setting as it is, but create a new view file
		 * inside you own app at `app/views/packages/fbf/laravel-competitions/competitions/index.blade.php`.
		 *
		 * @type string
		 */
		'view' => 'laravel-competitions::competitions.index',

		/**
		 * The number of items to show per page on the index page
		 *
		 * @type int
		 */
		'results_per_page' => 4,

	),

	/**
	 * Configuration options for the view page
	 */
	'view_page' => array(

		/**
		 * The view to use for the detail page. You can change this to a view in your app, and inside your own view you
		 * can @include the various partials in the package if you want to, or you can use this one provided,
		 * provided you have a blade layout called `master`, or you can leave this setting as it is, but create a new
		 * view file inside you own app at `app/views/packages/fbf/laravel-competitions/competitions/view.blade.php`.
		 */
		'view' => 'laravel-competitions::competitions.view',

		/**
		 * Determines whether to show the share partial on the post view page. Note, if you want to change the share
		 * partial, just override it by creating a new file at `app/views/packages/fbf/laravel-competitions/partials/share.blade.php`
		 *
		 * @type bool
		 */
		'show_share_partial' => true,

	),

);