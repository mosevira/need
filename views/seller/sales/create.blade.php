@extends('layouts/sellerlayout')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">Создать Продажу</h1>

    <form action="{{ route('sales.store') }}" method="POST" class="needs-validation" novalidate>
        @csrf
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th scope="col">Товар</th>
                    <th scope="col">Количество</th>
                    <th scope="col">Действия</th>
                </tr>
            </thead>
            <tbody id="items">
                <tr class="item">
                    <td>
                        <select name="items[0][product_id]" class="form-select" required>
                            <option value="" disabled selected>Выберите товар</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->price }} ₽)</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="number" name="items[0][quantity]" class="form-control" min="1" required>
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger" onclick="removeItem(this)">Удалить</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <button type="button" class="btn btn-secondary mb-3" onclick="addItem()">Добавить товар</button>
        <button type="submit" class="btn btn-primary">Создать</button>
    </form>

    <script>
        let itemIndex = 1; // Индекс для новых товаров
        function addItem() {
            const itemsDiv = document.getElementById('items');
            const itemRow = document.createElement('tr');
            itemRow.className = 'item';
            itemRow.innerHTML = `
                <td>
                    <select name="items[${itemIndex}][product_id]" class="form-select" required>
                        <option value="" disabled selected>Выберите товар</option>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->price }} ₽)</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type="number" name="items[${itemIndex}][quantity]" class="form-control" min="1" required>
                </td>
                <td>
                    <button type="button" class="btn btn-danger" onclick="removeItem(this)">Удалить</button>
                </td>
            `;
            itemsDiv.appendChild(itemRow);
            itemIndex++;
        }

        function removeItem(button) {
            const row = button.closest('tr');
            row.remove();
        }
    </script>

    {{-- <a href="{{ route('sales.index') }}" class="btn btn-link">Назад к списку продаж</a> --}}
</div>
@endsection
