<?php
include ("connect.php");
require 'php-image-resize-master\lib\ImageResize.php';
require 'php-image-resize-master\lib\ImageResizeException.php';
$logout ;

   $endcookie = time() - 10000;
   $TwoHours = time() + 7200;

    if (isset($_COOKIE['UserName'])) 
    {
    $login  = $_COOKIE['UserName'];
    }else{
    $login ='';
    }
    if (isset($_COOKIE['UserImage'])) 
    {
    $UserImage  = $_COOKIE['UserImage'];
    }else{
    $UserImage ='';
    }


    if (isset($_POST['UserLogout']) )
    {
        $logout = '';
      
        setcookie("UserName", "", $endcookie);
        setcookie("UserImage", "", $endcookie);  
        header('Location: index.php');
    }

    if (isset($_POST['ChangePhoto']) )
    {
        header('Location: index.php');
    }

    if (isset($_POST['UserRemoveImg']) )
    {
        $query = "UPDATE stockwebsiteusers SET UserImage = :UserImage WHERE UserName = :UserName";
        $statement = $db->prepare($query);
        $statement->bindValue(':UserName',$login);
        $statement->bindValue(':UserImage','DefaultUser.png');
        $statement->execute();
        unlink("Uploads/$login.png");
        setcookie("UserImage", "", $endcookie);  
        setcookie('UserImage',"Uploads/DefaultUser.png");
        header('Location: index.php');
    }

        
    if (isset($_FILES['fileToUpload']['name'])){
        // Where the file is going to be stored
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
         $target_dir = "Uploads/";
         $file = $_FILES['fileToUpload']['name'];
         $path = pathinfo($file);
         $filename = $path['filename'];
         $ext = $path['extension'];
         $temp_name = $_FILES['fileToUpload']['tmp_name'];
         $path_filename_ext = $target_dir.$filename.".".$ext;
    
        if(array_search($ext,$allowed_file_extensions,true)){
            $image = new \Gumlet\ImageResize($temp_name);
            $image->resize(50,50);
            $image->save("Uploads/$login.png");
            $newImage = "Uploads/$login.png";
            $query = "UPDATE stockwebsiteusers SET UserImage = :UserImage WHERE UserName = :UserName";
            $statement = $db->prepare($query);
            $statement->bindValue(':UserName',$login);
            $statement->bindValue(':UserImage',$newImage);
            $statement->execute();
            setcookie("UserImage", "", $endcookie);  
      
            setcookie('UserImage',"Uploads/$login.png",$TwoHours);
            header('Location: index.php');
            
        }else{
            
            function_alert("wrong file type");
        }
    }

    if (isset($_COOKIE['UserImage'])) 
{
$UserImage  = $_COOKIE['UserImage'];
}else{
    $UserImage ='';
}


if (isset($_GET['SearchStockInput']) )
{
    $search = filter_input(INPUT_GET, 'SearchStockInput', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(strlen($search) > 0){
        header('Location: search.php?SearchStockInput='.$search);
    }
    
}
   //print_r($_COOKIE)
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Profile - Stock Watchers</title>
    <link rel="stylesheet" href="bootstrap.min.css">
</head>

<body id="page-top">

    <div id="wrapper">
        <div class="d-flex flex-column" id="content-wrapper">
        <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid">
                    <?php if($login != ''):?>
                    <a href="index.php"><span>Home</span></a><a href="profile.php"></a><a href="index.php"><span style="padding: 30px;">My Watch List</span><a href="profile.php"><span>Profile</span></a>
                    <?php endif ?>   
                        <form class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group" method="POST">
                                 <form class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" >
                                    <div class="input-group"><input type="text" class="bg-light form-control border-0 small" name="SearchStockInput" placeholder="Search for Stock" value="" />
                                    <div class="input-group-append"><button class="btn btn-primary py-0"  type="submit"></button></div>
                                 </div>
                                 </form>
                            </div>
                        </form>
                        <ul class="nav navbar-nav flex-nowrap ml-auto">
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <?php if($login == 'Admin'):?>
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="UserTableView.php"><span class="d-none d-lg-inline mr-2 text-gray-600 small">View Users</span></a>
                                <?php endif ?>
                                <?php if($login == ''):?>
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="login.php"><span class="d-none d-lg-inline mr-2 text-gray-600 small">Login</span></a>
                                <?php endif ?>
                                <?php if($login != '' && $login != 'Admin'):?>
                                <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="profile.php"><span class="d-none d-lg-inline mr-2 text-gray-600 small">Welcome Back <?php echo $login ?></span></span><img class="border rounded-circle img-profile" src="<?php echo $UserImage ?>"></a>
                                <?php endif ?>       
                    </div>
                    </li>
                    </ul>
            </div>
            </nav>
            <div class="container-fluid">
                <h3 class="text-dark mb-4">Profile</h3>
                <div class="row mb-3">
                    <div class="col-lg-12 col-xl-12">
                        <div class="card mb-3">
                        <form method="POST" enctype="multipart/form-data">
                            <div class="card-body text-center shadow"><img class="rounded-circle mb-3 mt-4" src="<?php echo $UserImage ?>" width="160" height="160">
                          
                           
                                 <input class="btn btn-primary btn-sm"  name="fileToUpload" type="file" value="Change Image"/>
                                 <input type="submit" value="Upload Image" name="submit">
                                </div>
                        </form>
                            </div>
                            </form>
                            <div class="card-header py-3">
                                <p class="text-primary m-0 font-weight-bold">User Settings</p>
                            </div>
                            <form method="POST" enctype="multipart/form-data">
                            <div class="card-body">
                                <form>
                                    <div class="form-row">
                                        <div class="col">
                                            <div class="form-group"><label for="username"><strong>Username</strong></label><input class="form-control" type="text" placeholder="user.name" name="username"></div>
                                        </div>
                                        <div class="col">
                                            <div class="form-group"><label for="email"><strong>Email Address</strong></label><input class="form-control" type="email" placeholder="user@example.com" name="email"></div>
                                        </div>
                                    </div>

                                    <div class="form-group"> <button class="btn btn-primary btn-sm" type="submit" name="UserLogout" >Logout</button>
                                    <?php if($UserImage != 'Uploads/DefaultUser.png'):?>
                                    <button class="btn btn-primary btn-sm" type="submit" name="UserRemoveImg" >Remove Image</button></div>
                                    <?php endif ?>
                                   
                            </div>
                            </form>
                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card shadow mb-5"></div>
            </div>
        </div>
        <footer class="bg-white sticky-footer">
            <div class="container my-auto">
                <div class="text-center my-auto copyright"><span>Copyright © Stock Watchers 2020</span></div>
            </div>
        </footer>
    </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>

</body>

</html>