<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>ドラッグ＆ドロップ</title>
        <style>
            #dropzone {
                width: 150px;
                height: 250px;
                background-color: pink;
                text-align: center;
padding-top: 50px;
                /* line-height: 150px; */
            }
        </style>
        <script src="http://code.jquery.com/jquery-1.7.2.min.js"></script>
	<script type="text/javascript">

	function send_imgfile( files ){
	  $form = $("#hogeForm");
          fd = new FormData($form[0]);
          fd.append('file', files[0] );
	    $.ajax('/cake11/posts/upload5', {
	      type: 'post',
	      processData: false,
	      contentType: false,
	      data: fd,
	      dataType: 'html',
	      success: function(data){
	        console.log(data);
alert(  'complete send :'+files[0].name );
		disp_items( files[0].name );
	      }
	    });
	    return false;
	}
	function disp_items( fnm ){
 	  var s_div  = '';
	  s_div += '<img src="/cake11/img/' +fnm+ '" />';
	  $('div#id_img_disp').append( s_div );
	}
	</script>
    </head>
    <body style="margin:0px ; padding:0px;">
        <p >画像ファイルのアップロード</p>
        <div id="dropzone"  ondragover="onDragOver(event)" ondrop="onDrop(event)">
            <p>ここへ画像を<br />ドロップ</p>
        </div>
	<br />
	<div id="id_img_disp" style="width: 1024px; height: 200px; padding: 1em; background : #E1E1E1; margin-top: 20;" >
	</div>
	<hr style="margin-top : 250px;" />
	<div id="msg" ></div>
<script type="text/javascript"> 
var msg = document.getElementById("msg");
function onDragOver(event){ 
    event.preventDefault(); 
} 
function onDrop(event){ 
    var files = event.dataTransfer.files;
    var files_info = "";
    for (var i=0; i<files.length; i++) {
        files_info += (i+1) + "つ目のファイル情報：" + "<b>[name]</b> "
      + files[i].name + " <b>[size]</b> " + files[i].size + " <b>[type]</b> "
      + files[i].type + "<br>";
    }
    msg.innerHTML = files_info;
    send_imgfile( files );

    event.preventDefault(); 
} 
</script>

<h1>hogeForm</h1>
<div id="id_form_div" style="display: none;">
<form id="hogeForm" method="post" action="" enctype="multipart/form-data">
  <div><input type="file" name="file1"></div>
  <div>ファイルの説明：<input type="text" name="hogeText"></div>
  <input id="hogeSubmit" type="submit" value="うｐする">
</form>
</div>
<br />     
<br />
 </body>
</html>