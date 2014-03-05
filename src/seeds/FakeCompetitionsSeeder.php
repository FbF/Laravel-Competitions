<?php namespace Fbf\LaravelCompetitions;

class FakeCompetitionsSeeder extends \Seeder {

	protected $competition;

	public function run()
	{
		$this->truncate();

		$this->faker = \Faker\Factory::create();

		$numberToCreate = \Config::get('laravel-competitions::seed.number');

		for ($i = 0; $i < $numberToCreate; $i++)
		{
			$this->create();
		}

		echo 'Database seeded' . PHP_EOL;
	}

	protected function truncate()
	{
		$replace = \Config::get('laravel-competitions::seed.replace');
		if ($replace)
		{
			\DB::table('fbf_competitions')->delete();
		}
	}

	protected function create()
	{
		$this->competition = new Competition();
		$this->setTitle();
		$this->setMedia();
		$this->setSummary();
		$this->setContent();
		$this->setQuestion();
		$this->setAnswers();
		$this->setRequiresLogin();
		$this->setMultipleEntries();
		$this->setIsSticky();
		$this->setPageTitle();
		$this->setMetaDescription();
		$this->setMetaKeywords();
		$this->setStatus();
		$this->setPublishedDate();
		$this->setClosingDate();
		$this->competition->save();
	}

	protected function setTitle()
	{
		$title = $this->faker->sentence(rand(1, 10));
		$this->competition->title = $title;
	}

	protected function setMedia()
	{
		if ($this->hasMainImage())
		{
			$this->doMainImage();
		}
	}

	protected function hasMainImage()
	{
		$mainImageFreq = \Config::get('laravel-competitions::seed.images.main_image.freq');
		$hasMainImage = $mainImageFreq > 0 && rand(1, $mainImageFreq) == $mainImageFreq;
		return $hasMainImage;
	}

	protected function doMainImage()
	{
		$imageOptions = \Config::get('laravel-competitions::images.main_image');
		if (!$imageOptions['show'])
		{
			return false;
		}
		$seedOptions = \Config::get('laravel-competitions::seed.images.main_image');
		$original = $this->faker->image(
			public_path($imageOptions['original']['dir']),
			$seedOptions['original_width'],
			$seedOptions['original_height'],
			$seedOptions['category']
		);
		$filename = basename($original);
		foreach ($imageOptions['sizes'] as $sizeOptions)
		{
			$image = $this->faker->image(
				public_path($sizeOptions['dir']),
				$sizeOptions['width'],
				$sizeOptions['height']
			);
			rename($image, public_path($sizeOptions['dir']) . $filename);
		}
		$this->competition->main_image = $filename;
		$this->competition->main_image_alt = $this->competition->title;
	}

	protected function setSummary()
	{
		$this->competition->summary = '<p>'.implode('</p><p>', $this->faker->paragraphs(rand(1, 2))).'</p>';
	}

	protected function setContent()
	{
		$this->competition->content = '<p>'.implode('</p><p>', $this->faker->paragraphs(rand(4, 10))).'</p>';
	}

	protected function setQuestion()
	{
		$this->competition->question = $this->faker->words(rand(6, 15), true).'?';
	}

	protected function setAnswers()
	{
		$this->setCorrectAnswer();
		foreach (range(1, rand(2,4)) as $num)
		{
			$this->setIncorrectAnswer($num);
		}
	}

	protected function setCorrectAnswer()
	{
		$this->competition->correct_answer = $this->faker->words(rand(1, 4), true);
	}

	protected function setIncorrectAnswer($num)
	{
		$this->competition->{'incorrect_answer_'.$num} = $this->faker->words(rand(1, 4), true);
	}

	public function setRequiresLogin()
	{
		$this->competition->is_sticky = (bool) rand(0, 1);
	}

	public function setMultipleEntries()
	{
		$this->competition->is_sticky = (bool) rand(0, 1);
	}

	protected function setIsSticky()
	{
		$this->competition->is_sticky = (bool) rand(0, 1);
	}

	protected function setPageTitle()
	{
		$this->competition->page_title = $this->competition->title;
	}

	protected function setMetaDescription()
	{
		$this->competition->meta_description = $this->faker->paragraph(rand(1, 2));
	}

	protected function setMetaKeywords()
	{
		$this->competition->meta_keywords = $this->faker->words(10, true);
	}

	protected function setStatus()
	{
		$statuses = array(
			Competition::DRAFT,
			Competition::APPROVED
		);
		$this->competition->status = $this->faker->randomElement($statuses);
	}

	protected function setPublishedDate()
	{
		$this->competition->published_date = $this->faker->dateTimeBetween('-2 years', '+1 month')->format('Y-m-d H:i:s');
	}

	protected function setClosingDate()
	{
		$this->competition->closing_date = $this->faker->dateTimeBetween('-1 weeks', '+3 months')->format('Y-m-d H:i:s');
	}
}