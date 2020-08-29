@extends('layouts.minesweeper')
@section('sidebar')
    <gameslist userid="{{$userid}}"><gameslist>
@endsection
@section('content')
    <h1>{{$username}}</h1>
    <minesweeper userid="{{$userid}}" game="{{json_encode($game)}}"></minesweeper>
@endsection