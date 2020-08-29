@extends('layouts.minesweeper')
@section('content')
    <h1>{{$username}}</h1>
    <minesweeper userid="{{$userid}}"></minesweeper>
@endsection