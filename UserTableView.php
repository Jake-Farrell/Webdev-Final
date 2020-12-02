<?php
include ("connect.php");
require 'php-image-resize-master\lib\ImageResize.php';
require 'php-image-resize-master\lib\ImageResizeException.php';
$logout ;


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

 if ($login != "admin"){
    header('Location: index.php');    
    exit;
 }


$Allusers = "SELECT  UserName AS UserName ,UserEmail,UserImage FROM stockwebsiteusers order by UserName";
$statement = $db->prepare($Allusers); 
$statement->execute(); 
$Allusers= $statement->fetchAll();




if (isset($_POST['RemoveUsers'])) 
{
$selected  = $_POST['RemoveUsers'];
}else{
    $selected ='';
}

if (isset($_POST['RemoveUsers'])) {

    $query = "DELETE FROM stockwebsiteusers WHERE UserName =  '$selected'";
    $statement->bindValue(':UserName', $selected);  
    $statement = $db->prepare($query);
    $statement->execute();
    header('Location: index.php'); 
    exit;   
}

//$query     = "UPDATE stockwebsiteusers SET UserName = :UserName, UserEmail = :UserEmail WHERE UserName =  '$UserName'";
//$statement = $db->prepare($query);
//$statement->bindValue(':UserName', $NewUserName);        
//$statement->bindValue(':UserEmail', $NewUserEmail);
//$statement->execute();
//header('Location: UserTableView.php');

if (isset($_POST['UserRemoveImg']) )
{
    $query = "UPDATE stockwebsiteusers SET UserImage = :UserImage WHERE UserName = :UserName";
    $statement = $db->prepare($query);
    $statement->bindValue(':UserName',$login);
    $statement->bindValue(':UserImage','DefaultUser.png');
    $statement->execute();
    setcookie("UserImage", "", $endcookie);  
    setcookie('UserImage',"Uploads/DefaultUser.png");
    unlink("Uploads/$login.png");
    //header('Location: index.php');

}



print_r($selected);

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Blank Page - Stock Watchers</title>
    <link rel="stylesheet" href="bootstrap.min.css">

</head>
<?php if($login == 'Admin'):?>
<body id="page-top">
<form method="Post">
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
                <h3 class="text-dark mb-1">Users</h3>  <div class="container-fluid"><a href="UserTableAdd.php"><span><input type="button" value="Add New user" name="submit"></span></a><a href="profile.html"></a> 
                <form method="Post">
                <?php foreach($Allusers as $AllUsers): ?>
                    <tr>
                   <td> <?=$AllUsers['UserName']?> <?php echo "&nbsp"; ?> </td> 
                   <td> <?=$AllUsers['UserEmail']?> <?php echo "&nbsp"; ?> </td>
                 
                   <p> 
                   <form method="Post">
                    <img class="border rounded-circle img-profile" src="<?=$AllUsers['UserImage']?>"> 
               
                   <input type="submit" value="<?=$AllUsers['UserName']?>" name="RemoveUsers"><label>remove user</label>
                   <button class="btn btn-primary btn-sm" type="submit" name="UserRemoveImg" >Remove Image</button>
                   </form>
                   <form action="EditBlog.php" method="get">
                   <a href="UserTableEdit.php?UserName=<?=$AllUsers['UserName']?>">Change user</a>
                   </form>
                </p>
                </tr>
            <?php endforeach ?>
                </form>
            </div>
        </div>
        <footer class="bg-white sticky-footer">
            <div class="container my-auto">
                <div class="text-center my-auto copyright"><span>Copyright © Stock Watchers 2020</span></div>
            </div>
        </footer>
        </div>
    </div>
</form>
</body>
<?php endif ?>    
</html>