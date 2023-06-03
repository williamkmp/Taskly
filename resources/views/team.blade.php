@extends('layout.page')

@section('content')
    <div class="flex flex-col w-full h-full overflow-auto">
        <header class="w-full h-52 bg-pattern-{{ $team->pattern }}">

        </header>
        <div class="flex flex-col w-full h-full gap-6 px-8 py-6 overflow-auto">
            <h1>{{ $team->name }}</h1>
            <p>{{ $team->description }}</p>
        </div>
    </div>
@endsection

@pushOnce('page')
@endPushOnce
