<?php
namespace App\Controllers;

require_once '../../Models/Empresa.php';

use App\Models\Empresa;


class EmpresaController{
    private $empresaModel;

    public function __construct() {
        $this->empresaModel = new Empresa();
    }

    //faz a listagem de empresas específicamente para a aba de list.php // não foi feito a lógica do search tudo junto para melhor encapsulamento
    public function listarEmpresas() {
        $empresas = $this->empresaModel->getAllEmpresas();
        $html = '';

        if ($empresas) {
            while ($row = $empresas->fetch_assoc()) {
                $html .= "<tr>";
                $html .= "<td><a href='details.php?id=" . urlencode($row['ID_empresa']) . "'>" . htmlspecialchars($row['nome_empresa']) . "</a></td>";
                $html .= "<td>" . htmlspecialchars($row['cnpj_empresa']) . "</td>";
                $html .= "<td>
                <div class='row'>
                    <div class='col-md-6'>
                        <form method='POST' action='' class='m-0'>
                            <input type='hidden' name='delete_id' value='" . htmlspecialchars($row['ID_empresa']) . "'>
                                <button type='submit' name='delete' class='btn btn-danger btn-sm w-100' style='background-color: #a40c1c; color: white; onclick='return confirm(\"Tem certeza que deseja excluir esta empresa?\");'>
                                Excluir
                            </button>
                        </form>
                    </div>
                    <div class='col-md-6'>
                        <form method='POST' action='edit.php' class='m-0'>
                            <input type='hidden' name='ID_empresa' value='" . htmlspecialchars($row['ID_empresa']) . "'>
                                <button type='submit' name='delete' class='btn btn-danger btn-sm w-100' style='background-color: #043464; color: white;);'>
                                Editar
                            </button>
                        </form>
                    </div>
                </div>
              </td>";
                $html .= "</tr>";
            }
        }

        return $html; 
    }

    public function searchEmpresa($search = '') {
        $result = $this->empresaModel->getEmpresaBySearch($search);
        $empresasData = [];
    
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $empresasData[] = [
                    'id' => $row['ID_empresa'],
                    'nome' => $row['nome_empresa'],
                    'cnpj' => $row['cnpj_empresa']
                ];
            }
        }
        $html = '';
        foreach ($empresasData as $empresa) {
            $html .= "<tr>
                        <td><a href='details.php?id=" . urlencode($empresa['id']) . "'>" . htmlspecialchars($empresa['nome']) . "</a></td>
                        <td>{$empresa['cnpj']}</td>
                        <td>
                            <div class='row'>
                                <div class='col-md-6'>
                                    <form method='POST' action='' class='m-0'>
                                        <input type='hidden' name='delete_id' value='" . htmlspecialchars($empresa['id']) . "'>
                                        <button type='submit' name='delete' class='btn btn-danger btn-sm w-100' style='background-color: #a40c1c; color: white; onclick='return confirm(\"Tem certeza que deseja excluir esta empresa?\");'>
                                            Excluir
                                        </button>
                                    </form>
                                </div>
                                <div class='col-md-6'>
                                    <a href='update.php?id=" . urlencode($empresa['id']) . "' class='btn btn-primary btn-sm w-100' style='background-color: #043464; color: white;text-decoration: none;'>
                                        Editar
                                    </a>
                                </div>
                            </div>
                        </td>
                      </tr>";
        }
        return $html;
    }
    //algo a trabalhar definitivamente
    public function exibirDetalhes($id) {
        $empresa = $this->empresaModel->getEmpresaByID($id);
        return $empresa;
    }

    //Prepara dados para formas e subformas:
    public function retornaFormasdeRecebimento($id){
        $empresa_recebimento = $this->empresaModel->getEmpresaRecebimentoById($id);
        foreach ($empresa_recebimento as $empresa_R){
            $data = $empresa_R['forma_recebimento_id'];
        }
        $fRecebimentos = $this->empresaModel->getFormaRecebimentoByID($data);
        return $fRecebimentos;
    }

    public function retornaSubformasdeRecebimento($id){
        $empresa_recebimento = $this->empresaModel->getEmpresaRecebimentoById($id);
        if ($empresa_recebimento) {
            foreach ($empresa_recebimento as $empresa_R){
            $data = $empresa_R['forma_recebimento_id'];
    
            $subFormas = $this->empresaModel->getSubFormaRecebimentoById($data);
        }
            return $subFormas;
        } else {
            echo 'Empresa de recebimento não encontrada.';
        }
    }
    
    public function retornaObsFormasRecebimento($id){
        $empresa_recebimento = $this->empresaModel->getEmpresaRecebimentoById($id);
        foreach ($empresa_recebimento as $empresa_R){
            $OBS_recebimento = $empresa_R['obs_recebimento'];
        }
        return $OBS_recebimento;
    }

    public function montaHTMLRecebimentos($id){
        $formaRecebimento = $this->retornaFormasdeRecebimento($id);
        $subFormasRecebimento = $this->retornaSubformasdeRecebimento($id);
        $obsRecebimento = $this->retornaObsFormasRecebimento($id);
    
        $html = '<form>';
        $html .= '<label class="form-label">Selecione as Formas de Recebimento:</label>';
        $html .= '<select class="form-select mb-3" name="forma_recebimento">';
        $formas = ['digital' => 'Digital', 'fisico' => 'Físico', 'digital e fisico' => 'Digital e Físico'];
        foreach ($formas as $value => $label) {
            $selected = ($formaRecebimento == $value) ? 'selected' : '';
            $html .= "<option value=\"$value\" $selected>$label</option>";
        }
        $html .= '</select>';
        $html .= '<label class="form-label">Selecione as Subformas de Recebimento:</label>';
        $subFormas = ['email' => 'Email', 'Whatsapp' => 'WhatsApp', 'Skype' => 'Skype', 'Assessorias' => 'Assessorias', 'Malote' => 'Malote'];
        foreach ($subFormas as $value => $label) {
            $checked = in_array($value, $subFormasRecebimento) ? 'checked' : '';
            $html .= "<div class=\"form-check\">
                        <input class=\"form-check-input\" type=\"checkbox\" name=\"subformas_recebimento[]\" value=\"$value\" id=\"subforma_$value\" $checked>
                        <label class=\"form-check-label\" for=\"subforma_$value\">$label</label>
                      </div>";
        }
        $html .= '<div class="mb-3">
                    <label for="OBS_recebimentos" class="form-label">Observações sobre os Recebimentos</label>
                    <input type="text" id="OBS_recebimentos" name="OBS_recebimentos" class="form-control" placeholder="Observações" value="' . htmlspecialchars($obsRecebimento) . '">
                  </div>';
        $html .= '</form>';
        return $html;
    }

    public function criarEmpresa() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->empresaModel->createEmpresa($_POST);
        }
    }


    public function searchORList($searchTerm) {
        if ($searchTerm) {
            return $this->searchEmpresa($searchTerm);
        } else {
            return $this->listarEmpresas();
        }
    }
    
    public function empresaEncontradaURL(){
        $empresa = $this->empresaModel->getEmpresaBYUrl();
        if (!$empresa) {
            echo "Empresa não encontrada.";
            exit();
        }else{
            return $empresa;
        }
    }
    
    public function DetailsFormasDeImportacao(){
        $importacao = $this->empresaModel->getFormasImportacaoBYUrl();
        $obs_importacao = null;
        $importacao_data = [];
    
        // Verifica se há resultados
        if ($importacao->num_rows > 0) {
            // Itera sobre os resultados
            while ($row = $importacao->fetch_assoc()) {
                $importacao_data[] = $row['tipo_formasImportacao'];
                $obs_importacao = $row['obs_importacao']; // Obter a observação, assumindo que é a mesma para todas as formas de importação
            }
    
            // Gera a tabela HTML
            echo "<table class='table table-bordered'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Forma de Importação</th>";
            echo "<th>Observações</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
    
            foreach ($importacao_data as $index => $forma) {
                echo "<tr>";
                echo "<td>";
                echo htmlspecialchars($forma);
                echo "</td>";
    
                // Exibe a observação apenas na primeira linha
                if ($index === 0) {
                    echo "<td rowspan=" . count($importacao_data) . ">";
                    echo htmlspecialchars($obs_importacao);
                    echo "</td>";
                }
                echo "</tr>";
            }
    
            echo "</tbody>";
            echo "</table>";
        } else {
            echo "<p>Nenhuma forma de importação encontrada.</p>";
        }
    }
    
    public function DetailsLinks(){
        $links = $this->empresaModel->getLinksBYUrl();

        echo "<p><strong>Links:</strong>";
        echo htmlspecialchars($links['links_empresa']);
        echo "</p>";
        echo "<p><strong>Observações:</strong>";
        echo htmlspecialchars($links['obs_link']);
        echo "</p>";
    }

    public function DetailsFormasDeRecebimento(){
        $recebimento = $this->empresaModel->getFormasRecebimentoBYUrl();
        // Obter observação única
        $obs_recebimento = null;
        $recebimento_data = [];
        while ($row = $recebimento->fetch_assoc()) {
            $recebimento_data[] = $row;
            $obs_recebimento = $row['obs_recebimento']; // Obter a observação, assumindo que é a mesma para todas as formas de recebimento
        }
        if (!empty($recebimento_data)){
            echo "<table class='table table-bordered'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Forma de Recebimento</th>
                  <th>Subforma</th>              
                  <th>Observações</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            foreach ($recebimento_data as $index => $row){
                echo "<tr>";
                echo "<td>";
                echo htmlspecialchars($row['Tipo_formaRecebimento']);
                echo "</td>";
                echo "<td>";
                echo htmlspecialchars($row['nome_subforma']);
                echo "</td>";
                if ($index === 0){
                    echo "<td rowspan=";
                    echo count($recebimento_data);
                    echo ">";
                    echo htmlspecialchars($obs_recebimento);
                    echo "</td>";
                }
                echo "</tr>";
            }
            echo "</tbody>";
            echo "</table>";
        }else{
            echo "Nenhuma forma de recebimento encontrada.";
        }        
    }
    public function DetailsParticularidades(){
        $particularidades = $this->empresaModel->getParticularidadesBYUrl();
        if ($particularidades->num_rows > 0){
            $row = $particularidades->fetch_assoc();
            echo "<table class='table table-bordered'>";
            echo "<thead>";
            echo "<tr>";
            echo "<th>Particularidades</th>
                  <th>Observações</th>";
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";
            echo "<tr>";
            echo "<td>";
            echo htmlspecialchars($row['particularidades_empresa']);
            echo "</td>";
            echo "<td>";
            echo htmlspecialchars($row['OBS_particularidades']);
            echo "</td>";
            echo "</tr>";
            echo "</tbody>";
            echo "</table>";
        }
    }


}  
?>
