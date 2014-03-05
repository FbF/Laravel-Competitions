@extends('layouts.master')

@section('page_title')
	Competitions
@endsection

@section('meta_description')
	This is the meta_description for the index page
@endsection

@section('meta_keywords')
	These are the meta_keywords for the index page
@endsection

@section('content')
	@include('laravel-competitions::partials.list')
@stop