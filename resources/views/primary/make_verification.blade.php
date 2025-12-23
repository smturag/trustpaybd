<!DOCTYPE html>
<html>
<head>
    <title>Your Awesome Email</title>
    <style>
        /* Inline CSS for cross-email client compatibility */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            margin: auto;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }

        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 10px;
            text-align: center;
            font-size: 24px;
        }

        .content {
            padding: 20px;
        }

        .button {
            display: block;
            width: 200px;
            background-color: #007bff;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            margin: 20px auto;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding-top: 10px;
            color: #888888;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{$user->email}}
        </div>
        <div class="content">
            <p>Hello {{$user->name}} </p>
            <p>You will get varification email through your email</p>
            <p>Expiry time is one (1) minutes</p>
            <form  class="row g-3" action="{{route('customer.new_token_create')}}" method="POST">
                @csrf
                <input type="text" name="id" value="{{$user->id}}" hidden>
                <button type="submit" class="button">Didn't get email/ Exprire time</button>
            </form>
            {{-- <a href="https://www.example.com/get" class="button">Didn't get email</a> --}}
        </div>
        <div class="footer">
            Sent with love from Awesome Company.
        </div>
    </div>
</body>
</html>
