<?php
include("db.php");
class pFriend{
    
    protected $db;

    public function __construct($db_connection){
        $this->db = $db_connection;
    }

    // CHECK IF ALREADY FRIENDS
    public function is_already_friends($my_id, $user_id){
        try{
            $sql = "SELECT * FROM `friends` WHERE (user_one = :my_id AND user_two = :frnd_id) OR (user_one = :frnd_id AND user_two = :my_id)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            if($stmt->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
        
    }

    public function send_agmt($pid, $nid, $sd, $ed, $py){
        $sql = "INSERT INTO `tmp_agmt`(`pid`, `nid`, `start`, `end`, `payment`) VALUES ('$pid', '$nid', '$sd', '$ed', '$py')";
        if(mysqli_query($conn, $sql)){
            $sql1 = "INSERT INTO `agmt_status`(`pid`, `nid`, `status`) VALUES ('$pid', '$nid', 'pending')";
            mysqli_query($conn, $sql1);
            header("location: p2n_profile.php?id=$nid");
        }
    }

    public function update_agmt($pid, $nid, $sd, $ed, $py){
        $sql = "UPDATE `tmp_agmt` SET `start`= '$sd',`end`= '$ed',`payment`= '$py' WHERE `pid` = '$pid' and `nid` = '$nid'";
        mysqli_query($conn, $sql);
        header("location: p2n_profile.php?id=$nid");
    }

    //  IF I AM THE REQUEST SENDER
    public function am_i_the_req_sender($my_id, $user_id){
        try{
            $sql = "SELECT * FROM `friend_request` WHERE sender = ? AND receiver = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$my_id, $user_id]);

            if($stmt->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    //  IF I AM THE RECEIVER 
    public function am_i_the_req_receiver($my_id, $user_id){
        
        try{
            $sql = "SELECT * FROM `friend_request` WHERE sender = ? AND receiver = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$user_id, $my_id]);

            if($stmt->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // CHECK IF REQUEST HAS ALREADY BEEN SENT
    public function is_request_already_sent($my_id, $user_id){
        
        try{
            $sql = "SELECT * FROM `friend_request` WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
    
            if($stmt->rowCount() === 1){
                return true;
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    // MAKE PENDING FRIENDS (SEND FRIEND REQUEST)
    public function make_pending_friends($my_id, $user_id){
        
        try{
            $sql = "INSERT INTO `friend_request`(sender, receiver) VALUES(?,?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$my_id, $user_id]);
            header('Location: p_profile.php?id='.$user_id);
            exit;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // CANCLE FRIEND REQUEST
    public function cancel_or_ignore_friend_request($my_id, $user_id){
        
        try{
            $sql = "DELETE FROM `friend_request` WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            header('Location: p_profile.php?id='.$user_id);
            exit;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    // MAKE FRIENDS
    public function make_friends($my_id, $user_id){
        
        try{

            $delete_pending_friends = "DELETE FROM `friend_request` WHERE (sender = :my_id AND receiver = :frnd_id) OR (sender = :frnd_id AND receiver = :my_id)";
            $delete_stmt = $this->db->prepare($delete_pending_friends);
            $delete_stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $delete_stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $delete_stmt->execute();
            if($delete_stmt->execute()){

                $sql = "INSERT INTO `friends`(user_one, user_two) VALUES(?, ?)";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$my_id, $user_id]);
                header('Location: p_profile.php?id='.$user_id);
                exit;
                
            }            
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }
    // DELETE FRIENDS 
    public function delete_friends($my_id, $user_id){
        try{
            $delete_friends = "DELETE FROM `friends` WHERE (user_one = :my_id AND user_two = :frnd_id) OR (user_one = :frnd_id AND user_two = :my_id)";
            $delete_stmt = $this->db->prepare($delete_friends);
            $delete_stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $delete_stmt->bindValue(':frnd_id', $user_id, PDO::PARAM_INT);
            $delete_stmt->execute();

            $sql = "DELETE FROM `agmt_status` WHERE `pid` = '$my_id' and `nid` = '$user_id'";
            mysqli_query($conn, $sql);
            $sql1 = "DELETE FROM `agreement` WHERE `pid` = '$my_id' and `nid` = '$user_id'";
            mysqli_query($conn, $sql1);
            $sql2 = "DELETE FROM `tmp_agmt` WHERE `pid` = '$my_id' and `nid` = '$user_id'";
            mysqli_query($conn, $sql2);

            header('Location: p_profile.php?id='.$user_id);
            exit;
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // Patient -> Nurse REQUEST NOTIFICATIONS
    public function request_notification($my_id, $send_data){
        try{
            $sql = "SELECT sender, name, img FROM `friend_request` JOIN patient ON friend_request.sender = patient.id WHERE receiver = ?";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$my_id]);
            if($send_data){
                return $stmt->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return $stmt->rowCount();
            }

        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    public function get_all_friends($my_id, $send_data){
        try{
            $sql = "SELECT * FROM `friends` WHERE user_one = :my_id OR user_two = :my_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':my_id',$my_id, PDO::PARAM_INT);
            $stmt->execute();

                if($send_data){

                    $return_data = [];
                    $all_users = $stmt->fetchAll(PDO::FETCH_OBJ);

                    foreach($all_users as $row){
                        if($row->user_one == $my_id){
                            $get_user = "SELECT id, name, img FROM `nurse` WHERE id = ?";
                            $get_user_stmt = $this->db->prepare($get_user);
                            $get_user_stmt->execute([$row->user_two]);
                            array_push($return_data, $get_user_stmt->fetch(PDO::FETCH_OBJ));
                        }else{
                            $get_user = "SELECT id, name, img FROM `nurse` WHERE id = ?";
                            $get_user_stmt = $this->db->prepare($get_user);
                            $get_user_stmt->execute([$row->user_one]);
                            array_push($return_data, $get_user_stmt->fetch(PDO::FETCH_OBJ));
                        }
                    }

                    return $return_data;

                }
                else{
                    return $stmt->rowCount();
                }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
?>