<?php
    require_once 'classes.php';
    $database = new Database;

    $name = $email = $message = "";
    $errors = array('name'=>'', 'email'=>'', 'message'=>'', 'success'=>'', 'dbError'=>'');

    if(isset($_POST['submitContactForm'])) {

        $name = $_POST['name'];
        $email = $_POST['email'];
        $message = $_POST['message'];

        // check first name
        if(empty($name)){
            $errors['name'] = 'Your name is required';
        } else{
            if(!preg_match('/^[a-zA-Z\s]+$/', $name)){
                $errors['name'] = 'Name must be letters and space only';
            }
        }

        // check email
        if(empty($email)){
            $errors['email'] = 'Your email address is required';
        } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'Email must be a valid email address';
        }

        if(empty($message)){
            $errors['message'] = 'A message is required';
        }

        if(array_filter($errors)){
            // echo 'There are errors in the form';
        } else {
            unset($_POST['submitContactForm']);
            
            // $errors['success'] = "Thanks! We've received your message and will respond soon.";

           
            $datas = [
                "name" => $name,
                "email" => $email,
                "message" => $message
            ];    

            if ($database->insertdb("contact", $datas)) {
                $errors['success'] = "Thanks! We've received your message and will respond soon.";
            } else {
                $errors['dbError'] = 'error sending message to the database';
            }
        }
    }

?>