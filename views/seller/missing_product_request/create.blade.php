<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Создать заявку на недостающий товар</title>
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center">Создать заявку на недостающий товар</h1>
        <form method="POST" action="{{ route('seller.missing_product.store') }}" class="mt-4">
            @csrf
            <div class="form-group">
                <label for="product_id">Товар:</label>
                <select name="product_id" id="product_id" class="form-control">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="requested_quantity">Количество:</label>
                <input type="number" name="requested_quantity" id="requested_quantity" min="1" required class="form-control">
            </div>
            <div class="form-group">
                <label for="reason">Причина:</label>
                <textarea name="reason" id="reason" class="form-control" rows="3"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Создать заявку</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
