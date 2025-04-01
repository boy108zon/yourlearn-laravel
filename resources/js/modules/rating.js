document.addEventListener('DOMContentLoaded', () => {
    initializeRatings();
});

function initializeRatings() {
    document.querySelectorAll('.rating').forEach(ratingEl => {
        const stars = ratingEl.querySelectorAll('.star');
        const productId = ratingEl.dataset.productId;
        const initialRating = parseInt(ratingEl.dataset.initialRating) || 0;

        updateStars(stars, initialRating);

        stars.forEach(star => {
            star.addEventListener('click', () => {
                const ratingValue = parseInt(star.dataset.value);
                updateStars(stars, ratingValue);
                submitRating(productId, ratingValue, ratingEl);
            });
        });
    });
}

function updateStars(stars, rating) {
    stars.forEach(star => {
        star.classList.toggle('filled', parseInt(star.dataset.value) <= rating);
    });
}

async function submitRating(productId, rating, ratingEl) {
    try {
        const response = await fetch(`/products/${productId}/rate`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ rating })
        });

        if (response.status === 401) {
            handleUnauthorized();
            return;
        }

        if (!response.ok) {
            await handleHttpError(response);
            return;
        }

        const data = await response.json();
        handleSuccessResponse(data, ratingEl);
    } catch (error) {
        console.error('Network error:', error);
        showMessage('Network error. Please try again later.', 'danger');
    }
}

function handleUnauthorized() {
    showMessage('You must be logged in to rate a product.', 'danger');
    setTimeout(() => {
        window.location.href = '/login?st=1';
    }, 2000);
}

async function handleHttpError(response) {
    const errorData = await response.json().catch(() => ({}));
    const status = response.status;
    switch (status) {
        case 400:
            showMessage(errorData.message, 'danger');
            break;
        case 403:
            showMessage(errorData.message, 'danger');
            break;
        case 404:
            showMessage(errorData.message, 'danger');
            break;
        case 500:
            showMessage('Internal Server Error. Please try again later.', 'danger');
            break;
        default:
            showMessage(errorData.message || 'An unexpected error occurred.', 'danger');
    }
}

function handleSuccessResponse(data, ratingEl) {
    if (data.status === 0) {
        showMessage(data.message, 'success');
        updateAverageRating(ratingEl, data.average_rating, data.rating_count);
    } else {
        handleErrorResponse(data);
    }
}

function updateAverageRating(ratingEl, avgRating, count) {
    const ratingText = ratingEl.querySelector('.rating-text');
    ratingText.textContent = `(${avgRating.toFixed(1)} average from ${count} ratings)`;
}

function showMessage(message, type = 'success') {
    const messageContainer = document.getElementById('js-error-messages');
    messageContainer.textContent = message;
    messageContainer.className = `alert ${type === 'danger' ? 'alert-danger' : 'alert-success'}`;
    messageContainer.classList.remove('d-none');

    messageContainer.scrollIntoView({ behavior: 'smooth' });

    setTimeout(() => {
        messageContainer.classList.add('d-none');
    }, 5000);
}
