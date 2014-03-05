<div class="item{{ $competition->is_sticky ? ' item__sticky' : '' }}{{ $competition->isClosed() ? ' item__closed' : '' }}">

	<p class="item--all-link">
		<a href="{{ action('Fbf\LaravelCompetitions\CompetitionsController@index') }}">
			{{ trans('laravel-competitions::messages.details.all_link_text') }}
		</a>
	</p>

	<h2 class="item--title">
		{{ $competition->title }}
	</h2>

	<p class="item--closing-date">
		@if ($competition->isClosed())
			{{ trans('laravel-competitions::messages.list.is_closed') }}
		@else
			{{ trans('laravel-competitions::messages.list.closing_date', array('closing_date' => $competition->getFormattedClosingDate())) }}
		@endif
	</p>

	<div class="item--summary">
		{{ $competition->summary }}
	</div>

	@if (Config::get('laravel-competitions::views.view_page.show_share_partial'))
		@include('laravel-competitions::partials.share')
	@endif

	@if (!empty($competition->main_image))
		<div class="item--media item--media__image">
			{{ $competition->getImage('main_image', 'resized') }}
		</div>
	@endif

	{{ $competition->content }}

	@include('laravel-competitions::partials.enter')

</div>