<?php

function com_tst01(){
	return "#com_tst01";
}

function com_load_csv( $fnm ){
	$csv  = array();
	$file = $fnm;
	$fp   = fopen($file, "r");
	 
	while (($data = fgetcsv($fp, 0, ",")) !== FALSE) {
	  $csv[] = $data;
	}
	fclose($fp);
	 
	//var_dump($csv);	
	
	return $csv;
}

function com_export_xml( $fnm, $items ){
   
		// インスタンスの生成
		$dom = new DomDocument('1.0', 'UTF-8');
		// prefs ノードを追加
		$rss = $dom->appendChild($dom->createElement('rss'));
		$rss->setAttribute('version', '2.0');

		//channel
		$channel     = $rss->appendChild($dom->createElement('channel'));
		$channel_tit = $channel->appendChild($dom->createElement('title', 'ctitle'));

		foreach($items as $low){
			$item = $channel->appendChild($dom->createElement('item'));
			// code 属性の追加
			//$item->setAttribute('code', '01');

			// 要素ノードを追加してテキストを入れる
			$item->appendChild($dom->createElement('itemId'   , $low["FahBook"]["itemId"] ));
			$item->appendChild($dom->createElement('itemName' , $low["FahBook"]["itemName"]));
			$item->appendChild($dom->createElement('koukai'   , $low["FahBook"]["koukai"] ));
			$item->appendChild($dom->createElement('urlCard'  , $low["FahBook"]["urlCard"] ));
			$item->appendChild($dom->createElement('authId'   , $low["FahBook"]["authId"]));
			$item->appendChild($dom->createElement('sei'      , $low["FahBook"]["sei"] ));
			$item->appendChild($dom->createElement('mei'      , $low["FahBook"]["mei"] ));
			$item->appendChild($dom->createElement('seiStr'   , $low["FahBook"]["seiStr"] ));
			$item->appendChild($dom->createElement('meiStr'   , $low["FahBook"]["meiStr"] ));
			$item->appendChild($dom->createElement('urlHtml'  , $low["FahBook"]["urlHtml"] ));
		}

		//XML を整形（改行・字下げ）して出力
		$dom->formatOutput = true;
		//保存（上書き）
		$dom->save( $fnm );	
}

//------------------------------------
// @calling
// @purpose : 共通番号TBLから、Noを取得する。
// @date
// @argment : $date_str=yyyymmdd+hhmmss, $i_num=rand()
// @return  : -1 =NG
//------------------------------------
function CM001_def_no( $date_str, $i_num, $sys_id, $uid){
		$i_def_id =0;
	
		$db     =new ComMysql();
		
		/*
		$con = $db->Get_Conection();
		$result = mysql_query( "START TRANSACTION;" , $con );
		if ($result == false) {
			print "START TRANSACTION Error!";
			return -1;
			exit;
		}
		
		//Insert
		$param["@001"] = "";
		$result = $db->Exec_NonQuery( "CM001", 4,  $param );
		 */
		
		//(1) select
		$param["@001"] = $date_str;
		$param["@002"] = $i_num;
		$param["@003"] = $sys_id;
		$result = $db->GetRecord_byId("CM001", 5, $param);
		while ($row = mysql_fetch_array ($result)) {
			$i_ct = $row["CT_NUM"];
		}
		if($i_ct > 0){
			return -1;
		}
		//(2) Insert
		$param["@001"] = $date_str;
		$param["@002"] = $i_num;
		$param["@003"] = $sys_id;
		$param["@004"] = $uid;

		//Insert
		$result = $db->Exec_NonQuery( "CM001", 4,  $param );
		//実行した結果にエラーあったらエラー表示する
		if ($result == false) {
			print "Data Insert Error!";
			return -1;
			exit;
		}
		/*
		$result = mysql_query( "COMMIT;" , $con );
		if ($result == false) {
			print "COMMIT Error!";
			return -1;
			exit;
		}
		 */
				
		//(3) Select_ID
		$result = $db->GetRecord_byId("CM001", 6, $param);
		while ($row = mysql_fetch_array ($result)) {
			$i_def_id = $row["ID"];
		}
		
	$db =NULL;
	return $i_def_id;
 //
}
//------------------------------------
// @calling : 時間(int)の差分時間を求める。
// @purpose :
// @date
// @argment : 
// @return  : 差分時間(分)
//------------------------------------
function CM001_getDiff_mm($start_hh, $start_mm ,$end_hh, $end_mm){

	$st_dt   = mktime($start_hh    , $start_mm    , 0, 1, 1, 2000 );
	$end_dt  = mktime($end_hh      , $end_mm      , 0, 1, 1, 2000 );
	$diff_mm = $end_dt - $st_dt;
    $diff_mm = $diff_mm / 60;

return $diff_mm;
}
//------------------------------------
// @calling
// @purpose : 次月、１日の日付を返す。(YYYY-MM-DD 形式)
// @date
// @argment
// @return
//------------------------------------
function CM001_geNextmonth( $bef_dt ){

//	$s_buf ='2010-03-01';
	$s_yy  = substr($bef_dt, 0, 4);
	$s_mm  = substr($bef_dt, 5, 2);
	$now_dt    = mktime(1, 0, 0, $s_mm, 1,   $s_yy );
	$nextmonth = mktime(1, 0, 0,  date("m", $now_dt ) +1, 1,    date("Y", $now_dt ) );

	$s_next = date("Y", $nextmonth ) . "-" . date("m", $nextmonth ) . "-" . date("d", $nextmonth );

return $s_next;
}
//------------------------------------
// @calling
// @purpose : 前/次月、１日の日付を返す。(YYYY-MM-DD 形式)
// @date
// @argment
// @return
//------------------------------------
function CM001_geMovemonth( $typ, $bef_dt ){

	$s_yy  = substr($bef_dt, 0, 4);
	$s_mm  = substr($bef_dt, 5, 2);

	$now_dt   = mktime(1, 0, 0, $s_mm, 1,   $s_yy );
	$nextmonth = mktime(1, 0, 0, date("m", $now_dt ) + $typ, 1,   date("Y", $now_dt ) );

	$s_next = date("Y", $nextmonth ) . "-" . date("m", $nextmonth ) . "-" . date("d", $nextmonth );

return $s_next;
}
//nextmonth


//------------------------------------
// @calling
// @purpose
// @date
// @argment
// @return
//------------------------------------
function CM001_Get_CodeStr($kbn, $i_num){
		$s_str1="";
		$db     =new ComMysql();
		
		//(1) select
		$param["@001"] = $kbn;
		$param["@002"] = $i_num;
		$result = $db->GetRecord_byId("CM001", 4, $param);
		while ($row = mysql_fetch_array ($result)) {
			$s_str1 = $row["STR_VAL_01"];
		}
		return $s_str1;
}
//------------------------------------
// @calling
// @purpose
// @date
// @argment
// @return : bool
//------------------------------------
function Com_checkAgent(){
	$clsConst = new AppConst();
	
	$s_buf = $_SERVER["HTTP_USER_AGENT"];

	$i_pos = strpos($s_buf , "MSIE");
	if( $i_pos != false){
	  $_SESSION["CM001"]["HTTP_USER_AGENT"]= $clsConst->VAL014_WEB_IE ;
	  return true;
	}
//var_dump($i_pos);
// exit;

	$i_pos_ch = strpos($s_buf , "Chrome");

	//Chrome
	// var_dump($s_buf ."<br>");
	// var_dump($i_pos );
/*
	if ($i_pos == false) {
		return false;
	}
*/
	if (($i_pos == false) && ($i_pos_ch == false)) {
		return false;
	}
	
	return true;
}

//------------------------------------
// @calling
// @purpose
// @date
// @argment
// @return
//------------------------------------
function Com_logWrite($msg){
	$s_time = date("Y/m/d H:i:s");
	
	if(LOG_FLG==true){
		error_log($s_time  . " ". $msg . "\r\n" ,3, LOG_FNAME);
	//	error_log($s_time  . " ". $msg . "\n" ,3, LOG_FNAME);
	}
}
//
function init() {
	//MySmartyクラスの読み込み
	require_once($_SERVER["DOCUMENT_ROOT"]. "/../libs/MySmarty.class.php");
	
	//セッションを開始する
	session_start();
	session_regenerate_id(true);

}

//------------------------------------
// @calling
// @purpose : 
// @date
// @argment : 
// @return  : 
//------------------------------------
function CM001_uploadFile( $obj , $uid){
	
		$uploaddir = BT_IMAGE_USR_DIR;
		$uploadfile = $uploaddir . $uid . "_" . basename($obj['name']);
//		$uploadfile = $uploaddir . "aa.png";

		if (move_uploaded_file($obj['tmp_name'] , $uploadfile) == false ) {
		    echo "Possible file upload attack!\n";
		    return false;
		}

	return true;
//		echo "Here is some more debugging info:";
//		print_r($_FILES);

}
//------------------------------------
// @calling
// @purpose
// @date
// @argment :
// @return  :
//------------------------------------
function CM001_Conv_href( $s_buf ){
	

//	$i_pos = -1;
	 $i_pos  = strpos($s_buf , "http://");
//var_dump($i_pos);
	if(is_numeric($i_pos)==true){
// var_dump($i_pos);
// var_dump($s_buf);
	 if($i_pos ==0){
	 	return "<a href='". $s_buf ."' target='_blank'>" . $s_buf . "</a>";
	 }
	}

 return $s_buf;
}
//------------------------------------
// @calling
// @purpose
// @date
// @argment : 
// @return  :
//------------------------------------
function CM001_Conv_urlString( $s_buf ){
	//Replace
	$s_buf = trim($s_buf);
	$s_buf  = str_replace("　", " ", $s_buf );

	$low = explode(" ", $s_buf);
// var_dump(count($low));
// var_dump($low);

	if(count($low) > 1){
		$s_out ="";
		foreach($low as $value){
			$s_out  .=CM001_Conv_href($value) . " ";
		}
	}else{
		return  $s_buf;
	}

	return $s_out;
}
//------------------------------------
// @calling
// @purpose : Zero Str, max=10 char
// @date
// @argment
// @return
//------------------------------------
function CM001_getZeroStr( $src, $num ){
	if($num > 10){
		return "";
	}
	
	
	$buff="0000000000";

	$buff = $buff . $src;
	$i_len = strlen($buff);
	$ret = substr($buff, $i_len - $num, $num);
	
	return $ret;
}
?>