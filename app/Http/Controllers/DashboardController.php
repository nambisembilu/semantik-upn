<?php

namespace App\Http\Controllers;

use App\Models\Master\Periode;
use App\Models\Transaksi\Skor;

class DashboardController extends Controller
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

    public function index()
    {
        $data=Periode::get();
        $index=0;
        foreach ($data as $d) {
            $data[$index]->detail=Skor::where('id_periode',$d->id_periode)->get();
            $index++;
        }

        return view('home', compact('data'));
    }
}
