<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inspector Reviews</title>
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
            margin-bottom: 20px;
            text-align: center;
        }
        .inspector-card h5 { margin-bottom: 5px; color: #333; }
        .inspector-card .rating {
            font-size: 1.2em;
            color: #ffc107;
            margin-bottom: 10px;
        }

        .review-text {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            word-break: break-word;
            white-space: normal;
            text-overflow: ellipsis;
            line-height: 1.4;
            max-height: calc(1.4em * 3); /* height for 3 lines */
            margin-bottom: 5px;
        }

        .review-text.expanded {
            -webkit-line-clamp: unset;
            max-height: none;
        }

        .show-more {
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
    @include('layouts.adminSidebar')
</div>

<div class="content">
    <h2 class="mb-4"><i class="fas fa-star"></i> Inspector Reviews</h2>

    <div class="row">
        @foreach($inspectors as $inspector)
        <div class="col-md-4 mb-4">
            <div class="card inspector-card shadow">
                <div class="card-body text-center">
                    <h5>{{ $inspector->first_name }} {{ $inspector->last_name }}</h5>
                    @php
                        $inspectorTickets = $reviews->where('repairedBy', $inspector->first_name . ' ' . $inspector->last_name);
                        $averageRating = $inspectorTickets->avg('rating');
                        $averageRating = $averageRating ? number_format($averageRating, 1) : 'No Rating';
                    @endphp
                    <p><strong>Average Rating:</strong> 
                        @if(is_numeric($averageRating))
                            ⭐ {{ $averageRating }}
                        @else
                            {{ $averageRating }}
                        @endif
                    </p>
                    @if($inspectorTickets->count())
                        <button class="btn btn-outline-primary toggle-reviews" data-inspector="{{ $inspector->id }}">
                            View Reviews ({{ $inspectorTickets->count() }})
                        </button>

                        <div class="reviews mt-3 overflow-auto" id="reviews-{{ $inspector->id }}" style="display: none; max-height: 300px;">
                            @foreach($inspectorTickets as $review)
                                <div class="border rounded p-2 mb-2 text-start">
                                    <strong>Ticket #{{ $review->id }}</strong><br>
                                    ⭐ {{ $review->rating }} Stars<br>
                                    @if($review->review)
                                        "{{ $review->review }}"
                                    @else
                                        <em class="text-muted">No written review</em>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">No reviews or ratings yet.</p>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <hr class="my-5">

    <h3><i class="fas fa-list"></i> All Reviews</h3>
    <div class="mb-3">
        <input type="text" id="reviewSearch" class="form-control" placeholder="Search by ticket ID, inspector, rating, or review...">
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Ticket ID</th>
                    <th>Inspector</th>
                    <th>Rating</th>
                    <th>Review</th>
                    <th>Report</th> 
                </tr>
            </thead>
            <tbody>
                @foreach($reviews as $review)
                <tr>
                    <td>{{ $review->id }}</td>
                    <td>{{ $review->repairedBy }}</td>
                    <td>⭐ {{ $review->rating }}</td>
                    <td>
                        @if($review->review)
                            <div style="max-width: 400px;">
                                <div class="review-text" id="text-{{ $review->id }}">
                                    {{ $review->review }}
                                </div>
                                @if(strlen($review->review) > 120)
                                    <div class="show-more" data-id="{{ $review->id }}">Show more</div>
                                @endif
                            </div>
                        @else
                            <em class="text-muted">—</em>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('finalreport', $review->id) }}"
                           class="btn btn-sm btn-outline-warning">
                            View Report
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

<!-- JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Toggle inspector reviews
    document.querySelectorAll('.toggle-reviews').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.dataset.inspector;
            const target = document.getElementById('reviews-' + id);

            if (target.style.display === 'none') {
                target.style.display = 'block';
                this.textContent = 'Hide Reviews';
            } else {
                target.style.display = 'none';
                this.textContent = 'View Reviews (' + target.children.length + ')';
            }
        });
    });

    // Show more/less
    document.querySelectorAll('.show-more').forEach(link => {
        link.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            const text = document.getElementById('text-' + id);

            text.classList.toggle('expanded');
            this.textContent = text.classList.contains('expanded') ? 'Show less' : 'Show more';
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
