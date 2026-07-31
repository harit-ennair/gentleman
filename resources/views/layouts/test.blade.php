<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Gentleman Test')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">
<nav class="bg-slate-900 text-white">
    <div class="mx-auto flex max-w-7xl flex-wrap items-center gap-4 px-4 py-3 text-sm">
        <a href="{{ route('home') }}" class="font-bold">Gentleman</a>
        <a href="{{ route('services.index') }}">Services</a>
        <a href="{{ route('products.index') }}">Products</a>
        <a href="{{ route('categories.index') }}">Categories</a>
        <a href="{{ route('cart.index') }}">Cart</a>
        <a href="{{ route('appointments.index') }}">Appointments</a>
        <a href="{{ route('orders.index') }}">Orders</a>
        <a href="{{ route('admin.dashboard') }}">Admin</a>
        <span class="grow"></span>
        @auth
            <span>{{ auth()->user()->full_name }}</span>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="rounded bg-red-600 px-3 py-1">Logout</button></form>
        @else
            <a href="{{ route('login') }}">Login</a><a href="{{ route('register') }}">Register</a>
        @endauth
    </div>
</nav>
<main class="mx-auto grid max-w-7xl gap-4 px-4 py-6">
    @if(session('success'))<div class="rounded bg-green-100 p-3 text-green-800">{{ session('success') }}</div>@endif
    @if($errors->any())<div class="rounded bg-red-100 p-3 text-red-800"><ul class="list-disc pl-5">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
    @yield('content')
</main>
</body>
</html>
