<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Накладные</title>
</head>

<body>
    <h1>Накладные</h1>

    <a href="{{ route('storekeeper.shipments.create') }}">Создать накладную</a>
    <a href="{{ route('storekeeper.shipments.create', ['incoming' => true]) }}">Принять товар</a>

    @if ($shipments->count() > 0)
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Магазин получатель</th>
                <th>Тип</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($shipments as $shipment)
            <tr>
                <td>{{ $shipment->id }}</td>
                <td>{{ $shipment->toBranch->name }}</td>
                <td>{{ $shipment->is_incoming ? 'Приемка' : 'Отправка' }}</td>
                <td>{{ $shipment->status }}</td>
                <td>
                    <a href="{{ route('storekeeper.shipments.show', $shipment->id) }}">Просмотреть</a>
                    <a href="{{ route('storekeeper.shipments.edit', $shipment->id) }}">Редактировать</a>
                    <form method="POST" action="{{ route('storekeeper.shipments.destroy', $shipment->id) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Удалить</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <p>Нет накладных.</p>
    @endif

    <a href="{{ route('storekeeper.dashboard') }}">Назад на главную</a>
</body>

</html>
