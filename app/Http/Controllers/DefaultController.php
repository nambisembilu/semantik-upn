<?php

namespace App\Http\Controllers;

use App\Models\Master\Personal;
use App\Models\Master\PersonalWorkUnit;
use Illuminate\Support\Facades\Auth;

class DefaultController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $personal = Personal::where('user_id', $user->id)->first();
        $data = [
            'personal' => $personal,
        ];
        return view('dashboard', $data);
    }
}
