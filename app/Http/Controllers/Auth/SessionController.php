<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class SessionController extends Controller
{
    public function index()
    {
        if (config('session.driver') !== 'database') {
            return back()->with('error', 'Session management requires database session driver.');
        }

        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {
                $session->agent = $this->getDevice($session->user_agent);
                $session->ip_address = $session->ip_address;
                $session->last_activity = Carbon::createFromTimestamp($session->last_activity)->diffForHumans();
                $session->is_current = $session->id === request()->session()->getId();
                return $session;
            });

        return view('profile.sessions', compact('sessions'));
    }

    public function destroy(Request $request, $sessionId)
    {
        if (config('session.driver') !== 'database') {
            return back()->with('error', 'Session management requires database session driver.');
        }

        DB::table('sessions')
            ->where('id', $sessionId)
            ->where('user_id', Auth::id())
            ->delete();

        return back()->with('status', 'Session revoked successfully.');
    }

    public function destroyOthers(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return back()->with('error', 'Session management requires database session driver.');
        }

        $request->validate(['password' => 'required']);

        if (!Hash::check($request->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => ['The provided password is incorrect.'],
            ]);
        }

        DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', '!=', $request->session()->getId())
            ->delete();

        return back()->with('status', 'Other sessions revoked successfully.');
    }

    protected function getDevice($userAgent)
    {
        if (preg_match('/(iPhone|iPad|iPod)/i', $userAgent)) {
            return 'iOS';
        } elseif (preg_match('/Android/i', $userAgent)) {
            return 'Android';
        } elseif (preg_match('/Windows/i', $userAgent)) {
            return 'Windows';
        } elseif (preg_match('/Mac/i', $userAgent)) {
            return 'macOS';
        } elseif (preg_match('/Linux/i', $userAgent)) {
            return 'Linux';
        }

        return 'Unknown';
    }
}
