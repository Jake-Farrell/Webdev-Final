<?php
    include ("connect.php");
    $UserEmail = filter_input(INPUT_POST, 'UserEmail', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $UserPassword = filter_input(INPUT_POST, 'UserPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $TuringTest = filter_input(INPUT_POST, 'TuringTest', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $captcha = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz';
    $captcha = substr(str_shuffle($captcha),0,6);
    $_SESSION['captcha'] = $captcha;
    $font =  "C:/xampp/htdocs/Final/Arial.ttf";
   
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
    //var_dump($_SESSION['captcha']);

    
    
    if( $_SESSION['captcha'] = $TuringTest){
       
        if (!($UserPassword)  || !($UserEmail))
        { 

        }else{
            $query = "SELECT * FROM stockwebsiteusers WHERE  UserEmail = :UserEmail ";
            $statement = $db->prepare($query);
            $statement->bindValue(':UserEmail',$UserEmail);
            $statement->execute();
            $row = $statement ->fetch();
            if(!$row){
                  function_alert("Email or password ot Found");
            }
            else if(password_verify($UserPassword,$row['UserPassword'])){
                $TwoHours = time() + 7200;
                setcookie('UserName',$row['UserName'],$TwoHours);
                setcookie('UserImage',$row['UserImage'],$TwoHours);
                header('Location: index.php');
            }else{
    
            }
            //print_r($row );
            //echo password_hash($UserPassword,PASSWORD_DEFAULT);
        }

    }elseif(strlen($TuringTest) > 0){
        function_alert("Please enter CAPTCHA again");
    }else{

    }


    //print_r($_POST);
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Login - Stock Watchers</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9 col-lg-12 col-xl-10">
                <div class="card shadow-lg o-hidden border-0 my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 offset-lg-3 offset-xl-3">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h4 class="text-dark mb-4">Welcome Back!</h4>
                                    </div>
                                    <form method="POST">
                                    <form class="user">
                                        <div class="form-group"><input class="form-control form-control-user" type="email" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Email Address..." name="UserEmail" value= "<?=  $UserEmail ?>"></div>
                                        <div class="form-group"><input class="form-control form-control-user" type="password" id="exampleInputPassword" placeholder="Password" name="UserPassword" value= ""></div>
                                        <div class="form-group"><?php echo $image; ?> <input class="form-control form-control-user"  id="exampleInputPassword" placeholder="Prove your a real person" name="TuringTest" value= ""></div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <div class="form-check"><input class="form-check-input custom-control-input" type="checkbox" id="formCheck-1"><label class="form-check-label custom-control-label" for="formCheck-1">Remember Me</label></div>
                                            </div>
                                        </div><button class="btn btn-primary btn-block text-white btn-user" type="submit">Login</button>
                                        
                                        <hr>
                                    </form>
                                    </form>
                                    <div class="text-center"><a class="small" href="forgot-password.php">Forgot Password?</a></div>
                                    <div class="text-center"><a class="small" href="register.php">Create an Account!</a></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>