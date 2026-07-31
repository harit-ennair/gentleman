@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Appointment</h1><div class="rounded bg-white p-5 shadow"><p>{{ $appointment->user->full_name }} / {{ $appointment->service->name }}</p><p>{{ $appointment->appointment_at }} — {{ $appointment->status->value }}</p><div class="flex gap-2">@foreach(['confirm','complete','cancel'] as $action)<form method="POST" action="{{ route('admin.appointments.'.$action,$appointment) }}">@csrf<button class="rounded bg-slate-700 p-2 text-white">{{ ucfirst($action) }}</button></form>@endforeach</div></div>
@endsection
