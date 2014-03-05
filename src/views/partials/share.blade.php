<div class="share-buttons">
	<p>{{ trans('laravel-competitions::messages.share.label') }}</p>
	<p class="share-button share-button__twitter">
		<a href="https://twitter.com/intent/tweet?text={{ urlencode($competition->title . ' ' . $competition->getUrl()) }}" target="_blank">
			{{ trans('laravel-competitions::messages.share.twitter') }}
		</a>
	</p>
	<p class="share-button share-button__facebook">
		<a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($competition->getUrl()) }}" target="_blank">
			{{ trans('laravel-competitions::messages.share.facebook') }}
		</a>
	</p>
</div>