<?php
require 'includes/p_init.php';
//require 'includes/n_init.php';
if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
    $user_data = $pat_obj->find_pat_by_id($_SESSION['user_id']);
    if($user_data ===  false){
        header('Location: p_logout.php');
        exit;
    }
    // FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID
    $all_users = $pat_obj->all_nurses($_SESSION['user_id']);
}
else{
    header('Location: p_logout.php');
    exit;
}
// REQUEST NOTIFICATION NUMBER
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
</head>
<body style="background-color:black;">
    <div class="profile_container">
        
        <div class="inner_profile">
            <div class="img">
                <img style="width: 100%; height: auto;" src="upload/<?php echo $user_data->img; ?>" alt="Profile image">
            </div>
            <h1><?php echo  $user_data->name;?></h1>
        </div>
        <nav>
            <ul>
                <li><a href="p_profile.php" rel="noopener noreferrer" class="active">Home</a></li>
                <li><a href="p_notifications.php" rel="noopener noreferrer">Requests<span class="badge <?php
                if($get_req_num > 0){
                    echo 'redBadge';
                }
                ?>"><?php echo $get_req_num;?></span></a></li>
                <li><a href="p_friends.php" rel="noopener noreferrer">Friends<span class="badge"><?php echo $get_frnd_num;?></span></a></li>
                <li><a href="p_logout.php" rel="noopener noreferrer">Logout</a></li>
            </ul>
        </nav>
        <div class="all_users">
            <h3>All Nurses</h3>
            <div class="usersWrapper">
                <?php
                if($all_users){
                    foreach($all_users as $row){
                        echo '<div class="user_box">
                                <div class="user_img"><img src="upload/'.$row->img.'" alt="Profile image"></div>
                                <div class="user_info"><span>'.$row->name.'</span>
                                <span><a href="p2n_profile.php?id='.$row->id.'" class="see_profileBtn">See profile</a></div>
                            </div>';
                    }
                }
                else{
                    echo '<h4>There is no user!</h4>';
                }
                ?>
            </div>
        </div>
        
    </div>
</body>
</html>