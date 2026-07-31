@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Profile</h1><form method="POST" action="{{ route('profile.update') }}" class="grid max-w-lg gap-3 rounded bg-white p-5 shadow">@csrf @method('PATCH')<input class="border p-2" name="first_name" value="{{ old('first_name',$user->first_name) }}"><input class="border p-2" name="last_name" value="{{ old('last_name',$user->last_name) }}"><input class="border p-2" type="email" name="email" value="{{ old('email',$user->email) }}"><input class="border p-2" name="phone" value="{{ old('phone',$user->phone) }}"><button class="rounded bg-blue-600 p-2 text-white">Update</button></form>
@endsection
