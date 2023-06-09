<?php 
    include "config.php";
    session_start();
    //PAGINATION
    if (isset($_GET['pageno'])) {
        $pageNo = $_GET['pageno'];
    }
    else{
        $pageNo = 1;
    }
    $limit = 5;
    $offset = ($pageNo-1) * $limit;
    $sql = "SELECT COUNT(*) FROM `user_details`";
    $result = $connection->query($sql);
    $totalRows = mysqli_fetch_array($result)[0];
    $totalPages = ceil($totalRows / $limit);
    $sql = "SELECT * FROM user_details LIMIT $offset, $limit";
    $result = $connection->query($sql);

    //SORTING
    $orderBy = "id";
    $order = "asc";
    if(isset($_GET['orderBy']) && isset($_GET['order']))
    {
        if(!empty($_GET["orderBy"])){
            $orderBy = $_GET["orderBy"];
        }
        if(!empty($_GET["order"])){
            $order = $_GET["order"];
        }

        $nameNextOrder = 'asc';
        $emailNextOrder = 'asc';
        $mobileNextOrder = 'asc';
        $addressNextOrder = 'asc';
        $stateNextOrder = 'asc';
        $genderNextOrder = 'asc';
        $messageNextOrder = 'asc';

        if($orderBy == "name" and $order == "asc"){
            $nameNextOrder = 'desc';
        }
        if($orderBy == "email" and $order == "asc"){
            $emailNextOrder = 'desc';
        }
        if($orderBy == "mobile" and $order == "asc"){
            $mobileNextOrder = 'desc';
        }
        if($orderBy == "address" and $order == "asc"){
            $addressNextOrder = 'desc';
        }
        if($orderBy == "state" and $order == "asc"){
            $stateNextOrder = 'desc';
        }
        if($orderBy == "gender" and $order == "asc"){
            $genderNextOrder = 'desc';
        }
        if($orderBy == "message" and $order == "asc"){
            $messageNextOrder = 'desc';
        }
    
        $sql = "SELECT * FROM `user_details` ORDER BY ".$orderBy." ".$order." LIMIT $offset, $limit";
        $result = $connection->query($sql);       
    }

    //SEARCH
    if(($_POST['search-input']))
    {   
        $searchBy = $_POST['searchBy'];
        $value = $_POST['search-input'];    
        $sql = "SELECT * FROM `user_details` WHERE ".$searchBy." LIKE '%".$value."%'";
        $result = $connection->query($sql);  
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>READ</title>
    <style>
        * {
            box-sizing: border-box;
            margin:0px;
            font-family: Inter,sans-serif,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,
                    "Helvetica Neue",Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol";
        }
        nav{
        display:flex;
        justify-content:space-between;
        align-items:center;
        min-height: 12vh;
        background-color:teal;
        font-family: "Montserrat", sans-serif;
        } 
        .navbar{
            color:white;
            letter-spacing:5px;
            font-size:35px;
            margin-left:20px
        }
        .nav-link{
            display:flex;
            justify-content:flex-end;
            width:18%;
            margin-right: 5px;
        }
        .nav-link li{
            list-style:none;
        }
        .nav-link a{
            color:white;
            text-decoration:none;
            letter-spacing:3px;
            font-weight: bold;
            font-size: 20px;
            padding: 14px 16px;
        }
        .nav-link a:hover:not(.active) {
            background-color: lightseagreen;
        }
    
        .nav-link li a.active {
            background-color: #4caf50;
        }
        .container{
            background-color: #F4F5F4;
            color:rgba(58, 53, 65, 0.87);
            height: 100vh;
        }
        .container p {
            font-size: 35px;
            font-weight: 600;
            letter-spacing: 3px;
            text-align: center;
            padding-top: 15px;
            margin-bottom: 15px;
        }
        form{
            height:40px;
            margin: 0 75px 15px 75px;
        }
        form input{
            padding:5px 10px;
            font-size:17px;
            border:1px solid #ddd;
            border-radius:30px;
            background: white;
        }
        form input:focus {
            outline: none;
            border:2px solid #9155FD;
        }
        form button,select{
            padding:5px;
            font-size:15px;
        }
        form button{
            float:right;
        }
        form button a{
            text-decoration: none;
            color: black;
        }
        table{
            margin:auto;
            width: 95%;
            box-shadow:0px 2px 10px 0px rgb(58 53 65 / 10%);
            border: none;
            border-collapse: collapse;
        }
        td,th{
            border-collapse: collapse;
            border-bottom: 1px solid #ddd;
        }
        th {
            font-size:22px;
            text-align:left;
            padding:15px 5px;
        }
        th a{
            text-decoration:none;
            color:rgba(58, 53, 65, 0.87);
        }
        .table-heading{
            height:40px;
            font-size: 20px;
        }
        .table-data{
            text-align:center;

        }
        td{
            font-size:17px;
            padding:7px 5px;
            text-align: left;
        }
        .mess{
            outline:none;
            
            max-width:25px;
        }
        .edit-btn{
            font-size: 18px;
            background-color:green;
            border:0;
            border-radius:5px;
            padding: 3px 10px;
            color:white;
        }
        .del-btn{
            border:0;
            font-size:18px;
            background-color:rgba(220, 0, 0, 1);
            border-radius:5px;
            padding: 3px 10px;
            color:white;
        }
        .btn{
            text-decoration:none;
            color:white;
        }
        .page-num{
            margin-top:30px;
        }
        .pagination{
            display:flex;
            justify-content:center;  
            height: 40px; 
        }
        .pagination li{
            list-style:none;
            margin-right:10px;   
        }
        .pagination li a{
            text-decoration:none;
            border:1px solid black;
            font-size:20px;
            background-color:moccasin;
            color:black;
            padding:5px 5px;
            border-radius:5px;
        }
        .pagination li a:hover{
            background-color: #4CAF50;
            color:white;
        }
        .page-number{
            font-size:20px;
        }
        #searchBy, button{
            border:1px solid #ddd;
            background-color:transparent;
            border-radius:5px;
        }
        #searchBy:focus{
            outline: none;
            border:2px solid #9155FD;
        }
    </style>
</head>

<body>
    <nav>
        <div class="navbar">
            <h4>LOGO</h4>
        </div>
        <ul class="nav-link">
            <li><a href="create.php">Add</a></li>
            <?php
                if(isset($_SESSION['name']))
                {
            ?>
            <li><a href="logout.php">Logout</a></li>
            <?php }?>
        </ul>
    </nav>

    <div class="container">
        <p>LIST OF USERS</p>
        
        <!-- SEARCH BOX -->
        <form action="" method="POST">
            <select id="searchBy" name="searchBy" >
                <option> Search By</option>
                <option value="name" name="searchBy">Name</option>
                <option value="email" name="searchBy">Email</option>
                <option value="mobile" name="searchBy">Mobile</option>
                <option value="address" name="searchBy">Address</option>
                <option value="state" name="searchBy">State</option>
                <option value="gender" name="searchBy">Gender</option>
                <option value="message" name="searchBy">Message</option>
            </select>
            <input type="text" placeholder="Search" name="search-input">
            <button><a href="?orderBy=id&order=asc">Reset</a></button>
        </form>
        

        <table class="table">
            <thead>
                <tr class="table-heading">
                    <th style="width:10%"><a href="?orderBy=name&order=<?php echo $nameNextOrder;?>">NAME<?php if($orderBy == "name" && $order == "desc"){echo "↓";}elseif($orderBy == "name" && $order == "asc"){echo "↑";}?></a></th>
                    <th style="width:17%"><a href="?orderBy=email&order=<?php echo $emailNextOrder;?>">EMAIL<?php if($orderBy == "email" && $order == "desc"){echo "↓";}elseif($orderBy == "email" && $order == "asc"){echo "↑";}?></a></th>
                    <th style="width: 8%"><a href="?orderBy=mobile&order=<?php echo $mobileNextOrder;?>">MOBILE<?php if($orderBy == "mobile" && $order == "desc"){echo "↓";}elseif($orderBy == "mobile" && $order == "asc"){echo "↑";}?></a></th>
                    <th style="width:12%"><a href="?orderBy=address&order=<?php echo $addressNextOrder; ?>">ADDRESS<?php if($orderBy == "address" && $order == "desc"){echo "↓";}elseif($orderBy == "address" && $order == "asc"){echo "↑";}?></a></th>
                    <th style="width: 6%"><a href="?orderBy=state&order=<?php echo $stateNextOrder; ?>">STATE<?php if($orderBy == "state" && $order == "desc"){echo "↓";}elseif($orderBy == "state" && $order == "asc"){echo "↑";}?></a></th>
                    <th style="width: 9%"><a href="?orderBy=gender&order=<?php echo $genderNextOrder; ?>">GENDER<?php if($orderBy == "gender" && $order == "desc"){echo "↓";}elseif($orderBy == "gender" && $order == "asc"){echo "↑";}?></a></th>
                    <th style="width:20%"><a href="?orderBy=message&order=<?php echo $messageNextOrder; ?>">MESSAGE<?php if($orderBy == "message" && $order == "desc"){echo "↓";}elseif($orderBy == "message" && $order == "asc"){echo "↑";}?></a></th>
                    <th style="width:12%">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php   
                    while( $_SESSION['name'] && $data = $result->fetch_assoc()){
                ?>

                <tr class="table-data">
                    <td><?php echo $data['name']; ?></td>
                    <td><?php echo $data['email']; ?></td>
                    <td><?php echo $data['mobile']; ?></td>
                    <td><?php echo $data['address']; ?></td>
                    <td><?php echo $data['state']; ?></td>
                    <td><?php echo $data['gender']; ?></td>
                    <td id="message" class="mess"><?php echo $data['message']; ?></td>   
                    <td>
                        <button class="edit-btn"><a class="btn" href="update.php?id=<?php echo $data['id'];?>">Edit</a></button>&nbsp;
                        <button class="del-btn"><a class="btn" href="delete.php?id=<?php echo $data['id'];?>">Delete</a></button>
                    </td>
                </tr>
                <?php   
                    }        
                ?>
            </tbody>
        </table>

        <!-- PAGINATION LINKS -->
        <?php
            if (!isset($_GET['pageno']) || isset($_GET['pageno']))
            {
        ?>
            <div class="page-num">
                <ul class="pagination" >
                    <li><a href="<?php if($pageNo <= 1){ echo '#'; } else { echo "?pageno=".($pageNo - 1)."&orderBy=".$orderBy."&order=".$order; } ?>">Prev</a></li>
                    <li class="page-number"><?php echo $pageNo;?></li>
                    <li><a href="<?php if($pageNo >= $totalPages){ echo '#'; } else { echo "?pageno=".($pageNo + 1)."&orderBy=".$orderBy."&order=".$order; } ?>">Next</a></li>
                </ul>
            </div>
        <?php 
            }
        ?>
    </div>   
</body>
</html>
