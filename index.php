<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste King Host</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Lista de Estados e Usuários</h1>
        <?php
        
        // URL da API
        $url = "https://dummyjson.com/users" ;

        // Inicializando a sessão cURL
        $ch = curl_init();

        // Configurando a URL e outras opções
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Retorna o resultado como uma string

        // Executando a requisição
        $response = curl_exec($ch);

        // Verificando se houve erro na requisição cURL
        if (curl_errno($ch)) {
            echo 'Erro: ' . curl_error($ch);
        } else {
            // Obtém o código de status da resposta de erro
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            // Decodificando a resposta JSON
            $users = json_decode($response, true);

            // Avaliando se a resposta contém a lista de usuários
            if (isset($users['users'])) {
                // Array para armazenar usuários por estado
                $usersByState = [];

                // Separando os usuários por estado
                foreach ($users['users'] as $user) {
                    $state = $user['address']['state'];
                    $usersByState[$state][] = $user;
                }

                // Ordenando os usuários por nome dentro de cada estado
                foreach ($usersByState as $state => &$users) {
                    usort($users, function($a, $b) {
                        return strcmp($a['firstName'], $b['firstName']);
                    });
                }

                // Exibindo os usuários separados por estado e ordenados por nome
                foreach ($usersByState as $state => $users) {
                    echo "<h2>Estado: " . htmlspecialchars($state) . "</h2>";
                    echo "<div class='user-list'>";
                    foreach ($users as $user) {
                        echo "<div class='user'>";
                        echo "<p><strong>ID:</strong> " . htmlspecialchars($user['id']) . "</p>";
                        echo "<p><strong>Nome:</strong> " . htmlspecialchars($user['firstName']) . " " . htmlspecialchars($user['lastName']) . "</p>";
                        echo "<p><strong>Idade:</strong> " . htmlspecialchars($user['age']) . "</p>";
                        echo "<p><strong>Cidade:</strong> " . htmlspecialchars($user['address']['city']) . "</p>";
                        echo "<p><strong>Email:</strong> " . htmlspecialchars($user['email']) . "</p>";
                        echo "<p><strong> - - - - - - - - - - </strong> ";
                        echo "</div>";
                    }
                    
                    echo "</div>";
                }
            } else {
                // Exibindo erro se acaso a URL esta incorreta ou não encontrou os usuários
                echo "<p>Nenhum usuário encontrado.</p>";
                echo "<p><strong>Erro: </strong>" . htmlspecialchars($httpcode) . "</p>";
            }
        }

        // Fechando a sessão cURL
        curl_close($ch);
        ?>
    </div>
</body>
</html>

