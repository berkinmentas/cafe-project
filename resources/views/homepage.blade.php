@extends('layouts.app')

@section('title', 'Ana Sayfa')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($cafes as $cafe)
            <a href="{{ route('cafe.show', $cafe) }}" class="block">
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition">
                    <img src="#" alt="{{ $cafe->name }}" class="w-full h-48 object-cover rounded-t-lg">
                    <div class="p-4">
                        <h2 class="text-xl font-semibold">{{ $cafe->name }}</h2>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
@endsection
