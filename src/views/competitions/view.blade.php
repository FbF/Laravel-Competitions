@extends('layouts.master')

@section('title')
	{{ $competition->page_title }}
@endsection

@section('meta_description')
	{{ $competition->meta_description }}
@endsection

@section('meta_keywords')
	{{ $competition->meta_keywords }}
@endsection

@section('content')
	@include('laravel-competitions::partials.details')
@stop