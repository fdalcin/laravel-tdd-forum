<div class="card mt-4 mb-2">
    <div class="card-header level">
        <h5 class="flex">
            <a href="#">{{ $reply->owner->name }}</a>

            said {{ $reply->created_at->diffForHumans() }}...
        </h5>

        <div>
            <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                {{ csrf_field() }}

                <button type="submit" class="btn btn-default" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                    {{ $reply->favorites()->count() }} {{ str_plural('Favorite', $reply->favorites()->count()) }}
                </button>
            </form>
        </div>
    </div>

    <div class="card-body">
        {{ $reply->body }}
    </div>
</div>