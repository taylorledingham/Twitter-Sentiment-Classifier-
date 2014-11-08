<?php

session_start();

chdir('/Volumes/Macintosh HD/Users/taylorledingham/Sites');

require_once("twitteroauth/twitteroauth.php"); //Path to twitteroauth library

include "NaiveBayes.php";
include "processTweets.php";

$keyword =  $_GET['keyword'];

//$keyword OR ...
 
$search = $keyword;
$notweets = 100;
$consumerkey = "emvaDcfWTj46aX2Mn5sAA";
$consumersecret = "cISy5ORha9pJVtLp4BQNDx4QPBNfHaviwoqgyDJoM";
$accesstoken = "28741914-5tRsB8X59dMHcKOOFT3jhej2cprwJlVaFdsBEh0rg";
$accesstokensecret = "NCefcj8aMLKGHAAGLil0MH5ToTadeA7lgHnGkTi5zMH5I";


function setKeyword($key)
{

$search = $key;


}
  
function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
  return $connection;
}


   
$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);
 
$search = str_replace("#", "%23", $search);
$tweets = $connection->get("search/tweets", array("q" => $search, "count" => $notweets, "lang" => "en"));

$file = fopen('tweets.txt', 'w');

$tweetText;
$i = 0;

foreach($tweets->statuses as $tweet)
{

$tweetText[$i] = $tweet->text;
$tweet2 = $tweet->text;
$tweet2 = processTweets($tweet2);
$tweet2 .= "\n";
fwrite($file, $tweet2);

//echo $tweet2;

$i++;
}


$classifer = new NaiveBayes();

ini_set('memory_limit', '-1');

process('neg.txt', 'negativeProcessed.txt');
process('pos.txt', 'positiveProcessed.txt');
process('neut.txt', 'neutralProcessed.txt');


$classifer->trainClassifer('negativeProcessed.txt', 'negative');
$classifer->trainClassifer('positiveProcessed.txt', 'positive');
$classifer->trainClassifer('neutralProcessed.txt', 'neutral');

$pos = 0;
$neg = 0;
$neut = 0;

$tweetfile = fopen("tweets.txt", "r");

while(!feof($tweetfile))
  {
    $line =  fgets($tweetfile);
    $processedTweet = processTweets($line);
    $processedTweet = removeStopWords($processedTweet);
    
    $result = $classifer->classifySentiment($processedTweet);
    
    
    if($result == 'positive')
    {
	    $pos++;
	    
	    
    }
    
    else if ($result == 'negative')
    {
	    $neg++;
	    
    }
    else
    {
	    $neut++;
	    
    }
    
  }
    
fclose($tweetfile);

$file = fopen("results.txt", "w");

fwrite($file, $pos .  "\n");
fwrite($file, $neg .  "\n");
fwrite($file, $neut .  "\n");


fclose($file);

/*
echo "number of negative tweets = " . $neg . "\n";
echo "number of postive tweets = " . $pos . "\n";
echo "number of neutral tweets = " . $neut . "\n";
*/

$arr = array('pos' => $pos, 'neg' => $neg, 'neut' => $neut);

echo json_encode($arr);

//echo json_encode(1);

fclose($file);

//exit();

?>