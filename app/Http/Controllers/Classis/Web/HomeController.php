<?php

namespace App\Http\Controllers\Classis\Web;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
//       $this->middleware(["auth"]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $usuario = Auth::user();

        if (!Auth::check($usuario)) {
            return response()->redirectToRoute('logout');
        }

        $menus = Auth::user()->montaMenu();
        return view('front.home', [
            "menus" => $menus
        ]);
    }

    public function login()
    {
        $usuario = Auth::user();

        if (Auth::check($usuario)) {
            return response()->redirectToRoute('home');
        }

        return view('front.login');
    }
}
