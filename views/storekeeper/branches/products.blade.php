@extends('layouts/storekeeperlayout')

@section('content')
        <h1>Товары в магазине {{ $branch->name }}</h1>

        @if ($productsWithQuantity->count() > 0)
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Количество</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productsWithQuantity as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->quantity }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>В этом магазине нет товаров.</p>
        @endif

        <a href="{{ route('storekeeper.branches.index') }}" class="btn btn-primary">Назад к списку магазинов</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
