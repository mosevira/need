@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Приемка накладной #{{ $invoice->id }}</h2>

    <form action="{{ route('invoices.process-accept', $invoice) }}" method="POST">
        @csrf

        <table class="table">
            <thead>
                <tr>
                    <th>Товар</th>
                    <th>Артикул</th>
                    <th>Отправлено</th>
                    <th>Принято</th>
                </tr>
            </thead>
            <tbody>
                @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->product->article }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>
                        <input type="number"
                               name="items[{{ $loop->index }}][accepted_qty]"
                               value="{{ $item->quantity }}"
                               min="0"
                               max="{{ $item->quantity }}"
                               class="form-control">
                        <input type="hidden" name="items[{{ $loop->index }}][id]" value="{{ $item->id }}">
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Подтвердить приемку</button>
    </form>
</div>
@endsection
