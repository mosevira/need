<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Детали</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <h1 class="text-center">{{ $product->name }}</h1>
        <p><strong>Цена:</strong> {{ $product->price }} ₽</p>
        <p><strong>Описание:</strong> {{ $product->description }}</p>

        <h2>Количество товара в текущем магазине:</h2>
        <p>{{ $quantity }}</p>

        <h2>Количество товара в филиалах:</h2>
        <table class="table">
            <thead class="thead-light">
                <tr>
                    <th>Филиал</th>
                    <th>Количество</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($branches as $branch)
                    <tr>
                        <td>{{ $branch->name }}</td>
                        <td>{{ $inventory->get($branch->id)->sum('quantity') ?? 0 }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Форма для изменения количества товара -->
        <h2>Изменить количество товара</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('seller.products.updateQuantity', $product->id) }}" class="mb-4">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="quantity">Количество:</label>
                <input type="number" name="quantity" id="quantity" value="{{ $quantity }}" min="0" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Изменить</button>
        </form>

        <!-- Форма для списания товара -->
        <h2>Списать товар</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('seller.products.writeOff', $product->id) }}" class="mb-4">
            @csrf
            <div class="form-group">
                <label for="write_off_quantity">Количество:</label>
                <input type="number" name="quantity" id="write_off_quantity" min="1" required class="form-control">
            </div>
            <div class="form-group">
                <label for="reason">Причина:</label>
                <textarea name="reason" id="reason" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-danger">Списать</button>
        </form>

        <a href="{{ route('seller.products.index') }}" class="btn btn-secondary">Назад к списку товаров</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>
