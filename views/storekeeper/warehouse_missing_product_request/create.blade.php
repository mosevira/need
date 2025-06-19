@extends('layouts/storekeeperlayout')

@section('content')
        <h1>Создать заявку на недостающий товар на складе</h1>

        <form method="POST" action="{{ route('storekeeper.warehouse_missing_product_request.store') }}">
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
                <input type="number" name="requested_quantity" id="requested_quantity" class="form-control" min="1"
                    required>
            </div>
            <div class="form-group">
                <label for="reason">Причина:</label>
                <textarea name="reason" id="reason" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Создать заявку</button>
        </form>

        <a href="{{ route('storekeeper.dashboard') }}" class="btn btn-secondary mt-3">Назад на главную</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
