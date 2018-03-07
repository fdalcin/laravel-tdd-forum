<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class ThreadSubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channel, Thread $thread)
    {
        $thread->subscribe();

        return response(['status' => 'You have been subscribed to this thread']);
    }

    public function destroy($channel, Thread $thread)
    {
        $thread->unsubscribe();

        return response(['status' => 'You have been unsubscribed from this thread']);
    }
}
