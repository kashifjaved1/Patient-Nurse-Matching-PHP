<?php
// patient.php
class Patient{
    protected $db;
    protected $user_name;
    protected $user_email;
    protected $user_pass;
    protected $hash_pass;
    
    function __construct($db_connection){
        $this->db = $db_connection;
    }

    // SING UP USER
    function singUpPatient($nm, $em, $gndr, $cnic, $pwd, $loc, $img, $diz, $cont){
        try{
            $this->user_name = trim($nm);
            $this->user_email = trim($em);
            $this->user_pass = trim($pwd);
            $this->gndr = $gndr;
            $this->cnic = $cnic;
            $this->diz = $diz;
            $this->loc = $loc;
            $this->img = $img;
            $this->cont = $cont;
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
                        $sql = "INSERT INTO `patient`(`name`, `email`, `pwd`, `gender`, `cnic`, `contact`, `city`, `img`, `disease`) VALUES(:username, :user_email, :user_pass, :gndr, :cnic, :cont, :loc, :img, :diz)";
            
                        $sign_up_stmt = $this->db->prepare($sql);
                        //BIND VALUES
                        $sign_up_stmt->bindValue(':username',htmlspecialchars($this->user_name), PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_email',$this->user_email, PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':user_pass',$this->hash_pass, PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':gndr',$gndr , PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':cnic',$cnic , PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':cont',$cont , PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':loc',$loc , PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':img',$img, PDO::PARAM_STR);
                        $sign_up_stmt->bindValue(':diz',$diz , PDO::PARAM_STR);
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

    // LOGIN Patient
    function loginPatient($email, $password){
        
        try{
            $this->user_email = trim($email);
            $this->user_pass = trim($password);

            $find_email = $this->db->prepare("SELECT * FROM `patient` WHERE email = ?");
            $find_email->execute([$this->user_email]);
            
            if($find_email->rowCount() === 1){
                $row = $find_email->fetch(PDO::FETCH_ASSOC);

                $match_pass = password_verify($this->user_pass, $row['pwd']);
                if($match_pass){
                    $_SESSION = [
                        'user_id' => $row['id'],
                        'email' => $row['email']
                    ];
                    header('Location: p_profile.php');
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
    function all_patients($id){
        try{
            $get_users = $this->db->prepare("SELECT id, username, user_image FROM `users` WHERE id != ?");
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
}
?>