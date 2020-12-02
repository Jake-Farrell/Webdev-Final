<?php
 include ("connect.php");

$login;
$UserImage;
session_start();
$search = filter_input(INPUT_POST, 'SearchStockInput', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
if (isset($_GET['SearchStockInput']) )
{
    $search = filter_input(INPUT_GET, 'SearchStockInput', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(strlen($search) > 0){
        header('Location: search.php?SearchStockInput='.$search);
    }
    
}

$query = "SELECT * FROM stockwebsitefrontpagestory WHERE  storyID = :storyID ";
$statement = $db->prepare($query);
$statement->bindValue(':storyID','1');
$statement->execute();
$row = $statement ->fetch();



$story = $row['story'];

if (isset( $_POST['NewStory'])) 
{
    $NewStory  = $_POST['NewStory'];
}else{
    $NewStory ='';
}


$currentDateTime = date('Y-m-d ');

if (isset($_POST['SubmitEdit']) ){
    
    $AllComments ="UPDATE stockwebsitefrontpagestory SET story = :story where storyID = :storyID";
    $statement = $db->prepare($AllComments);
    $statement->bindValue(':storyID','1');
    $statement->bindValue(':story',$NewStory);
    $statement->execute();
    header('Location: index.php');  
}

//print_r($_POST);

?> 
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <script src="https://cdn.tiny.cloud/1/ohf63s02n7tysywmjsxyiy9i4ecudd5hvamp3i0g3vmzkob6/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <title>Dashboard - Stock Watchers</title>
    <script>
      tinymce.init({
        selector: '#myEditor',
        width: '100%',
        height: 400
        
      });
    </script>
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
               
            <div class="row">
           <form  method="POST">
                    
                    <div class="card shadow mb-4"></div>
                </div>
                <div class="col">
                    <div class="row">
                    
                        <textarea = id="myEditor" name="NewStory">
                        <?php echo $story?>
                        </textarea>

                        <p></p>
                        <button class="btn btn-primary btn-block text-white btn-user" type="submit" value="SubmitEdit" name="SubmitEdit" >Submit Edit</button>
                        </form>
                       
              
                        </div>
                    </div>
                </div>
            </div>
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