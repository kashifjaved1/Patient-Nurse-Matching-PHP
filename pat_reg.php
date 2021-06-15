<?php
require 'includes/p_init.php';
// IF USER MAKING SIGNUP REQUEST
if(isset($_POST['pat_reg'])){
  $nm = $_POST['name'];
  $em = $_POST['email'];
  $gndr = $_POST['gender'];
  $cnic = $_POST['cnic'];
  $pwd = $_POST['pwd'];
  $loc = $_POST['location'];
  $diz = $_POST['diz'];
  $cont = $_POST['cont'];
  $target_dir = "./upload/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            //echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            //echo "File is not an image.";
            $uploadOk = 0;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            //echo "Sorry, file already exists.";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 99999999999999) {
        //echo "Sorry, your file is too large.";
        $uploadOk = 0;
        }

        // Allow certain file formats
        if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
        && $imageFileType != "gif" ) {
        //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
        //echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        }
        else {
            $temp = explode(".", $_FILES["fileToUpload"]["name"]);
            $newfilename = $cnic . '.' . end($temp);
            //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "../upload/" . $newfilename);
            //move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], "./upload/" . $newfilename)) {
                //echo "The file ". $newfilename. " has been uploaded.";
                $img = $newfilename;
            }
            else {
                //echo "Sorry, there was an error uploading your file.";
            }
        }

  $result = $pat_obj->singUpPatient($nm, $em, $gndr, $cnic, $pwd, $loc, $img, $diz, $cont);
}
// IF USER ALREADY LOGGED IN
if(isset($_SESSION['email'])){
  header('Location: profile.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Patient Signup</title>
    <link rel="stylesheet" href="./mystyle.css">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
   <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
   <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body style="background-color:black;">
  <div class="main_container login_signup_container">
    <h1 style="margin-bottom: 30px;">Patient Sign Up</h1>
    <form action="" method="POST" novalidate enctype="multipart/form-data">
      <div class="form-group">
        <label>Image</label>
        <input class="form-control" required type="file" name="fileToUpload" id="fileToUpload">
      </div>
      <div class="form-group">
        <label>Full Name</label>
        <input required type="text" name="name" class="form-control" placeholder="Enter your Full Name">
      </div>
      <div class="form-group">
        <label>Patient Disease</label>
        <textarea name="diz" rows="3" required class="form-control"></textarea>
      </div>
      <div class="form-group">
        <label>Gender</label>
        <select class="form-control" name="gender">
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
        </select>
      </div>
      <div class="form-group">
        <label>CNIC</label>
        <input required type="number" name="cnic" class="form-control" placeholder="National Identification Card" pattern="[3]{1}[5]{1}[2]{1}[0-9]{2}[0-9]{8}">
      </div>
      <div class="form-group">
        <label>Contact</label>
        <input required pattern="[0]{1}[3]{1}[0-9]{9}" type="number" name="cont" class="form-control" placeholder="Contact / Phone Number">
      </div>
      <div class="form-group">
        <label>City</label>
        <select class="form-control" name="location" required>
            <option value="" disabled selected>Select The City</option>
            <option value="Islamabad">Islamabad</option>
            <option value="" disabled>Punjab Cities</option>
            <option value="Ahmed Nager Chatha">Ahmed Nager Chatha</option>
            <option value="Ahmadpur East">Ahmadpur East</option>
            <option value="Ali Khan Abad">Ali Khan Abad</option>
            <option value="Alipur">Alipur</option>
            <option value="Arifwala">Arifwala</option>
            <option value="Attock">Attock</option>
            <option value="Bhera">Bhera</option>
            <option value="Bhalwal">Bhalwal</option>
            <option value="Bahawalnagar">Bahawalnagar</option>
            <option value="Bahawalpur">Bahawalpur</option>
            <option value="Bhakkar">Bhakkar</option>
            <option value="Burewala">Burewala</option>
            <option value="Chillianwala">Chillianwala</option>
            <option value="Chakwal">Chakwal</option>
            <option value="Chichawatni">Chichawatni</option>
            <option value="Chiniot">Chiniot</option>
            <option value="Chishtian">Chishtian</option>
            <option value="Daska">Daska</option>
            <option value="Darya Khan">Darya Khan</option>
            <option value="Dera Ghazi Khan">Dera Ghazi Khan</option>
            <option value="Dhaular">Dhaular</option>
            <option value="Dina">Dina</option>
            <option value="Dinga">Dinga</option>
            <option value="Dipalpur">Dipalpur</option>
            <option value="Faisalabad">Faisalabad</option>
            <option value="Ferozewala">Ferozewala</option>
            <option value="Fateh Jhang">Fateh Jang</option>
            <option value="Ghakhar Mandi">Ghakhar Mandi</option>
            <option value="Gojra">Gojra</option>
            <option value="Gujranwala">Gujranwala</option>
            <option value="Gujrat">Gujrat</option>
            <option value="Gujar Khan">Gujar Khan</option>
            <option value="Hafizabad">Hafizabad</option>
            <option value="Haroonabad">Haroonabad</option>
            <option value="Hasilpur">Hasilpur</option>
            <option value="Haveli Lakha">Haveli Lakha</option>
            <option value="Jatoi">Jatoi</option>
            <option value="Jalalpur">Jalalpur</option>
            <option value="Jattan">Jattan</option>
            <option value="Jampur">Jampur</option>
            <option value="Jaranwala">Jaranwala</option>
            <option value="Jhang">Jhang</option>
            <option value="Jhelum">Jhelum</option>
            <option value="Kalabagh">Kalabagh</option>
            <option value="Karor Lal Esan">Karor Lal Esan</option>
            <option value="Kasur">Kasur</option>
            <option value="Kamalia">Kamalia</option>
            <option value="Kamoke">Kamoke</option>
            <option value="Khanewal">Khanewal</option>
            <option value="Khanpur">Khanpur</option>
            <option value="Kharian">Kharian</option>
            <option value="Khushab">Khushab</option>
            <option value="Kot Addu">Kot Addu</option>
            <option value="Jauharabad">Jauharabad</option>
            <option value="Lahore">Lahore</option>
            <option value="Lalamusa">Lalamusa</option>
            <option value="Layyah">Layyah</option>
            <option value="Liaquat Pur">Liaquat Pur</option>
            <option value="Lodhran">Lodhran</option>
            <option value="Malakwal">Malakwal</option>
            <option value="Mamoori">Mamoori</option>
            <option value="Mailsi">Mailsi</option>
            <option value="Mandi Bahauddin">Mandi Bahauddin</option>
            <option value="Mian Channu">Mian Channu</option>
            <option value="Mianwali">Mianwali</option>
            <option value="Multan">Multan</option>
            <option value="Murree">Murree</option>
            <option value="Muridke">Muridke</option>
            <option value="Mianwali Bangla">Mianwali Bangla</option>
            <option value="Muzaffargarh">Muzaffargarh</option>
            <option value="Narowal">Narowal</option>
            <option value="Nankana Sahib">Nankana Sahib</option>
            <option value="Okara">Okara</option>
            <option value="Renala Khurd">Renala Khurd</option>
            <option value="Pakpattan">Pakpattan</option>
            <option value="Pattoki">Pattoki</option>
            <option value="Pir Mahal">Pir Mahal</option>
            <option value="Qaimpur">Qaimpur</option>
            <option value="Qila Didar Singh">Qila Didar Singh</option>
            <option value="Rabwah">Rabwah</option>
            <option value="Raiwind">Raiwind</option>
            <option value="Rajanpur">Rajanpur</option>
            <option value="Rahim Yar Khan">Rahim Yar Khan</option>
            <option value="Rawalpindi">Rawalpindi</option>
            <option value="Sadiqabad">Sadiqabad</option>
            <option value="Safdarabad">Safdarabad</option>
            <option value="Sahiwal">Sahiwal</option>
            <option value="Sangla Hill">Sangla Hill</option>
            <option value="Sarai Alamgir">Sarai Alamgir</option>
            <option value="Sargodha">Sargodha</option>
            <option value="Shakargarh">Shakargarh</option>
            <option value="Sheikhupura">Sheikhupura</option>
            <option value="Sialkot">Sialkot</option>
            <option value="Sohawa">Sohawa</option>
            <option value="Soianwala">Soianwala</option>
            <option value="Siranwali">Siranwali</option>
            <option value="Talagang">Talagang</option>
            <option value="Taxila">Taxila</option>
            <option value="Toba Tek Singh">Toba Tek Singh</option>
            <option value="Vehari">Vehari</option>
            <option value="Wah Cantonment">Wah Cantonment</option>
            <option value="Wazirabad">Wazirabad</option>
            <option value="" disabled>Sindh Cities</option>
            <option value="Badin">Badin</option>
            <option value="Bhirkan">Bhirkan</option>
            <option value="Rajo Khanani">Rajo Khanani</option>
            <option value="Chak">Chak</option>
            <option value="Dadu">Dadu</option>
            <option value="Digri">Digri</option>
            <option value="Diplo">Diplo</option>
            <option value="Dokri">Dokri</option>
            <option value="Ghotki">Ghotki</option>
            <option value="Haala">Haala</option>
            <option value="Hyderabad">Hyderabad</option>
            <option value="Islamkot">Islamkot</option>
            <option value="Jacobabad">Jacobabad</option>
            <option value="Jamshoro">Jamshoro</option>
            <option value="Jungshahi">Jungshahi</option>
            <option value="Kandhkot">Kandhkot</option>
            <option value="Kandiaro">Kandiaro</option>
            <option value="Karachi">Karachi</option>
            <option value="Kashmore">Kashmore</option>
            <option value="Keti Bandar">Keti Bandar</option>
            <option value="Khairpur">Khairpur</option>
            <option value="Kotri">Kotri</option>
            <option value="Larkana">Larkana</option>
            <option value="Matiari">Matiari</option>
            <option value="Mehar">Mehar</option>
            <option value="Mirpur Khas">Mirpur Khas</option>
            <option value="Mithani">Mithani</option>
            <option value="Mithi">Mithi</option>
            <option value="Mehrabpur">Mehrabpur</option>
            <option value="Moro">Moro</option>
            <option value="Nagarparkar">Nagarparkar</option>
            <option value="Naudero">Naudero</option>
            <option value="Naushahro Feroze">Naushahro Feroze</option>
            <option value="Naushara">Naushara</option>
            <option value="Nawabshah">Nawabshah</option>
            <option value="Nazimabad">Nazimabad</option>
            <option value="Qambar">Qambar</option>
            <option value="Qasimabad">Qasimabad</option>
            <option value="Ranipur">Ranipur</option>
            <option value="Ratodero">Ratodero</option>
            <option value="Rohri">Rohri</option>
            <option value="Sakrand">Sakrand</option>
            <option value="Sanghar">Sanghar</option>
            <option value="Shahbandar">Shahbandar</option>
            <option value="Shahdadkot">Shahdadkot</option>
            <option value="Shahdadpur">Shahdadpur</option>
            <option value="Shahpur Chakar">Shahpur Chakar</option>
            <option value="Shikarpaur">Shikarpaur</option>
            <option value="Sukkur">Sukkur</option>
            <option value="Tangwani">Tangwani</option>
            <option value="Tando Adam Khan">Tando Adam Khan</option>
            <option value="Tando Allahyar">Tando Allahyar</option>
            <option value="Tando Muhammad Khan">Tando Muhammad Khan</option>
            <option value="Thatta">Thatta</option>
            <option value="Umerkot">Umerkot</option>
            <option value="Warah">Warah</option>
            <option value="" disabled>Khyber Cities</option>
            <option value="Abbottabad">Abbottabad</option>
            <option value="Adezai">Adezai</option>
            <option value="Alpuri">Alpuri</option>
            <option value="Akora Khattak">Akora Khattak</option>
            <option value="Ayubia">Ayubia</option>
            <option value="Banda Daud Shah">Banda Daud Shah</option>
            <option value="Bannu">Bannu</option>
            <option value="Batkhela">Batkhela</option>
            <option value="Battagram">Battagram</option>
            <option value="Birote">Birote</option>
            <option value="Chakdara">Chakdara</option>
            <option value="Charsadda">Charsadda</option>
            <option value="Chitral">Chitral</option>
            <option value="Daggar">Daggar</option>
            <option value="Dargai">Dargai</option>
            <option value="Darya Khan">Darya Khan</option>
            <option value="Dera Ismail Khan">Dera Ismail Khan</option>
            <option value="Doaba">Doaba</option>
            <option value="Dir">Dir</option>
            <option value="Drosh">Drosh</option>
            <option value="Hangu">Hangu</option>
            <option value="Haripur">Haripur</option>
            <option value="Karak">Karak</option>
            <option value="Kohat">Kohat</option>
            <option value="Kulachi">Kulachi</option>
            <option value="Lakki Marwat">Lakki Marwat</option>
            <option value="Latamber">Latamber</option>
            <option value="Madyan">Madyan</option>
            <option value="Mansehra">Mansehra</option>
            <option value="Mardan">Mardan</option>
            <option value="Mastuj">Mastuj</option>
            <option value="Mingora">Mingora</option>
            <option value="Nowshera">Nowshera</option>
            <option value="Paharpur">Paharpur</option>
            <option value="Pabbi">Pabbi</option>
            <option value="Peshawar">Peshawar</option>
            <option value="Saidu Sharif">Saidu Sharif</option>
            <option value="Shorkot">Shorkot</option>
            <option value="Shewa Adda">Shewa Adda</option>
            <option value="Swabi">Swabi</option>
            <option value="Swat">Swat</option>
            <option value="Tangi">Tangi</option>
            <option value="Tank">Tank</option>
            <option value="Thall">Thall</option>
            <option value="Timergara">Timergara</option>
            <option value="Tordher">Tordher</option>
            <option value="" disabled>Balochistan Cities</option>
            <option value="Awaran">Awaran</option>
            <option value="Barkhan">Barkhan</option>
            <option value="Chagai">Chagai</option>
            <option value="Dera Bugti">Dera Bugti</option>
            <option value="Gwadar">Gwadar</option>
            <option value="Harnai">Harnai</option>
            <option value="Jafarabad">Jafarabad</option>
            <option value="Jhal Magsi">Jhal Magsi</option>
            <option value="Kacchi">Kacchi</option>
            <option value="Kalat">Kalat</option>
            <option value="Kech">Kech</option>
            <option value="Kharan">Kharan</option>
            <option value="Khuzdar">Khuzdar</option>
            <option value="Killa Abdullah">Killa Abdullah</option>
            <option value="Killa Saifullah">Killa Saifullah</option>
            <option value="Kohlu">Kohlu</option>
            <option value="Lasbela">Lasbela</option>
            <option value="Lehri">Lehri</option>
            <option value="Loralai">Loralai</option>
            <option value="Mastung">Mastung</option>
            <option value="Musakhel">Musakhel</option>
            <option value="Nasirabad">Nasirabad</option>
            <option value="Nushki">Nushki</option>
            <option value="Panjgur">Panjgur</option>
            <option value="Pishin Valley">Pishin Valley</option>
            <option value="Quetta">Quetta</option>
            <option value="Sherani">Sherani</option>
            <option value="Sibi">Sibi</option>
            <option value="Sohbatpur">Sohbatpur</option>
            <option value="Washuk">Washuk</option>
            <option value="Zhob">Zhob</option>
            <option value="Ziarat">Ziarat</option>
        </select>
      </div>
      <div class="form-group">
        <label>Email</label>
        <input required type="email" name="email" class="form-control" placeholder="Enter your Email">
      </div>
      <div class="form-group">
        <label>Password</label>
        <input required type="password" name="pwd" class="form-control" placeholder="Password">
      </div>
      <input type="submit" value="Sign Up" name="pat_reg">
      <a href="p_login.php" class="form_link">Login</a>
    </form>
    <div>
      <?php
        if(isset($result['errorMessage'])){
          echo '<br><p class="errorMsg">'.$result['errorMessage'].'</p>';
        }
        if(isset($result['successMessage'])){
          //echo '<br><p class="successMsg">'.$result['successMessage'].'</p>';
          header("location: p_login.php");
        }
      ?>
    </div>

  </div>
</body>
</html>

