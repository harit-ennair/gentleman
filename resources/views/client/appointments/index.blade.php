@extends('layouts.test')
@section('content')
<div class="flex justify-between"><h1 class="text-2xl font-bold">My Appointments</h1><a class="rounded bg-blue-600 p-2 text-white" href="{{ route('appointments.create') }}">Book</a></div>
@forelse($appointments as $appointment)<a class="rounded bg-white p-4 shadow" href="{{ route('appointments.show',$appointment) }}">{{ $appointment->service->name }} — {{ $appointment->appointment_at }} — {{ $appointment->status->value }}</a>@empty<p>No appointments.</p>@endforelse{{ $appointments->links() }}
@endsection
