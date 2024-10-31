<?php    
require('../../../wp-blog-header.php');

   if (isset($_POST['addQuote'])) {   
    $query = sprintf("      
      INSERT INTO wp_testimonials   
      SET name = '%s', queued = 1, quote = '%s'",  
      $_POST['name'], $_POST['quote']   
    );      
    $result = mysql_query($query);    
    
  };         

?>
<html>
<head>
<meta http-equiv="refresh" content="3; url=<? echo $_SERVER['HTTP_REFERER']; ?>" />
<title>Thank You for Your Submission</title>
</head>
<body>
<div style="text-align: center; font-family: verdana; width: 300px; height: 200px; margin: 200px auto; padding: 10px; border: 1px solid #006C00; background-color: #CDFABE;"><span style="font-size: 16px; font-weight: bold;">Thank You for Your Testimonial</span><br/><br/>Your submission has been added to our queue and once approved will be live on our site.  We appreciate your testimonial!<br/><br/>You will be redirected automatically.</div>
</body>
</html>
