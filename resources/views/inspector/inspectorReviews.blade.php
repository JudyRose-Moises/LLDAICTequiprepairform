<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Reviews</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .content { margin-left: 280px; padding: 30px; }
        .inspector-card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .review-content {
            font-size: 18px;
            word-wrap: break-word;
            white-space: normal;
        }
        .toggle-link {
            color: #007bff;
            cursor: pointer;
            font-size: 0.9em;
        }
        @media (max-width: 768px) {
            .content { margin-left: 0; padding: 20px; }
        }
    </style>
</head>
<body>

<div>
    @include('layouts.inspectorSidebar')
</div>

<div class="content">
    <h2 class="mb-4"><i class="fas fa-star"></i> My Reviews</h2>

    <div class="card mb-4 shadow inspector-card">
        <div class="card-body text-center">
            <h4>{{ $user->first_name }} {{ $user->last_name }}</h4>
            @php
                $averageRating = $reviews->avg('rating');
                $averageRating = $averageRating ? number_format($averageRating, 1) : 'No Rating';
            @endphp
            <p><strong>Average Rating:</strong>
                @if(is_numeric($averageRating))
                    ⭐ {{ $averageRating }}
                @else
                    {{ $averageRating }}
                @endif
            </p>
        </div>
    </div>

    <div class="mb-3">
        <input type="text" id="reviewSearch" class="form-control" placeholder="Search your reviews...">
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Ticket ID</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Final Report</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                @php
                    $fullText = $review->review ?? '—';
                    $truncated = strlen($fullText) > 120 ? Str::limit($fullText, 120, '...') : $fullText;
                    $needsToggle = strlen($fullText) > 120;
                @endphp
                <tr>
                    <td>{{ $review->id }}</td>
                    <td>⭐ {{ $review->rating }}</td>
                    <td>
                        <div class="trunc-review" data-full="{{ $fullText }}" data-truncated="{{ $truncated }}">
                            <span class="review-content">{{ $truncated }}</span>
                            @if($needsToggle)
                                <br>
                                <a href="javascript:void(0);" class="toggle-link">Show more</a>
                            @endif
                        </div>
                    </td>
                    <td>
                        <a href="{{ route('finalreport', $review->id) }}" class="btn btn-sm btn-outline-warning">
                            View Report
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle full/truncated review text
    document.querySelectorAll('.toggle-link').forEach(link => {
        link.addEventListener('click', function () {
            const container = this.closest('.trunc-review');
            const content = container.querySelector('.review-content');
            const full = container.dataset.full;
            const truncated = container.dataset.truncated;

            if (this.textContent === 'Show more') {
                content.textContent = full;
                this.textContent = 'Show less';
            } else {
                content.textContent = truncated;
                this.textContent = 'Show more';
            }
        });
    });

    // Search filter
    document.getElementById('reviewSearch').addEventListener('keyup', function () {
        const val = this.value.toLowerCase();
        document.querySelectorAll('table tbody tr').forEach(row => {
            row.style.display = row.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });
});
</script>

</body>
</html>
