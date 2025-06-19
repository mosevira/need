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
        <h1 class="mt-4 mb-4">Редактировать пользователя</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="name">Имя:</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label for="surname">Фамилия:</label>
                <input type="text" class="form-control" name="surname" id="surname" value="{{ old('surname', $user->surname) }}" required>
            </div>
            <div class="form-group">
                <label for="patronymic">Отчество:</label>
                <input type="text" class="form-control" name="patronymic" id="patronymic" value="{{ old('patronymic', $user->patronymic) }}">
            </div>
            <div class="form-group">
                <label for="birth_date">Дата рождения:</label>
                <input type="date" class="form-control" name="birth_date" id="birth_date" value="{{ old('birth_date', $user->birth_date) }}">
            </div>
            <div class="form-group">
                <label for="phone">Телефон:</label>
                <input type="tel" class="form-control" name="phone" id="phone" value="{{ old('phone', $user->phone) }}">
            </div>
            <div class="form-group">
                <label for="start_job_date">Дата начала работы:</label>
                <input type="date" class="form-control" name="start_job_date" id="start_job_date" value="{{ old('start_job_date', $user->start_job_date) }}">
            </div>
            <div class="form-group">
                <label for="end_job_date">Дата окончания работы:</label>
                <input type="date" class="form-control" name="end_job_date" id="end_job_date" value="{{ old('end_job_date', $user->end_job_date) }}">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" name="email" id="email" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group">
                <label for="role">Роль:</label>
                <select class="form-control" name="role" id="role" required>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Администратор</option>
                    <option value="storekeeper" {{ old('role', $user->role) == 'storekeeper' ? 'selected' : '' }}>Кладовщик</option>
                    <option value="seller" {{ old('role', $user->role) == 'seller' ? 'selected' : '' }}>Продавец</option>
                </select>
            </div>
            <div class="form-group">
                <label for="branch_id">Филиал:</label>
                <select class="form-control" name="branch_id" id="branch_id">
                    <option value="">Выберите филиал</option>
                    @foreach($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id', $user->branch_id) == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Активен</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>

        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary mt-3">Назад к списку пользователей</a>
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
