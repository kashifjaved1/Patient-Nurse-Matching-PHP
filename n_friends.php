<?php
require 'includes/n_init.php';
include("db.php");
error_reporting(0);
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    $user_data = $nurse_obj->find_nurse_by_id($_SESSION['user_id']);
    if($user_data ===  false){
        header('Location: n_logout.php');
        exit;
    }
}
else{
    header('Location: n_logout.php');
    exit;
}
// TOTAL REQUESTS
$get_req_num = $frnd_obj->request_notification($_SESSION['user_id'], false);
// TOTLA FRIENDS
$get_frnd_num = $frnd_obj->get_all_friends($_SESSION['user_id'], false);
// GET MY($_SESSION['user_id']) ALL FRIENDS
$get_all_friends = $frnd_obj->get_all_friends($_SESSION['user_id'], true);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo  $user_data->name;?></title>
    <link rel="stylesheet" href="./style.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        .hovlink:hover{
            color: black;
        }
    </style>
</head>
<body style="background-color:black;">
    <div class="profile_container">
        
        <div class="inner_profile">
            <div class="img">
                <img src="upload/<?php echo $user_data->img; ?>" alt="Profile image">
            </div>
            <h1><?php echo $user_data->name;?></h1>
        </div>
        <nav>
            <ul>
                <li><a href="n_profile.php" rel="noopener noreferrer">Home</a></li>
                <li><a href="n_notifications.php" rel="noopener noreferrer">Requests<span class="badge <?php
                if($get_req_num > 0){
                    echo 'redBadge';
                    $nid = $_SESSION['user_id'];
                    $sql = "SELECT `pid`, `start`, `end`, `payment` FROM `tmp_agmt` WHERE `nid` = '$nid'";
                    $res = mysqli_query($conn, $sql);
                    $r = mysqli_fetch_row($res);

                }
                ?>"><?php echo $get_req_num;?></span></a></li>
                <li><a href="n_friends.php" rel="noopener noreferrer" class="active">Friends<span class="badge"><?php echo $get_frnd_num;?></span></a></li>
                <?php
                if($get_frnd_num > 0){
                    $n_id = $_SESSION['user_id'];
                    $sql = "SELECT `pid`, `start`, `end`, `payment` FROM `tmp_agmt` WHERE `nid` = '$n_id'";
                    if($res = mysqli_query($conn, $sql)){
                        $r = mysqli_fetch_row($res);
                        $p_id = $r[0];
                        $sql1 = "SELECT `name`,`img`, `rating` FROM `patient` WHERE `id` = '$p_id'";
                        $res1 = mysqli_query($conn, $sql1);
                        $r1 = mysqli_fetch_row($res1);
                        if(!empty($r)){?>
                            <li><a style="cursor: pointer; text-decoration: none; color: white;" rel="noopener noreferrer" class="hovlink" data-toggle="modal" data-target="#myModal">Pending Agreements</a></li>
                    <?php
                        }
                    }
                }
                ?>
                <li><a href="n_logout.php" rel="noopener noreferrer">Logout</a></li>
            </ul>
        </nav>
        <div class="all_users">
            <h3>All friends</h3>
            <div class="usersWrapper">
                <?php
                if($get_frnd_num > 0){
                    foreach($get_all_friends as $row){
                        echo '<div class="user_box">
                                <div class="user_img"><img src="upload/'.$row->img.'" alt="Profile image"></div>
                                <div class="user_info"><span>'.$row->name.'</span>
                                <span><a href="n2p_profile.php?id='.$row->id.'" class="see_profileBtn" style="text-decoration: none; color: white;">See profile</a></div>
                            </div>';
                    }
                }
                else{
                    echo '<h4>You have no friends!</h4>';
                }
                ?>

            

            </div>
        </div>
        
    </div>

<!-- Patient - Nurse Agreement Bootstrap 4 Modal ==>> Starts Here <<== -->

    <div class="container">
        <div class="modal" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <center>
                            <h4 class="modal-title" style="margin-left: 45px;">
                                <div class="inner_profile">
                                    <div class="img">
                                        <img src="upload/<?php echo $r1[1] ?>" alt="Profile image">
                                    </div>
                                    <h1><?php echo  $r1[0]." Agreement Proposal";?></h1>
                                </div>
                            </h4>
                        </center>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <center>
                            <form action="n_functions.php" method="post">
                                <input type="hidden" readonly name="pid" value="<?php echo $p_id; ?>">
                                <input type="hidden" readonly name="nid" value="<?php echo $n_id; ?>">
                                <label for="">Start Date</label><br>
                                <input readonly type="text" id="datepicker" name="sd" value="<?php echo $r[1]; ?>"><br><br>
                                <label for="">End Date</label><br>
                                <input readonly type="text" id="datepicker1" name="ed" value="<?php echo $r[2]; ?>"><br><br>
                                <label for="">Job Payment</label><br>
                                <input readonly type="number" name="py" value="<?php echo $r[3]; ?>"><br><br>
                                <input type="submit" name="accept" value="Accept" class="btn btn-success">
                                <input type="submit" name="reject" value="Reject" class="btn btn-danger">
                            </form>
                        </center>
                    </div>    
                </div>
            </div>
        </div>
    
    </div>

<!-- Patient - Nurse Agreement Bootstrap 4 Modal ==>> Ends Here <<== -->

</body>
</html>