@extends('layouts.master')
@section('title', 'User Profile')
@section('content')
<div class="row">
    <div class="alert alert-success">
        <div class="m-4 col-sm-6">
            <strong> Congratulation! </strong>
            Dear {{$user->name}}, your email {{$user->email}} is verified.

        </div>
    </div>
</div>
@endsection