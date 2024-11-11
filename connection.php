<?php
// Configuração das credenciais de conexão com o banco de dados
$servername = "localhost"; // Endereço do servidor de banco de dados
$username = "root"; // Nome de usuário para acessar o banco de dados
$password = ""; // Senha do banco de dados
$dbname = "hotel_db"; // Nome do banco de dados onde as reservas serão armazenadas

// Tentativa de conexão ao banco de dados usando try-catch para tratamento de erros
try {
    // Cria uma nova conexão com o banco de dados MySQL usando as credenciais fornecidas
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8"); // Define o conjunto de caracteres para UTF-8 para suportar caracteres especiais
    
    // Verifica se houve erro ao tentar estabelecer a conexão
    if ($conn->connect_error) {
        // Lança uma exceção com a mensagem de erro em caso de falha
        throw new Exception("Conexão falhou: " . $conn->connect_error);
    }

    // Recebe e sanitiza os dados enviados pelo formulário para prevenir ataques de injeção SQL e espaços em branco desnecessários
    $nome = $conn->real_escape_string(trim($_POST['nome']));
    $sexo = $conn->real_escape_string(trim($_POST['sexo']));
    $email = $conn->real_escape_string(trim($_POST['email']));
    $data_chegada = $conn->real_escape_string(trim($_POST['data_chegada']));
    $numero_noites = (int) $_POST['numero_noites'];
    $numero_hospedes = (int) $_POST['numero_hospedes'];
    $total_estimado = (float) $_POST['total_estimado'];
    $mensagem = $conn->real_escape_string(trim($_POST['mensagem']));
    $newsletter = isset($_POST['newsletter']) ? 1 : 0;

    // Prepara uma instrução SQL para inserção segura dos dados na tabela 'reservas'
    $stmt = $conn->prepare("INSERT INTO reservas (nome, sexo, email, data_chegada, numero_noites, numero_hospedes, total_estimado, mensagem, newsletter) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Associa os parâmetros da query aos valores das variáveis
    $stmt->bind_param("ssssiiisi", $nome, $sexo, $email, $data_chegada, $numero_noites, $numero_hospedes, $total_estimado, $mensagem, $newsletter);

    // Executa a query preparada e verifica se foi bem-sucedida
    if ($stmt->execute()) {
        // Exibe mensagem de sucesso e redireciona após 3 segundos
        echo "<p>Reserva criada com sucesso! Redirecionando...</p>";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'reservas.php';
                }, 3000); // Redireciona após 3 segundos
              </script>";
    } else {
        // Lança uma exceção com a mensagem de erro se a execução falhar
        throw new Exception("Erro ao criar reserva: " . $stmt->error);
    }

    // Fecha o statement e a conexão para liberar recursos
    $stmt->close();
    $conn->close();

} catch (Exception $e) {
    // Captura a exceção e exibe a mensagem de erro
    echo "Erro: " . $e->getMessage();
}
?>