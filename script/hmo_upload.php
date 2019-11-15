<?php
if(isset($_REQUEST['upload']) && $_REQUEST['upload'] == 'patient'){
	$file = $_FILES['file'];
	$hmo = $_REQUEST['hmo_id'];
	$tmp = $file['tmp_name'];
	$type = $_REQUEST['upload_type'];
	if($file['error'] == 0){
		$name = parse_url($tmp);
		$name = explode(".",end(explode("\\",$name['path'])));
		$name = $name[0];
		$ext = explode('.',$file['name']);
		$ext = $ext[1];
		$name = $name.'.'.$ext;
		$dest = 'document/'.$name;
		if(strtolower($ext) == "xlsx"){
			move_uploaded_file($tmp,$dest);
			$Reader = new SpreadsheetReader($dest);
			try{
				foreach ($Reader as $key => $row)
				{
					if($key == 0){
						continue;
					}
					
					if($type == 'patient'){
						if($key == 1){
							$db->execute("delete from hmo_patient where hmo_id = ?",array($hmo));
						}
						$db->execute("INSERT INTO `hmo_patient` (`name`, `hospital_id`, `phone`, `address`,`sex`, `hmo_id`) VALUES (?, ?, ?, ?, ?, ?)",
												array($row[0],$row[1],$row[2],$row[3],$row[4],$hmo));
						
					}else if($type == 'service'){
						$category = $_REQUEST['category'];
						if($key == 1){
							$db->execute("delete from hmo_services where hmo_id = ? and cat_id = ?",array($hmo,$category));
						}
						if($category == 0){
							$db->execute("INSERT INTO `hmo_services` (`name`, `dosing`,`unit`, `cost`, `hmo_id`, `cat_id`) 
										VALUES (?, ?, ?, ?, ?, ?)",array($row[0],$row[1],$row[2],$row[3],$hmo,$category));
						}else{
							$db->execute("INSERT INTO `hmo_services` (`name`, `unit`, `cost`, `hmo_id`, `cat_id`) 
										VALUES (?, ?, ?, ?, ?)",array($row[0],$row[1],$row[2],$hmo,$category));
						}
					}
				}
				unlink($dest);
			}catch(PDOException $e){
				header("location:?hmo=".$hmo."&failed=Error in uploading document. Kindly please check file");
			}catch(Exception $e){
				header("location:?hmo=".$hmo."&failed=Unable to upload document");
			}
		}else{
			
			header("location:?hmo=".$hmo."&failed=Invalid file type");
		}
	}else{
		
		header("location:?hmo=".$hmo."&failed=Error in upload");
	}
}
