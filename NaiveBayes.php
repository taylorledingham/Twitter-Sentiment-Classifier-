<?php

//include "tweets.php";

class NaiveBayes
{
        private $featureVector = array();
        private $sentiment = array('positive', 'negative', 'neutral');
        private $sentimentWordCounts = array('positive' => 0, 'negative' => 0,  'neutral' => 0);
        //keep track of the amount of positive, negative, and neutral words
        private $wordCount = 0; //number of words in all tweets
        private $sentimentTweetCounts = array('positive' => 0, 'negative' => 0,  'neutral' => 0);
        //keep track of the amount of positive, negative, and neutral tweets
        private $tweetCount = 0; // number os tweets in all text files
        private $score = array('positive' => 0.36, 'negative' => 0.36,  'neutral' => 0.28);
        //the scores for each class are calculated based on the number tweets in each class and in total

        public function trainClassifer($file, $sentiment) 
        /*
        this function takes the training dataset to train the classifer to be able to classify new data
        Pre-conditions: file must be an existiing file in the directory and sentiment must be either 
        positive, negative, or neutral
        Post-conditions: The classifer has created a feature vector of that sentiment and now is able to 
        classify new tweets.
        */
        
        {
                $fh = fopen($file, 'r');
                if(!in_array($sentiment, $this->sentiment)) 
                {
                        echo "This sentiment is not supported\n";
                        return;
                }
                while($line = fgets($fh)) 
                {
                    
                       
                        $this->tweetCount++;
                        $this->sentimentTweetCounts[$sentiment]++;
                        $wordArray = $this->getWords($line);
                        foreach($wordArray as $word) 
                        {
                                if(!isset($this->featureVector[$word][$sentiment])) 
                                {
                                //if the word doesn't already exists in the feature vector set its count to zero
                                        $this->featureVector[$word][$sentiment] = 0;
                                }
                                $this->featureVector[$word][$sentiment]++; 
                                $this->sentimentWordCounts[$sentiment]++;
                                $this->wordCount++;
                                //increment word count, the sentiment word count, and the feature vector for that word
                        }
                }
                fclose($fh);
        }
       
        public function classifySentiment($tweet) 
        /*
        this function takes a line of text and classifies each it as either positive, negative, or neutral.
        Pre-conditions: none.
        Post-conditions: The classifer has classified the tweet as either positive, negative, or neutral.
        */
        
        {
                $this->score['positive'] = $this->sentimentTweetCounts['positive'] / $this->tweetCount;
                $this->score['negative'] = $this->sentimentTweetCounts['negative'] / $this->tweetCount;
                $this->score['neutral'] = $this->sentimentTweetCounts['neutral'] / $this->tweetCount;
                
                //calcuate the score of each sentiment class

                $wordArray = $this->getWords($tweet);
                $classScores = array();

                foreach($this->sentiment as $sentiment) {
                        $classScores[$sentiment] = 1; 	//set the class scores
                        foreach($wordArray as $word) {
                                        if(isset($this->featureVector[$word][$sentiment]) == true)
                                        //if the word doesn't exist set the count to zero
                                        {
	                                        
	                                        $count = $this->featureVector[$word][$sentiment];
	                                          
                                        }
                                        
                                        else
                                        {
	                                        $count=0;
	                                        
                                        }

                                $classScores[$sentiment] *= ($count + 1) / 
                                ($this->sentimentWordCounts[$sentiment] + $this->wordCount);
                                //calculate the product of each word
                        }
                        $classScores[$sentiment] = $this->score[$sentiment] * $classScores[$sentiment];
                        //calculate the final probability of the sentiment
                }
               
                arsort($classScores); //sort the array in descending order according to its index
                					  //arsort keeps the index association
               
                return key($classScores); //return the highest scored sentiment of the tweet
        }

        private function getWords($txtfile) 
        //this function is is given a text file of lines and must spit each line into words 
        //and return each word in an array
        {
                $txtfile = strtolower($txtfile);	//make all characters lowercase
                preg_match_all('/\w+/', $txtfile, $words); 	//spit text into words
                return $words[0];
                
                
                
                
        }
}

?>