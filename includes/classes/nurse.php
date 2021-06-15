<?php

///////////////////////////// Nurse Document(s) Upload ////////////////////////////////

if(isset($_POST['doc_upload'])){
        
    $uploadsDir = "../upload/";
    $allowedFileType = array('jpg','png','jpeg');
    $nic = $_POST['n_cnic'];
    
    // Velidate if files exist
    if (!empty(array_filter($_FILES['fileUpload']['name']))) {
        
        // Loop through file items
        foreach($_FILES['fileUpload']['name'] as $id=>$val){
            // Get files upload path
            $fileName        = $_FILES['fileUpload']['name'][$id];
            $tempLocation    = $_FILES['fileUpload']['tmp_name'][$id];
            $targetFilePath  = $uploadsDir . $fileName;
            $fileType        = strtolower(pathinfo($targetFilePath, PATHINFO_EXTENSION));
            $uploadDate      = date('Y-m-d H:i:s');
            $uploadOk = 1;

            if(in_array($fileType, $allowedFileType)){
                    if(move_uploaded_file($tempLocation, $targetFilePath)){
                        $sqlVal = "('".$fileName."', '".$uploadDate."')";
                    } else {
                        $response = array(
                            "status" => "alert-danger",
                            "message" => "File coud not be uploaded."
                        );
                    }
                
            } else {
                $response = array(
                    "status" => "alert-danger",
                    "message" => "Only .jpg, .jpeg and .png file formats allowed."
                );
            }
            // Add into MySQL database
            if(!empty($sqlVal)) {
                $insert = $conn->query("INSERT INTO `nurse_docs`(`n_cnic`, `docs`) VALUES ('$nic', '$fileName')");
                if($insert) {
                    $response = array(
                        "status" => "alert-success",
                        "message" => "Files successfully uploaded."
                    );
                    header("n_login.php");
                } else {
                    $response = array(
                        "status" => "alert-danger",
                        "message" => "Files coudn't be uploaded due to database error."
                    );
                }
            }
        }

    } else {
        // Error
        $response = array(
            "status" => "alert-danger",
            "message" => "Please select a file to upload."
        );
    }
}

// nurse.php
class Nurse{
    protected $db;
    protected $user_name;
    protected $user_email;
    protected $user_pass;
    protected $hash_pass;
    
    function __construct($db_connection){
        $this->db = $db_connection;
    }

    // SING UP USER
    function singUpNurse($nm, $em, $gndr, $cnic, $pwd, $loc, $img, $cont, $exp, $sal){
        try{
            $this->user_name = trim($nm);
            $this->user_email = trim($em);
            $this->user_pass = trim($pwd);
            $this->gndr = $gndr;
            $this->cnic = $cnic;
            $this->loc = $loc;
            $this->img = $img;
            $this->cont = $cont;
            $this->xp = $exp;
            $this->sal = $sal;

            if(!empty($this->user_name) && !empty($this->user_email) && !empty($this->user_pass)){

                if (filter_var($this->user_email, FILTER_VALIDATE_EMAIL)) { 
                    $check_email = $this->db->prepare("SELECT * FROM `users` WHERE user_email = ?");
                    $check_email->execute([$this->user_email]);

                    if($check_email->rowCount() > 0){
                        return ['errorMessage' => 'This Email Address is already registered. Please Try another.'];
                    }
                    else{
                        
                        $user_image = rand(1,12);

                        $this->hash_pass = password_hash($this->user_pass, PASSWORD_DEFAULT);
                        $sql = "INSERT INTO `nurse`(`name`, `pwd`, `email`, `gender`, `cnic`, `img`, `exp`, `salary`, `contact`, `city`) VALUES(:username, :user_pass, :user_email, :gndr, :cnic, :img, :xp, :sal, :cont, :loc)";
            
                        $sign_up_stmt = $this->db->prepare($sql);
                        //BIND VALUES
                        $sign_up_stmt->bindValue(':username',htmlspecialchars($this->user_name), PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_pass',$this->hash_pass, PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_email',$this->user_email, PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':gndr',$gndr , PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':cnic',$cnic , PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':img',$img, PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':xp',$exp , PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':sal',$sal , PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':cont',$cont , PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':loc',$loc , PDO::PARAM_STR);
                        $sign_up_stmt->execute();
                        return ['successMessage' => 'You have signed up successfully.'];                   
                    }
                }
                else{
                    return ['errorMessage' => 'Invalid email address!'];
                }    
            }
            else{
                return ['errorMessage' => 'Please fill in all the required fields.'];
            } 
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // LOGIN Nurses
    function loginNurse($email, $password){
        
        try{
            $this->user_email = trim($email);
            $this->user_pass = trim($password);

            $find_email = $this->db->prepare("SELECT * FROM `nurse` WHERE email = ?");
            $find_email->execute([$this->user_email]);
            
            if($find_email->rowCount() === 1){
                $row = $find_email->fetch(PDO::FETCH_ASSOC);

                $match_pass = password_verify($this->user_pass, $row['pwd']);
                if($match_pass){
                    $_SESSION = [
                        'user_id' => $row['id'],
                        'email' => $row['email']
                    ];
                    header('Location: n_profile.php');
                }
                else{
                    return ['errorMessage' => 'Invalid password'];
                }
                
            }
            else{
                return ['errorMessage' => 'Invalid email address!'];
            }

        }
        catch (PDOException $e) {
            die($e->getMessage());
        }

    }

    // FIND USER BY ID
    function find_nurse_by_id($id){
        try{
            $find_user = $this->db->prepare("SELECT * FROM `nurse` WHERE id = ?");
            $find_user->execute([$id]);
            if($find_user->rowCount() === 1){
                return $find_user->fetch(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // FIND USER BY ID
    function find_pat_by_id($id){
        try{
            $find_user = $this->db->prepare("SELECT * FROM `patient` WHERE id = ?");
            $find_user->execute([$id]);
            if($find_user->rowCount() === 1){
                return $find_user->fetch(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
    
    // FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID
    function all_nurses($id){
        try{
            $get_users = $this->db->prepare("SELECT id, `name`, `img` FROM `nurse` WHERE `id` != ?");
            $get_users->execute([$id]);
            if($get_users->rowCount() > 0){
                return $get_users->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    // FETCH ALL USERS WHERE ID IS NOT EQUAL TO MY ID
    function all_patients($id){
        try{
            $get_users = $this->db->prepare("SELECT `id`, `name`, `img` FROM `patient` WHERE `id` != ?");
            $get_users->execute([$id]);
            if($get_users->rowCount() > 0){
                return $get_users->fetchAll(PDO::FETCH_OBJ);
            }
            else{
                return false;
            }
        }
        catch (PDOException $e) {
            die($e->getMessage());
        }
    }
}
?>