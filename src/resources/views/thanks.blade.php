@extends('layouts.app')

@section('hideHeader', true)

@section('css')
    <link rel="stylesheet" href="{{ asset('css/thanks.css') }}">
@endsection

@section('content')
    <div class="thanks-wrap">
        <p class="thanks-message">お問い合わせありがとうございました</p>
        <a class="home-btn" href="/">HOME</a>
    </div>
@endsection