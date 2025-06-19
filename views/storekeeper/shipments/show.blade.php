<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Накладная {{ $shipment->id }}</title>
</head>

<body>
    <h1>Накладная {{ $shipment->id }}</h1>

    <p>Магазин получатель: {{ $shipment->toBranch->name }}</p>
    <p>Статус: {{ $shipment->status }}</p>

    <h2>Товары:</h2>

    @if ($shipment->items->count() > 0)
    <table>
        <thead>
            <tr>
                <th>Название</th>
                <th>Количество</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shipment->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>В накладной нет товаров.</p>
    @endif

    <a href="{{ route('storekeeper.shipments.index') }}">Назад к списку накладных</a>
</body>

</html>
