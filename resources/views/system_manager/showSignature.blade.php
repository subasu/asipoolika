@extends('layouts.adminLayout');
@section('title')

@endsection
@section('content')
    @foreach($signatures as $signature)
        <img src="{{$signature->signature}}" style="width: 100%; height: 600px;"/>
    @endforeach
@endsection