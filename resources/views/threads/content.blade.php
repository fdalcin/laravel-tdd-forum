<div class="card" v-if="editing">
    <div class="card-header">
        <input type="text" class="form-control" value="{{ $thread->title }}">
    </div>

    <div class="card-body">
        <textarea name="body" class="form-control" rows="10" v-model="body"></textarea>
    </div>

    <div class="card-footer">
        <div class="level">
            <button class="btn btn-success btn-sm mr-2" @click="update">Update</button>

            <button class="btn btn-link" @click="cancel">Cancel</button>

            @can('update', $thread)
                <form action="{{ $thread->path() }}" method="POST" class="ml-a">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}

                    <button type="submit" class="btn btn-link">Delete thread</button>
                </form>
            @endcan
        </div>
    </div>
</div>

<div class="card" v-else>
    <div class="card-header">
        <div class="level">
            <img src="{{ $thread->owner->avatar_path }}" alt="" width="25" height="25" class="mr-2">

            <span class="flex">
                <a href="{{ route('profile', $thread->owner) }}">{{ $thread->owner->name }}</a> posted:
                {{ $thread->title }}
            </span>
        </div>
    </div>

    <div class="card-body">
        {{ $thread->body }}
    </div>

    <div class="card-footer">
        <button class="btn btn-sm" @click="editing = true">Edit</button>
    </div>
</div>