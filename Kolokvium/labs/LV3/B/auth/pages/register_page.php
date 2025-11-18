<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register</title>
</head>
<body>
<form method="post" action="../handlers/register_handler.php">
    <div>
        <label for="username">Username</label>
        <input id="username" name="username" type="text">
    </div>
    <div>
        <label for="password">Password</label>
        <input id="password" name="password" type="password">
    </div>
    <button>Register</button>
</form>
<a href="login_page.php">Login</a>
</body>
</html>