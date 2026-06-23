<?php

function getSetting(
    string $key,
    $default = null
)
{
    global $mysqli;

    $sql = "
        SELECT
            setting_value
        FROM ml_settings
        WHERE setting_key = ?
        LIMIT 1
    ";

    $stmt = $mysqli->prepare($sql);

    if (!$stmt) {
        return $default;
    }

    $stmt->bind_param(
        's',
        $key
    );

    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result->num_rows) {
        return $default;
    }

    $setting =
        $result->fetch_assoc();

    return $setting['setting_value'];
}