<?php

$url = isset($_REQUEST['url']) ? $_REQUEST['url'] : '';

// Inspired by a Stack Overflow post
function readFromUrl($curlURL) {
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $curlURL);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
  $contents = curl_exec($ch);
  if ($contents === false) { $contents = "An error occured: " . curl_error($ch); }
  curl_close($ch);
  return $contents;
}



?>
<html>
<head>
<style type="text/css">
  #body { padding: 40px; font-family: Lato, "Helvetica Neue", sans-serif;}
  #body h1 { font-weight: bold; }
  .highlight { background-color: #aef; color: #669; }
</style>
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
<link href='http://fonts.googleapis.com/css?family=Lato:400,700' rel='stylesheet' type='text/css'>


</head>
<body id="body">
<div class="container">

<div class="row">
<div class="col-md-10 col-md-offset-1">

<h1>Page Viewer</h1>

<p>View the content of, and information about, any page on the 'nets!</p>

<form  action="" method="POST">
<input type="text" name="url" class="form-control" placeholder="http://" value="<?php echo $url ?>" required autofocus><br>
<button class="btn btn-primary " type="submit">View This Page</button>
</form> 

<?php 
if(isset($_REQUEST['url'])) { 
  $url=$_REQUEST['url'];
  $urlContents=readFromUrl($url); 

?>

<hr>

<div class="panel panel-success">
  <div class="panel-heading">
    <h3 class="panel-title">HTML Tags</h3>
  </div>
  <div class="panel-body" id="pageReport" >
<?php 


preg_match_all("/<([a-z]+)/i", $urlContents, $tagSearch);
$allTags=$tagSearch[1];
sort($allTags);
$tagCounts=array_count_values($allTags);
$uniqueTags=array_unique($allTags);

foreach($uniqueTags as $tag) {
  $count = $tagCounts[$tag];
  echo '<a href="javascript:highlightWord(\'<' . $tag . '\');">' 
    . $tag . '(' . $count . ')</a> , &nbsp; ';
}
?>


  </div>
</div>



<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title">Page Contents</h3>
  </div>
  <div class="panel-body" id="pageContents" ><pre><?php echo htmlspecialchars($urlContents) ?></pre></div>
</div>
<div style="display: none;" id="urlContents"><?php echo htmlspecialchars($urlContents) ?></div>




<?php } ?>


</div>
</div>




</div>
</body>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
<script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
<script type="text/javascript" src="jquery_highlight.js"></script>
<script type="text/javascript">

function highlightWord(word) {
  $('#pageContents').removeHighlight().highlight(word);
}


</script>

</html>
