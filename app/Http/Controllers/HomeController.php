<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $services = Service::where('is_active', true)->orderBy('name')->get();
        $latestProducts = Product::with('category')->where('is_active', true)->latest()->limit(4)->get();

        return view('welcome', compact('services', 'latestProducts'));
    }
}
