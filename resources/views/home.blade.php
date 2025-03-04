<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h1 class="h4 mb-0">URL Shortener</h1>
                    </div>
                    <div class="card-body">
                        @if(session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form id="shortenForm">
                            @csrf
                            <div class="mb-3">
                                <label for="original_url" class="form-label">Enter URL to shorten:</label>
                                <input type="url" class="form-control" id="original_url" name="original_url" required>
                            </div>
                            <div class="mb-3">
                                <label for="custom_slug" class="form-label">Custom slug (optional):</label>
                                <input type="text" class="form-control" id="custom_slug" name="custom_slug">
                                <div class="form-text">Leave empty for auto-generated slug</div>
                            </div>
                            <div class="mb-3">
                                <label for="expires_at" class="form-label">Expiration (optional):</label>
                                <input type="datetime-local" class="form-control" id="expires_at" name="expires_at">
                            </div>
                            <button type="submit" class="btn btn-primary">Shorten URL</button>
                        </form>

                        <div id="result" class="mt-4 d-none">
                            <div class="alert alert-success">
                                <h5>Your shortened URL:</h5>
                                <div class="input-group mb-3">
                                    <input type="text" id="shortened_url" class="form-control" readonly>
                                    <button class="btn btn-outline-secondary" type="button" id="copyButton">Copy</button>
                                </div>
                                <p>
                                    <a id="analytics_link" href="#" class="btn btn-sm btn-info">View Analytics</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('shortenForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            try {
                const response = await fetch('/shorten', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        original_url: formData.get('original_url'),
                        custom_slug: formData.get('custom_slug'),
                        expires_at: formData.get('expires_at'),
                    }),
                });
                
                const data = await response.json();
                
                if (data.success) {
                    document.getElementById('shortened_url').value = data.data.short_url;
                    document.getElementById('analytics_link').href = `/analytics/${data.data.slug}`;
                    document.getElementById('result').classList.remove('d-none');
                } else {
                    // alert('Error: ' + JSON.stringify(data.errors));
                    if (response.status === 429) { 
                        alert('Too many attempts. Please try again later.');
                    } else {
                        alert('Error: ' + JSON.stringify(data.errors));
                    }
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while shortening the URL.');
            }
        });

        document.getElementById('copyButton').addEventListener('click', function() {
            const shortenedUrl = document.getElementById('shortened_url');
            shortenedUrl.select();
            document.execCommand('copy');
            this.innerHTML = 'Copied!';
            setTimeout(() => {
                this.innerHTML = 'Copy';
            }, 2000);
        });
    </script>
</body>
</html>