@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Client Dashboard</h1>
<p class="rounded bg-white p-5 shadow">Use the navigation to test appointments, cart, orders and profile.</p>
<a class="text-blue-600" href="{{ route('profile.edit') }}">Edit profile</a>
@endsection
