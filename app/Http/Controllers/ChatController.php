<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ChatController extends Controller
{
    // Halaman Chat Sisi User
    public function userChat()
    {
        $messages = Message::where('user_id', Auth::id())->orderBy('created_at', 'asc')->get();
        return view('user.chat', compact('messages'));
    }

    public function sendMessage(Request $request)
    {
        $request->validate(['message' => 'required']);

        Message::create([
            'user_id' => $request->user_id ?? Auth::id(),
            'message' => $request->message,
            'sender' => Auth::user()->role == 'admin' ? 'admin' : 'user',
            'created_at' => Carbon::now('Asia/Jakarta'), // Set ke waktu lokal
        ]);

        return response()->json(['status' => 'success']);
    }

    public function adminIndex()
    {
        $users = User::whereHas('messages')
            ->withCount(['messages as unread_count' => function ($query) {
                $query->where('is_read', false)
                    ->where('sender', 'user');
            }])
            ->with(['messages' => function ($q) {
                $q->latest();
            }])
            ->get()
            ->sortByDesc(function ($user) {
                return $user->messages->first()->created_at;
            });

        return view('admin.chat.index', compact('users'));
    }

    public function adminShow($id)
    {
        $user = User::findOrFail($id);
        $messages = Message::where('user_id', $id)->orderBy('created_at', 'asc')->get();

        // Inilah yang membuat badge hilang saat halaman dibuka
        Message::where('user_id', $id)
            ->where('sender', 'user')
            ->update(['is_read' => true]);

        return view('admin.chat.show', compact('user', 'messages'));
    }
    
    // Tambahkan fungsi ini di dalam ChatController
    public function updateMessage(Request $request, $id)
    {
        $message = Message::findOrFail($id);
        $roleSender = Auth::user()->role == 'admin' ? 'admin' : 'user';

        // Cek: Apakah pengirimnya adalah orang yang sedang login?
        if ($message->sender == $roleSender) {
            $message->update(['message' => $request->message]);
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error'], 403);
    }

    public function deleteMessage($id)
    {
        $message = Message::findOrFail($id);
        $roleSender = Auth::user()->role == 'admin' ? 'admin' : 'user';

        if ($message->sender == $roleSender) {
            $message->delete();
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'error'], 403);
    }
}
