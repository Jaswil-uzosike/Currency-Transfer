<?php
// This is code for the suspicious algorithm. 
// It considers your max monthly limit. If money received or transferred goes up to twice that amount, it flags it as suspicious.
// It also updates the records accordingly.
// There are 2 functions. One for the sending functionality and the other for the bank requesting functionality.
function suspicious()
{
    $suspended = "suspended";
    $judgement = true;
    $sender_flag = true;
    $receiver_flag = true;
    $sender_sum = 0;
    $receiver_sum = 0;
    $senderid = $_SESSION['user_id'];
    $receiverid = $_SESSION['user_id2'];
    $amount_sent = $_SESSION['amount_sent'];
    $amount_received = $_SESSION['amount_received'];
    $current_date = date('Y-m-d');
    $current_month = date('m', strtotime($current_date));
    $current_year = date('Y', strtotime($current_date));
    $max_sender = 0;
    $max_receiver = 0;
    $remontada = 0;

    $host = 'localhost';
    $dbname = 'my_stage2';
    $username = 'root';
    $password = '';

    if ($judgement) {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmtx = $pdo->query("SELECT MAX(transaction_id) AS lastid FROM transactions");
        $rowx = $stmtx->fetch(PDO::FETCH_ASSOC);

        $stmty = $pdo->query("SELECT MAX(admin_id) AS lastid FROM admins");
        $rowy = $stmty->fetch(PDO::FETCH_ASSOC);

        if ($rowx != null) {
            $_SESSION['nextid'] = $rowx["lastid"] + 1;
        }

        if ($rowy != null) {
            $_SESSION['maxid'] = $rowy["lastid"] ;
        }

        $pdo = NULL;
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = 'SELECT * FROM currencyacc WHERE user_id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $senderid]);
        $sender = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql2 = 'SELECT * FROM currencyacc WHERE user_id = :id';
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute(['id' => $receiverid]);
        $receiver = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($sender) {
            $max_sender = $sender['max_income'];
        }

        if ($receiver) {
            $max_receiver = $receiver['max_income'];
        }

        // SQL query to select the month and year from the date field
        $stmt3 = $pdo->prepare("SELECT amount_sent, MONTH(trans_date) AS sender_month, YEAR(trans_date) AS sender_year FROM transactions WHERE senderacc_id = $senderid and trans_state = 'success' ");
        $stmt3->execute();
        $result = $stmt3->fetchAll(PDO::FETCH_ASSOC);

        $stmt4 = $pdo->prepare("SELECT amount_received, MONTH(trans_date) AS receiver_month, YEAR(trans_date) AS receiver_year FROM transactions WHERE receiveracc_id = $receiverid and trans_state = 'success' ");
        $stmt4->execute();
        $result2 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

        foreach ($result as $row) {
            if ($row["sender_month"] == $current_month && $row["sender_year"] == $current_year) {

                $sender_sum = $sender_sum + $row["amount_sent"];
            }
        }

        foreach ($result2 as $row2) {
            if ($row2["receiver_month"] == $current_month && $row2["receiver_year"] == $current_year) {

                $receiver_sum = $receiver_sum + $row2["amount_received"];
            }
        }
        $pdo = NULL;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }

    if ($amount_sent > (2 * $max_sender)) {
        $sender_flag = false;
    }

    if ($amount_received > (2 * $max_receiver)) {
        $receiver_flag = false;
    }

    if (($sender_sum + $amount_sent) > (2 * $max_sender)) {
        $sender_flag = false;
    }

    if (($receiver_sum + $amount_received) > (2 * $max_receiver)) {
        $receiver_flag = false;
    }


    if (!$sender_flag && !$receiver_flag) {

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $stmt5 = $pdo->prepare("UPDATE useracc SET acc_status = :value1 WHERE user_id = :id");
            $stmt6 = $pdo->prepare("UPDATE useracc SET acc_status = :value1 WHERE user_id = :id");

            $stmt6->bindParam(':value1', $suspended);
            $stmt6->bindParam(':id', $receiverid);

            $stmt5->bindParam(':value1', $suspended);
            $stmt5->bindParam(':id', $senderid);

            $stmt5->execute();
            $stmt6->execute();

        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

        $pdo = NULL;
        $_SESSION['remontada'] = 1;

    } elseif (!$sender_flag) {

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $stmt5 = $pdo->prepare("UPDATE useracc SET acc_status = :value1 WHERE user_id = :id");

            $stmt5->bindParam(':value1', $suspended);
            $stmt5->bindParam(':id', $senderid);

            $stmt5->execute();

        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

        $pdo = NULL;
        $_SESSION['remontada'] = 2;

    } elseif (!$receiver_flag) {

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {
            $description = "Receiver went over their max spend limit";
            $adminid = mt_rand(1, 2);

            $stmt6 = $pdo->prepare("UPDATE useracc SET acc_status = :value1 WHERE user_id = :id");

            // Bind parameters
            $stmt6->bindParam(':value1', $suspended);
            $stmt6->bindParam(':id', $receiverid);

            $stmt6->execute();

        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

        $pdo = NULL;
        $_SESSION['remontada'] = 3;
    }

    if (!$sender_flag || !$receiver_flag) {
        $judgement = false;
    }

    return $judgement;

}


function suspicious2()
{
    $suspended = "suspended";
    $judgement = true;
    $receiver_flag = true;
    $receiver_sum = 0;
    $receiverid = $_SESSION['user_id'];
    $amount_received = $_SESSION['amount_added'];
    $current_date = date('Y-m-d');
    $current_month = date('m', strtotime($current_date));
    $current_year = date('Y', strtotime($current_date));
    $max_receiver = 0;


    $host = 'localhost';
    $dbname = 'my_stage2';
    $username = 'root';
    $password = '';

    if ($judgement) {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmtx = $pdo->query("SELECT MAX(transaction_id) AS lastid FROM transactions");
        $rowx = $stmtx->fetch(PDO::FETCH_ASSOC);

        $stmty = $pdo->query("SELECT MAX(admin_id) AS lastid FROM admins");
        $rowy = $stmty->fetch(PDO::FETCH_ASSOC);

        if ($rowx != null) {
            $_SESSION['nextid'] = $rowx["lastid"] + 1;
        }

        if ($rowy != null) {
            $_SESSION['maxid'] = $rowy["lastid"] ;
        }

        $pdo = NULL;
    }

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


        $sql2 = 'SELECT * FROM currencyacc WHERE user_id = :id';
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute(['id' => $receiverid]);
        $receiver = $stmt2->fetch(PDO::FETCH_ASSOC);


        if ($receiver) {
            $max_receiver = $receiver['max_income'];
        }

        // SQL query to select the month and year from the date field

        $stmt4 = $pdo->prepare("SELECT amount_received, MONTH(trans_date) AS receiver_month, YEAR(trans_date) AS receiver_year FROM transactions WHERE receiveracc_id = $receiverid and trans_state = 'success' ");
        $stmt4->execute();
        $result2 = $stmt4->fetchAll(PDO::FETCH_ASSOC);


        foreach ($result2 as $row2) {
            if ($row2["receiver_month"] == $current_month && $row2["receiver_year"] == $current_year) {

                $receiver_sum = $receiver_sum + $row2["amount_received"];
            }
        }
        $pdo = NULL;
    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }


    if ($amount_received > (2 * $max_receiver)) {
        $receiver_flag = false;
    }

    if (($receiver_sum + $amount_received) > (2 * $max_receiver)) {
        $receiver_flag = false;
    }


   if (!$receiver_flag) {

        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        try {

            $stmt6 = $pdo->prepare("UPDATE useracc SET acc_status = :value1 WHERE user_id = :id");

            // Bind parameters
            $stmt6->bindParam(':value1', $suspended);
            $stmt6->bindParam(':id', $receiverid);

            $stmt6->execute();

        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }

        $pdo = NULL;
        $judgement = false;
    }


    return $judgement;

}