<?php
if (empty($_POST['firstname']) || empty($_POST['middlename']) || empty($_POST['lastname']) || empty($_POST['dateofbirth']) || empty($_POST['gender']) || empty($_POST['state']) || empty($_POST['city']) ||empty($_POST['street']) ||empty($_POST['phonep']) ||empty($_POST['yearlyvacationdays']) || empty($_POST['salary'])||  empty($_POST['phones']) || empty($_POST['hiredate']) || empty($_POST['educationstatus']) || empty($_FILES['photo']) || empty($_POST['email']) || empty($_FILES['file']) || empty($_POST['department']) || empty($_POST['position'])) {
    // One or more inputs are empty
    echo '<script>
    alert("One or more inputs are empty. Please fill in all required fields.");
    window.location.href = "addbranchmanager.php";
    </script>';
} else {
    // All inputs are not empty
    require_once "connection.php";
    $result = mysqli_query($conn, "SELECT MAX(id) FROM manager");
    $row = mysqli_fetch_row($result);
    $last_id = $row[0];
    $new_value = "M" . $last_id + 1;
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $dateofbirth = $_POST['dateofbirth'];
    $gender = $_POST['gender'];
    $state = $_POST['state'];
    $city = $_POST['city'];
    $street = $_POST['street'];
    $phonep = $_POST['phonep'];
    $phones = $_POST['phones'];
    $hiredate = $_POST['hiredate'];
    $educationstatus = $_POST['educationstatus'];
    $yearlyvacationdays = $_POST['yearlyvacationdays'];
    $basesalary = $_POST['salary'];
    if (isset($_FILES['photo'])) {
        $photo = $_FILES['photo']['name'];
        $photo_contents = file_get_contents($_FILES['photo']['tmp_name']);
        $photo_contents = mysqli_real_escape_string($conn, $photo_contents);
        // ...
    
    }
    $email = $_POST['email'];
    if (isset($_FILES['file'])) {
        
        $file = $_FILES['file']['name'];
        // ...
            $file_contents = file_get_contents($_FILES['file']['tmp_name']);
            $file_contents = mysqli_real_escape_string($conn, $file_contents);
            // ...
        
    }
    $departmentID = $_POST['department'];
    $positionID = $_POST['position'];
    
    // Insert data into manager table
    $sql = "INSERT INTO `manager`(`managerID`,`firstname`, `middlename`, `lastname`, `dateofbirth`, `gender`, `state`, `city`, `street`, `primary_phone`, `secondary_phone`, `dateofjoin`, `education_status`, `manager_photo`, `email`,`managerfile`,`yearlyvacationdays`,`basesalary`, `departmentID`,  `positionID`) 
            VALUES ('$new_value', '$firstname', '$middlename', '$lastname', '$dateofbirth', '$gender', '$state', '$city', '$street', '$phonep', '$phones', '$hiredate', '$educationstatus', '$photo_contents', '$email',  '$file_contents', '$yearlyvacationdays', '$basesalary', '$departmentID', '$positionID')";
    if ($conn->query($sql) === TRUE) {
        // Data inserted successfully
        // ...
        if ($conn->affected_rows > 0 ) {
            // Data inserted successfully
            // Data inserted successfully
            $employeeID = $conn->insert_id;
    
            // Calculate tax rate and deduction amount based on base salary
            if ($basesalary <= 600) {
                $taxRate=0;
                $dAmount=0;
            } elseif ($basesalary <= 1650) {
                $deduction=60;
                $taxRate=0.1;
                $dAmount=($basesalary*0.1)-60;
            } elseif ($basesalary <= 3200) {
                $deduction=142.50;
                $taxRate=0.15;
                $dAmount=($basesalary*0.15)-142.50;
            } elseif ($basesalary <= 5250) {
                $deduction=302.50;
                $taxRate=0.2;
                $dAmount=($basesalary*0.2)-302.50;
            } elseif ($basesalary <= 7800){
                $deduction=565;
                $taxRate=0.25;
                $dAmount=($basesalary*0.25)-565;
            } elseif ($basesalary <= 10900) {
                $deduction=955;
                $taxRate=0.3;
                $dAmount=($basesalary*0.3)-955;
            } else {
                $deduction=1500;
               $taxRate=0.35;
               $dAmount=($basesalary*0.35)-1500; 
            }
            
            
            // Calculate employee pension
            $employeePension=0.07; 
           $ePension=$basesalary*0.07; 
           $deductionAmount=$ePension+$dAmount; 
            // Insert data into deduction table
            $sql = "INSERT INTO deduction (employeeID, taxRate, deductionAmount, Pension,deductionType)
                VALUES ($employeeID, $taxRate, $deductionAmount, $employeePension,'pension and tax')";
            $conn->query($sql);
        echo '<script>
        alert("data inserted successfully.");
        </script>';
    } else {
        // Error inserting data
        echo '<script>
        alert("error inserting the data.");
        </script>';
    }
}
$conn->close();
echo '<script>
window.location.href = "addbranchmanager.php.php";
</script>';
}
?>