<?php

chdir('/Volumes/Macintosh HD/Users/taylorledingham/Sites');

function getStopWords($fname)
//get all the stop words in the text file and add it to an array
{

$stop_words = array();
array_push($stop_words, "AT_USER");
array_push($stop_words, "URL");

$file = fopen($fname, "r");

while(!feof($file))
  {
  $word =  fgets($file);
  
  array_push($stop_words, $word);
  
  //echo $word; 
  
  }


fclose($file);

return $stop_words;

}

function removeStopWords($string)
//this function removes all the stop words in a string
{

$stopWords = getStopWords("common-english-stop-words.txt");

	foreach ($stopWords as $word) {
			$place = strpos($string,$word);
				if(!empty($place)){
   
                   $string = str_replace($word, ' ', $string );
       
		            
		        }
		        else {
		           
		              //do nothing
		            
		        }
		    }

		    return $string;
}

function removeRepeatChars($word)
//this function removes repeating characters in a string (ie heyyyyy will become hey)
{

$word = preg_replace("/(.)\\1+/", "$1", $word);

return $word;

}




function processTweets($tweet)
{

$tweet = strtolower($tweet);
//convert char to lower
$tweet = preg_replace('((www\.[\s]+)|(https?://[^\s]+))','URL', $tweet);
//replace www and https to URL
//$tweet = preg_replace('@[^\s]+','AT_USER',$tweet);
$tweet = str_replace('@','',$tweet);
//replace @ char to AT_USER
$tweet = preg_replace( '/\s+/', ' ', $tweet);
//remove addtional whitespaces
//$tweet = preg_replace(r'#([^\s]+)', r'\1', $tweet);
$tweet = str_replace('#', '', $tweet );
//get rid of hashtag symbol
$tweet = trim($tweet, '\'"');
//trim

//$tweet = str_replace(' ', '',$tweet);

$tweet = preg_replace("/(.)\\1+/", "$1", $tweet);
//remove repeating chars ie heyyyy to hey

return $tweet;

}

function process($file, $file2)
//process the tweets in one file and save it to another
{


$file = fopen($file, "r");
$newfile = fopen($file2, "w");

file_put_contents($file2, "");

while(!feof($file))
  {
    $line =  fgets($file);
   
   $processedTweet = processTweets($line);
    
    $processedTweet = removeStopWords($processedTweet);
  
  
  fwrite($newfile, $processedTweet .  "\n");
  
  }

fclose($file);
fclose($newfile);

}





?>