@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Customers</h1><form><input class="border p-2" name="search" value="{{ request('search') }}" placeholder="Search"><button>Search</button></form>@foreach($users as $user)<a class="rounded bg-white p-4 shadow" href="{{ route('admin.users.show',$user) }}">{{ $user->full_name }} — {{ $user->email }} — {{ $user->is_active?'active':'inactive' }}</a>@endforeach{{ $users->links() }}
@endsection
