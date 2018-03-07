<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['channel', 'owner'];

    protected $appends = ['isSubscribedTo'];

    public static function boot()
    {
        parent::boot();

        static::deleting(function ($thread) {
            $thread->replies->each->delete();
        });
    }

    public function path()
    {
        return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function addReply($reply)
    {
        return $this->replies()->create($reply);
    }

    public function subscribe()
    {
        $this->subscriptions()->create([
            'user_id' => auth()->id()
        ]);
    }

    public function unsubscribe()
    {
        $this->subscriptions()->where('user_id', auth()->id())->delete();
    }

    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()->where('user_id', auth()->id())->exists();
    }

    public function scopeFilter($query, $filters)
    {
        return $filters->apply($query);
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }
}
