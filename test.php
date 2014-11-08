<?php

session_start();

chdir('/Volumes/Macintosh HD/Users/taylorledingham/Sites');

require_once("twitteroauth/twitteroauth.php"); //Path to twitteroauth library

include "NaiveBayes.php";
include "processTweets.php";


$classifer = new NaiveBayes();

ini_set('memory_limit', '-1');

process('neg3.txt', 'negativeProcessed.txt');
process('pos3.txt', 'positiveProcessed.txt');
process('neut.txt', 'neutralProcessed.txt');


$classifer->trainClassifer('negativeProcessed.txt', 'negative');//, 4020);
$classifer->trainClassifer('positiveProcessed.txt', 'positive');//, 4056);
$classifer->trainClassifer('neutralProcessed.txt', 'neutral');//, 2321);

$pos = 0;
$neg = 0;
$neut = 0;

$i = 2;
$tweetfile = fopen("test.txt", "r");

while(!feof($tweetfile))
  {
    $line =  fgets($tweetfile);
    $processedTweet = processTweets($line);
    $processedTweet = removeStopWords($processedTweet);
    
    //echo $processedTweet;
    
    $result = $classifer->classifySentiment($processedTweet);

    
    
echo $i . ". " .  $result . "\n";

$i++;

}

//exit();

?>