<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>Code Infinity Test 2</title>
</head>

<body>
    <?php

    $names = [
        "Sophia",
        "Benjamin",
        "Isabella",
        "Ethan",
        "Olivia",
        "Alexander",
        "Mia",
        "William",
        "Ava",
        "James",
        "Charlotte",
        "Michael",
        "Amelia",
        "Daniel",
        "Harper",
        "Matthew",
        "Evelyn",
        "Jacob",
        "Abigail",
        "Lucas"
    ];

    $surnames = [
        "Anderson",
        "Patel",
        "Garcia",
        "Smith",
        "Johnson",
        "Williams",
        "Kim",
        "Martinez",
        "Jones",
        "Nguyen",
        "Rodriguez",
        "Brown",
        "Lee",
        "Davis",
        "Gonzalez",
        "Miller",
        "Jackson",
        "Taylor",
        "Wilson",
        "Moore"
    ];

    function getRandomValue($arr)
    {
        $randomIndex = array_rand($arr);
        return $arr[$randomIndex];
    }

    function getRandomNumber($period)
    {
        if ($period == "day") {
            return rand(1, 28);
        } elseif ($period == "month") {
            return rand(1, 12);
        } elseif ($period == "year") {
            return rand(1930, 2018);
        }
    }

    $recordsErr = "";

    if ($_SERVER['REQUEST_METHOD'] == "POST") {

        if (!empty($_POST['num-of-records'])) {

            $numOfRecords = $_POST['num-of-records'];

            $csvFile = "./output/output.csv";

            $file = fopen($csvFile, "w");

            fputcsv($file, ["Id", "Name", "Surname", "Initials", "Age", "DateOfBirth"]);

            $id = 1;

            $generatedRecords = [];

            while (count($generatedRecords) < $numOfRecords) {

                $randomName = getRandomValue($names);
                $randomSurname = getRandomValue($surnames);
                $randomDay = getRandomNumber("day");
                $randomMonth = getRandomNumber("month");
                $randomYear = getRandomNumber("year");
                $initials = $randomName[0];
                $age = date("Y") - $randomYear;
                $dateOfBirth = "{$randomDay}/{$randomMonth}/{$randomYear}";

                $record = [$randomName, $randomSurname, $initials, $age, $dateOfBirth];

                $recordKey = implode('-', $record);

                if (!isset($generatedRecords[$recordKey])) {
                    $generatedRecords[$recordKey] = true;
                    fputcsv($file, [$id, ...$record]);
                    $id++;
                }
            }

            fclose($file);
            header("Location: file_upload.php");
        } else {
            $recordsErr = "Please input a number of records to generate";
        }
    }

    ?>
    <div class="form-container generate-page">
        <h1>Code Infinity Test 2</h1>
        <div class="input-section">
            <h3>How much data would you like to generate?</h3>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" onsubmit="generateRecordsSpinner()">
                <div class="flex-container">
                    <div>
                        <input type="text" name="num-of-records" class="form-control">
                        <span>records</span>
                    </div>
                    <button type="submit" class="btn btn-primary">GENERATE</button>
                    <div class="spinner generate-spinner"></div>
                </div>
            </form>
        </div>
        <?php echo $recordsErr ?>
    </div>
    <script src="main.js"></script>
</body>

</html>