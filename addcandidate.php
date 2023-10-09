<?php
include 'db_con.php';

$msg = '';

// Retrieve all elections
$electionsQuery = "SELECT * FROM election";
$electionsResult = mysqli_query($conn, $electionsQuery);

if (isset($_POST['submit'])) {
    $election_id = $_POST['election_id'];

    // Retrieve form data
    $name = $_POST['name'];
    $citizenship_num = $_POST['citizenship_num'];
    $taddress = $_POST['taddress'];
    $paddress = $_POST['paddress'];
    $dob = $_POST['dob'];
    $father = $_POST['father'];
    $mother = $_POST['mother'];
    $vote_reason = $_POST['vote_reason'];
    $vote_reason = mysqli_real_escape_string($conn, $vote_reason);

    // Upload Candidate's Image
    $imgname = $_FILES['img']['name'];
    $imgtemp = $_FILES['img']['tmp_name'];
    $extension = pathinfo($imgname, PATHINFO_EXTENSION);
    $newname = str_replace(' ', '-', $name) . '.' . $extension;
    $imagePath = "electionpics/Candidate/" . $newname;
    move_uploaded_file($imgtemp, $imagePath);

    // Upload Citizenship Image
    $citizenship = $_FILES['citizenship']['name'];
    $citizenshipTemp = $_FILES['citizenship']['tmp_name'];
    $citizenshipPath = "electionpics/Citizenship/" . $citizenship; // Update folder name as needed
    move_uploaded_file($citizenshipTemp, $citizenshipPath);

    if (empty($name) || empty($citizenship_num) || empty($taddress) || empty($paddress) || empty($dob) || empty($father) || empty($mother)) {
        $msg = "Please enter all details.";
    } else {
        $sql = "INSERT INTO candidate (name, img, citizenship, citizenship_num, dob, paddress, taddress, father, mother, election_id, vote_reason)
        VALUES ('$name', '$newname', '$citizenship', '$citizenship_num', '$dob', '$paddress', '$taddress', '$father', '$mother', '$election_id', '$vote_reason')";
        

        if (mysqli_query($conn, $sql)) {
            $msg = "Candidate added successfully.";
        } else {
            $msg = "Error: " . mysqli_error($conn);
        }
    }

    
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add candidate</title>
    <link rel="stylesheet" href="adddcandidate.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .form {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            width: 400px;
        }

        .title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .inputs {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
        }

        .button {
            text-align: center;
            margin-top: 20px;
        }

        .msg {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form">
            <form action="" name="myform" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">
                <div class="title">Add Candidate</div>
                <table>
                <tr>
                        <td><label for="election_id">Select Election:</label></td>
                        <td>
                            <select id="election_id" name="election_id">
                                <?php
                                while ($election = mysqli_fetch_assoc($electionsResult)) {
                                    echo '<option value="' . $election['id'] . '">' . $election['election_title'] . '</option>';
                                }
                                ?>
                            </select>
                        </td>
                    </tr>

                <tr>
                        <td><label for="name">Name<label></td>
                        <td><input type="text" name="name" id="name" value=" "><br></td>
                    </tr>
                    <tr>
                        <td><label for="pp">Your Image<label></td>
                        <td><input type="file"accept=".jpg,.jpge"  class="inputs" id="pp" name="img"  ><br></td>
                    </tr>

                    <tr>
                        <td><label for="dob">Date of Birth:</label></td>
                        <td>  <input type="date" id="dob" placeholder="Date of Birth" class="inputs" name="dob"  ></td>
                    </tr>


                    <tr>
                        <td><label for="fa">Father:</label></td>
                        <td>  <input type="text" id="fa"  class="inputs" name="father"   ></td>
                    </tr>

                    <tr>
                        <td><label for="mo">Mother:</label></td>
                        <td>  <input type="text" id="mo"  class="inputs" name="mother"  value=" " ></td>
                    </tr>
                         
                   
                    
                    <tr>                     
                        <td><label for="taddress">Temprory Adderess: </label></td>
                        <td> <input type="text" id="taddress"" class="inputs" value=""  name="taddress" required></td>
                    </tr>

                    <tr>

                        <td><label for="paddress">Permanent Adderess: </label></td>
                        <td><input type="text" id="paddress"" class="inputs" value="" name="paddress" required></td>
               
                    </tr>


                    <tr>

                        <td><label for="cid">Citizen-ship Number: </label></td>
                        <td><input type="text" id="cid" class="inputs" name="citizenship_num" value="" required></td>

                        </tr>
                    
                     <tr>
                        <td><label for="cship">Citizenship:</label></td>
                        <td><input type="file" accept=".jpg,.jpeg" id="cship" class="inputs" name="citizenship"><br></td>
                    </tr>

                    <tr>
                        <td><label for="vote_reason">Why should people vote for you?</label></td>
                        <td><textarea id="vote_reason" class="inputs" name="vote_reason" rows="4"></textarea></td>
                    </tr>

                </table>
               <div class="button">
                    <button type="submit" name="submit" id="submit">Add Candidate</button>
                    <a href="admindashboard.php">Go to Dashboard</a>
                </div>
                 <div class="msg">
                    <p id="error"><?php echo $msg; ?></p>
                </div>
            </form>
        </div>
    </div>
    <script>
        function validateForm(){
            var name = document.forms['myform']["fullname"].value;
            var email = document.forms["myform"]["email"].value;
            var password = document.forms["myform"]["password"].value;
            var cpassword = document.forms["myform"]["cpassword"].value;
            
           if(name==""||email==""||password==""||cpassword==""){
                document.getElementById("error").innerHTML="Please enter every detail";
                alert("Please enter every detail");
                return false;
            }

            if(password!=cpassword){
                alert("Paswword not matched");
                return false;
           }

           if (/^[a-zA-Z]+$/.test(name)!=true) {
            alert("Name can content only alphabets");
            return true;
   
            }

            if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email)!=true){
                alert("Not a email");
            return false;
            }

            //Password validation need more then 4 character
            var pLength=password.length;
            if(pLength<=4){
                alert("password too short");
                return false;
            }
 
         
           
        }
    

    </script>
    
</body>
</html>