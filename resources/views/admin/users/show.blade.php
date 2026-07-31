@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">{{ $user->full_name }}</h1><form method="POST" action="{{ route('admin.users.update',$user) }}" class="grid max-w-lg gap-2 rounded bg-white p-4">@csrf @method('PUT')<input class="border p-2" name="first_name" value="{{ $user->first_name }}"><input class="border p-2" name="last_name" value="{{ $user->last_name }}"><input class="border p-2" name="email" value="{{ $user->email }}"><input class="border p-2" name="phone" value="{{ $user->phone }}"><button class="bg-blue-600 p-2 text-white">Update</button></form><form method="POST" action="{{ route('admin.users.toggle-status',$user) }}">@csrf<button class="text-red-600">Toggle account</button></form>
@endsection
