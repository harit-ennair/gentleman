@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Book Appointment</h1><form method="POST" action="{{ route('appointments.store') }}" class="grid max-w-lg gap-3 rounded bg-white p-5 shadow">@csrf<select class="border p-2" name="service_id">@foreach($services as $service)<option value="{{ $service->id }}">{{ $service->name }} — {{ $service->price }} DH</option>@endforeach</select><input class="border p-2" type="datetime-local" name="appointment_at" required><textarea class="border p-2" name="notes" placeholder="Notes"></textarea><button class="rounded bg-blue-600 p-2 text-white">Book</button></form>
<form action="{{ route('appointments.available-slots') }}" class="flex max-w-lg gap-2"><input class="border p-2" type="date" name="date" required><button>Check available slots</button></form>
@endsection
