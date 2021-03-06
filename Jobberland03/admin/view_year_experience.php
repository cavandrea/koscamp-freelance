<?php  require_once( "../initialise_files.php" );  

	include_once("sessioninc.php");
	
	$smarty->assign( 'action', $_GET['action'] );
	$smarty->assign( 'id', (int)$_GET['id'] );
	
if( isset($_GET['action']) && $_GET['action'] == "edit" && isset($_GET['id']) ) { 

    $id = (int)$_GET['id'];
    $jt_name = Experience::find_by_id( $id );    
    $jt_name2 = $jt_name->experience_name;

	$smarty->assign( 'jt_name2', $jt_name2 );
	$smarty->assign( 'is_active', $jt_name->is_active );
	
		if( isset($_GET['bt_update']) ){
			$experience = new Experience();
			$experience->id = (int)$_GET['id'];
			$experience->experience_name = $_GET['txt_name'];
			$experience->var_name 	= $experience->mod_write_check($_GET['txt_name'], $jt_name->var_name);
			$experience->is_active = $_GET['txt_active'];
			
			if($experience->save()){
				$session->message ("Experience updated ");
				redirect_to( $_SERVER['PHP_SELF']."?#". $_GET['id'] );
				die;
			}else{
				$message = join("<br />", $experience->errors );
			}
		}
}

if( isset($_GET['action']) && $_GET['action'] == "delete" && isset($_GET['id']) ) { 
	$experience = new Experience();
	$experience->id = (int)$_GET['id'];
	if( $experience->delete() ){
		$session->message ("Experience deleted ");
		redirect_to( $_SERVER['PHP_SELF']."?#". $_GET['id'] );
		die;
	}else{
		$message = join("<br />", $experience->errors );
	}
	
}

if( isset($_GET['action']) && $_GET['action'] == "add" ) { 
	if( isset($_POST['bt_add']) ){
			$add_new = new Experience();
			
			$add_new->experience_name	= $_POST['txt_experience_name'];
			$add_new->var_name 			= $add_new->mod_write_check($_POST['txt_experience_name'] );
			$add_new->is_active			= $_POST['txt_is_active'];
			
			if( $add_new->save() ){
				$session->message("New Year Experience added.");
				redirect_to( $_SERVER['PHP_SELF'] );
				die;
			}else{
				$message = join("<br />", $add_new->errors );
			}
	}
}

	$experience = Experience::find_all();

		$manage_lists = array();
		if($experience && is_array($experience)){
			$i=1;
			foreach( $experience as $list ):			
			  $manage_lists[$i]['id'] = $list->id;		 
			  $manage_lists[$i]['experience_name'] = $list->experience_name;
			  $manage_lists[$i]['is_active'] = $list->is_active;
			  $i++;
			endforeach;
			$smarty->assign( 'manage_lists', $manage_lists );
		}
		
		$query = "";
		if( !empty($_GET) ) {
			foreach( $_GET as $key => $data){
				if( !empty($data) && $data != "" && $key != "page" && $key != "bt_search"){
					$query .= "&amp;".$key."=".$data;
				}
			}
			$smarty->assign( 'query', $query );
		}
				

$html_title = SITE_NAME . " Add New Employer ";
$smarty->assign('lang', $lang);
$smarty->assign( 'message', $message );	
$smarty->assign('rendered_page', $smarty->fetch('admin/view_year_experience.tpl') );
$smarty->display('admin/index.tpl');
//view_year_experience.php


?>