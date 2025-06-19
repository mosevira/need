<!DOCTYPE html>
<html>
<head>
    <title>Детали накладной</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h1>Детали накладной #{{ $invoice->id }}</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Количество</th>
                    <th>Подтверждено</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->invoiceItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->is_confirmed ? 'Да' : 'Нет' }}</td>
                    <td>
                        @if(!$item->is_confirmed)
                        <form action="{{ route('invoices.items.confirm', [$invoice->id, $item->id]) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">Подтвердить</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
