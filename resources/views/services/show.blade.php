@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">{{ $service->name }}</h1><div class="rounded bg-white p-5 shadow"><p>{{ $service->description }}</p><p class="font-bold">{{ $service->price }} DH · {{ $service->duration }} min</p></div>
@endsection
