<?php
if (empty($_POST['page'])) {  // When no page is sent from the client; The initial display
                                // You may use if (!isset($_POST['page'])) instead of empty(...).
    $display_type = 'none';  // This variable will be used in 'view_startpage.php'.
                              // It will display the start page without any box, i.e., no SignIn box, no Join box, ...
    include ('SignInPage.html');
    exit();
}


require('model.php');

// When commands come from StartPage
if ($_POST['page'] == 'StartPage')
{
    $command = $_POST['command'];
    switch($command) {  // When a command is sent from the client
        case 'SignIn':  // With username and password
            // if (there is an error in username and password) {
            if (!is_valid($_POST['username'], $_POST['password'])) {
                $error_msg_username1 = '* Wrong username, or';
                $error_msg_password1 = '* Wrong password'; // Set an error message into a variable.
                                                        // This variable will used in the form in 'view_startpage.php'.
                //$display_type = 'signin';  // It will display the start page with the SignIn box.
                                           // This variable will be used in 'view_startpage.php'.
                include('SignInPage.html');
            } 
            else {
                $username = $_POST['username'];
                include('MainPage.html');
            }
            exit();

        case 'Join':  // With username, password, email, some other information
            if (does_exist($_POST['username'])) {
                $error_msg_username2 = '* The user exists.';
                //$display_type = 'join';
                include('SignInPage.html');
            }
			else {
				if($_POST['password'] == $_POST['password2'])
				{
					if (insert_new_user($_POST['username'], $_POST['password'], $_POST['email'])) {
						//$display_type = 'signin';
						//if user creates account, should it bring them to signin or main?
						//check if password and confirm pwd is the same
						alert("Account Created.");
						$username = $_POST['username'];
						include('MainPage.html');
					} 					
				}
				else {
						$error_msg_username2 = '* Passwords do not match.';
						//$display_type = 'join';
						include('SignInPage.html');
					}
            }
            exit();
        //...
    }
}

// When commands come from 'MainPage'
else if ($_POST['page'] == 'MainPage') 
{
    // support commands
    
    $command = $_POST['command'];
    //alert($command);
	
    switch($command) {
        case 'SignOut':
            // go to 'StartPage'
            //$display_type = 'none';
            include('SignInPage.html');
            break;
			
		case 'DeleteAccount':
			delete_account($_POST['username']);
            include('SignInPage.html');
            break;
            
        // posting a Video
        case 'PostVideo':
			/* alert("Title: " . $_POST['title']);
			alert("url: " . $_POST['url']);
			alert("description: " . $_POST['description']);
			alert("tags: " . $_POST['tags']); */
			
            if (post_video($_POST['title'], $_POST['url'], $_POST['description'], $_POST['tags'], $_POST['postedBy']))  // in model.php
                echo 'Posted, refresh to see post';
            else
                echo 'Error';
            break;
        
        // list questions
        case 'ListVideos':
			//alert("order:" . $_POST['order']);
            $data = list_videos($_POST['order2'], $_POST['term']);  // in model.php
            $s = json_encode($data);			// send the JSON string of video posts records back to the client
            echo $s;
			break;
        
		//like video
		case 'Like':
			add_like($_POST['postID']);
			include('MainPage.html');
			break;
		
		//dislike video
		case 'Dislike':
			add_dislike($_POST['postID']);
			include('MainPage.html');
			break;
			
		//add comment
		case 'AddComment':
			//alert('postID: ' . $_POST['postID']);
			//alert('comment: ' . $_POST['comment']);
			if(add_comment($_POST['postID'],$_POST['comment']))
				echo 'Posted';
			else
				echo 'error';
			include('MainPage.html');
			break;
		case 'Test':
			alert("test");
        default:
            echo 'Unknown command - ' . $command . '<br>';
    }
}

//alert for testing
function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}
?>