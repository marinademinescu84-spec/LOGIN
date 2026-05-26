<?php
function getConfig($conn, $label) {
    $stmt = $conn->prepare("SELECT valore FROM configurazioni WHERE label = ?");
    $stmt->bind_param("s", $label);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row ? $row['valore'] : null;
}
 

?>