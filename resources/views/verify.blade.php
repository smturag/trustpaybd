<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Unavailable</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Your CSS -->
</head>
<body>
    <div class="container text-center mt-5">
        <h1>404 - {{ isset($data['message_status']) && $data['message_status'] == 1 ? $data['message'] : 'Service Unavailable' }}</h1>
        <p>Sorry, the page you are looking for does not exist or is currently unavailable.</p>
      <h1><strong>Contact with administrator</strong> </h1>
    </div>
</body>
</html>