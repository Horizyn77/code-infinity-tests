<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/css/bootstrap-datepicker.min.css" integrity="sha512-34s5cpvaNG3BknEWSuOncX28vz97bRI59UnVtEEpFX536A7BtZSJHsDyFoCl8S7Dt2TPzcrCEoHBGeM4SUBDBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="style.css">
    <title>Code Infinity Test 1</title>
</head>

<body>
    <?php
    session_start();
    require_once __DIR__ . '/vendor/autoload.php';

    $name = $surname = $idNumber = $dateOfBirth = "";
    $nameErr = $surnameErr = $idNumberErr = $dateOfBirthErr = "";
    $submitResultMsg = "";
    $idNumberDobMatchErr = "";


    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        if (isset($_SESSION['name']) && isset($_SESSION['surname']) && isset($_SESSION['idNumber']) && isset($_SESSION['dateOfBirth'])) {
            unset($_SESSION['name']);
            unset($_SESSION['surname']);
            unset($_SESSION['idNumber']);
            unset($_SESSION['dateOfBirth']);
        }

        if (empty($_POST["name"])) {
            $nameErr = "Please enter a name";
        } else {
            $name = test_input($_POST["name"]);

            if (!preg_match("/^[a-zA-Z]+$/", $name)) {
                $nameErr = "No numbers or special characters are allowed";
            }
        }

        if (empty($_POST["surname"])) {
            $surnameErr = "Please enter a surname";
        } else {
            $surname = test_input($_POST["surname"]);

            if (!preg_match("/^[a-zA-Z]+$/", $surname)) {
                $surnameErr = "No numbers or special characters are allowed";
            }
        }

        if (empty($_POST["id-number"])) {
            $idNumberErr = "Please enter an id number";
        } else {
            $idNumber = test_input($_POST["id-number"]);

            if (!preg_match("/^\d+$/", $idNumber)) {
                $idNumberErr = "Only numbers are allowed";
            } elseif (strlen($idNumber) !== 13) {
                $idNumberErr = "ID Number must be 13 characters long";
            }
        }

        if (empty($_POST["date-of-birth"])) {
            $dateOfBirthErr = "Please enter a date of birth";
        } else {
            $dateOfBirth = test_input($_POST["date-of-birth"]);

            if (!preg_match("/^([0-2][0-9]|(3)[0-1])(\/)(((0)[0-9])|((1)[0-2]))(\/)\d{4}$/", $dateOfBirth)) {
                $dateOfBirthErr = "Please enter a valid date in the format dd/mm/yyyy";
            }
        }

        if (!empty($idNumber) && !empty($dateOfBirth)) {

            $dateOfBirthFormatArr = explode("/", $dateOfBirth);

            $yearReformat = substr($dateOfBirthFormatArr[2], 2);

            array_pop($dateOfBirthFormatArr);

            array_push($dateOfBirthFormatArr, $yearReformat);

            $dateOfBirthReversed = array_reverse($dateOfBirthFormatArr);

            $dateOfBirthReformat = implode("", $dateOfBirthReversed);

            $idNumberReformat = substr($idNumber, 0, 6);

            if ($idNumberReformat !== $dateOfBirthReformat) {

                $idNumberDobMatchErr = "ID Number and Date of Birth do not match";
            }
        }

        if (empty($nameErr) && empty($surnameErr) && empty($idNumberErr) && empty($dateOfBirthErr) && empty($idNumberDobMatchErr)) {

            $mongoClient = new MongoDB\Client("mongodb://localhost:27017");

            $db = $mongoClient->test_1;

            $schema = [
                'validator' => [
                    '$jsonSchema' => [
                        'bsonType' => 'object',
                        'required' => ['name', 'surname', 'id_number', 'date_of_birth'],
                        'properties' => [
                            'name' => ['bsonType' => 'string'],
                            'surname' => ['bsonType' => 'string'],
                            'id_number' => ['bsonType' => 'number'],
                            'date_of_birth' => ['bsonType' => 'string']
                        ]
                    ]
                ]
            ];

            $db->createCollection('users', $schema);

            $collection = $db->users;

            $existingIdNumber = $collection->findOne(['id_number' => (int) $idNumber]);

            if ($existingIdNumber) {

                $_SESSION['name'] = $name;
                $_SESSION['surname'] = $surname;
                $_SESSION['idNumber'] = $idNumber;
                $_SESSION['dateOfBirth'] = $dateOfBirth;

                header("Location: index.php?error=User with the specified ID Number already exists");
            } else {
                $insertUser = $collection->insertOne([
                    'name' => $name,
                    'surname' => $surname,
                    'id_number' => (int) $idNumber,
                    'date_of_birth' => $dateOfBirth
                ]);

                if ($insertUser->getInsertedCount() > 0) {
                    $submitResultMsg = "Details successfully submitted";
                }

                unset($_SESSION['name']);
                unset($_SESSION['surname']);
                unset($_SESSION['idNumber']);
                unset($_SESSION['dateOfBirth']);
            }
        }
    }

    function test_input($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    ?>

    <div class="form-container">
        <h1>Code Infinity Test 1</h1>
        <h2>Details</h2>
        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3 mt-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" placeholder="Enter name" value="<?php echo isset($_SESSION['name']) ? $_SESSION['name'] : ''; ?>">
            </div>
            <div id="error-msg-name">
                <?php echo $nameErr ?>
            </div>
            <div class="mb-3">
                <label for="surname" class="form-label">Surname</label>
                <div class="flex-container">
                    <input type="surname" name="surname" id="surname" class="form-control" placeholder="Enter surname" value="<?php echo isset($_SESSION['surname']) ? $_SESSION['surname'] : ''; ?>">
                </div>
            </div>
            <div id="error-msg-surname">
                <?php echo $surnameErr ?>
            </div>
            <div class="mb-3 mt-3">
                <label for="id-number" class="form-label">ID Number</label>
                <input type="text" name="id-number" id="id-number" class="form-control" placeholder="Enter id number" value="<?php echo isset($_SESSION['idNumber']) ? $_SESSION['idNumber'] : ''; ?>">
            </div>
            <div id="error-msg-id-number">
                <?php echo $idNumberErr ?>
            </div>
            <div class="mb-3 mt-3">
                <label for="date-of-birth" class="form-label">Date of Birth</label>
                <input type="text" name="date-of-birth" id="date-of-birth" class="form-control datepicker" data-date-format="dd/mm/yyyy" placeholder="Enter date of birth" value="<?php echo isset($_SESSION['dateOfBirth']) ? $_SESSION['dateOfBirth'] : ''; ?>">
            </div>
            <div id="error-msg-date-of-birth">
                <?php echo $dateOfBirthErr ?>
            </div>
            <button type="submit" class="btn btn-primary">POST</button>
            <button type="button" class="btn btn-danger" onclick="clearInputs()">CANCEL</button>
        </form>
        <div id="submit-result-msg">
            <?php

            if (isset($_GET['error'])) {
                echo "<span id='error-msg'>{$_GET['error']}<span>";
                echo "<br>";
        } elseif (isset($submitResultMsg)) {
                echo "<span id='success-msg'>{$submitResultMsg}<span>";
            }

            if (isset($idNumberDobMatchErr)) {
                echo "<span id='error-msg'>{$idNumberDobMatchErr}<span>";
            }

            ?>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js" integrity="sha512-ykZ1QQr0Jy/4ZkvKuqWn4iF3lqPZyij9iRv6sGqLRdTPkY69YX6+7wvVGmsdBbiIfN/8OdsI7HABjvEok6ZopQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.10.0/js/bootstrap-datepicker.min.js" integrity="sha512-LsnSViqQyaXpD4mBBdRYeP6sRwJiJveh2ZIbW41EBrNmKxgr/LFZIiWT6yr+nycvhvauz8c2nYMhrP80YhG7Cw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="main.js"></script>
</body>

</html>