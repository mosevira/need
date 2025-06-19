@extends('layouts/sellerlayout')

@section('content')
<div class="container mt-5">
    <h1>Список продаж</h1>
    <a href="{{ route('sales.create') }}" class="btn btn-primary mb-3">Создать продажу</a>

    @if($sales->isEmpty())
        <div class="alert alert-warning">Продаж нет</div>
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    {{-- <th>Seller ID</th> --}}
                    <th>Сумма</th>
                    <th>Дата</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sales as $sale)
                    <tr>
                        <td>{{ $sale->id }}</td>
                        {{-- <td>{{ $sale->seller_id }}</td> --}}
                        <td>${{ number_format($sale->amount, 2) }}</td>
                        <td>{{ $sale->created_at->format('Y-m-d') }}</td>
                        <td>
                            <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-info btn-sm">Просмотр</a>
                            <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-warning btn-sm">Редактировать</a>
                            <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Вы уверены, что хотите удалить эту продажу?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Удалить</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
