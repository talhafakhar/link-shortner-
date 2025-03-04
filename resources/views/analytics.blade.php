<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Analytics - {{ $shortUrl->slug }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h1 class="h4 mb-0">URL Analytics</h1>
                        <a href="{{ route('home') }}" class="btn btn-sm btn-light">Back to Home</a>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h5>Short URL:</h5>
                            <div class="input-group">
                                <input type="text" class="form-control" value="{{ route('redirect', $shortUrl->slug) }}" readonly>
                                <button class="btn btn-outline-secondary copy-btn" type="button" data-copy="{{ route('redirect', $shortUrl->slug) }}">Copy</button>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h5>Original URL:</h5>
                            <p class="text-break">{{ $shortUrl->original_url }}</p>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card text-bg-primary">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Total Visits</h6>
                                        <p class="display-4">{{ $analytics['total_visits'] }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card text-bg-info">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Created</h6>
                                        <p class="small">{{ $analytics['created_at']->diffForHumans() }}</p>
                                        <p class="small">{{ $analytics['created_at']->format('Y-m-d H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card {{ $shortUrl->isExpired() ? 'text-bg-danger' : 'text-bg-success' }}">
                                    <div class="card-body text-center">
                                        <h6 class="card-title">Expires</h6>
                                        @if(!empty($analytics['expires_at']))
                                         @php 
                                            $expiresAt = \Carbon\Carbon::parse($analytics['expires_at']);
                                            
                                            $minutesRemaining = now()->diffInMinutes($expiresAt, false); // Calculate minutes difference

                                        @endphp
                                        @if ($minutesRemaining > 0)
                                                    <p class="small">Link will expire at {{ $expiresAt->format('h:i A') }}</p>
                                                @else
                                                    <p class="small text-warning">This link has expired</p>
                                                @endif
                                                <p class="small">
                                                {{ $expiresAt->format('Y-m-d h:i A') }}
                                            </p>
                                            @else
                                            <p>Never</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h5>Recent Visits</h5>
                        @if(count($analytics['recent_visits']) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>IP Address</th>
                                            <th>Timestamp</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($analytics['recent_visits'] as $visit)
                                            <tr>
                                                <td>{{ $visit['ip_address'] }}</td>
                                                <td>{{ $visit['visited_at']->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">No visits recorded yet</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.copy-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const text = this.getAttribute('data-copy');
                const tempInput = document.createElement('input');
                tempInput.value = text;
                document.body.appendChild(tempInput);
                tempInput.select();
                document.execCommand('copy');
                document.body.removeChild(tempInput);
                
                this.innerHTML = 'Copied!';
                setTimeout(() => {
                    this.innerHTML = 'Copy';
                }, 2000);
            });
        });
    </script>
</body>
</html>