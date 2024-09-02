<?php
// Aqui você pode adicionar qualquer lógica PHP necessária antes do HTML
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário Bootstrap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h1>Formulário de Cadastro de Empresa</h1>
        <form action="createEmpresa.php" method="post">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome da Empresa</label>
                <input type="text" id="nome" name="nome_empresa" class="form-control" placeholder="Nome da Empresa" required>
            </div>
            <div class="mb-3">
                <label for="cnpj" class="form-label">CNPJ</label>
                <input type="text" id="cnpj" name="cnpj_empresa" class="form-control" placeholder="CNPJ" required>
            </div>
            <div class="mb-3">
                <label for="endereco" class="form-label">Endereço</label>
                <input type="text" id="endereco" name="endereco_empresa" class="form-control" placeholder="Endereço">
            </div>
            <div class="mb-3">
                <label for="Links" class="form-label">Links da Empresa</label>
                <input type="text" id="links" name="links_empresa" class="form-control" placeholder="links">
            </div>
            <div class="mb-3">
                <label for="particularidades" class="form-label">particularidades</label>
                <input type="text" id="particularidades" name="particularidades" class="form-control" placeholder="particularidades">
            </div>


        <h3>Formas de importação</h3>
        <label class="form-label">Selecione as Formas de importação:</label>
        <div class="row">
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="importacao[]" value="Entrada por SPED" id="importacao1">
                    <label class="form-check-label" for="integracao1">Entrada por SPED</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="importacao[]" value="Saída por SPED" id="importacao2">
                    <label class="form-check-label" for="integracao2">Saída por SPED</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="importacao[]" value="Entradas por XML" id="importacao3">
                    <label class="form-check-label" for="integracao3">Entradas por XML</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="importacao[]" value="Saida por XML" id="importacao4">
                    <label class="form-check-label" for="integracao3">Saida por XML</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="importacao[]" value="Entradas pelo SAT" id="importacao5">
                    <label class="form-check-label" for="integracao3">Entradas pelo SAT</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="importacao[]" value="Saida pelo Sieg" id="importacao6">
                    <label class="form-check-label" for="integracao3">Saida pelo Sieg</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="importacao[]" value="NFCe por Sped" id="importacao7">
                    <label class="form-check-label" for="integracao3">NFCe por Sped</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="importacao[]" value="NFCe por xml - Sieg" id="importacao8">
                    <label class="form-check-label" for="integracao3">NFCe por xml - Sieg</label>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="importacao[]" value="NFCe por XML - Copiado do Cliente" id="importacao9">
                    <label class="form-check-label" for="integracao3">NFCe por XML - Copiado do Cliente</label>
                </div>
            </div>
        </div> <br>
            <div class="mb-3">
                <label for="obs importacao" class="form-label">Observações sobre as importações</label>
                <input type="text" id="OBS_importacao" name="OBS_importacao" class="form-control" placeholder="importacao">
            </div>
        

        <h3 class="mt-4">Formas de Recebimento</h3>
        <label class="form-label">Selecione as Formas de Recebimento:</label>
        <select class="form-select" name="forma_recebimento">
            <option value="1">Digital</option>
            <option value="2">Físico</option>
            <option value="3">Digital e Físico</option>
        </select>

        <label class="form-label mt-3">Selecione as Subformas de Recebimento:</label>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="subformas_recebimento[]" value="1" id="subforma1">
            <label class="form-check-label" for="subforma1">Email</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="subformas_recebimento[]" value="2" id="subforma2">
            <label class="form-check-label" for="subforma2">WhatsApp</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="subformas_recebimento[]" value="3" id="subforma3">
            <label class="form-check-label" for="subforma3">Skype</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="subformas_recebimento[]" value="4" id="subforma4">
            <label class="form-check-label" for="subforma4">Assessorias</label>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" name="subformas_recebimento[]" value="4" id="subforma4">
            <label class="form-check-label" for="subforma4">Malote</label>
        </div>


            <button type="submit" class="btn btn-primary">Criar Empresa</button>
        </form>
    </div>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
