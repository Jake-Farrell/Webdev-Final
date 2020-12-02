<?php
include ("connect.php");
$login;
$UserImage;
$Found = 1;

session_start();

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
$search = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$Newcommont = filter_input(INPUT_POST, 'NewComment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$link ="https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=".$search."&apikey=D6P44CTM5Q64IL0X";
//$json = file_get_contents($link);
$json = file_get_contents('https://www.alphavantage.co/query?function=GLOBAL_QUOTE&symbol=IBM&apikey=demo');
$stocks = json_decode($json, true);
//print_r($stocks["Global Quote"]);

    $query = "SELECT * FROM stockwebsitestocks WHERE  StockSymbol = :StockSymbol ";
    $statement = $db->prepare($query);
    $statement->bindValue(':StockSymbol',$stocks["Global Quote"]["01. symbol"]);
    $statement->execute();
    $row = $statement ->fetch();
    if(!$row){
        $query = "INSERT INTO stockwebsitestocks (StockSymbol,StockOpen,StockHigh,StockLow,StockPrice,StockVolume) VALUES (:StockSymbol,:StockOpen,:StockHigh,:StockLow,:StockPrice,:StockVolume)";
        $statement = $db->prepare($query);
        $statement->bindValue(':StockSymbol',$stocks["Global Quote"]["01. symbol"]);
        $statement->bindValue(':StockOpen',$stocks["Global Quote"]["02. open"]);
        $statement->bindValue(':StockHigh',$stocks["Global Quote"]["03. high"]);
        $statement->bindValue(':StockLow',$stocks["Global Quote"]["04. low"]);
        $statement->bindValue(':StockPrice',$stocks["Global Quote"]["05. price"]);
        $statement->bindValue(':StockVolume',$stocks["Global Quote"]["06. volume"]);
        $statement->execute();
    }
    else {
        $query = "INSERT INTO stockwebsitestocks (StockOpen,StockHigh,StockLow,StockPrice,StockVolume) VALUES (?,?,?,?,?)  WHERE StockSymbol = ?";
        $statement = $db->prepare($query);
        $statement->execute([$stocks["Global Quote"]["01. symbol"],$stocks["Global Quote"]["02. open"],$stocks["Global Quote"]["03. high"],$stocks["Global Quote"]
        ["04. low"],$stocks["Global Quote"]["05. price"],$stocks["Global Quote"]["06. volume"]]);
    }

$AllComments = "SELECT * FROM stockwebsitecomments WHERE StockSymbol = :StockSymbol ORDER BY PostDate ASC";
$statement = $db->prepare($AllComments); 
$statement->bindValue(':StockSymbol',$stocks["Global Quote"]["01. symbol"]);
$statement->execute(); 
$AllComment= $statement->fetchAll();

if (isset($_POST['NewComment'])) 
{
$AllComments ="INSERT INTO stockwebsitecomments (StockSymbol,PostComment,UserName) VALUES (:StockSymbol,:PostComment,:UserName)";
$statement = $db->prepare($AllComments);
$statement->bindValue(':StockSymbol',$stocks["Global Quote"]["01. symbol"]);
$statement->bindValue(':PostComment',$Newcommont);
$statement->bindValue(':UserName',$login);
$statement->execute();
header('Location: StockDetail.php');    
}
//echo $stocks["Global Quote"]["01. symbol"]

if (isset($_GET['SearchStockInput']) )
{
    $search = filter_input(INPUT_GET, 'SearchStockInput', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if(strlen($search) > 0){
        header('Location: search.php?SearchStockInput='.$search);
    }
    
}

//$jake = $_POST['Remove'];

//print_r(array_keys($_POST));



if (in_array('Remove',$_POST)) {
    $tes = key($_POST);

    $query = "DELETE FROM stockwebsitecomments WHERE PostID =  :PostID ";
    $statement = $db->prepare($query);
    $statement->bindValue(':PostID', key($_POST));  
    $statement->execute();
    header('Location: StockDetail.php');    
}

$tes ;

if (in_array('Edit',$_POST)) {
    $tes = key($_POST);
}

    if (in_array('Submit Edit',$_POST)  ) 
    {
        $tes = key($_POST);
        print_r(key($_POST));
        $query = "UPDATE stockwebsitecomments SET PostComment = :PostComment WHERE PostID = :PostID";
        $statement = $db->prepare($query);
        $statement->bindValue(':PostID',$tes);
        $statement->bindValue(':PostComment', $Newcommont);    
        $statement->execute();
        header('Location: StockDetail.php'); 
    }



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
                        <?php elseif($login == ''):?>
                        <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="login.php"><span class="d-none d-lg-inline mr-2 text-gray-600 small">Login</span></a>
                        <?php elseif($login != '' && $login != 'Admin'):?>
                        <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link" data-toggle="dropdown" aria-expanded="false" href="profile.php"><span class="d-none d-lg-inline mr-2 text-gray-600 small">Welcome Back <?php echo $login ?></span></span><img class="border rounded-circle img-profile" src="<?php echo $UserImage ?>"></a>
                        <?php endif ?>       
                    </div>
                    </li>
                    </ul>
            </div>
            </nav>
            <form method="Post">
            <div class="container-fluid">
                <h3 class="text-dark mb-4"><?php echo $stocks["Global Quote"]["01. symbol"] ?></h3>
                    <h6>Stock Open Value <?=$stocks["Global Quote"]["02. open"]?></h6>
                    <h6>Stock High: <?=$stocks["Global Quote"]["03. high"]?></h6>
                    <h6>Stock Low: <?=$stocks["Global Quote"]["04. low"]?></h6>
                    <h6>Stock Price: <?=$stocks["Global Quote"]["05. price"]?></h6>
                    <h6>Stock Volume: <?=$stocks["Global Quote"]["06. volume"]?></h6>
                    <br>

        <?php if($login != '' ):?>
            <input type="submit"  name="Add" value="Add comment">
        <a class="btn btn-primary btn-sm d-none d-sm-inline-block" type="submit" href="#"><i class="fas fa-download fa-sm text-white-50"></i> Add Stock to watch list</a>
            <?php if (isset($_POST['Add']) ):?>
            <br>
            <br>
            <div class="col-sm-3"><input type="text" class="form-control form-control-user" id="NewComment" name="NewComment" value= "" /></div>
            <br>
            <input type="submit"  name="Add" value="submit commont">
            <?php endif ?>  
        <?php endif ?> 
        <br>
      
        <?php foreach($AllComment as $AllComments): ?>
            <br>
            <?php if($login == 'Admin'):?>
                <p> User <?= $AllComments["UserName"]?> Posted this on <?= $AllComments["PostDate"]?></p>
                <p> <?= $AllComments["PostComment"]?></p>
                <input type="submit" value="Remove" name="<?=$AllComments['PostID']?>">
                <input type="submit" value="Edit" name="<?=$AllComments['PostID']?>">
            <?php elseif($login != '' && $login != 'Admin'):?>
                <?php if($login == $AllComments["UserName"]):?>
                    <p> User <?= $AllComments["UserName"]?> Posted this on <?= $AllComments["PostDate"]?></p>
                    <p> <?= $AllComments["PostComment"]?></p>
                    <input type="submit" value="Remove" name="<?=$AllComments['PostID']?>">
                    <input type="submit" value="Edit" name="<?=$AllComments['PostID']?>">
                <?php if (in_array('Edit',$_POST) ):?>
                    <?php if ($AllComments['PostID'] == key($_POST)):?>
                        <div class="col-sm-3"><input type="text" class="form-control form-control-user" id="NewComment" name="NewComment" value= "<?= $AllComments["PostComment"]?>" /></div>
                        <input type="submit"  name="<?=key($_POST)?>" value="Submit Edit">
                    <?php endif ?>
                <?php endif ?>  
                <br>
            <?php else :?>
                <p> User <?= $AllComments["UserName"]?> Posted this on <?= $AllComments["PostDate"]?></p>
                <p> <?= $AllComments["PostComment"]?></p>
            <?php endif ?>  
            <?php endif ?> 
            </tr>
        <?php endforeach ?>
    
        </div>
        </form>
        <footer class="bg-white sticky-footer">
            <div class="container my-auto">
                <div class="text-center my-auto copyright"><span>Copyright © Stock Watchers 2020</span></div>
            </div>
        </footer>
  
</body>

</html>