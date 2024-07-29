<?php
session_start();
require_once "../function/function.php";
$conexao = conn();

function excluirUsuario($conexao, $id_usuario) {
    $id_usuario = mysqli_real_escape_string($conexao, $id_usuario);

    // Verifica se o usuário existe
    $sql = "SELECT * FROM usuario WHERE id_user = '$id_usuario'";
    $resultado = mysqli_query($conexao, $sql);

    if (mysqli_num_rows($resultado) === 0) {
        return "Usuário não encontrado.";
    }

    // Exclui o usuário
    $sql_delete_usuario = "DELETE FROM usuario WHERE id_user = '$id_usuario'";
    if (!mysqli_query($conexao, $sql_delete_usuario)) {
        return "Erro ao excluir usuário: " . mysqli_error($conexao);
    }

    if (mysqli_affected_rows($conexao) > 0) {
        return "Usuário foi excluído com sucesso.";
    } else {
        return "Nenhum usuário foi excluído.";
    }
}

if (!isset($_GET['id_user']) || empty($_GET['id_user'])) {
    echo "ID do usuário não foi recebido.";
    exit();
}

$resultado_exclusao = excluirUsuario($conexao, $_GET['id_user']);
mysqli_close($conexao);

// Destrói a sessão
session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <title>Exclusão de Usuário</title>
</head>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if ($resultado_exclusao === "Usuário foi excluído com sucesso.") { ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Sucesso',
                    text: 'Usuário foi excluído com sucesso!',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '../index.html';
                });
            <?php } else { ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: '<?php echo $resultado_exclusao; ?>',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    window.history.back();
                });
            <?php } ?>
        });
    </script>
</body>
</html>
