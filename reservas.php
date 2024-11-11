<?php
// Configuração de conexão com o banco de dados
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_db";

try {
    // Cria a conexão com o banco de dados
    $conn = new mysqli($servername, $username, $password, $dbname);
    $conn->set_charset("utf8"); // Define o charset para UTF-8
    if ($conn->connect_error) {
        throw new Exception("Falha na conexão: " . $conn->connect_error); // Verifica erros na conexão
    }

    // Verifica se o parâmetro delete_id está presente na URL e executa a exclusão
    if (isset($_GET['delete_id'])) {
        $delete_id = (int) $_GET['delete_id']; // Converte o ID para inteiro
        $conn->query("DELETE FROM reservas WHERE id=$delete_id"); // Executa a exclusão
        header("Location: reservas.php"); // Redireciona para a mesma página
        exit();
    }

    // Verifica se o formulário foi enviado para atualizar uma reserva
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_id'])) {
        $update_id = (int) $_POST['update_id']; // Converte o ID para inteiro
        // Escapa os dados para evitar SQL Injection
        $nome = $conn->real_escape_string(trim($_POST['nome']));
        $sexo = $conn->real_escape_string(trim($_POST['sexo']));
        $email = $conn->real_escape_string(trim($_POST['email']));
        $data_chegada = $conn->real_escape_string(trim($_POST['data_chegada']));
        $numero_noites = (int) $_POST['numero_noites'];
        $numero_hospedes = (int) $_POST['numero_hospedes'];
        $total_estimado = (float) $_POST['total_estimado'];
        $mensagem = $conn->real_escape_string(trim($_POST['mensagem']));
        $newsletter = isset($_POST['newsletter']) ? 1 : 0; // Verifica se o checkbox foi marcado

        // Atualiza os dados na tabela do banco de dados
        $conn->query("UPDATE reservas SET 
                        nome='$nome', sexo='$sexo', email='$email', data_chegada='$data_chegada',
                        numero_noites=$numero_noites, numero_hospedes=$numero_hospedes,
                        total_estimado=$total_estimado, mensagem='$mensagem', newsletter=$newsletter
                        WHERE id=$update_id");
        header("Location: reservas.php"); // Redireciona para a página de reservas
        exit();
    }

    // Consulta todas as reservas no banco de dados
    $result = $conn->query("SELECT * FROM reservas");

} catch (Exception $e) {
    echo "Erro: " . $e->getMessage(); // Exibe erros de conexão ou consulta
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas</title>
    <!-- Inclusão do CSS específico para esta página -->
    <link rel="stylesheet" href="reservas.css">
</head>
<body>
    <div class="form-container">
        <h1>Reservas</h1>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Sexo</th>
                    <th>Email</th>
                    <th>Data de Chegada</th>
                    <th>Nº Noites</th>
                    <th>Nº Hóspedes</th>
                    <th>Total Estimado</th>
                    <th>Mensagem</th>
                    <th>Newsletter</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Loop para exibir as reservas -->
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <form method="POST" action="reservas.php">
                            <input type="hidden" name="update_id" value="<?php echo $row['id']; ?>">
                            <td><input type="text" name="nome" value="<?php echo htmlspecialchars($row['nome']); ?>"></td>
                            <td>
                                <select name="sexo">
                                    <option value="M" <?php echo ($row['sexo'] == 'M') ? 'selected' : ''; ?>>Masculino</option>
                                    <option value="F" <?php echo ($row['sexo'] == 'F') ? 'selected' : ''; ?>>Feminino</option>
                                    <option value="Outro" <?php echo ($row['sexo'] == 'Outro') ? 'selected' : ''; ?>>Outro</option>
                                </select>
                            </td>
                            <td><input type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>"></td>
                            <td><input type="date" name="data_chegada" value="<?php echo htmlspecialchars($row['data_chegada']); ?>"></td>
                            <td><input type="number" name="numero_noites" value="<?php echo $row['numero_noites']; ?>"></td>
                            <td><input type="number" name="numero_hospedes" value="<?php echo $row['numero_hospedes']; ?>"></td>
                            <td><input type="number" step="0.01" name="total_estimado" value="<?php echo $row['total_estimado']; ?>"></td>
                            <td><textarea name="mensagem"><?php echo htmlspecialchars($row['mensagem']); ?></textarea></td>
                            <td><input type="checkbox" name="newsletter" value="1" <?php echo ($row['newsletter'] == 1) ? 'checked' : ''; ?>></td>
                            <td>
                                <button type="submit">Atualizar</button>
                                <a href="reservas.php?delete_id=<?php echo $row['id']; ?>" onclick="return confirm('Deseja excluir esta reserva?');">Excluir</a>
                            </td>
                        </form>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <!-- Botão para redirecionar para a página inicial -->
        <a href="index.html" class="btn-home">Voltar para a Página Inicial</a>
    </div>
</body>
</html>