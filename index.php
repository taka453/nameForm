<?php

require_once "dbconnection.php";

$lastName = '';
$firstName = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];

    $error_message = array();

    if($lastName == '') {
        $error_message[] = '苗字を入力してください。';
    }

    if($firstName == '') {
        $error_message[] = '名前を入力してください。';
    }

    if(!preg_match('/^[ァ-ヶー]+$/u', $lastName)) {
        $error_message[] = '苗字をカタカナで入力してください。';
    }

    if(!preg_match('/^[ァ-ヶー]+$/u', $firstName)) {
        $error_message[] = '名前をカタカナで入力してください。';
    }
}

if(empty($error_message)) {
    $sql = "INSERT INTO nameForm (lastName, firstName) VALUE (:lastName, :firstName)";
    $stmh = $dbh->prepare($sql);
    $stmh->bindValue(':lastName', $lastName, PDO::PARAM_STR);
    $stmh->bindValue(':firstName', $firstName, PDO::PARAM_STR);
    $stmh->execute();
}

$sql = "SELECT * FROM nameForm";
$stmt = $dbh->prepare($sql);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $data[] = $row;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>フォームの練習</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>フォームの練習</h1>
    <p>カタカナで氏名を入力してください。</p>
    <form action="#" method="post">
        <label>セイ:</label>
        <input class="text" type="text" name="lastName">
        <label>メイ:</label>
        <input class="text" type="text" name="firstName">
        <button class="btn" type="submit">登録</button>
    </form>
    <?php if(!empty($error_message)): ?>
        <ul class="error_message">
            <?php foreach($error_message as $value): ?>
                <li><?php echo $value; ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
    <hr>
    <?php foreach($data as $row): ?>
    <div class="content">
        <div class="right">
            <p><?php echo htmlspecialchars($row['lastName'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
        <div class="left">
            <p><?php echo htmlspecialchars($row['firstName'], ENT_QUOTES, 'UTF-8'); ?></p>
        </div>
    </div>
    <?php endforeach; ?>
</body>
</html>