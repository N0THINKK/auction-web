<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
</head>
<body>
    <h1>Your Transaction History</h1>
    <ul>
        <?php foreach ($transactions as $transaction): ?>
        <li>
            Item ID: <?= $transaction['item_id'] ?> - Final Price: <?= $transaction['final_price'] ?> - Status: <?= $transaction['status'] ?>
        </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
