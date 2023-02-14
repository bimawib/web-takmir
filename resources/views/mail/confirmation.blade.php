<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Email Confirmation</title>
</head>
<body>
        <h2>{{ $details['title'] }}{{ $details['username'] }}</h2>
       <a href="{{ $details['url'] }}" target="_blank">Click here!</a>
        <p>After confirmation, you will gain access to create blog, and lost item post on MBU's website</p>
        <p>Thank You!</p>
</body>
</html>