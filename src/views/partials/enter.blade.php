<div class="competition-entry">

	<p class="competition-question">
		{{ $competition->question }}
	</p>

	@if (Session::has('fbf_competitions_thanks_message'))

		<div class="alert alert-success competition-thanks">
			{{  Session::get('fbf_competitions_thanks_message') }}
		</div>

	@else

		@if (Session::has('fbf_competitions_error_message'))

			<div class="alert alert-danger competition-error">
				{{ trans('laravel-competitions::messages.details.' . Session::get('fbf_competitions_error_message')) }}
			</div>

		@endif

		{{ Form::open(array('action' => array('Fbf\LaravelCompetitions\CompetitionsController@enter', $competition->slug), 'class' => 'competition-form')) }}

			<div class="form-group{{ $errors->has('answer') ? ' has-error' : '' }}">

				@foreach ($competition->getAnswers() as $value => $answer)
					<div class="radio">
						<label>
							@if ($competition->isClosed() || $competition->requiresLoginButUserIsNot() || $competition->singleEntryAndUserAlreadyEntered())
								{{ Form::radio('answer', $value, Input::old('answer') == $value, array('disabled')) }}
							@else
								{{ Form::radio('answer', $value, Input::old('answer') == $value) }}
							@endif
							{{ $answer }}
						</label>
					</div>
				@endforeach

				@if ($errors->has('answer'))
					<span class="help-block">{{ $errors->first('answer') }}</span>
				@endif

			</div>

			@if ($competition->isClosed())

				<div class="competition-closed">
					{{ trans('laravel-competitions::messages.details.closed') }}
				</div>

			@elseif ($competition->requiresLoginButUserIsNot())

				<div class="competition-requires-login">
					{{ trans('laravel-competitions::messages.details.requires_login') }}
				</div>
				@include('laravel-competitions::partials.login_register_buttons')

			@elseif ($competition->singleEntryAndUserAlreadyEntered())

				<div class="competition-already-entered">
					{{ trans('laravel-competitions::messages.details.already_entered') }}
				</div>

			@else

				{{ Form::submit(trans('laravel-competitions::messages.details.enter'), array('class' => 'btn btn-default competition-enter')) }}

			@endif

		{{ Form::close() }}

	@endif

</div>