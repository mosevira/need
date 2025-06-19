<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storekeeper Dashboard</title>
</head>
<body>
    <h1>Storekeeper Dashboard</h1>
    <p>Добро пожаловать, кладовщик!</p>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
