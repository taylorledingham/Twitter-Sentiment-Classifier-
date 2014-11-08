


<html xmlns="http://www.w3.org/1999/xhtml">


	<head>
		<meta http-equiv="content-type" content="text/html;charset=utf-8" />
		<title>Twitter Sentiment Analyzer</title>

<link rel="stylesheet" type="text/css" media="all" href="styles.css">  

<script type = "text/javascript" src="jquery-1.10.2.js"></script>	
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type = "text/javascript"> 
google.load('visualization', '1', {packages: ['corechart']});
google.load('visualization', '1', {packages:['gauge']});
      
</script>


</head>

<body>


<div class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <a href="../" class="navbar-brand">Twitter Sentiment Analyzer</a>
          <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
        </div>
        
        
      </div>
</div>

<div class="container">



      <div class="page-header" id="banner">
        <div class="row">
          <div class="col-lg-6">
         </br>
            <h2>Twitter Sentiment Analyzer</h2>
          </div>
          </br> </br></br> </br> </br>
          <div class="col-lg-6" style="padding: 15px 15px 0 15px;">
            <div class="well sponsor">
                <h4 style="margin-bottom: 0.4em;">Please type a keyword to analyze:</h4></br>
                <div class="clearfix">
                 
           <form id="myform" method="GET" action=''>
         <label for="keyword"> Keyword:  </label>
         <input type="text" id="keyword">
         <input type="hidden" name="e" value="search_tweets"></input>
           </br>
           </br>
           <button type="submit" class="button" class="btn" id='enter'> Submit </button>
              </form>  
                </div>
                 </br>
                <div id="piechart_3d""></div> </br> </br>
                <div id="gauge_chart" align="center"> </div>
              </a>
            </div>
          </div>
        </div>
      </div>


<div class="modal">
</div>

<script type="text/javascript">

$body = $("body");

//$(document).ready(function () {
$(document).on({

ajaxStart: function() { $body.addClass("loading");    },
ajaxStop: function() { $body.removeClass("loading"); } 

});

$(document).ready(function () {
 $("#myform").submit(function(e)
		{ 
		e.preventDefault();
		var getData = jQuery(this).serialize();
		var key = document.getElementById('keyword').value;
		$.ajax({
		type: "GET",
		url: "tweets.php?keyword="+key,
		dataType: 'json',
		success: function(data){
				
	    var pos = data.pos;
		var neg = data.neg;
		var neut = data.neut;
				
		
		$.ajax({
		  url: 'https://www.google.com/jsapi?callback',
		  cache: true,
		  dataType: 'script',
		  success: function(){
		    google.load('visualization', '1', {packages:['corechart'], 'callback' : function()
		      {
		
		                                //var data = google.visualization.arrayToDataTable(jsondata);
		                    var data = google.visualization.arrayToDataTable([
					          ['Sentiment', '# of Tweets'],
					          ['Positive',     pos],
					          ['Negative',     neg]
					         // ['Neutral',      neut]
					        ]);
		
		                   var options = {
					          title: 'Sentiment Results',
					          is3D: true,
					        };
		
		                   var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
		                   chart.draw(data, options);
		              
		       // ]);
		
		      }
		    });
		    
		}
		
		});
		    
		    $.ajax({
		  url: 'https://www.google.com/jsapi?callback',
		  cache: true,
		  dataType: 'script',
		  success: function(){
		    google.load('visualization', '1', {packages:['gauge'], 'callback' : function()
		      {
		
		                                //var data = google.visualization.arrayToDataTable(jsondata);
		                    var data = google.visualization.arrayToDataTable([
					          ['Label', 'Value'],
					          ['Positive',  pos],
					          ['Negative',  neg],
					          ['Neutral',  neut]
					        ]);
		
		                   var options = {
					         // title: 'Sentiment Results',
					          width: 400, height: 120,
					          redFrom: 80, redTo: 100,
					          yellowFrom:60, yellowTo: 80,
					          minorTicks: 5

					        };
		
		                   var chart2 =new google.visualization.Gauge(document.getElementById('gauge_chart'));

		                   chart2.draw(data, options);
		              
		       // ]);
		
		      }
		    });
		    
		    }
		    });

		    //return true;
	

		
		
		},
		error: function(jqXHR, exception) {
		if (jqXHR.status === 0) {
		alert('Not connect.\n Verify Network.');
		} else if (jqXHR.status == 404) {
		alert('Requested page not found. [404]');
		} else if (jqXHR.status == 500) {
		alert('Internal Server Error [500].');
		} else if (exception === 'parsererror') {
		alert('Requested JSON parse failed.');
		} else if (exception === 'timeout') {
		alert('Time out error.');
		} else if (exception === 'abort') {
		alert('Ajax request aborted.');
		} else {
		alert('Uncaught Error.\n' + jqXHR.responseText);
		}
		}

		});		
	
		
		});
		

		



})

function loadChart(){

	    var pos = 22;
		var neg = 58;
		var neut = 28;
		
        google.load('visualization', '1', {packages:['corechart']});
             google.setOnLoadCallback(drawChart);
        function drawGauge() {
            var data = google.visualization.arrayToDataTable([
			          ['Sentiment', '# of Tweets'],
					          ['Positive',     pos],
					          ['Negative',      neg],
					          ['Neutral',  neut]
					          
            ]);

            var options = {
                title: 'Sentiment Results',
				 is3D: true,
            };


           var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
		   chart.draw(data, options);
        }
    }

</script>

</body>
</html>
