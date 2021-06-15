<?php
require 'includes/init.php';
include("db.php");
error_reporting(0);
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    $cid = $_SESSION['user_id'];
    $rid = $_GET['id'];
    if(isset($_GET['id'])){
        $user_data = $pat_obj->find_pat_by_id($_GET['id']);
        if($user_data ===  false){
            header('Location: n_profile.php');
            exit;
        }
        else{
            if($user_data->id == $_SESSION['user_id']){
                header('Location: n_profile.php');
                exit;
            }
        }
    }
}
else{
    header('Location: n_logout.php');
    exit;
}
// CHECK FRIENDS
$is_already_friends = $frnd_obj->is_already_friends($_SESSION['user_id'], $user_data->id);
//  IF I AM THE REQUEST SENDER
$check_req_sender = $frnd_obj->am_i_the_req_sender($_SESSION['user_id'], $user_data->id);
// IF I AM THE REQUEST RECEIVER
$check_req_receiver = $frnd_obj->am_i_the_req_receiver($_SESSION['user_id'], $user_data->id);
// TOTAL REQUESTS
$get_req_num = $frnd_obj->request_notification($_SESSION['user_id'], false);
// TOTAL FRIENDS
$get_frnd_num = $frnd_obj->get_all_friends($_SESSION['user_id'], false);
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
</head>
<body style="background-color:black;">
    <div class="profile_container">
        <a href="../pnmat/rateplug/examples/index.php?cid=<?php echo $cid;?>&rid=<?php echo $rid;?>&ap=nurse"><button style="float: right;" class="btn btn-secondary">Rate <?php echo  $user_data->name;?></button></a>
        <div class="inner_profile">
            <div class="img">
                <img style="width: 100%; height: auto; margin-top: 5%" src="upload/<?php echo $user_data->img; ?>" alt="Profile image">
            </div>
            <h1><?php echo  $user_data->name;?></h1>
            <nav>
            <ul>
                <li><a href="n_profile.php" rel="noopener noreferrer">Home</a></li>
                <li><a href="n_notifications.php" rel="noopener noreferrer">Requests<span class="badge <?php
                if($get_req_num > 0){
                    echo 'redBadge';
                }
                ?>"><?php echo $get_req_num;?></span></a></li>
                <li><a href="n_friends.php" rel="noopener noreferrer">Friends<span class="badge"><?php echo $get_frnd_num;?></span></a></li>
                <li><a href="n_logout.php" rel="noopener noreferrer">Logout</a></li>
            </ul>
        </nav>
            <div class="actions">
                <?php
                if($is_already_friends){                    
                    $sql = "SELECT `id` FROM `agreement` WHERE `nid` = '$cid' AND `pid` = '$rid'";
                    if($res = mysqli_query($conn, $sql)){
                        $r = mysqli_fetch_assoc($res);
                        $agmt_id = $r['id'];
                        echo '<a style="text-decoration: none; color: white;" href="p_functions.php?action=unfriend_req&id='.$user_data->id.'" class="btn btn-danger">Unfriend</a> '; ?>
                        <button data-toggle="modal" data-target="#myModal" class="btn btn-secondary">Cancel Agreement</button>
                        <?php
                    }
                }
                elseif($check_req_sender){
                    echo '<a href="n_functions.php?action=cancel_req&id='.$user_data->id.'" class="req_actionBtn cancleRequest">Cancel Request</a>';
                }
                elseif($check_req_receiver){
                    echo '<a href="n_functions.php?action=ignore_req&id='.$user_data->id.'" class="req_actionBtn ignoreRequest">Ignore</a>&nbsp;
                    <a href="n_functions.php?action=accept_req&id='.$user_data->id.'" class="req_actionBtn acceptRequest">Accept</a>';
                }
                else{
                    echo '<a href="n_functions.php?action=send_req&id='.$user_data->id.'" class="req_actionBtn sendRequest">Send Request</a>';
                }
                ?>        
            </div>
        </div>
    </div>

<!-- Confirmation BS4 Modal -->

<div class="container">
  <!-- The Modal -->
  <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">
        <!-- Modal body -->
        <div class="modal-body">
            <center><h5>Are you sure?</h5></center><br>
            <div class="row">
                <a class="col-md-6" href="./n_functions.php?rid=<?php echo $rid; ?>&agmt_id=<?php echo $agmt_id; ?>"><button class="btn btn-danger" style="float: right;">Yes</button></a>
                <a class="col-md-6"><button style="margin-left: -10px;" class="btn btn-danger" data-dismiss="modal">No</button></a>
            </div>
        </div>        
      </div>
    </div>
  </div>  
</div>

<!-- Confirmation BS4 Modal -->

</body>
</html>