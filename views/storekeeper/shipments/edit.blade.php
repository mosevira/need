<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Редактировать накладную {{ $shipment->id }}</title>
</head>

<body>
    <h1>Редактировать накладную {{ $shipment->id }}</h1>

    <form method="POST" action="{{ route('storekeeper.shipments.update', $shipment->id) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="to_branch_id">Магазин получатель:</label>
            <select name="to_branch_id" id="to_branch_id" required>
                @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" {{ $shipment->to_branch_id == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <h2>Товары:</h2>

        <div id="products-container">
            @foreach ($shipment->items as $key => $item)
            <div class="product">
                <label for="product_id">Товар:</label>
                <select name="products[{{ $key }}][product_id]" class="product_id" required>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}" {{ $item->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>

                <label for="quantity">Количество:</label>
                <input type="number" name="products[{{ $key }}][quantity]" class="quantity" min="1" value="{{ $item->quantity }}" required>
            </div>
            @endforeach
        </div>

        <button type="button" id="add-product">Добавить товар</button>

        <button type="submit">Сохранить накладную</button>
    </form>

    <a href="{{ route('storekeeper.shipments.index') }}">Назад к списку накладных</a>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addProductButton = document.getElementById('add-product');
            const productsContainer = document.getElementById('products-container');
            let productIndex = {{ $shipment->items->count() }};

            addProductButton.addEventListener('click', function () {
                const productDiv = document.createElement('div');
                productDiv.classList.add('product');

                productDiv.innerHTML = `
                    <label for="product_id">Товар:</label>
                    <select name="products[${productIndex}][product_id]" class="product_id" required>
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>

                    <label for="quantity">Количество:</label>
                    <input type="number" name="products[${productIndex}][quantity]" class="quantity" min="1" required>
                `;

                productsContainer.appendChild(productDiv);
                productIndex++;
            });
        });
    </script>
</body>

</html>
