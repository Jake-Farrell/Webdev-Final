<?php
include ("connect.php");
    $UserName = filter_input(INPUT_POST, 'NewUserName', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $UserEmail  = filter_input(INPUT_POST, 'NewUserEmail', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ;
    $UserPassWord = filter_input(INPUT_POST, 'NewUserPassword', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

        if (strlen($UserName) <= 0 || strlen($UserEmail) <= 0 || strlen($UserPassWord) <= 0){ 
        

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
                header('Location: UserTableView.php');
        }
        print_r($_POST);
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
<form method="post">
    <div id="wrapper">
        <div class="d-flex flex-column" id="content-wrapper">
            <div id="content">
                <nav class="navbar navbar-light navbar-expand bg-white shadow mb-4 topbar static-top">
                    <div class="container-fluid"></span></a><a href="profile.html"></a> </span>
                    <div class="input-group">   
                    <form class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                <div class="input-group-append"></div>
                                 <form class="form-inline d-none d-sm-inline-block mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                                    <div class="input-group"><input type="text" class="bg-light form-control border-0 small" placeholder="Search for Stock" />
                                    <div class="input-group-append"><button class="btn btn-primary py-0" type="button"><i class="fas fa-search"></i></button>
                                </div>
                                 </div>
                                </form>
                            </div>
                        </form>
                        </form><i class="fas fa-search"></i></button>
                        <ul class="nav navbar-nav flex-nowrap ml-auto">
                            <div class="d-none d-sm-block topbar-divider"></div>
                            <li class="nav-item dropdown no-arrow">
                                <div class="nav-item dropdown no-arrow"><input type="submit" value="Add user " name="SubmitChanges">
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
                <h3 class="text-dark mb-1">New User</h3>

                    <input type="text" id="NewUserName" name="NewUserName" value="<?php echo $UserName ?>">
                    <input type="text" id="NewUserEmail" name="NewUserEmail" value="<?php echo $UserEmail ?>"> 
                    <input type="text" id="NewUserPassword" name="NewUserPassword">
                   
         
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