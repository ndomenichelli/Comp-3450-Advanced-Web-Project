<?php
// connect to MySQL DB
$conn = mysqli_connect('localhost','ndomenichellf9','ndomenichellf9424','C354_ndomenichellf9');

function insert_new_user($username, $password, $email)
{
    global $conn;
    
    if (does_exist($username))
        return false;
    else {
        $current_date = date('Ymd');
				
		$sql = "insert into Users values (NULL, '$username', '$password', '$email', $current_date)";
		$result = mysqli_query($conn, $sql);
		return $result;
		
    }
}

function is_valid($username, $password) 
{
    global $conn;
    
    $sql = "select * from Users where (Username = '$username' and Password = '$password')";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0)
        return true;
    else
        return false;
}

function does_exist($username) 
{
    global $conn;
    
    $sql = "select * from Users where (Username = '$username')";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0)
        return true;
    else
        return false;
}

function delete_account($username)
{
	global $conn;
	$sql = "delete from Users 
            where (Username = '$username')";
			
	if (mysqli_query($conn, $sql))
        return true;
    else
        return false;
}
function post_video($title, $url, $description, $tags, $username)  // post
{
    global $conn;
    
	$comment = "";
	$likes = 0;
	$postedBy = $username;
    // insert statement
    $sql = "insert into Videos values(NULL,'$title','$url','$description','$tags','$comment','$likes','$postedBy')";
    if (mysqli_query($conn, $sql))
        return true;
    else
        return false;
}
function list_videos($order2, $term)  // List all the questions containing $term
{
    global $conn;
    
	$orderBy = "PostId ASC";
	
	//alert($orderBy);
	
    $sql = "select * from Videos 
	where Tags like '%".$term."%'
	order by " . $order2;
    $result = mysqli_query($conn, $sql);
    
    $data = [];  // empty array
    $i = 0;	// fetch $no many rows and insert them into $data, so that $data will become a linear of rows
    while($row = mysqli_fetch_array($result)){
		$data[$i] = $row;
		$i++;
	}
	
    return $data; // return the above array
}

function add_like($postID){
	
	global $conn;
	
	$sql = "update Videos set likes = likes + 1 
            where (PostId = '$postID')";
			
	if (mysqli_query($conn, $sql))
        return true;
    else
        return false;
}

function add_dislike($postID){
	
	global $conn;
	
	$sql = "update Videos set likes = likes - 1 
            where (PostId = '$postID')";
			
	if (mysqli_query($conn, $sql))
        return true;
    else
        return false;
}

function add_comment($postID, $comment){
	
	global $conn;
	
	$sql = "update Videos set comment = CONCAT(comment,'$comment')
            where (PostId = '$postID')";
			
	if (mysqli_query($conn, $sql))
        return true;
    else
        return false;
}






























?>