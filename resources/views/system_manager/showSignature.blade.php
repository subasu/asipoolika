@extends('layouts.adminLayout')
@section('content')
    <div>
    @foreach($signatures as $signature)
        <img src="{{$signature->signature}}" style="width: 100%; height: 600px;"/>
    @endforeach
@endsection