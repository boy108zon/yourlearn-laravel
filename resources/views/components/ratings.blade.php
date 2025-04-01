@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Product Ratings</h2>

    <h4>Average Rating: {{ number_format($averageRating, 1) }} ⭐️</h4>

    <div>
        @foreach ($ratingDistribution as $star => $count)
            <p>{{ ucfirst($star) }}: {{ $count }} ⭐️</p>
        @endforeach
    </div>

    <form action="{{ route('ratings.store') }}" method="POST">
        @csrf
        <input type="hidden" name="product_id" value="{{ request()->route('product') }}">
        <label for="rating">Your Rating:</label>
        <select name="rating" required>
            <option value="5">5 ⭐️</option>
            <option value="4">4 ⭐️</option>
            <option value="3">3 ⭐️</option>
            <option value="2">2 ⭐️</option>
            <option value="1">1 ⭐️</option>
        </select>
        <button type="submit" class="btn btn-primary">Submit Rating</button>
    </form>

    <hr>

    <h4>All Ratings:</h4>
    <ul>
        @foreach ($ratings as $rating)
            <li>{{ $rating->user->name }} rated {{ $rating->rating }} ⭐️</li>
        @endforeach
    </ul>
</div>
@endsection
