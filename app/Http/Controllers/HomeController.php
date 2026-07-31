<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Display the home page.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function index()
    {
        $services = Service::where('is_active', true)->get();
        $latestProducts = Product::with('category')->where('is_active', true)->latest()->limit(4)->get();

        if (request()->wantsJson()) {
            return response()->json([
                'services' => $services,
                'latest_products' => $latestProducts,
            ]);
        }

        return view('welcome', compact('services', 'latestProducts'));
    }
}
