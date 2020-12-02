<?php
include ("connect.php");
$login;
$UserImage;
$Found = 1;
$remover = '';
$Amount ;
$Region  ;
$Currency ;   

session_start();



if (isset($_Get['amountshone'])) 
{
$Amount = filter_input(INPUT_GET, 'amountshone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

}else if (isset($_POST['Amount'])) 
{
$Amount = filter_input(INPUT_POST,'Amount', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}else{
    $Amount = 'Amount';
}



if (isset($_POST['Region'])) 
{
$Region = filter_input(INPUT_POST, 'Region', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}else{
    $Region ='Region';
}
if (isset($_POST['Currency'])) 
{
$Currency = filter_input(INPUT_POST, 'Currency', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
}else{
    $Currency ='Currency';
}

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

$PageNum = 1;

$search = filter_input(INPUT_GET, 'SearchStockInput', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

$link ="https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=".$search."&apikey=D6P44CTM5Q64IL0X";

//$json = file_get_contents($link);
$json = file_get_contents('https://www.alphavantage.co/query?function=SYMBOL_SEARCH&keywords=tesco&apikey=demo');
$stocks = json_decode($json, true);

$startnum;
$endnum;

print_r(sizeof($stocks['bestMatches']))

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
                                    <div class="input-group"><input type="text" class="bg-light form-control border-0 small" name="SearchStockInput" placeholder="Search for Stock" value="<?php echo($_GET['SearchStockInput'])?>" />
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
                <form method="POST">
                <h3 class="text-dark mb-4">Heres what we found</h3>  
                <select name="Amount" id="Amount">    
                <option value="Amount"<?php if($Amount == 'Amount')echo " selected";?>>Amount Show</option>
                <option value="1"<?php if($Amount == '1')echo " selected";?>>1</option>
                <option value="5"<?php if($Amount == '5')echo " selected";?>>5</option>
                <option value="10"<?php if($Amount == '10')echo " selected";?>>10</option>
                </select>
               
                <select name="Region" id="Region">
                <option value="Region">Region</option>
                <?php foreach($stocks as $stock): ?>
                <?php foreach($stock as $stockss): ?>    
                <?php  if ($remover != $stockss["4. region"]):?>
                <option value="<?=$stockss["4. region"]?>"><?=$stockss["4. region"]?></option>
                <?php endif ?>
                <?php $remover = $stockss["4. region"]?>
                <?php endforeach ?>   
                <?php endforeach ?>
                </select>
                <select name="Currency" id="Currency">    
                <option value="Currency">Currency</option>
                <?php foreach($stocks as $stock): ?>
                <?php foreach($stock as $stockss): ?>
                <?php  if ($remover != $stockss["8. currency"]):?>
                <option value="<?=$stockss["8. currency"]?>"><?=$stockss["8. currency"]?></option>
                <?php endif ?>
                <?php $remover = $stockss["8. currency"]?>
                <?php endforeach ?>   
                <?php endforeach ?>
                </select>
                <button class="btn btn-primary py-0"  type="submit">search</button>
                </form>
                <br>
                <br>
                <br>
                <br>
                <br>
                <?php foreach($stocks as $stock): ?>
                    <?php  if (isset($_GET['page']) AND isset($_GET['amountshone'])):?>

                    <?php $PageNum = $_GET['page']  ?>
                    <?php $startnum = $PageNum * $_GET['amountshone'] ?>
                    <?php $endnum = ($PageNum * $_GET['amountshone']) + $_GET['amountshone'] ?>

                    <?php for($x = $startnum; $x < $endnum; $x++): ?>
                      
                    <a href="StockDetail.php?id=<?=$stocks['bestMatches'][$x]["1. symbol"]?>"><h1><?= $stocks['bestMatches'][$x]["2. name"]?></h1></a>
                    <h5>Symbol:  <?=$stocks['bestMatches'][$x]["1. symbol"]?></h5>
                    <h6>Operating region: <?=$stocks['bestMatches'][$x]["4. region"]?></h6>
                    <h6>Currency used: <?=$stocks['bestMatches'][$x]["8. currency"]?></h6>

                    <?php  if  (sizeof($stocks['bestMatches']) > $endnum ): ?>

                    <p> <a href="search.php?SearchStockInput=<?php echo($search)?>&page=<?php echo($PageNum) + 1 ?>&amountshone=1"><span>Next Page</span></a><a href="profile.php"></a> <a href="search.php?SearchStockInput=jake".<?php echo($search)?>><span>Previous Page</span></a><a href="profile.php"></a>  </p>
                    
                    <?php endif ?>
                    <br>
                    <?php endfor ?> 
                    <?php else :?>
                    <?php foreach($stock as $stockss): ?>
                        <form action="StockDetail.php" method="post">
                        <?php  if ($Region == $stockss["4. region"] || $Region == 'Region'):?>
                        <?php  if ($Currency == $stockss["8. currency"] || $Currency == 'Currency'):?>
                            <a href="StockDetail.php?id=<?=$stockss["1. symbol"]?>"><h1><?= $stockss["2. name"]?></h1></a>
                            <h5>Symbol:  <?=$stockss["1. symbol"]?></h5>
                            <h6>Operating region: <?=$stockss["4. region"]?></h6>
                            <h6>Currency used: <?=$stockss["8. currency"]?></h6>
                            <br>
                            <?php  if ($Found == $Amount):?>
                                
                            <?php  if  (sizeof($stocks['bestMatches']) > $Amount ): ?>
                            <p> <a href="search.php?SearchStockInput=<?php echo($search)?>&page=<?php echo($PageNum) ?>&amountshone=<?php echo($Amount)?>"><span>Next Page</span></a><a href="profile.php"></a> <a href="search.php?SearchStockInput=jake".<?php echo($search)?>><span>Previous Page</span></a><a href="profile.php"></a>  </p>
                            <?php  break;?>
                            <?php endif ?>
                            <?php endif ?>
                            <?php $Found++ ?>
                        </form>
                        <?php endif ?>
                        <?php endif ?>
                <?php endforeach ?> 
                <?php endif ?> 
            <?php endforeach ?>
           

                                
        </div>
        <footer class="bg-white sticky-footer">
            <div class="container my-auto">
                <div class="text-center my-auto copyright"><span>Copyright © Stock Watchers 2020</span></div>
            </div>
        </footer>
    </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a></div>
</body>

</html>