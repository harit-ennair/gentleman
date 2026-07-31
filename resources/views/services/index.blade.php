@extends('layouts.test')
@section('content')
<div class="flex justify-between"><h1 class="text-2xl font-bold">Services</h1>@auth<a class="rounded bg-blue-600 px-4 py-2 text-white" href="{{ route('appointments.create') }}">Book</a>@endauth</div>
<div class="grid gap-4 md:grid-cols-3">@foreach($services as $service)<a href="{{ route('services.show',$service) }}" class="rounded bg-white p-4 shadow"><h2 class="font-bold">{{ $service->name }}</h2><p>{{ $service->price }} DH · {{ $service->duration }} min</p></a>@endforeach</div>
{{ $services->links() }}
@endsection
