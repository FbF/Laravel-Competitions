<div class="item-list">

	@if (!$competitions->isEmpty())

		@foreach ($competitions as $competition)
	
			<div class="item{{ $competition->is_sticky ? ' item__sticky' : '' }}{{ $competition->isClosed() ? ' item__closed' : '' }}">
		
				<h2 class="item--title">
					<a href="{{ $competition->getUrl() }}" title="{{ $competition->title }}">
						{{ $competition->title }}
					</a>
				</h2>
		
				<p class="item--closing-date">
					@if ($competition->isClosed())
						{{ trans('laravel-competitions::messages.list.is_closed') }}
					@else
						{{ trans('laravel-competitions::messages.list.closing_date', array('closing_date' => $competition->getFormattedClosingDate())) }}
					@endif
				</p>
		
				@if (!empty($competition->main_image))
					<div class="item--thumb item--thumb__image">
						<a href="{{ $competition->getUrl() }}" title="{{ $competition->title }}">
							{{ $competition->getImage('main_image', 'thumbnail') }}
						</a>
					</div>
				@endif
		
				<div class="item--summary">
					{{ $competition->summary }}
				</div>
		
				<p class="item--more-link">
					<a href="{{ $competition->getUrl() }}" title="{{ $competition->title }}">
						{{ trans('laravel-competitions::messages.list.more_link_text') }}
					</a>
				</p>
		
			</div>
	
		@endforeach

		{{ $competitions->links() }}

	@else

		<p class="item-list--empty">
			{{ trans('laravel-blog::messages.list.no_items') }}
		</p>

	@endif

</div>