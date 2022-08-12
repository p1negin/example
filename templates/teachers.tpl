<!DOCTYPE html>
<html>
<head>
    <title>Преподаватели</title>
    <link rel="stylesheet" href="/assets/css/bootstrap5/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">Школа</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="/teachers">Преподаватели</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/subjects">Предметы</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/logout">Logout ({{ user.login }})</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="container">

        <h1>Преподаватели</h1>
        <div class="row">
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>
            <div class="col"></div>

            <div class="col mb-3">
                <a class="btn btn-outline-dark" href="/teachers/create" role="button">Создать</a>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table">
                    <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Фото</th>
                        <th scope="col">Имя учителя</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    {% for i, teacher in teachers %}
                        <tr>
                            <td scope="row">{{ teacher.id }}</td>
                            <td><img width="80" src="data:image/jpeg;base64,{{ teacher.avatar }}"/></td>
                            <td width="800">{{ teacher.name }}</td>
                            <td>
                                <a class="btn btn-xs btn-outline-dark" href="/teachers/edit/{{ teacher.id }}" role="button">Изменить</a>
                                <a class="btn btn-xs btn-danger" href="/teachers/remove/{{ teacher.id }}" role="button">Удалить</a>
                            </td>
                        </tr>
                    {% endfor %}
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script src="/assets/js/bootstrap5/bootstrap.min.js"></script>
</body>
</html>