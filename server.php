<?php 
	session_start();
	$username = "";
	$email = "";
	$errors = array();

	//connect to the database
	$db = mysqli_connect('localhost', 'root', '', 'registration');

	//if the register button clickec
	if (isset($_POST['register'])) {
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];

		//ensure that form fields are filled properly
		if (empty($username)) {
			array_push($errors, "Username is required"); 
		}

		if (empty($email)) {
			array_push($errors, "Email is required"); 
		}

		if (empty($password1)) {
			array_push($errors, "Password is required"); 
		}

		if ($password1 != $password2) {
			array_push($errors, "Password not Match");
		}

		//if there are no errors, save users to database
		if (count($errors) == 0) {
			$password = $password1; //encrypt password before storing in databases (security)
			$sql = "INSERT INTO users (username, email, password)
			VALUES('$username', '$email', '$password')";

			mysqli_query($db, $sql);
			$_SESSION['username'] = $username;
			$_SESSION['success'] = "You are logged in";
			header('location: index.php'); //homepage
		}


	}

	//log user in from login page
	if (isset($_POST['login'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];

		//ensure that form fields are filled properly
		if (empty($username)) {
			array_push($errors, "Username is required"); 
		}
		if (empty($password)) {
			array_push($errors, "Password is required"); 
		}
		if (count($errors) == 0 ) {
			$password = $password;
			$query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
			$result = mysqli_query($db, $query);
			if (mysqli_num_rows($result) == 1) {
				//log user in
				$_SESSION['username'] = $username;
				$_SESSION['success'] = "You are logged in";
				header('location: index.php'); //homepage
			}else{
				array_push($errors, "The Username or Password is Incorrect");
			}
		}
	}

	//logout
	if (isset($_GET['logout'])){
		session_destroy();
		unset($_SESSION['username']);
		header('location: login.php');
	}



?>