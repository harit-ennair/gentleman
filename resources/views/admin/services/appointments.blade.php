@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">{{ $service->name }} Appointments</h1>@foreach($appointments as $appointment)<a class="rounded bg-white p-4 shadow" href="{{ route('admin.appointments.show',$appointment) }}">{{ $appointment->user->full_name }} — {{ $appointment->appointment_at }} — {{ $appointment->status->value }}</a>@endforeach{{ $appointments->links() }}
@endsection
