<?php

require("config.php");

if (isset($_GET["url"]))
{
    shorten($_GET["url"]);
    die();
}

if (preg_match("|/(\w+)$|", $_SERVER["REQUEST_URI"], $matches))
{
    redirect($matches[1]);
    die();
}

function get_pdo()
{
    static $pdo;
    if (!$pdo)
    {
        $pdo = new PDO(DB_DSN, DB_USER, DB_PASSWORD) or die("database error");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    return $pdo;
}

function get_token($url)
{
    $pdo = get_pdo();

    $statement = $pdo->prepare("SELECT id FROM shtfy WHERE url = ?");
    $statement->execute(array($url));
    $id = $statement->fetchColumn();

    if ($id === false)
    {
        $statement = $pdo->prepare("INSERT INTO shtfy (url) VALUES (?)");
        $statement->execute(array($url));
        $id = $pdo->lastInsertId();
    }

    return base62_encode($id);
}

function get_url($token)
{
    $pdo = get_pdo();
    $id = base62_decode($token);

    $statement = $pdo->prepare("UPDATE shtfy SET hits = hits + 1 WHERE id = ?");
    $statement->execute(array($id));

    $statement = $pdo->prepare("SELECT url FROM shtfy WHERE id = ?");
    $statement->execute(array($id));
    return $statement->fetchColumn();
}

function shorten($url)
{
    if (!preg_match("|^https?://|", $url))
        $url = "http://$url";

    $token = get_token($url);
    print($_SERVER["SCRIPT_URI"] . $token);
}

function redirect($token)
{
    $url = get_url($token);
    if (!$url)
        $url = $_SERVER["SCRIPT_URI"];
    header("Location: $url", true, 301);
}

// source: http://programanddesign.com/php/base62-encode/
function base62_encode($val)
{
    $base=62;
    $chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str = '';
    do {
        $m = bcmod($val, $base);
        $str = $chars[$m] . $str;
        $val = bcdiv(bcsub($val, $m), $base);
    } while(bccomp($val,0)>0);
    return $str;
}

// source: http://programanddesign.com/php/base62-encode/
function base62_decode($str)
{
    $base=62;
    $chars='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $len = strlen($str);
    $val = 0;
    $arr = array_flip(str_split($chars));
    for($i = 0; $i < $len; ++$i) {
        $val = bcadd($val, bcmul($arr[$str[$i]], bcpow($base, $len-$i-1)));
    }
    return $val;
}
