@extends('layouts/storekeeperlayout')

@section('content')
                <h2>Последние заявки из магазинов:</h2>
                @if ($latestRequests->count() > 0)
                <div class="card">
                    <ul class="list-group list-group-flush">
                        @foreach ($latestRequests as $request)
                        <li class="list-group-item">
                            {{ $request->product->name }} - {{ $request->requested_quantity }}
                            ({{ $request->created_at }})
                            <a href="{{ route('storekeeper.requests.show', $request->id) }}">Подробнее</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <p>Нет заявок.</p>
                @endif

                <h2>Заявки на недостающий товар на складе:</h2>
                @php
                use App\Models\WarehouseMissingProductRequest;
                $warehouseRequests = WarehouseMissingProductRequest::latest()->limit(5)->get();
                @endphp
                @if ($warehouseRequests->count() > 0)
                <div class="card">
                    <ul class="list-group list-group-flush">
                        @foreach ($warehouseRequests as $request)
                        <li class="list-group-item">
                            {{ $request->product->name }} - {{ $request->requested_quantity }}
                            ({{ $request->created_at }})
                            <a href="{{ route('storekeeper.warehouse_requests.show', $request->id) }}">Подробнее</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
                @else
                <p>Нет заявок на склад.</p>
                @endif

                <h2>Список кончающегося товара на складе:</h2>
                @if ($lowStockWarehouse->count() > 0)
                <div class="card">
                    <ul class="list-group list-group-flush">
                        @foreach ($lowStockWarehouse as $product)
                        <li class="list-group-item">{{ $product->name }}</li>
                        @endforeach
                    </ul>
                </div>
                @else
                <p>Все товары в достаточном количестве.</p>
                @endif

                <h2>Список кончающегося товара в магазинах:</h2>
                @if ($lowStockStores->count() > 0)
                <div class="card">
                    <ul class="list-group list-group-flush">
                        @foreach ($lowStockStores as $product)
                        <li class="list-group-item">{{ $product->name }}</li>
                        @endforeach
                    </ul>
                </div>
                @else
                <p>Все товары в достаточном количестве.</p>
                @endif
            </main>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
