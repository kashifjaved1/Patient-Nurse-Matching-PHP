<?php

    session_start();
    //error_reporting(0);
    include '../../db.php';
    
    if(isset($_SESSION['user_id']) && isset($_SESSION['email'])){
        
        $ratee = $_GET['rid'];
        
        $query = mysqli_query($conn,"SELECT AVG(rating) as AVGRATE from user_rating where rid = '$ratee'");
        
        $row = mysqli_fetch_array($query);
        $AVGRATE = $row['AVGRATE'];

        $query = mysqli_query($conn,"SELECT count(rating) as Total from user_rating where rid = '$ratee'");
        $row = mysqli_fetch_array($query);
        $Total = $row['Total'];

        $query = mysqli_query($conn,"SELECT count(comment) as Totalreview from  user_rating where rid = '$ratee'");
        $row = mysqli_fetch_array($query);
        $Total_review = $row['Totalreview'];

        $review = mysqli_query($conn,"SELECT comment, rating, rname from user_rating where rid = '$ratee' limit 4");
        
        $rating = mysqli_query($conn,"SELECT count(*) as Total, rating from user_rating where rid = '$ratee' group by rating order by rating desc");

        if(isset($_POST['rating'])){
            $ap = $_POST['ap'];
            $cid = $_POST['cid'];
            $rid = $_POST['rid'];
            $rtng = $_POST['rateval'];
            $cmnt = $_POST['cmnt'];
            if($ap == "patient"){
                $c = "SELECT `name` FROM `patient` WHERE `id` = '$cid'";
                $r = "SELECT `name` FROM `nurse` WHERE `id` = '$rid'";
                $res = mysqli_query($conn, $c);
                $cn = mysqli_fetch_row($res);
                $cname = $cn[0];
                $re = mysqli_query($conn, $r);
                $rn = mysqli_fetch_row($re);
                $rname = $rn[0];
                $cqry = "INSERT INTO `user_rating`(`cid`, `cname`, `rid`, `rname`, `rating`, `comment`) VALUES ('$cid', '$cname', '$rid', '$rname', '$rtng', '$cmnt')";
                mysqli_query($conn, $cqry);
                header("location: ../../p2n_profile.php?id=$rid");
            }
            else{
                $c = "SELECT `name` FROM `nurse` WHERE `id` = '$cid'";
                $r = "SELECT `name` FROM `patient` WHERE `id` = '$rid'";
                $res = mysqli_query($conn, $c);
                $cn = mysqli_fetch_assoc($res);
                $cname = $cn['name'];
                $re = mysqli_query($conn, $r);
                $rn = mysqli_fetch_assoc($re);
                $rname = $rn['name'];
                $cqry = "INSERT INTO `user_rating`(`cid`, `cname`, `rid`, `rname`, `rating`, `comment`) VALUES ('$cid', '$cname', '$rid', '$rname', '$rtng', '$cmnt')";
                mysqli_query($conn, $cqry);
                header("location: ../../n2p_profile.php?id=$rid");
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Bootstrap Rating Plugin</title>
    <link href="http://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <link href="../css/star-rating.css" media="all" rel="stylesheet" type="text/css"/>
    <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="../js/star-rating.js" type="text/javascript"></script>
<body>
<div class="container">
    
    <!-- User Rating Display - start -->

    <h3><b>Rating & Reviews</b></h3><hr>

    <div class="row container">
        <div class="col-md-4 ">
            <div class="row">
            
                <div class="col-md-6">
                    <h3 ><b><?php echo round($AVGRATE,1);?></b> <i class="fa fa-star" data-rating="2" style="font-size:20px;color:#ff9f00;"></i></h3>
                    <p><?=$Total;?> ratings and <?=$Total_review;?> reviews</p>
                </div>
                <div class="col-md-6">
                    <?php
                    while($db_rating= mysqli_fetch_array($rating)){
                    ?>
                        <h4 ><?=$db_rating['rating'];?> <i class="fa fa-star" data-rating="2" style="font-size:20px;color:green;"></i> Total <?=$db_rating['Total'];?></h4>
                    <?php	
                    }
                        
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">	
                <?php
                    while($db_review= mysqli_fetch_array($review)){
                ?>
                        <h4><?=$db_review['rating'];?> <i class="fa fa-star" data-rating="2" style="font-size:20px;color:green;"></i> by <span style="font-size:14px;"><?=$db_review['rname'];?></span></h4>
                        <p><?=$db_review['comment'];?></p>
                        <hr>
                <?php	
                    }
                        
                ?>
                </div>
            </div>
        </div>
	</div><br>

    <!-- User Rating Display - end -->

    <h4 style="margin-left: 1%;">Rate this Person <hr style="color: black;"></h4>
 
    <form action="" method="post" style="margin-left: 1%;">
        <input type="hidden" name="ap" value="<?php echo $_GET['ap']; ?>">
        <input type="hidden" name="cid" value="<?php echo $_GET['cid']; ?>">
        <input type="hidden" name="rid" value="<?php echo $_GET['rid']; ?>">
        <input required id="rateit" name="rateval" class="rating" data-stars="5" data-step="0.1" title=""/>
        <div class="form-group">
            <label>Comment</label>
            <input required type="text" class="form-control" name="cmnt">
        </div>
        <button class="btn btn-primary form-group" name="rating" type="submit" class="btn btn-primary">Submit</button>
    </form>
    <br>
    <script>
        jQuery(document).ready(function () {
            $("#input-21f").rating({
                starCaptions: function (val) {
                    if (val < 3) {
                        return val;
                    } else {
                        return 'high';
                    }
                },
                starCaptionClasses: function (val) {
                    if (val < 3) {
                        return 'label label-danger';
                    } else {
                        return 'label label-success';
                    }
                },
                hoverOnClear: false
            });
            var $inp = $('#rating-input');

            $inp.rating({
                min: 0,
                max: 5,
                step: 1,
                size: 'lg',
                showClear: false
            });

            $('#btn-rating-input').on('click', function () {
                $inp.rating('refresh', {
                    showClear: true,
                    disabled: !$inp.attr('disabled')
                });
            });


            $('.btn-danger').on('click', function () {
                $("#rateit").rating('destroy');
            });

            $('.btn-success').on('click', function () {
                $("#rateit").rating('create');
            });

            $inp.on('rating.change', function () {
                alert($('#rating-input').val());
            });


            $('.rb-rating').rating({
                'showCaption': true,
                'stars': '3',
                'min': '0',
                'max': '3',
                'step': '1',
                'size': 'xs',
                'starCaptions': {0: 'status:nix', 1: 'status:wackelt', 2: 'status:geht', 3: 'status:laeuft'}
            });
            $("#input-21c").rating({
                min: 0, max: 8, step: 0.5, size: "xl", stars: "8"
            });
        });
    </script>
</div>
</body>
</html>

<?php

}else{
    header("location: ../../p_logout.php");
}
