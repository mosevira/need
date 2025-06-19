@extends('layouts/storekeeperlayout')

@section('content')
    <div class="container">
        <h1>Список магазинов</h1>

        @if ($branches->count() > 0)
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Название</th>
                    <th>Адрес</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($branches as $branch)
                <tr>
                    <td>{{ $branch->name }}</td>
                    <td>{{ $branch->address }}</td>
                    <td><a href="{{ route('storekeeper.branches.products', $branch->id) }}" class="btn btn-primary btn-sm">Просмотреть товары</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>Нет магазинов.</p>
        @endif

        <a href="{{ route('storekeeper.dashboard') }}" class="btn btn-secondary">Назад на главную</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
@endsection
