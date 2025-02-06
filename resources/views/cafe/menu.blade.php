@extends('layouts.app')
@section('content')

    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold">{{ $category->title }}</h1>

        <div class="mt-8 grid gap-6">
            @foreach($products as $product)
                <div class="flex justify-between items-center border-b pb-4">
                    <div class="flex gap-4">
                        <img src="{{ $product->image_url }}" class="w-24 h-24 object-cover rounded">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $product->title }}</h3>
                            <p class="text-gray-600">{{ $product->description }}</p>
                        </div>
                    </div>
                    <div class="text-xl font-semibold">{{ number_format($product->price, 2) }} ₺</div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
