<?php 

$db = new PDO("pgsql:host=pg-db dbname=devdb user=devuser password=devpass");
$token = trim($_GET["token"]);
$token_hash = hash("sha256", $token);

$getUser = $db->prepare('SELECT id FROM "user" WHERE account_activation_hash = :token');
$getUser->execute(["token" => $token_hash]);
$currentUser = $getUser->fetch();


$updateUser = $db->prepare('UPDATE "user" SET account_activation_hash = NULL WHERE id = :id');
$updateUser->execute(["id" => $currentUser["id"]]);

?>
