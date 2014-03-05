<?php

/**
 * Config settings for the Seed
 */
return array(

	/**
	 * Should the seeder append (replace = false) or replace (true)
	 */
	'replace' => true,

	/**
	 * Number of fake items to create
	 */
	'number' => 20,

	/**
	 * Configuration options for the images for the fake seeder
	 */
	'images' => array(

		/**
		 * Configuration options for the main image for the fake seeder
		 */
		'main_image' => array(

			/**
			 * One in every X posts that is not a YouTube Video, has a main image (use 0 for no main images, or set
			 * images.main_image.show to false)
			 */
			'freq' => 2,

			/**
			 * Lorem Pixel category to use for the main image
			 */
			'category' => 'abstract',

			/**
			 * Width of the original image to fetch by the fake seeder
			 */
			'original_width' => 400,

			/**
			 * Height of the original image to fetch by the fake seeder
			 */
			'original_height' => 400,

		),

	),

);