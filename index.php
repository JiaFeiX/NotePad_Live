<?php

/** Root URL of the website

如果需要统一为一个固定URL，则设置为
$URL = "http://notepad.live";

如果可以允许用户自由访问空间绑定的域名，则采用如下默认设置
$URL = "http://".$_SERVER["HTTP_HOST"];

**/

$URL = "http://".$_SERVER["HTTP_HOST"];

// 内容存放文件夹
$FOLDER = "_tmp";

//密码标识前缀
$pw_tag = "**@_#PassWord**";


function sanitize_file_name($filename) {
    // Original function borrowed from Wordpress.
    $special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", ".");
    $filename = str_replace($special_chars, '', $filename);
    $filename = preg_replace('/[\s-]+/', '-', $filename);
    $filename = trim($filename, '.-_');
    return $filename;
}


if (!isset($_GET["f"])) {
    // User has not specified a name, get one and refresh.
    $lines = file("words.txt");
    $name = trim($lines[array_rand($lines)], "\n");
    while (file_exists($FOLDER."/".$name) && strlen($name) < 10) {
        $name .= rand(0,9);
    }
    if (strlen($name) < 10) {
        header("Location: ".$URL."/".$name);
    }
    die();
}

$name = sanitize_file_name($_GET["f"]);
$path = $FOLDER."/".$name;


?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php print $name; ?> - Simple Cloud Notepad</title>
	  <link rel="stylesheet" href="/lib/master.css" type="text/css" media="screen" charset="utf-8" />
  <link rel="stylesheet" href="/lib/print.css" type="text/css" media="print" charset="utf-8" />
  <link rel="stylesheet" href="/lib/browser.css" type="text/css" media="screen" charset="utf-8" /> 
	<link rel="shortcut icon" type="image/gif" href="favicon.gif" />
	<link href="lib/normalize.css" rel="stylesheet" />
    <link href="lib/styles.css" rel="stylesheet" />
	
</head>
<body>
    <div class="container">



<?php
//include_once("lib/password.php");

if (file_exists($path)) {
				
  $file_head = file_get_contents($path,FALSE,NULL,0,47);
				
}				
				

if (isset($_POST["checkpw"])) {
    // 检查密码的函数
    
	if ( md5($_POST["submit_pw"]) == substr($file_head,15,32) ){
	
	$check_status = "OK";
	$check_tip = "您的密码正确";
    
	//echo $check_tip;
	
	}
	
	else {
		
	$check_tip = "密码错误，4秒后页面自动刷新，请重试
	
	<meta http-equiv='refresh' content='4; url=/$_GET[f]'>
	
	";
    $check_status = "noOK";	
	
	echo $check_tip;

	exit();
	
	}
	
		
	
}




if (isset($_POST["setpw"])) {
    // 检查设置密码
    
	echo "
	
	
	<br><br>

<center>					
					
					<form action='/$_GET[f]' method='post'>

						设置密码： <input type='password' name='submit_pw' /><input type='submit' value='提交' />
						
						<input type='hidden' name='setpw2' value='1' />
						
						<textarea style='display:none;'></textarea>
						
						
						</form>
						
						
<center>						
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>	
					
					<center>
&copy; 2015 EverTools软件基金会 . 交流QQ群：229593086

<br><br>

Notepad.Live | 最简洁的云笔记本

</center>
	
	
	
	";
	
	exit();
	
}



if (isset($_POST["setpw2"])) {
    // 设置密码——实际操作
    
	
	
	if (substr($file_head,0,15) == $pw_tag )
	
	{
		
    	$new_text = $pw_tag.md5($_POST['submit_pw']).file_get_contents($path,FALSE,NULL,47);
		
		//echo ($new_text);
		
		file_put_contents($path, $new_text);
		
	}
	
	else {
		
		
		$new_text = $pw_tag.md5($_POST['submit_pw']).file_get_contents($path);
		
		//echo ($new_text);
		file_put_contents($path, $new_text);
		
		
	}
	
	
	
	
	echo "<br><br>
<center>设置成功！ 4秒后跳转，您需要输入刚才设置密码，才能再次打开<meta http-equiv='refresh' content='4; url=/$_GET[f]'><center>";
	
	exit();
	
}






if (isset($_POST["t"])) {
    // Update content of file
	
	if (substr($file_head,0,15) == $pw_tag ){
		
		$tt = $pw_tag.substr($file_head,15,32).$_POST["t"];
		
	}
		
		
	else{
		
		$tt = $_POST["t"];
	
        }
	
	
    file_put_contents($path, $tt);
	
	
	
    die();
}




?>



	
	<script type="text/javascript" src="lib/common.js"></script> 
<script type="text/javascript" src="lib/text.js"></script> 


	
        <?php 
            if (file_exists($path)) {
		
				//加密文件
	
				if (substr($file_head,0,15) == $pw_tag ){
					
					//$submit_pw = "shanghai";
					
					if ($check_status == "OK") {
						
						
						echo "<textarea class='content'>". htmlspecialchars(file_get_contents($path,FALSE,NULL,47))."</textarea>";
						
					}
					
					else {
						
						echo "
						
					<br><br>

<center>					
					
					<form action='/$_GET[f]' method='post'>

						本笔记已加密，请输入密码： <input type='password' name='submit_pw' /><input type='submit' value='提交' />
						
						<input type='hidden' name='checkpw' value='yes' />
						</form>
						
						
<center>						
					<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>	
					
					<center>
&copy; 2015 EverTools软件基金会 . 交流QQ群：229593086

<br><br>

Notepad.Live | 最简洁的云笔记本

</center>
						";
						
						
						
						exit();
						
					}
					
						
					
	
				}
				
				else {
					
					
					echo "<textarea class='content'>". htmlspecialchars(file_get_contents($path))."</textarea>";
					
					
					
				}  //显示非加密笔记
				
				
				
            }
			
			else{
			
			
			echo "<textarea class='content'></textarea>"; //显示新笔记
			
			}
?>



<p>&nbsp;</p>


<div id="controls">

    <a href="javascript:void(0)" onclick="history.go(0)">刷新</a> 
<span class="bubble_wrapper" onclick="just_clicked_bubble = 'change_url';">
<!--a href="#" onclick="return false;">更改url</a-->
<div class="bubble" id="bubble_for_change_url" style="display:none;">
<div class="highlight"></div>
<div class="message" id="message_for_change_url_unavailable" style="display:none;">
该URL不可用，可能已被占用。请输入4-30个字母或数字组成的字符串。
</div>
<form action="#" method="post" id="form_for_set_name" onsubmit="return validate_name_exists(); return false;">
<input type="text" class="text_input" name="new_name" id="change_url_input" placeholder="4-30个字母或数字" value="" />
<input type="submit" class="button" value="确定" />
</form>
<div class="nipple"></div>
</div>
</span>
 
  <span class="bubble_wrapper" onclick="just_clicked_bubble = 'set_password';">
  
  
  <form name=form9 method="post" action="<?php echo "/".$_GET['f']; ?>"> 
<INPUT TYPE="hidden" name="setpw" value="1"> 
<INPUT TYPE="submit" name="test" value = "go" style="display:none">  
</form> 
  
  <a href="javascript:void(0)" target="_self" onclick="javascript:document.form9.test.click();">设置密码</a>
  
  
  <!--a href="#" onclick="just_clicked_bubble = 'set_password'; return false;">设置密码</a-->
  
  
  <div class="bubble" id="bubble_for_set_password" style="display:none;">
  <div class="highlight"></div>
  <form action="#" method="post">
  <input type="password" class="text_input" name="password" id="set_password_input" placeholder="4-30个字母或数字" value="" />
  <input type="submit" class="button" value="确定" />
  </form>
  <div class="nipple"></div>
  </div>
  </span>
   

<span class="bubble_wrapper" onclick="just_clicked_bubble = 'share_this_read';">

<?php

$md5_path = md5($_GET['f']);

$zhidu_link="/share/".substr($md5_path,3,6).$_GET['f'];

?>

<a href="<?php echo($zhidu_link); ?>" target="_blank">获取只读地址</a>

<div class="bubble" id="bubble_for_share_this_read" style="display:none;">
<div class="highlight"></div>
<input type="text" class="text_input" id="share_this_read_input" onclick="$('#share_this_read_input').focus();" readonly="true"
value="#" 
/>
<div class="nipple"></div>
</div>   
</span>

</div>


    
    <pre class="print"></pre>
    <script src="lib/jquery.min.js"></script>
    <script src="lib/jquery.textarea.js"></script>
    <script src="lib/script.js"></script>
	
	
	<p>&nbsp;</p>
	<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
	
	<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
	
	
	<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
	<p><center></center></p>
	<p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
<p> 


<center>
&copy; 2015 EverTools软件基金会 . 交流QQ群：229593086

<p>&nbsp;</p>

Notepad.live是一个最简洁的云笔记本，您可以使用任意的域名后缀来自定义您的笔记地址。

<p>&nbsp;</p>

比如直接访问 <a href="http://notepad.live/zidingyi">http://notepad.live/zidingyi</a> 即可建立一个自定义地址的云笔记，特别适用于跨平台快速传输一些文本内容。

<p>&nbsp;</p>


本服务建立的原因是因为最简洁的云笔记本Notepad.cc停止运营，于是一群用户发起了这个公益项目。
<p>&nbsp;</p>
本服务（notepad.live）托管于EverTools基金会，保证不会关闭。
<p>&nbsp;</p>

<p>&nbsp;</p>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Zi Shi Ying Guang Gao -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-8948145024752924"
     data-ad-slot="1697399498"
     data-ad-format="auto"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>
</center>
</p>



<div style="display:none;">

<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1256937105'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/stat.php%3Fid%3D1256937105' type='text/javascript'%3E%3C/script%3E"));</script>

</div>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

<p>&nbsp;</p>

</div>
	
</body>
</html>
