<?php

$servername = "localhost";
$username = "user5655_listen";
$password = "fA5kF0uJ2a";

$conn = new PDO("mysql:host=$servername;dbname=user5655_listen", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function addUser($id, $ref_id)
{
    global $conn;
    $user = $conn->prepare("SELECT user_id FROM users WHERE user_id = '$id'");
    $user->execute();
    $check = $user->fetch(PDO::FETCH_ASSOC)['user_id'];
    if ($check == null) {
        $conn->exec("INSERT INTO users(user_id) VALUES ('$id')");
        sleep(1);
        $conn->exec("INSERT INTO referrals(user_id, ref_id) VALUES ('$id', '$ref_id')");
    }
    return null;
}

function getCountUserRef($id)
{
    global $conn;
    $stmt = $conn->query("SELECT COUNT(*) FROM `referrals` WHERE `ref_id` = '$id'");
    return $stmt->fetchColumn();
}

function update($table, $id, $name, $value)
{
    global $conn;
    $user = $conn->prepare("UPDATE $table SET $name='$value' WHERE user_id = '$id'");
    $user->execute();
}

function getRefId($id)
{
    global $conn;
    $user = $conn->prepare("SELECT ref_id FROM referrals WHERE user_id = '$id'");
    $user->execute();
    return $user->fetch(PDO::FETCH_ASSOC)['ref_id'];
}

function addChannel($id)
{
    global $conn;
    $channel = $conn->prepare("SELECT channel_id FROM channels WHERE channel_id = '$id'");
    $channel->execute();
    $check = $channel->fetch(PDO::FETCH_ASSOC);
    if ($check['channel_id'] == null) {
        $conn->exec("INSERT INTO channels (channel_id) VALUES ('$id')");
    }
}

function channelsArray()
{
    global $conn;
    $channels = $conn->prepare("SELECT channel_id FROM channels");
    $channels->execute();
    return $channels->fetchAll(PDO::FETCH_COLUMN);
}

function adminsArray()
{
    global $conn;
    $channels = $conn->prepare("SELECT user_id FROM admins");
    $channels->execute();
    return $channels->fetchAll(PDO::FETCH_COLUMN);
}
