<?php  
    $captcha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    $captcha = substr(str_shuffle($captcha),0,6);
    $_SESSION['captcha'] = $captcha;
    $font =  "C:\Windows\Fonts\arial.ttf";
    $font_Size = 30;
    $img_width = 210;
    $img_height = 40;
    $im = imagecreatetruecolor($img_width ,  $img_height);
    $text_color = imagecolorallocate($im, 255, 255, 255);

    imagettftext($im, $font_Size,0,15,30, $text_color, $font , $captcha);
    ob_start ();

    imagepng($im);
    imagedestroy($im);

    $data = ob_get_contents ();

    ob_end_clean ();

    $image = "<img src='data:image/png;base64,".base64_encode ($data)."'>";
    var_dump($_SESSION['captcha']);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0   Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>

<?php echo $image; ?> 

</body>
</html>