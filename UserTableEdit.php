<?php
include ("connect.php");
require 'php-image-resize-master\lib\ImageResize.php';
require 'php-image-resize-master\lib\ImageResizeException.php';

$UserName = $_GET['UserName'];

$Allusers = "SELECT  * FROM stockwebsiteusers WHERE UserName = '$UserName'"  ;
$statement = $db->prepare($Allusers); 
$statement->execute(); 
$Allusers= $statement->fetchAll();

$NewUserName  = filter_input(INPUT_POST, 'NewUserName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$NewUserEmail = filter_input(INPUT_POST, 'NewUserEmail', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

if (strlen($NewUserName) <= 0 || strlen($NewUserEmail) <= 0){

}else{
    $query     = "UPDATE stockwebsiteusers SET UserName = :UserName, UserEmail = :UserEmail WHERE UserName =  '$UserName'";
    $statement = $db->prepare($query);
    $statement->bindValue(':UserName', $NewUserName);        
    $statement->bindValue(':UserEmail', $NewUserEmail);
    $statement->execute();
    header('Location: UserTableView.php');
}



?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Blank Page - Stock Watchers</title>
    <link rel="stylesheet" href="bootstrap.min.css">

</head>

<body id="page-top">
<form method="Post">
    <div id="wrapper">
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"></span></a><a href="profile.html"></a> </span>
                        <form class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                            <div class="input-group">
                            <form class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                             <div class="input-group"><input type="text" class="bg-light form-control border-0 small" placeholder="Search for Stock" />
                                    <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button></div>
                                 </div>
                                </form>
                                <div class="input-group-append"></div>
                            </div>
                        
                        </form><i class="fas fa-search"></i></button>
                        <ul class="nav navbar-nav flex-nowrap ml-auto">
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><input type="submit" value="Submit Changes" name="SubmitChanges">
                                    <div
                                        class="dropdown-menu shadow dropdown-menu-right animated--grow-in"><a class="dropdown-item" href="#"><i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Profile</a><a class="dropdown-item" href="#"><i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Settings</a>
                                        <a
                                            class="dropdown-item" href="#"><i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Activity log</a>
                                            <div class="dropdown-divider"></div><a class="dropdown-item" href="#"><i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>&nbsp;Logout</a></div>
                    </div>
                    </li>
                    </ul>
            </div>
            </nav>
            <div class="container-fluid">
                <h3 class="text-dark mb-1">User</h3>

                <?php foreach($Allusers as $AllUsers): ?>
                    <input type="text" id="NewUserName" name="NewUserName" value="<?=$AllUsers['UserName']?>">
                    <input type="text" id="NewUserEmail" name="NewUserEmail" value="<?=$AllUsers['UserEmail']?>">
                    <p> <img class="border rounded-circle img-profile" src="<?=$AllUsers['UserImage']?>"></p>
            <?php endforeach ?>
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

</html>