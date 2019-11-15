<?php
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

/* A wrapper to do organise item names & prices into columns */
class column
{
    private $name;
    private $price;
    private $dollarSign;

    public function __construct($name = '', $price = '', $dollarSign = false)
    {
        $this -> name = $name;
        $this -> price = $price;
        $this -> dollarSign = $dollarSign;
    }
    
    public function __toString()
    {
        $rightCols = 12;
        $leftCols = 30;
		
		$serv = ( strlen($this -> name) > 30) ? substr($this -> name,0,27).'...' : $this -> name;
		//$serv = $serv .'...'; 
        $left = str_pad($serv, $leftCols) ;

        $right = str_pad($this -> price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
	public static function highest($arrs){
		$cserv = 0;
		$camt = 0;
		foreach ($arrs as $arr){
			if($cserv <= strlen($arr['service'])){
				$cserv = strlen($arr['service']);
			}
			if($camt <= strlen($arr['amount'])){
				$camt = strlen($arr['amount']);
			}
		}
		return array($cserv,$camt);
	}
}
function pagination($currentpage,$totalpages,$from,$to){
	/******  build the pagination links ******/
	// range of num links to show
	$range = 2;
	$hide = $currentpage - 1;
	// if not on page 1, don't show back links
	if ($currentpage > 1 && $hide > 2) {
	   // get previous page num
	   $prevpage = $currentpage - 1;
	   // show < link to go back to 1 page
	   echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?from=$from&to=$to&page=$prevpage'>Previous</a></li>";
	    // show << link to go back to page 1
	   echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?from=$from&to=$to&page=1'>1</a></li>";
		echo '<li class="page-item disabled"><span class="page-link">...</span></li>';	   
	} // end if 
	
	// loop to show links to range of pages around current page
	for ($x = ($currentpage - $range); $x < (($currentpage + $range) + 1); $x++) {
	   // if it's a valid page number...
	   if (($x > 0) && ($x <= $totalpages)) {
		  // if we're on current page...
		  if ($x == $currentpage) {
			 // 'highlight' it but don't make a link
			 echo '<li class="page-item active">
					<span class="page-link">
					  '.$x.'
					  <span class="sr-only">(current)</span>
					</span>
				  </li>';
		  // if not current page...
		  } else {
			 // make it a link
			 echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?from=$from&to=$to&page=$x'>$x</a></li>";
		  } // end else
	   } // end if 
	} // end for
	$hide = $totalpages - $currentpage;
	// if not on last page, show forward and last page links        
	if ($currentpage != $totalpages && $hide > 2 ) {
		echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
	   // echo forward link for lastpage
	   echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?from=$from&to=$to&page=$totalpages'>$totalpages</a></li> ";
	   // get next page
	   $nextpage = $currentpage + 1;
		// echo forward link for next page 
	   echo "<li class='page-item'><a class='page-link' href='{$_SERVER['PHP_SELF']}?from=$from&to=$to&page=$nextpage'>Next</a></li> ";
	} // end if
	/****** end build pagination links ******/
}