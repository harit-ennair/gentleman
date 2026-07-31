@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">Categories</h1><div class="grid gap-3">@foreach($categories as $category)<a class="rounded bg-white p-4 shadow" href="{{ route('categories.show',$category) }}">{{ $category->name }} ({{ $category->products_count }})</a>@endforeach</div>{{ $categories->links() }}
@endsection
