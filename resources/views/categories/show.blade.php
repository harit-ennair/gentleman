@extends('layouts.test')
@section('content')
<h1 class="text-2xl font-bold">{{ $category->name }}</h1><p>{{ $category->description }}</p><a class="text-blue-600" href="{{ route('categories.products',$category) }}">View products</a>
@endsection
