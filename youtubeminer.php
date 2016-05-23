<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>PHP Youtube Miner API - Reuniware Systems</title>

    <!-- Bootstrap -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
	<style type='text/css'>
      img { width: 100%; }
      body { margin-top: 10px; }
		[class*="col-"] { margin:10px 10px 150x 20px; }
	video { 
		width: 100%    !important;
  		height: auto   !important;
		}
      </style>
  </head>
  <body>
  	PHP Youtube Miner (API) functionalities :<br/>
  	- Makes a search on Youtube (through proxy that is the server IP) ;<br/>
  	- Extract unique IDs from Youtube ;<br/>
  	- Shows the embedded videos on the same screen ;<br/>
  	- Work in progess : API ;<br/>
  	- (++) Allows you to get only the best from Youtube without ads and double results.<br/>
  	- (++) Searches on Youtube without creating an account.<br/>
  	<br/>
  	A unique and simple solution for Youtube miners !<br/>
  	<br/>
  	<br/>
  	PHP Youtube Miner API Demo<br/>
  	<br/>
  	<br/>
  
  	<?php 
 		echo 'Current file name deployed = ' . getCurrentFileName() . '<br/>';
 
  		$query = $_POST['query'];
  		if (is_null($query)){
  			echo '<form action='. getCurrentFileName() .' method="post">Youtube query = <input type="text" name="query"><br><input type="submit" value="Submit"></form>';
  		} else {
  			echo '<form action='. getCurrentFileName() .' method="post">Youtube query = <input type="text" name="query"><br><input type="submit" value="Submit"></form>';
  			echo '<div class="container"><section class="row">';
  			echo(getCustomYoutubePage(urlencode($query)));
  			//echo $query;
  			echo '</section></div>';
  		}
  	?>
  
  


</body>
</html>

<script type='text/javascript'>
</script>

<?php
function getCurrentFileName()
{
	$uri = $_SERVER['REQUEST_URI'];
	$array = explode("/", $uri);
	if (!is_null($array))
	{
		if (count($array)>0)
		{
			$nb = count($array);
			return $array[$nb-1];
		}
	}
}
function getIFrame(){
	$w = 560/4; $h=315/4;
	$url = "https://www.youtube.com/embed/OmsNYRXtV3Q";
	return('<iframe width="' . $w . '" height="' . $h . '" src="' . $url . '" frameborder="32" allowfullscreen></iframe>');
}

function getIFrameForYoutubeId($youtubeId){
	$w = 560/2; $h=315/2;
	$url = "https://www.youtube.com/embed/" . $youtubeId;
	return('<iframe width="' . $w . '" height="' . $h . '" src="' . $url . '" frameborder="2" allowfullscreen></iframe>');
}

function getYoutubeIdsForSearch($search = ''){
	
	$idarray = array();
	
	//$data = file_get_contents('https://www.youtube.com/?gl=FR&hl=fr');
	//https://www.youtube.com/results?search_query=sizzla
	$data = file_get_contents('https://www.youtube.com/results?search_query=' . $search . '&gl=FR&hl=fr&');
	
	$data = str_replace('<', '[', $data);
	$data = str_replace('>', ']', $data);

	//echo $data;
	
	$array = explode('href="', $data);
	//echo(count($array));
	foreach($array as $str){
		//echo ">>>" . $str . "<br/><br/>";
		$array2 = explode('"', $str);
		if (count ($array2>0)){
			$str2 = str_replace('//', '', $array2[0]);
			//echo "" . $str2 . "<br/><br/>";
			$array3 = explode('?v=', $str2);
			if (count($array3)>1){
				$final = substr($array3[1], 0, 11);
				//echo (getIFrameForYoutubeId($final) . '<br/>');
				array_push($idarray, $final);
			}
		}
	}
	
	return array_unique($idarray);
}

function getCustomYoutubePage($query){
	$maxid = 1024; // Nombre maximum de résultats traités
	$maxcol = 2;
	
	$idarray = getYoutubeIdsForSearch($query);
	$result = '';
	
	$currentid = 0;
	$currentcol = 1;

	$result .= '<table>';	
	$result .= '<tr>';
	foreach($idarray as $id) {
		$currentid++;
		if ($currentid>$maxid) break;
		$html = getIFrameForYoutubeId($id);
		$result .= '<td><div class="col-lg-2">' . $html . '</div></td>';
		if ($currentcol>=$maxcol){
			$currentcol=0;
			$result .= '</tr><tr>';
		}
		$currentcol++;
	}
	$result .= '</tr>';
	$result .= '</table>';	
	
	return $result;
}


// Not developped yet
function getGoogleImagesForSearch($search = ''){
	
	$idarray = array();
	
	//$data = file_get_contents('https://www.youtube.com/?gl=FR&hl=fr');
	//https://www.youtube.com/results?search_query=sizzla
	$data = file_get_contents('https://www.google.fr/search?q=' . $search . '&source=lnms&tbm=isch&sa=X');
	

	$data = str_replace('<', '[', $data);
	$data = str_replace('>', ']', $data);

	//echo $data;
	
	$array = explode('href="', $data);
	//echo(count($array));
	foreach($array as $str){
		//echo ">>>" . $str . "<br/><br/>";
		$array2 = explode('"', $str);
		if (count ($array2>0)){
			$str2 = str_replace('//', '', $array2[0]);
			//echo "" . $str2 . "<br/><br/>";
			$array3 = explode('?v=', $str2);
			if (count($array3)>1){
				$final = substr($array3[1], 0, 11);
				//echo (getIFrameForYoutubeId($final) . '<br/>');
				array_push($idarray, $final);
			}
		}
	}
	
	return array_unique($idarray);
}


?>
