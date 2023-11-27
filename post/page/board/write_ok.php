<?php

include $_SERVER['DOCUMENT_ROOT']."/POST/db.php";
$date = date('Y-m-d');
$userpw = password_hash($_POST['pw'], PASSWORD_DEFAULT);
if(isset($_POST['lockpost'])){
	$lo_post = '1';
}else{
	$lo_post = '0';
}

$tmpfile =  $_FILES['b_file']['tmp_name'];
$o_name = $_FILES['b_file']['name'];
$filename = iconv("UTF-8", "EUC-KR",$_FILES['b_file']['name']);
$folder = "../../upload/".$filename;
move_uploaded_file($tmpfile,$folder);

$sql = query("insert into board(name,pw,title,content,date,lock_post,file) 
values('".$_POST['name']."','".$userpw."','".$_POST['title']."','".$_POST['content']."','".$date."','".$lo_post."','".$o_name."')");


if ($sql) {
    echo '<script type="text/javascript">alert("글쓰기 완료되었습니다.");</script>';
    echo '<meta http-equiv="refresh" content="0;url=/POST/index.html" />';
} else {
    echo '<script type="text/javascript">alert("글쓰기 실패하였습니다.");</script>';
}
?>