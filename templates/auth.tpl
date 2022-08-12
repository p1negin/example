<!DOCTYPE html>
<html>
<head>
    <title>Авторизация</title>
    <link rel="stylesheet" href="/assets/css/bootstrap5/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
    <div class="container-fluid">
        <a class="navbar-brand" href="/">Авторизация</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
<main class="container">
    {% if error != null %}
    <div class="alert alert-danger">{{ error }}</div>
    {% endif %}
    <h3 class="mb-4">Авторизация</h3>

    <div class="col-4">
    <form method="post" action="/authAction">

        <div class = "mb-3 row">
            <label for="form-select-sm login" class="col-sm-2 col-form-label">Логин</label>
            <div class="col-sm-10">
                <input name="login" class="form-control" placeholder="Введите логин" id="login"/>
            </div>
        </div>

        <div class = "mb-3 row">
            <label for="form-select-sm password" class="col-sm-2 col-form-label">Пароль</label>
            <div class="col-sm-10">
                <input name="password" type="password" class="form-control" placeholder="Введите пароль" id="password"/>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <td>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-outline-dark" name="authBtn">Войти в кабинет</button>
                    </div>
                </td>
                <td>
                    <div class="d-grid gap-2">
                        <a href="/reg" class="mt-2 btn btn-outline-dark">Создать аккаунт</a>
                    </div>
                </td>
            </div>
        </div>
    </form>
    </div>
</main>
<script src="/assets/js/bootstrap5/bootstrap.min.js"></script>
</body>
</html>