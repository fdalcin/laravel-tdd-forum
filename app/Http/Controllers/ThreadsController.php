<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Rules\SpamFree;
use App\Thread;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class ThreadsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        if (request()->wantsJson()) {
            return $threads;
        }

        $trending = array_map('json_decode', Redis::zrevrange('trending_threads', 0, 4));

        return view('threads.index', compact('threads', 'trending'));
    }

    public function create()
    {
        return view('threads.create');
    }

    public function store()
    {
        request()->validate([
            'title'      => ['required', resolve(SpamFree::class)],
            'body'       => ['required', resolve(SpamFree::class)],
            'channel_id' => 'required|exists:channels,id',
        ]);

        $thread = Thread::create([
            'user_id'    => auth()->id(),
            'channel_id' => request('channel_id'),
            'title'      => request('title'),
            'body'       => request('body'),
        ]);

        return redirect($thread->path())
            ->with('flash', 'Your thread has been published.');
    }

    public function show($channel, Thread $thread)
    {
        if (auth()->check()) {
            auth()->user()->read($thread);
        }

        Redis::zincrby('trending_threads', 1, json_encode([
            'title' => $thread->title,
            'path'  => $thread->path(),
        ]));

        return view('threads.show', compact('thread'));
    }

    public function destroy($channel, Thread $thread)
    {
        $this->authorize('update', $thread);

        $thread->delete();

        return redirect('/threads');
    }

    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = Thread::latest()->filter($filters);

        if ($channel->exists) {
            $threads->where('channel_id', $channel->id);
        }

        return $threads->paginate(10);
    }
}
