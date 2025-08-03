<?php

namespace App\Http\Controllers;
// use App\Events\StartVideoChat; // You'll create this event
use App\Events\VideoChatSignal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Inertia\Inertia;
use Pusher\Pusher;

class VideoChatController extends Controller
{

    protected $pusher;

    public function __construct()
    {
        $this->pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            ['cluster' => env('PUSHER_APP_CLUSTER'), 'useTLS' => true]
        );
    }

    public function index()
    {

        $users = User::where('id', '!=', Auth::id())->get(['id', 'name']);
        return Inertia::render('VideoChat', [
            'users' => $users,
            'currentUser' => Auth::user()->only(['id', 'name']),
            'pusherKey' => env('PUSHER_APP_KEY'),
            'pusherCluster' => env('PUSHER_APP_CLUSTER'),
        ]);

    }

    public function pusherAuth(Request $request)
    {
        if (Auth::check()) {

            return $this->pusher->socket_auth($request->input('channel_name'), $request->input('socket_id'));
        } else {
            return response('Forbidden', 403);
        }
    }

    public function callUser(Request $request, $userToCallId)
    {
        $user = Auth::user();
        $data = [
            'type' => 'incoming-call',
            'from' => $user->id,
            'fromName' => $user->name,
            'sdp' => $request->sdp,
        ];
        $this->pusher->trigger('private-user.' . $userToCallId, 'video-chat-event', $data);
        return response()->json(['status' => 'call initiated']);
    }

    public function acceptCall(Request $request)
    {
        $user = Auth::user();
        $data = [
            'type' => 'call-accepted',
            'from' => $user->id,
            'sdp' => $request->sdp,
        ];
        $this->pusher->trigger('private-user.' . $request->callerId, 'video-chat-event', $data);
        return response()->json(['status' => 'call accepted']);
    }

    public function sendCandidate(Request $request)
    {
        $user = Auth::user();
        $data = [
            'type' => 'ice-candidate',
            'from' => $user->id,
            'candidate' => $request->candidate,
        ];
        $this->pusher->trigger('private-user.' . $request->toUserId, 'video-chat-event', $data);
        return response()->json(['status' => 'candidate sent']);
    }

    public function declineCall(Request $request)
    {
        $user = Auth::user();
        $data = [
            'type' => 'call-declined',
            'from' => $user->id,
        ];
        // Notify the user who initiated the call
        $this->pusher->trigger('private-user.' . $request->callerId, 'video-chat-event', $data);
        return response()->json(['status' => 'call declined']);
    }

    public function endCall(Request $request)
    {
        $user = Auth::user();
        $data = [
            'type' => 'call-ended',
            'from' => $user->id,
        ];
        $this->pusher->trigger('private-user.' . $request->toUserId, 'video-chat-event', $data);
        return response()->json(['status' => 'call ended']);
    }



}
