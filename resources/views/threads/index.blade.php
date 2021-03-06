@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                @include('threads.list')

                {{ $threads->render() }}
            </div>

            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Trending threads
                    </div>

                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @forelse($trending as $thread)
                                <li class="list-group-item">
                                    <a href="{{ url($thread->path) }}">{{ $thread->title }}</a>
                                </li>
                            @empty
                                <p>No trending threads at this moment.</p>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
