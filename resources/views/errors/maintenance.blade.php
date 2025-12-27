<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Under Maintenance</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
        }

        .maintenance-container {
            text-align: center;
            padding: 40px;
            max-width: 600px;
        }

        .maintenance-icon {
            font-size: 120px;
            margin-bottom: 30px;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        h1 {
            font-size: 48px;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.2);
        }

        p {
            font-size: 20px;
            margin-bottom: 15px;
            line-height: 1.6;
            opacity: 0.95;
        }

        .back-soon {
            font-size: 16px;
            opacity: 0.8;
            margin-top: 30px;
        }

        .loader {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255,255,255,0.3);
            border-top: 5px solid #fff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-top: 30px;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="maintenance-container">
        <div class="maintenance-icon">🔧</div>
        <h1>Under Maintenance</h1>
        <p>We're currently performing scheduled maintenance to improve your experience.</p>
        <p>Please check back in a few minutes.</p>
        <div class="loader"></div>
        <p class="back-soon">We'll be back soon!</p>
    </div>
</body>
</html>
