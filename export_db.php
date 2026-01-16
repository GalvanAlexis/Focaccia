<?php

/**
 * Script para exportar la base de datos SQLite a formato SQL
 */

$dbPath = __DIR__ . '/database/database.sqlite';
$outputPath = __DIR__ . '/database/focaccia_export.sql';

if (!file_exists($dbPath)) {
    die("Error: No se encontró la base de datos en: $dbPath\n");
}

try {
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "-- Focaccia Database Export\n";
    $sql .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";

    // Obtener todas las tablas
    $tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%' ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);

    foreach ($tables as $table) {
        echo "Exportando tabla: $table\n";

        // Obtener el CREATE TABLE
        $createTable = $db->query("SELECT sql FROM sqlite_master WHERE type='table' AND name='$table'")->fetchColumn();
        $sql .= "-- Table: $table\n";
        $sql .= "DROP TABLE IF EXISTS `$table`;\n";
        $sql .= $createTable . ";\n\n";

        // Obtener los datos
        $rows = $db->query("SELECT * FROM `$table`")->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($rows)) {
            $sql .= "-- Data for table: $table\n";

            foreach ($rows as $row) {
                $columns = array_keys($row);
                $values = array_map(function ($val) use ($db) {
                    if ($val === null) {
                        return 'NULL';
                    }
                    return $db->quote($val);
                }, array_values($row));

                $sql .= "INSERT INTO `$table` (`" . implode('`, `', $columns) . "`) VALUES (" . implode(', ', $values) . ");\n";
            }

            $sql .= "\n";
        }
    }

    // Guardar el archivo
    file_put_contents($outputPath, $sql);

    echo "\n✓ Exportación completada exitosamente!\n";
    echo "Archivo generado: $outputPath\n";
    echo "Tamaño: " . round(filesize($outputPath) / 1024, 2) . " KB\n";
} catch (Exception $e) {
    die("Error: " . $e->getMessage() . "\n");
}
