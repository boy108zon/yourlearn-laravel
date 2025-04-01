

<div class="reviews mt-4 bg-white rounded shadow-sm">
    
    <hr class="gradient-hr">
    <h4 class="fw-bold text-dark mb-0">Ratings & Reviews</h4>
        
    <div class="rating-summary mb-2 text-center">
        <h4 class="mb-0 text-warning">
           {{ number_format($averageRating, 1) }} ⭐️
        </h4>
        <small class="text-muted">{{ $ratingCount }} Ratings</small>
    </div>

    <div class="rating-breakdown mb-4">
    <strong class="me-2">Review</strong><div class="rating d-flex align-items-center mb-2" data-initial-rating="0">
            @for ($i = 5; $i >= 1; $i--)
                <span class="star" data-value="{{ $i }}">&starf;</span>
            @endfor
        </div>
        @for ($i = 5; $i >= 1; $i--)
            <div class="d-flex align-items-center mb-2">
                <span class="me-3 text-muted fw-bold" style="min-width: 50px;">{{ $i }} ⭐️</span>
                
                <div class="progress flex-grow-1" style="height: 10px;">
                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $ratingPercentages[$i] ?? 0 }}%;" aria-valuenow="{{ $ratingPercentages[$i] ?? 0 }}" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                
                <span class="ms-3 text-muted" style="min-width: 30px;">{{ $ratingCounts[$i] ?? 0 }}</span>
            </div>
        @endfor
    </div>

    <div class="user-reviews">
        @forelse ($ratings as $rating)
            <div class="card mb-3 shadow-sm border-0 rounded">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <strong class="me-2">{{ $rating->user->name }}</strong>
                        <small class="text-muted">{{ $rating->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="mb-2">
                        @for ($i = 1; $i <= 5; $i++)
                            <span class="fa fa-star {{ $i <= $rating->rating ? 'text-warning' : 'text-muted' }}"></span>
                        @endfor
                    </div>
                    
                </div>
            </div>
        @empty
            <p class="text-muted">No reviews yet.</p>
        @endforelse
    </div>

   
    <hr class="gradient-hr">
</div>

<style>
.rating-breakdown .progress {
    background-color: #e9ecef;
    border-radius: 5px;
}

.progress-bar {
    transition: width 0.4s ease;
    border-radius: 5px;
}

.fa-star {
    font-size: 1.2rem;
    transition: color 0.3s ease;
}

.fa-star:hover {
    color: #ffcc00 !important;
}

.user-reviews .card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.user-reviews .card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
.gradient-hr {
    border: none;
    height: 2px;
    background: linear-gradient(to right, #ff9a9e, #fad0c4); /* Pink gradient */
    margin: 20px 0;
    border-radius: 2px;
}

</style>