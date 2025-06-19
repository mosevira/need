@extends('layouts/sellerlayout')
   @section('content')
     <div class="container">
         <h2>Непринятые накладные со склада:</h2>
        @if ($pendingInvoices->count() > 0)
            <ul class="list-group mb-4">
                @foreach ($pendingInvoices as $invoice)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        Накладная № {{ $invoice->id }} от {{ $invoice->date }}
                        <span>
                            <form action="{{ route('invoices.approve', $invoice->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-success btn-sm">Принять</button>
                            </form>
                            <form action="{{ route('invoices.reject', $invoice->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="btn btn-danger btn-sm">Отклонить</button>
                            </form>
                             <a href="{{ route('invoices.showForSeller', $invoice->id) }}" class="btn btn-primary btn-sm">Просмотреть</a>
                        </span>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="alert alert-warning">Нет непринятых накладных.</p>
        @endif

        <h2>Список кончающегося товара:</h2>
        @if ($lowStockProducts->count() > 0)
        <table class="table table-bordered mb-4">
            <thead>
                <tr>
                    <th>Название товара</th>
                    <th>Осталось</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($lowStockProducts as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="alert alert-warning">Нет товаров, количество которых подходит к концу.</p>
        @endif

    </div>

    <div class="container mt-5">
        <h1>Аналитика</h1>
        <div class="card">
            <div class="card-body">
                <h5>Количество продуктов: {{ $productsCount }}</h5>
                <h5>Общая цена продуктов: {{ $totalPrice }} ₽</h5>
            </div>
        </div>

        <canvas id="myChart"></canvas>
    </div>
</main>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const productNames = @json($products->pluck('name'));
        const productPrices = @json($products->pluck('price'));

        const myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: productNames,
                datasets: [{
                    label: 'Цена продуктов',
                    data: productPrices,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>

    <!-- Подключение JS Bootstrap через CDN -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
