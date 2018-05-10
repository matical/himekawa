@extends('ng-layouts.master')
@section('title', 'Available Apps')
@section('meta-description', 'APKs for weeb games.')

@section('content')
    <himekawa :available-apps='@json($apps)'></himekawa>
@endsection
