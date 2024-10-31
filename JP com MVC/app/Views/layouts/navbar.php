<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jp</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-custom {
            background-color: #003366; /* Azul Escuro */
        }
        .navbar-custom .navbar-brand,
        .navbar-custom .navbar-nav .nav-link {
            color: #ffffff;
            transition: color 0.3s ease;
        }
        .navbar-custom .navbar-brand:hover,
        .navbar-custom .navbar-nav .nav-link:hover {
            color: #ff0000; /* Vermelho */
            border-bottom: 2px solid #ff0000; /* Linha vermelha embaixo */
            padding-bottom: 0.5rem; /* Espaço adicional para a borda */
        }
        .navbar-custom .navbar-nav .nav-link {
            position: relative;
            display: inline-block;
            padding: 0.5rem 1rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom">
        <a class="navbar-brand" href="#">Minha Empresa</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="form.php">Cadastro de Empresa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="list.php">Listar Empresas</a>
                </li>
<!--                 <li class="nav-item">
                    <a class="nav-link" href="pagina_usuario.php">Página de Usuário</a>
                </li> -->
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <!-- Conteúdo da página -->
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
