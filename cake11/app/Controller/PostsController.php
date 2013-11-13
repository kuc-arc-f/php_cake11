<?php
//require_once( "../Vendor/ComFunc.php" );
//require_once( "../Vendor/AppConst.class.php");

class PostsController extends AppController {
    public $helpers= array('html' ,'Form');

	public function upload5(){
//var_dump( $_FILES['files']['name'] );
			$uploadfile = "../webroot/img/"  .  $_FILES['file']['name'] ;
			if (move_uploaded_file( $_FILES['file']['tmp_name'] , $uploadfile) == false ) {
	             $response = array('ret'=> "0");
		         echo json_encode($response );
		         exit();
		 	}else{
	             $response = array('ret'=> "1");
		         echo json_encode($response );
		         exit();
		 	}
	}

	public function dnd6(){
		$this->set('title_for_layout', 'upload dnd5');
		$this->layout = null;
	}
	

    
}

?>