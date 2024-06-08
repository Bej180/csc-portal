<!DOCTYPE html>
<html>
<head>
    <title>Generate QR Code</title>
</head>
<body>
    <form action="/generate-qr-code" method="GET">
        <label for="url">Enter URL:</label>
        <input type="text" id="url" name="url" placeholder="https://example.com">
        <button type="submit">Generate QR Code</button>
    </form>

    @if(request()->has('url'))
        <h2>QR Code for {{ request()->input('url') }}</h2>
        <img src="{{ url('/generate-qr-code?url=' . request()->input('url')) }}" alt="QR Code">
    @endif
</body>
</html>
