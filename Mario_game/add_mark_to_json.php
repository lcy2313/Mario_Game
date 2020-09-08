<?php
// load file
$data = file_get_contents('marks.json');

// decode json to associative array
$json_arr = json_decode($data, true);

$player_name = $_POST['name'];
$player_score = $_POST['score'];

$error = false;
$is_updata = false;

if ( strpos($player_name,"<")!==false || strpos($player_name, ">")!==false){
	$error_type = 1;
	$error = true;
}

if( !(is_numeric($player_score)) ){
	$error_type = 2;
	$error = true;
}

if (strlen($player_name) > 23){
	$error_type = 3;
	$error = true;
}

if ( intval($player_score) > 1000) {
	$error_type = 4;
	$error = true;
}

if ($error == false){
	foreach ($json_arr as $key => $value) {
		if ( $player_name == $value['Name']){
			$is_updata = true;
			if ( $player_score > $value['Score']){
				$json_arr[$key]['Score'] = $player_score;
			}
		}
	}
	if ($is_updata == false) {
		$json_arr[]=array('Name'=>$player_name,'Score'=>$player_score);
	}	
	file_put_contents('marks.json', json_encode($json_arr));
}

if ($error == false){
	$msg["response"] = "success";
	$msg["content"] = "Thanks! Your score is successfully uploaded!!";
}else{
	$msg["response"] = "error";
	if ($error_type == 1){
		$msg["content"] = "Please enter Correct Name without '<' or '>' !!";
	}elseif ($error_type == 2 || $error_type == 4) {
		$msg["content"] = "Please don't modify the Score Yourself !!";
	}elseif ($error_type == 3) {
		$msg["content"] = "Please Enter the name within 23 words";
	}
	
}
echo json_encode($msg);
?>