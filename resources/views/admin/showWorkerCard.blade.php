@extends('layouts.adminLayout');
@section('title')
ایجاد واحد
@endsection
@section('content')
    @foreach($cards as $card)
        <img src="{{$card->card}}" style="width: 100%; height: 600px;"/>
    @endforeach
@endsection