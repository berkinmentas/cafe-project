@extends('layouts.app')

@section('title', 'Ana Sayfa')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach($cafes as $cafe)
            <a href="{{ route('cafe.show', $cafe) }}" class="flex justify-center">
                <div class="bg-white rounded-lg shadow hover:shadow-lg transition ">
                    <img src="{{\Illuminate\Support\Facades\Vite::asset('resources/images/logo.png')}}" alt="{{ $cafe->name }}" class="w-72 h-48 object-cover rounded-t-lg">
                    <h2 class="text-xl font-semibold p-4 text-center">{{ $cafe->name }}</h2>
                </div>
            </a>
        @endforeach
    </div>
@endsection
