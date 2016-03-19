<?php

$URL = "http://notepad.live";

// Subfolder to output user content.
$FOLDER = "user";

//密码标识前缀
$pw_tag = "**@_#PassWord**";

function sanitize_file_name($filename) {
    // Original function borrowed from Wordpress.
    //$special_chars = array("?", "[", "]", "/", "\\", "=", "<", ">", ":", ";", ",", "'", "\"", "&", "$", "#", "*", "(", ")", "|", "~", "`", "!", "{", "}", ".");
    $filename = str_replace($special_chars, '', $filename);
    $filename = preg_replace('/[\s-]+/', '-', $filename);
    $filename = trim($filename, '.-_');
    return $filename;
}


if (!isset($_GET["f"])) {
    // User has not specified a name, get one and refresh.

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


$file_head = file_get_contents($path,FALSE,NULL,0,47);



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php print $name; ?> - User Center - Simple Cloud Notepad</title>
	  <link rel="stylesheet" href="/lib/master.css" type="text/css" media="screen" charset="utf-8" />
  <link rel="stylesheet" href="/lib/print.css" type="text/css" media="print" charset="utf-8" />
  <link rel="stylesheet" href="/lib/browser.css" type="text/css" media="screen" charset="utf-8" /> 
	<link rel="shortcut icon" type="image/gif" href="favicon.gif" />
	<link href="lib/normalize.css" rel="stylesheet" />
    <link href="lib/styles.css" rel="stylesheet" />
	<style>
	
	pre{
white-space: pre-wrap;       
white-space: -moz-pre-wrap;  
white-space: -pre-wrap;      
white-space: -o-pre-wrap;    
word-wrap: break-word;       
}
	
	</style>
	
</head>
<body>
    <div class="container">
	
	<script type="text/javascript" src="lib/common.js"></script> 
<script type="text/javascript" src="lib/text.js"></script> 


	
        <div class="content" style="background-color:#fff;width:80%;margin:30px auto;height:auto;color:#000;">
		
		<div style="padding:5px;">
		
		
        <?php echo $_GET["f"]; ?> 您好！这里是用户中心:
		
		<br><br>
		
		我收藏的云笔记：
		
		<br><br>
		
		<style>
		
		.listnote li{
			
			line-height:50px;
			font-size:30px;
			
			
		}
		
		
		</style>
		
		
		<ul class="listnote">
		
		
		<li><a href="/listnote" target="_blank">listnote测试页面</a></li>
		
		
		
		<?php 
            
				
				
				if (substr($file_head,0,15) == $pw_tag ){
					//echo "<pre>";
				print file_get_contents($path,FALSE,NULL,47);
					//echo "</pre>";
				}
				
				else {
				//echo "这里是文件读取";
				//echo $path;
                print file_get_contents($path);
				//echo "</pre>";
				
				}
           
        ?>
		
		
		</ul>
		
		

</div>

</div>

<p>&nbsp;</p>


<div id="controls">

      
<span class="bubble_wrapper" onclick="just_clicked_bubble = 'change_url';">

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
&copy; 2015 EverTools软件基金会 .  交流QQ群：229593086

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

</div>

<div style="display:none;">

<script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1256937105'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/stat.php%3Fid%3D1256937105' type='text/javascript'%3E%3C/script%3E"));</script>

</div>
	
</body>
</html>