@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Appointment</h1><div class="rounded bg-white p-5 shadow"><p>{{ $appointment->service->name }}</p><p>{{ $appointment->appointment_at }} — {{ $appointment->status->value }}</p><p>{{ $appointment->notes }}</p><form method="POST" action="{{ route('appointments.cancel',$appointment) }}">@csrf<button class="text-red-600">Cancel</button></form></div>
@endsection
