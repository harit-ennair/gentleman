@extends('layouts.test')
@section('title', 'Login')
@section('content')
<h1 class="text-2xl font-bold">Login</h1>
<form method="POST" action="{{ route('login') }}" class="grid max-w-md gap-3 rounded bg-white p-5 shadow">@csrf
<input class="rounded border p-2" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
<input class="rounded border p-2" type="password" name="password" placeholder="Password" required>
<label><input type="checkbox" name="remember"> Remember me</label>
<button class="rounded bg-blue-600 p-2 text-white">Login</button>
<p class="text-sm">Admin seed: admin@gentleman.com / password</p>
</form>
@endsection
