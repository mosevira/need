<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Создать накладную</title>
</head>

<body>
    <h1>Создать накладную</h1>

    <form method="POST" action="{{ route('storekeeper.shipments.store') }}">
        @csrf
        <div>
            <label for="to_branch_id">Магазин получатель:</label>
            <select name="to_branch_id" id="to_branch_id" required>
                @foreach ($branches as $branch)
                <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                @endforeach
            </select>
        </div>

        <h2>Товары:</h2>

        <div id="products-container">
            <div class="product">
                <label for="product_id">Товар:</label>
                <select name="products[0][product_id]" class="product_id" required>
                    @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>

                <label for="quantity">Количество:</label>
                <input type="number" name="products[0][quantity]" class="quantity" min="1" required>
            </div>
        </div>

        <button type="button" id="add-product">Добавить товар</button>

        <input type="hidden" name="is_incoming" value="{{ $isIncoming }}">

        <button type="submit">Создать накладную</button>
    </form>

    <a href="{{ route('storekeeper.shipments.index') }}">Назад к списку накладных</a>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const addProductButton = document.getElementById('add-product');
            const productsContainer = document.getElementById('products-container');
            let productIndex = 1;

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
