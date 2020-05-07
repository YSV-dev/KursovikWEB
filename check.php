<?
// Скрипт проверки

// Соединямся с БД
$link=mysqli_connect("localhost", "root", "", "wdt");

if (isset($_COOKIE['userid']) and isset($_COOKIE['hash']))
{
    $query = mysqli_query($link, "SELECT *,INET_NTOA(ip) AS ip FROM users WHERE userid = '".intval($_COOKIE['userid'])."' LIMIT 1");
    $userdata = mysqli_fetch_assoc($query);

    if(($userdata['hash'] !== $_COOKIE['hash']) or ($userdata['userid'] !== $_COOKIE['userid'])
 or (($userdata['ip'] !== $_SERVER['REMOTE_ADDR'])  and ($userdata['ip'] !== "0")))
    {
        setcookie("useridid", "", time() - 3600*24*30*12, "/");
        setcookie("hash", "", time() - 3600*24*30*12, "/", null, null, true); // httponly !!!
        print "Хм, что-то не получилось";
    }
    else
    {
        print "Привет, ".$userdata['login'].". Всё работает!";
    }
}
else
{
    print "Включите куки";
}
?>