<?php  

include '../../config/config.php';

if (isset($_GET['id'])) {
	$id = $_GET['id'];

	$query = mysqli_query($connection, "UPDATE peminjaman SET status=2 WHERE id='$id' ");

	if($query) {
		header("location:data-peminjaman.php");
	} else {
		echo "error : " . mysqli_error($connection);
	}
}


?>