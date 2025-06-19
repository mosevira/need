<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock</title>
</head>

<body>
    <h1>Stock</h1>
    <a href="{{ route('storekeeper.stock.create') }}">Добавить товар</a>
    <ul>
        @foreach ($productsWithQuantity as $product)
        <li>{{ $product->name }} - {{ $product->quantity }}</li>
        @endforeach
    </ul>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>

</html>
