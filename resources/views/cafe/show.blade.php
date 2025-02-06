@extends('layouts.app')
@section('title', $cafe->name)

@section('content')
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h1 class="text-3xl font-bold">{{ $cafe->name }}</h1>
            <h1 class="text-3xl font-bold">{{ $cafe->name }}</h1>
        </div>
    </div>

    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($categories as $menu)
            <a href="{{ route('cafe.menu', [$cafe->id, $menu]) }}" class=" flex justify-center">
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition p-10 text-center">
                    <img src="{{ $menu->image_url }}" class="w-40 h-40 object-cover rounded mb-3">
                    <h3 class="text-xl font-semibold">{{ $menu->title }}</h3>
                </div>
            </a>
        @endforeach
    </div>
@endsection
