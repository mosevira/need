<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Панель администратора</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bs-primary: #007bff;
            --bs-sidebar-width: 280px;
        }

        body {
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .navbar {
            background-color: var(--bs-primary);
        }

        #sidebar {
            width: var(--bs-sidebar-width);
            height: 100vh;
            position: fixed;
            top: 56px;
            left: 0;
            overflow-y: auto;
            transition: all 0.3s;
        }

        .main-content {
            margin-left: var(--bs-sidebar-width);
            padding: 20px;
            width: calc(100% - var(--bs-sidebar-width));
        }

        .sidebar-link {
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
        }

        .sidebar-link.active {
            background-color: var(--bs-primary);
            color: white !important;
        }

        .stat-card {
            transition: transform 0.2s;
            border-left: 4px solid var(--bs-primary);
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -var(--bs-sidebar-width);
            }

            .main-content {
                width: 100%;
                margin-left: 0;
            }

            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Навбар -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <button class="navbar-toggler me-2" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse">
                <span class="navbar-toggler-icon"></span>
            </button>
            <a class="navbar-brand" href="#">Панель администратора</a>

            <div class="d-flex align-items-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">
                        <i class="bi bi-box-arrow-right"></i> Выход
                    </button>
                </form>
            </div>
        </div>
    </nav>
    <!-- Основной контент -->
    <!-- Основной контент -->
    <div class="container-fluid">
        <div class="row">
            <!-- Сайдбар -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link sidebar-link active" href="{{route('dashboard.admin')}}">
                                <i class="bi bi-house-door me-2"></i> Главная
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-link" href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people me-2"></i> Пользователи
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-link" href="{{ route('branches.index') }}">
                                <i class="bi bi-shop me-2"></i> Филиалы
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link sidebar-link" href="{{ route('storekeeper.branches.index') }}">
                                <i class="bi bi-graph-up me-2"></i> Аналитика
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="container mt-5">
        <h1>Редактировать филиал</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('branches.update', $branch->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Название:</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ $branch->name }}" required>
            </div>
            <div class="form-group">
                <label for="address">Адрес:</label>
                <input type="text" class="form-control" name="address" id="address" value="{{ $branch->address }}">
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
            <a href="{{ route('branches.index') }}" class="btn btn-secondary">Отмена</a>
        </form>

        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Назад на главную</a>
    </div>
            </main>

             <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarCollapse = document.getElementById('sidebarCollapse');
            const sidebar = document.getElementById('sidebar');

            if (sidebarCollapse) {
                sidebarCollapse.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                });
            }
        });
    </script>
</body>
</html>
