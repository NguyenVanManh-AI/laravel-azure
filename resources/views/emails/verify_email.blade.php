<!DOCTYPE html>
<html>

<head>
    <title>Verify Email</title>
</head>
<style>
    p {
        color: #007bff;
    }
    p:hover {
        text-decoration: underline;
        color: #0059b9
    }
</style>
<body>
    <h1>Verify Email from System</h1>
    <h2>Click on this link to confirm your email. Only when the email is confirmed can you log in. <br>
        {{-- <a href="{{ $url }}">{{ $url }}</a> --}}
        <p>{{ $url }}</p>
    </h2>
</body>

</html>
