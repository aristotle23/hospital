<?php
require_once "dbHandler.php";
date_default_timezone_set("Africa/Lagos");
session_start();
$db = new dbHandler();
if(isset($_REQUEST['page'])){
	if(strtolower($_REQUEST['page']) == 'addservices'){
		$service = $_REQUEST['service'];
		$access = $_REQUEST['access'];
		if($_REQUEST['type'] != 'null' && $_REQUEST['title'] != 'null'){
			$title = $_REQUEST['title'];
			$type = $_REQUEST['type'];
			$chck = $db->getOne("select id from services where service_title_id = ? and service_type_id = ? and services = ?",
								array($title,$type,$service));
			if($chck){
				echo json_encode("exist");
			}else{
				$success = $db->executeGetId("INSERT INTO `services` (`service_title_id`, `service_type_id`, `services`, `access_right`)
											 VALUES (?, ?, ?, ?)",array($title,$type,$service,$access));
				if($success){
					echo json_encode(true);
				}else{
					echo json_encode(false);
				}											 					
			}
		}else{
			$chck = $db->getOne("select id from services where services = ? ",array($service));
			$tchck = $db->getOne("select id from service_title where title = ?",array($service)); 
			if($chck || $tchck){
				echo json_encode("exist");
			}else{
				$tid = $db->executeGetId("insert into service_title (title) values (?)",array($service));
				if($tid){
					$success = $db->executeGetId("INSERT INTO `services` (`service_title_id`, `services`, `access_right`) VALUES (?, ?, ?)",
													array($tid,$service,$access));
					if($success){
						echo json_encode(true);
					}else{
						$db->execute("delete from service_title where id = ?",array($tid));
						echo json_encode(false);
					}
				}else{
					echo json_encode(false);
				}
			}
		}
	}
}


function timeAgo($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time   = time();
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "just now";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "one minute ago";
        }
        else{
            return "$minutes minutes ago";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "an hour ago";
        }else{
            return "$hours hrs ago";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "yesterday";
        }else{
            return "$days days ago";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "a week ago";
        }else{
            return "$weeks weeks ago";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "a month ago";
        }else{
            return "$months months ago";
        }
    }
    //Years
    else{
        if($years==1){
            return "one year ago";
        }else{
            return "$years years ago";
        }
    }
}
?>