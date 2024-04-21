<?php

set_time_limit(600);

$csvFile = "./uploads/output.csv";

$databaseFile = "./database/test_2.db";

if (file_exists($databaseFile)) {
    unlink($databaseFile);
}

try {

    $db = new PDO("sqlite:$databaseFile");

    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $db->beginTransaction();

    if (($handle = fopen($csvFile, 'r')) !== false) {

        if (($data = fgetcsv($handle)) !== false) {

            $tableName = 'csv_import';

            $columns = implode(', ', array_map(function ($column) {

                if ($column === "Id" || $column === "Age") {
                    return "`$column` INTEGER";
                }
                return "`$column` TEXT";
            }, $data));

            $createTableQuery = "CREATE TABLE IF NOT EXISTS `$tableName` ($columns)";

            $db->exec($createTableQuery);

            $insertQuery = "INSERT INTO `$tableName` VALUES (" . rtrim(str_repeat('?,', count($data)), ',') . ")";

            $stmt = $db->prepare($insertQuery);

            $batchSize = 1000;
            $count = 0;

            while (($data = fgetcsv($handle)) !== false) {
                $stmt->execute($data);
                $count++;

                if ($count % $batchSize === 0) {
                    $db->commit();
                    $db->beginTransaction();
                }
            }

            $db->commit();

            echo "Table created and data imported successfully.";
        } else {
            echo "Error: Unable to read the first row of the CSV file.";
        }
        fclose($handle);
    } else {
        echo "Error: Unable to open the CSV file.";
    }
    $db = null;
} catch (PDOException $e) {
    echo $e->getMessage();
}
