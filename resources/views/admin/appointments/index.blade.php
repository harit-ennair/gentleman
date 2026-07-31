@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">All Appointments</h1>@foreach($appointments as $appointment)<a class="rounded bg-white p-4 shadow" href="{{ route('admin.appointments.show',$appointment) }}">{{ $appointment->user->full_name }} — {{ $appointment->service->name }} — {{ $appointment->status->value }}</a>@endforeach{{ $appointments->links() }}
@endsection
