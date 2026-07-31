@extends('layouts.test')
@section('title', 'Register')
@section('content')
<h1 class="text-2xl font-bold">Register</h1>
<form method="POST" action="{{ route('register') }}" class="grid max-w-md gap-3 rounded bg-white p-5 shadow">@csrf
<input class="rounded border p-2" name="first_name" value="{{ old('first_name') }}" placeholder="First name" required>
<input class="rounded border p-2" name="last_name" value="{{ old('last_name') }}" placeholder="Last name" required>
<input class="rounded border p-2" type="email" name="email" value="{{ old('email') }}" placeholder="Email" required>
<input class="rounded border p-2" name="phone" value="{{ old('phone') }}" placeholder="Phone">
<input class="rounded border p-2" type="password" name="password" placeholder="Password" required>
<input class="rounded border p-2" type="password" name="password_confirmation" placeholder="Confirm password" required>
<button class="rounded bg-blue-600 p-2 text-white">Register</button>
</form>
@endsection
