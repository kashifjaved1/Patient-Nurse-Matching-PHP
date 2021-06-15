<?php

    session_start();
    $conn = new mysqli("localhost", "root", "", "patient_nurse_match");

    ///////////////////////////// Patient Registration ////////////////////////////////

    if(isset($_POST['p_reg'])){

        /////////////////////////////// Patient Pic Upload ///////////////////////////////////
    
        // Uploaded Pic Renaming while moving to Storage Directory.
        // $temp = explode(".", $_FILES["fileToUpload"]["name"]);
        // $newfilename = "rajaGPic" . '.' . end($temp);
        // move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename);

        $name = $_POST['name'];
        $gndr = $_POST['gender'];
        $cnic = $_POST['cnic'];
        $pwd = $_POST['pwd'];
        $loc = $_POST['location'];
        $diz = $_POST['diz'];
        $cont = $_POST['cont'];

        $target_dir = "../upload/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 99999999999999) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        }
        else {
            $temp = explode(".", $_FILES["fileToUpload"]["name"]);
            $newfilename = $cnic . '.' . end($temp);
            //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename);
            //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename)) {
                //echo "The file ". $newfilename. " has been uploaded.";
                $img = $newfilename;
            }
            else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $sql = "INSERT INTO `patient`(`name`, `pwd`, `gender`, `cnic`, `contact`, `city`, `img`, `disease`) VALUES ('$name', '$pwd', '$gndr', '$cnic', '$cont', '$loc', '$img', '$diz')";
        if (mysqli_query($conn, $sql)) {
            header("location: ../frontend/p_login.php");
        }
    }

    ///////////////////////////// Patient Profile Updation ////////////////////////////////

    if(isset($_POST['p_upd'])){

       //$name = $_POST['name'];
       $pwd = $_POST['pwd'];
       $cont = $_POST['contact'];
       $img = $_POST['i'];
       $diz = $_POST['diz'];
       $cnic = $_POST['c'];

       $target_dir = "../upload/";
       $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
       $uploadOk = 1;
       $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

       $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
       if($check !== false) {
           echo "File is an image - " . $check["mime"] . ".";
           $uploadOk = 1;
       } else {
           $uploadOk = 0;
           $img = $_POST['i'];
           goto pat_skip_all;
       }

       // Check if file already exists
       if (file_exists($target_file)) {
           echo "Sorry, file already exists.";
           $uploadOk = 0;
       }

       // Check file size
       if ($_FILES["fileToUpload"]["size"] > 99999999999999) {
       echo "Sorry, your file is too large.";
       $uploadOk = 0;
       }

       // Allow certain file formats
       if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
       && $imageFileType != "gif" ) {
       echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
       $uploadOk = 0;
       }

       // Check if $uploadOk is set to 0 by an error
       if ($uploadOk == 0) {
       echo "Sorry, your file was not uploaded.";
       // if everything is ok, try to upload file
       }
       else {
           $temp = explode(".", $_FILES["fileToUpload"]["name"]);
           $newfilename = $cnic . '.' . end($temp);
           //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename);
           //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)
           if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename)) {
               //echo "The file ". $newfilename. " has been uploaded.";
               $img = $newfilename;
           }
           else {
               echo "Sorry, there was an error uploading your file.";
           }
       }

       pat_skip_all:
       $sql = "UPDATE `patient` SET `pwd` = '$pwd', `img` = '$img', `contact` = '$cont', `disease` = '$diz' WHERE `cnic` = '$cnic'";
       
       if (mysqli_query($conn, $sql)) {
           //header("location: ");
       }
    }

    ///////////////////////////// Patient Login ////////////////////////////////

    if(isset($_POST['p_log'])){
        $nm = $_POST['name'];
        $pw = $_POST['pwd'];
        $sql = "SELECT `id`, `name`, `pwd`, `gender`, `cnic`, `img` FROM `patient` WHERE `name` = '$nm' and `pwd` = '$pw'";
        if ($res = mysqli_query($conn, $sql)) {
            $r = mysqli_fetch_row($res);
            $_SESSION['id'] = $r[0];
            header("location: ../frontend/p_prof.php");
        }
        else{
            echo "account not found";
        }
    }

    ///////////////////////////// Nurse Reg Step 1 ////////////////////////////////

    if(isset($_POST['n_reg'])){

        /////////////////////////////// Nurse Pic Upload ///////////////////////////////////
    
        // Uploaded Pic Renaming while moving to Storage Directory.
        // $temp = explode(".", $_FILES["fileToUpload"]["name"]);
        // $newfilename = "rajaGPic" . '.' . end($temp);
        // move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename);

        $name1 = $_POST['name'];
        $gndr1 = $_POST['gender'];
        $cnic1 = $_POST['cnic'];
        $pwd1 = $_POST['pwd'];
        $cont1 = $_POST['contact'];
        $exp1 = $_POST['exp'];
        $sal1 = $_POST['salary'];
        $loc1 = $_POST['location'];

        $target_dir = "../upload/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 99999999999999) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        }
        else {
            $temp = explode(".", $_FILES["fileToUpload"]["name"]);
            $newfilename = $cnic . '.' . end($temp);
            //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename);
            //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename)) {
                //echo "The file ". $newfilename. " has been uploaded.";
                $img = $newfilename;
            }
            else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $sql = "INSERT INTO `nurse`(`name`, `pwd`, `gender`, `cnic`, `img`, `exp`, `salary`, `contact`) VALUES ('$name', '$pwd', '$gndr', '$cnic', '$img', '$exp', '$sal', '$cont')";
        
        if (mysqli_query($conn, $sql)) {
            $_SESSION['cnic'] = $cnic;
            header("location: ../frontend/nurse_reg_step2.php");
        }
    }

    ///////////////////////////// Nurse Login ////////////////////////////////

    if(isset($_POST['n_log'])){
        $nm = $_POST['name'];
        $pw = $_POST['pwd'];
        $sql = "SELECT `id`, `name`, `pwd`, `gender`, `cnic`, `img` FROM `nurse` WHERE `name` = '$nm' and `pwd` = '$pw'";
        if (mysqli_query($conn, $sql)) {
            //header("location: index.php");
            
        }
        else{
            echo "account not found";
        }
    }

    ///////////////////////////// Nurse Reg Step 2 ////////////////////////////////

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

    ///////////////////////////////// Nurse Profile Updation ////////////////////////

    if(isset($_POST['n_upd'])){

        //$name = $_POST['name'];
        $pwd = $_POST['pwd'];
        $cont = $_POST['contact'];
        $exp = $_POST['exp'];
        $sal = $_POST['salary'];
        $cnic = $_POST['c'];

        $target_dir = "../upload/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            $uploadOk = 0;
            $img = $_POST['i'];
            goto nurse_skip_all;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 99999999999999) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        }
        else {
            $temp = explode(".", $_FILES["fileToUpload"]["name"]);
            $newfilename = $cnic . '.' . end($temp);
            //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename);
            //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename)) {
                //echo "The file ". $newfilename. " has been uploaded.";
                $img = $newfilename;
            }
            else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        nurse_skip_all:
        $sql = "UPDATE `nurse` SET `pwd` = '$pwd', `img` = '$img', `exp` = '$exp', `salary` = '$sal', `contact` = '$cont' WHERE `cnic` = '$cnic'";
        
        if (mysqli_query($conn, $sql)) {
            header("location: ../frontend/nurse_reg_step2.php");
        }
    }

?>