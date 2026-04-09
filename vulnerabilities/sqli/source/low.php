<?php

if( isset( $_REQUEST[ 'Submit' ] ) ) {
    // Get input
    $id = $_REQUEST[ 'id' ];

    switch ($_DVWA['SQLI_DB']) {
        case MYSQL:
            // --- INICIO DE CÓDIGO SEGURO ---
            // 1. Usamos un marcador de posición (?) en lugar de la variable directa
            $query  = "SELECT first_name, last_name FROM users WHERE user_id = ?;";
            
            // 2. Preparamos la sentencia
            $stmt = mysqli_prepare($GLOBALS["___mysqli_ston"], $query);
            
            // 3. Vinculamos el parámetro (la "s" indica que el ID se trata como string)
            mysqli_stmt_bind_param($stmt, "s", $id);
            
            // 4. Ejecutamos la consulta de forma segura
            mysqli_stmt_execute($stmt);
            
            // 5. Obtenemos el resultado
            $result = mysqli_stmt_get_result($stmt);

            // Get results
            while( $row = mysqli_fetch_assoc( $result ) ) {
                $first = $row["first_name"];
                $last  = $row["last_name"];
                $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
            }

            mysqli_stmt_close($stmt);
            // --- FIN DE CÓDIGO SEGURO ---
            break;
            
        case SQLITE:
            // (Puedes dejar el código de SQLite como está o aplicar una lógica similar)
            global $sqlite_db_connection;
            $query  = "SELECT first_name, last_name FROM users WHERE user_id = '$id';";
            try {
                $results = $sqlite_db_connection->query($query);
            } catch (Exception $e) {
                echo 'Caught exception: ' . $e->getMessage();
                exit();
            }
            if ($results) {
                while ($row = $results->fetchArray()) {
                    $first = $row["first_name"];
                    $last  = $row["last_name"];
                    $html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                }
            }
            break;
    } 
}

?>
