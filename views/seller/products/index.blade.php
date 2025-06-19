<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Продукты</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Продукты</h1>
        <table class="table table-bordered">
            <thead class="thead-light">
                <tr>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество в магазине</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productsWithQuantity as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price }} ₽</td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            <form method="POST" action="{{ route('seller.products.updateQuantity', $product->id) }}" class="form-inline">
                                @csrf
                                @method('PUT')
                                <div class="form-group mb-2 mr-2">
                                    <input type="number" name="quantity" value="{{ $product->quantity }}" min="0" class="form-control" required>
                                </div>
                                <button type="submit" class="btn btn-primary mb-2">Изменить</button>
                            </form>
                            <a href="{{ route('seller.products.details', $product) }}" class="btn btn-info btn-sm mb-2">Подробнее</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <form method="POST" action="{{ route('logout') }}" class="mt-4">
            @csrf
            <button type="submit" class="btn btn-danger">Выход</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
