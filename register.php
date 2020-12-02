<?php
    include ("connect.php");
    session_start();
    $UserName = filter_input(INPUT_POST, 'UserName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $UserEmail = filter_input(INPUT_POST, 'UserEmail', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $UserPassWord  = filter_input(INPUT_POST, 'UserPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $UserRepeatPassword = filter_input(INPUT_POST, 'UserRepeatPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $TuringTest = filter_input(INPUT_POST, 'TuringTest', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
    //var_dump($_SESSION['captcha']);

    $image = "<img src='data:image/png;base64,".base64_encode ($data)."'>";

    if($_SESSION['captcha'] = $TuringTest){

        if (strlen($UserName) <= 0 || strlen($UserEmail) <= 0 || strlen($UserPassWord) <= 0 || strlen($UserRepeatPassword) <= 0 || $UserRepeatPassword != $UserPassWord){ 

        }else{
            $query = "SELECT * FROM stockwebsiteusers WHERE  UserEmail = :UserEmail ";
            $statement = $db->prepare($query);
            $statement->bindValue(':UserEmail',$UserEmail);
            $statement->execute();
            $UserEmailCheck = $statement ->fetch();
    
            $query = "SELECT * FROM stockwebsiteusers WHERE  UserName = :UserName ";
            $statement = $db->prepare($query);
            $statement->bindValue(':UserName',$UserName);
            $statement->execute();
            $UserNameCheck = $statement ->fetch();
    
            if($UserNameCheck ){
                function_alert("Sorry but that user name has all ready be taken");
            }elseif($UserEmailCheck ){
                function_alert("Sorry but that email has all ready be registerd");
            }else{
                $query = "INSERT INTO stockwebsiteusers (UserName,UserPassword,UserEmail) VALUES (:UserName,:UserPassword,:UserEmail)";
                $statement = $db->prepare($query);
                $statement->bindValue(':UserName',$UserName);
                $statement->bindValue(':UserEmail',$UserEmail);
                $statement->bindValue(':UserPassword',password_hash($UserPassWord,PASSWORD_DEFAULT));
                $statement->execute();
                
                $LoginTime = time() + 86400;
                setcookie('UserName',$UserName,$LoginTime );
                setcookie('UserImage','Uploads/DefaultUser.png',$LoginTime);
                header('Location: index.php');
            }
        }  
    }else{
        function_alert("Please enter CAPTCHA again");
    }
    
    //print_r($_POST);

?>
<html>

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no" />
        <title>Register - Stock Watchers</title>
        <link rel="stylesheet" href="bootstrap.min.css">
    </head>
    
    <body class="bg-gradient-primary">
    <form method="POST">
        <div class="container">
            <div class="card shadow-lg o-hidden border-0 my-5">
                <div class="card-body p-0">
                    <div class="row">
                        <div class="col-lg-12 col-xl-12">
                            <div class="p-5">
                                <div class="text-center">
                                    <h4 class="text-dark mb-4">Create an Account!</h4>
                                </div>
                                <form method="POST">
                                <form class="user">
                                    <div class="form-group row">
                                    <form method="post">
                                        <div class="col-sm-6 col-xl-12 mb-3 mb-sm-0"><input type="text" class="form-control form-control-user" id="UserName" placeholder="User Name" name="UserName" value= "<?php echo $UserName ?>" /></div>
                                        </form>
                                    </div>
                                    <div class="form-group"><input type="text" class="form-control form-control-user" id="UserEmail" aria-describedby="emailHelp" placeholder="Email Address" name="UserEmail" value= "<?php echo $UserEmail ?>" /></div>
                                    <?php if($UserPassWord != $UserRepeatPassword):?>
                                    <p>Your Password dose not match</p>
                                    <?php endif ?>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0"><input type="password" class="form-control form-control-user" id="UserPassword" placeholder="Password" name="UserPassword" value= "<?php echo $UserPassWord ?>" /></div>
                                        <div class="col-sm-6"><input type="password" class="form-control form-control-user" id="UserRepeatPassword" placeholder="Repeat Password" name="UserRepeatPassword" value= "<?php echo $UserRepeatPassword ?>" /></div>
                                        <div class="col-sm-3"><?php echo $image; ?> <input class="form-control form-control-user"  id="exampleInputPassword" placeholder="Prove your a real person" name="TuringTest" value= ""></div>
                                    </div>
                                    <hr /><button class="btn btn-primary btn-block text-white btn-user" type="submit">Register Account</button>
                                        <hr />
                                </form>
                                </form>
                                <div class="text-center"><a class="small" href="forgot-password.php">Forgot Password?</a></div>
                                <div class="text-center"><a class="small" href="login.php">Already have an account? Login!</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </form>
    </body>
    
    </html>