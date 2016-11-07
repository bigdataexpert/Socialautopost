<?php
error_reporting(E_ALL); ini_set('display_errors','On'); 
error_reporting(1); 
require_once('../config.php');
require_once('GoogleUrlApi.php');
date_default_timezone_set("Asia/Kolkata");
$socialsql="SELECT * FROM `social_poster`";
$socialquery=mysql_query($socialsql);
$constans=array();
while($in=mysql_fetch_row($socialquery)){
$constans[]=$in;
}
foreach($constans as $key => $value){
	define($value[1], $value[2]);
}
//echo TRAIN_NUM;
//echo TRAINS_ID;
//echo TIME_INTERVAL;
//echo PER_DAY_POST;
if(isset($_GET['newDate']) && isset($_GET['train_number'])){
	$datesearch=$_GET['newDate'];
	$trainCode[0]=$_GET['train_number'];
}else{
$datesearch=date('d-m-Y');
$trainCode[0]= TRAIN_NUM;	
}
$argurl="http://www.railroutes.in/trn_live_srch.php?train=".$trainCode[0]."&newDate=".$datesearch;
echo "<br/>";
echo $argurl;
echo "<br/>";
$sql="select * from trains where train_number=".$trainCode[0];
$sql1=mysql_query($sql);
$train_info=mysql_fetch_row($sql1);
$sql2=mysql_query("select * from train_routes where train_number=.".$trainCode[0]);
$sql3=mysql_query("select * from train_routes where train_number=".$trainCode[0]);
$totalStationsBetween=mysql_num_rows($sql2);
$tnName=$train_info[2];
$stnCode= array();
while($train_stationStn=mysql_fetch_row($sql3)) {
	$stnCode[]=$train_stationStn[3];
}
$lastCt=count($stnCode)-1;
$lastStation=$stnCode[1];
if(!isset($_GET['newDate'])){
$dt=date('Ymd');
}else{
$totalDates=explode("-",$_GET['newDate']);
$dt=$totalDates[2].$totalDates[1].$totalDates[0];
$_GET['newDate']=$dt;
}
$url='http://api.railwayapi.com/live/train/'.$trainCode[0].'/doj/'.$dt.'/apikey/76821';
$jsondata=file_get_contents($url);
$response = json_decode($jsondata);
if($response->response_code!=200){
	$failedFApi="yes";
}if(!isset($failedFApi)){
echo "<!-- Using Default Server API -->";
}
if(isset($failedFApi)){
$str='909c13e05d8b39fb46dda911e26efd3e'.$dt.$lastStation.$trainCode[0];
$str= strtolower($str);
$hmacSign=hash_hmac('sha1', 'c94e90e25b3f67452d5a0406481d3d4f', $str  ); 
$url='http://livetrainstatusapi.com/api/status/tnum/'.$trainCode[0].'/scode/'.strtoupper($lastStation).'/date/'.$dt.'/apikey/909c13e05d8b39fb46dda911e26efd3e/apisign/'.$hmacSign;
$response=file_get_contents($url);
if(!isset($response->location)){
	$authEr="yes";
	}
$lastLoc=$response->location;
if($lastLoc=='TRAIN NOT STARTED FROM SOURCE.'){
	$notStarted="yes";
}
$parts=explode("|",$lastLoc);
$actualSt=$parts[0];
$actualSt1=$parts[0];
$actualSt2=$parts[1];
$actualSt3=$parts[2];
$actualSta1=explode('[',$parts[1]);
$actualSta2=explode(']',$actualSta1[1]);
$actualStation=$actualSta2[0];
$delay=$response->arrival->delay;
$cnt=0;
$stnDel=explode(" ",$parts[2]);
for($j=0;$j<=$lastCt;$j++){
$cnt++;
if($stnCode[$j]==$actualStation){
	
		$stnNum=$cnt;
	}

}
	
}
?>
<?php
$sql1=mysql_query("select * from trains where train_number=".$trainCode[0]);
$train_info=mysql_fetch_row($sql1);
$sql3=mysql_query("select * from train_routes where train_number=".$trainCode[0]." and arrival='Start'");
$train_info2=mysql_fetch_row($sql3);
				 	$presentDate=date('Y-m-d H:i:s');		
							
									$today=date('N', strtotime(substr($dt,0,4)."-".substr($dt,4,2)."-".substr($dt,6,2)));
									$todayRun="";
									
									if(strtotime($presentDate)<strtotime(substr($dt,0,4)."-".substr($dt,4,2)."-".substr($dt,6,2)." ".$train_info2[5].":00")){
										$notStarted="yes";
									}
									
									$totalRuns="";
									
													for($i=3;$i<=9;$i++) {
													if($train_info[$i]=='Y'){
														switch($i){
														case 3:
														if($today==7){
															$todayRun="Yes";
															
															
														}
														$totalRuns.="<td>SUN</td>";
														
														break;
														case 4:
														if($today==1){
															$todayRun="Yes";
														}
														$totalRuns.= "<td>MON</td>";
														break;
														case 5:
														if($today==2){
															$todayRun="Yes";
														}
														$totalRuns.= "<td>TUE</td>";
														break;
														case 6:
														if($today==3){
															$todayRun="Yes";
														}
														$totalRuns.= "<td>WED</td>";
														break;
														case 7:
														if($today==4){
															$todayRun="Yes";
														}
														$totalRuns.= "<td>THUR</td>";
														break;
														case 8:
														if($today==5){
															$todayRun="Yes";
														}
														$totalRuns.= "<td>FRI</td>";
														break;
														case 9:
														if($today==6){
															$todayRun="Yes";
														}
														$totalRuns.= "<td>SAT</td>";
														break;
														}
													}
													
														
												} 
												?>
									
  
										<?php 
										$socialstatus=false;
									if($todayRun==''){
										 $output= "Not Running";
										 echo  $output;
										 }else{
											if($todayRun=='Yes' && isset($notStarted))
											{
												$output= "Not Started";
												echo  $output;
											}else{
												 if(!isset($failedFApi)){
													if(isset($noStatusEr)){ 
														$output= "Select Date for get the status"; 
														echo  $output;
													}else{ 
														 if($response->position!=''){ 
															if($response->position=='-'){ 
																$output= "Please Check Status Below Please Check Status Below Please Check Status Below Please Check Status Below"; 
																echo $output;
															}else{ 
																$output= $response->position; 
																echo $output;
																$socialstatus=true;
															} 
															}else{ 
																$output = "You Can find latest Running Status by Changing dates from below given box." ;
																echo $output;
															}
														}  
												}else{
													 $stTime=explode(" ",$actualSt3);
													 $ff=str_replace("Â","",$actualSt2);
													 if(!isset($notStarted) && !isset($authEr)){
														$output = "<strong>Last Update:</strong> Train has been ".$actualSt1." at " .$ff." at date- ".substr($stTime[0],6,2)."-".substr($stTime[0],4,2)."-".substr($stTime[0],0,4)." at time ".$stTime[1]; 
														//echo $output;
														$socialstatus=true;
													 }else{ 
														 if(isset($noStatusEr)){ 
															$output = "Select Date for get the status.";
															echo $output;
														 }else{
															 if(isset($authEr)){
																$output = "Server Not Responding. Pls try after Sometime";
																echo $output;	
															 }else{
																	$output = "Sorry ! Train NOT running for given date, Try to change the date."; 
																	echo $output;
																} 
															}  
														 }  
													 }  
												}  
											} 												
if($socialstatus){
		$runningstatus= "Train No ".$trainCode[0]." ( ".$train_info[2]." ) ".$output;	
		require_once ('twitter/src/codebird.php');
		
		//###############################
		$apiurl ="http://free.rome2rio.com/api/1.4/json/Search?key=qIwH2gSo&oName=delhi&dName=jaipur&flags=0x000FFFF0";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $apiurl);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT_MS, 11000);
		curl_setopt($ch, CURLOPT_TIMEOUT_MS, 11000);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$json = curl_exec($ch);
		curl_close($ch);
		$jset = json_decode($json, true);
		########################################
		
		
	
$key = 'AIzaSyDZTVC7G4bKjZIcHT3JXyk7C9ik3Dhw-jM';
$googer = new GoogleURLAPI($key);

// Test: Shorten a URL
$shortDWName = $googer->shorten($argurl);
//echo "====>".$shortDWName; // returns http://goo.gl/i002

// Test: Expand a URL
$longDWName = $googer->expand($shortDWName);
//echo $longDWName; // returns https://davidwalsh.name


			\Codebird\Codebird::setConsumerKey("w9W88WnMtbjeVZNpJXJMANpyE", "6wTtfQe3g0KlHFbtxlN6ppp2Gfb3UVAQ32xpFZAYAXECIHp05d");
			$cb = \Codebird\Codebird::getInstance();
			$cb->setToken("348982345-94Pec9VtsiFTUhbPC34SrguMUf16LusBKL5FnmGW", "5p43MdH4NUcaHHVnibK3h2j24jalPeqPYZwN1fUoOd8un");
			$params = array(
			'status' => $runningstatus." ".$shortDWName
			);
			
		$reply = $cb->statuses_update($params);	
		$t_id=(rand(1,2732));
	$q="SELECT * FROM `trains` WHERE `trains`.`id` > '$t_id' ORDER BY `trains`.`id` DESC ";
	
	$querydata=mysql_query($q);
	$traindetails= mysql_fetch_row($querydata);
	
	$tid=$traindetails[0];
	$tnum=$traindetails[1];
	$update="UPDATE  `social_poster` SET `value`= '$tid' WHERE `social_poster`.`name`='TRAINS_ID' ";
	$update1="UPDATE  `social_poster` SET `value`= '$tnum' WHERE `social_poster`.`name`='TRAIN_NUM' ";
	
	mysql_query($update);
	mysql_query($update1);
		
		die;
}else{
	$t_id=(rand(1,2732));
	$q="SELECT * FROM `trains` WHERE `trains`.`id` > '$t_id' ORDER BY `trains`.`id` DESC ";
	
	$querydata=mysql_query($q);
	$traindetails= mysql_fetch_row($querydata);
	
	$tid=$traindetails[0];
	$tnum=$traindetails[1];
	$update="UPDATE  `social_poster` SET `value`= '$tid' WHERE `social_poster`.`name`='TRAINS_ID' ";
	$update1="UPDATE  `social_poster` SET `value`= '$tnum' WHERE `social_poster`.`name`='TRAIN_NUM' ";
	
	mysql_query($update);
	mysql_query($update1);
	header("Refresh:2;");
	}



