<?php

$user = 'root';
$pass = 'root';
$db_old = 'jream';
$db_new = 'jream_new';

try {
    $old = new PDO("mysql:host=localhost;dbname=$db_old", $user, $pass);
    $new = new PDO("mysql:host=localhost;dbname=$db_new", $user, $pass);

} catch (PDOException $e) {
    print "Error!: " . $e->getMessage() . "<br/>";
    die();
}

$sth = $old->prepare("
  SELECT * FROM
  user_purchase AS up
  JOIN transaction AS t ON (
    up.transaction_id = t.id
  )
  LIMIT 10
");
$sth->execute();
$sth->setFetchMode(PDO::FETCH_OBJ);
$result = $sth->fetchAll();
print_r($result);

// Insert
$sth = $new->prepare("
  INSERT INTO user_purchase_new
  SET (
    user_id,
    product_id,
    type,
    transaction_id,
    gateway,
    amount,
    amount_after_discount,
    is_deleted,
    created_at,
    updated_at,
    deleted_at
  )
  VALUES (
    {$result->user_id},
    {$result->type},
    {$result->transaction_id},
    {$result->gateway},
    {$result->amount},
    {$result->amount_after_discount),
    {$result->is_deleted},
    {$result->created_at},
    {$result->updated_at},
    {$result->updated_at})
");
$sth->execute();
