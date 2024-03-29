@extends('layouts.app')
@section('title', $title)

@section('content')
    <div class="offset-md-2 col-md-8">
        <h2 class="mb-4 text-center">{{ $title }}</h2>

        <div class="list-group list-group-flush">

            @foreach ($users as $user)
                <div class="list-group-item">
                    <img class="mr-3" src="{{ $user->avatar }}" alt="{{ $user->name }}" width=32>
                    <a href="{{ route('users.show', $user) }}">
                        {{ $user->name }}
                    </a>
                    @if (Auth::check())
                        @include('users._follow_form')
                    @endif
                </div>
            @endforeach
        </div>

        <div class="mt-3">
            {!! $users->render() !!}
        </div>
    </div>

@stop
