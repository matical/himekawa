@extends('layouts.master')
@section('title', 'Short Links')

@section('content')
    <yuki :available-apps='@json($apps)'></yuki>
@endsection
