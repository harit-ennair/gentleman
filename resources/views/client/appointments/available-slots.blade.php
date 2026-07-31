@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Available Slots</h1><div class="flex flex-wrap gap-2">@foreach($availableSlots as $slot)<span class="rounded bg-green-100 px-3 py-2">{{ $slot }}</span>@endforeach</div>
@endsection
