<?php
// Função para validar o formato do email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para validar o tipo do relatorio
function validarRelatorio($relatorio) {
    $extensao = pathinfo($relatorio['name'], PATHINFO_EXTENSION);

    $extensoes_permitidas = array("txt", "pdf");

    if (in_array(strtolower($extensao), $extensoes_permitidas)) {
        return true;
    } else {
        return false;
    }
}

// Função para inserir os dados no banco de dados
function inserirDadosNoBanco($matricula, $nome, $email, $nome_relatorio) {

    $servername = "localhost";
    $username = "seu_usuario";
    $password = "sua_senha";
    $database = "seu_banco_de_dados";

    $conn = new mysqli($servername, $username, $password, $database);

    if ($conn->connect_error) {
        die("Erro de conexão: " . $conn->connect_error);
    }

    $matricula = $conn->real_escape_string($matricula);
    $nome = $conn->real_escape_string($nome);
    $email = $conn->real_escape_string($email);
    $nome_relatorio = $conn->real_escape_string($nome_relatorio);

    $sql = "INSERT INTO usuarios (matricula, nome, email, relatorio) VALUES ('$matricula', '$nome', '$email', '$nome_relatorio')";

    if ($conn->query($sql) === TRUE) {
        echo "Dados inseridos com sucesso no banco de dados.";
    } else {
        echo "Erro ao inserir dados no banco de dados: " . $conn->error;
    }

    $conn->close();
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (!empty($_POST['matricula']) && !empty($_POST['nome']) && !empty($_POST['email']) && isset($_FILES['relatorio'])) {
        

        if (validarEmail($_POST['email'])) {
            
            if(validarRelatorio($_FILES['relatorio'])) {
                
                $diretorio = "uploads/";
                
                $nome_relatorio = basename($_FILES["relatorio"]["name"]);

                $caminho_relatorio = $diretorio . $nome_relatorio;
                
                if (move_uploaded_file($_FILES["relatorio"]["tmp_name"], $caminho_relatorio)) {
                    
                    $matricula = $_POST['matricula'];
                    $nome = $_POST['nome'];
                    $email = $_POST['email'];
                    
                    inserirDadosNoBanco($matricula, $nome, $email, $nome_relatorio);
                    
                } else {
                    echo "Erro ao enviar o relatorio.";
                }
            } else {
                echo "Apenas relatorios .txt e .pdf são permitidos.";
            }
        } else {
            echo "Email inválido.";
        }
    } else {
        echo "Por favor, preencha todos os campos e selecione um relatorio.";
    }
}
?>