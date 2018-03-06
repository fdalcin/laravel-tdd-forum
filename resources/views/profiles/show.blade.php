@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="page-header mb-4">
                    <h1>{{ $user->name }}</h1>
                </div>

                @foreach($activities as $date => $activity)
                    <div class="page-header mb-2">
                        <h4>{{ $date }}</h4>
                    </div>

                    @foreach($activity as $record)
                        @if(view()->exists('profiles.activities.' . $record->type))
                            @include('profiles.activities.' . $record->type, ['activity' => $record])
                        @endif
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
@endsection