<reply :attributes="{{ $reply }}" inline-template v-cloak>
    <div id="reply-{{ $reply->id }}" class="card mt-4 mb-2">
        <div class="card-header level">
            <h5 class="flex">
                <a href="{{ route('profile', $reply->owner) }}">{{ $reply->owner->name }}</a>

                said {{ $reply->created_at->diffForHumans() }}...
            </h5>

            <div>
                <form method="POST" action="/replies/{{ $reply->id }}/favorites">
                    {{ csrf_field() }}

                    <button type="submit" class="btn btn-sm btn-secondary" {{ $reply->isFavorited() ? 'disabled' : '' }}>
                        {{ $reply->favorites_count }} {{ str_plural('Favorite', $reply->favorites_count) }}
                    </button>
                </form>
            </div>
        </div>

        <div class="card-body">
            <div v-if="editing">
                <div class="from-group mb-2">
                    <textarea class="form-control" v-model="body"></textarea>
                </div>
                
                <button class="btn btn-sm btn-success" @click="update">Update</button>
                <button class="btn btn-link" @click="editing = false">Cancel</button>
            </div>

            <div v-else v-text="body"></div>
        </div>

        @can('update', $reply)
            <div class="card-footer level">
                <button class="btn btn-sm mr-2" @click="editing = true">Edit</button>

                <form method="POST" action="/replies/{{ $reply->id }}">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">Delete</button>
                </form>
            </div>
        @endcan
    </div>
</reply>