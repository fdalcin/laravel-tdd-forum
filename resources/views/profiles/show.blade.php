@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="page-header mb-4">
            <h1>{{ $user->name }}</h1>

            <small>Since {{ $user->created_at->diffForHumans() }}</small>
        </div>

        @foreach($threads as $thread)
            <div class="card mb-4">
                <div class="card-header">
                    <div class="level">
                        <span class="flex">
                            <a href="#">{{ $thread->owner->name }}</a> posted:
                            {{ $thread->title }}
                        </span>

                        <span>{{ $thread->created_at->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="card-body">
                    {{ $thread->body }}
                </div>
            </div>
        @endforeach

        {{ $threads->links() }}
    </div>
@endsection