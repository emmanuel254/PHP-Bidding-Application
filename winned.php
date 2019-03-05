<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/bidding-system/init.php';
  include 'heading/header.php';
  include 'navigation.php';
  $errors = [];

  if (isset($_GET['complete'])) {
  	$item_id = $_GET['complete'];

  	//remove item from bidding
  	$db->query("UPDATE Items SET maximum = 10 WHERE item_id = '$item_id'");

  	$sql = $db->query("SELECT * FROM bidding_history WHERE item_id = '$item_id'");
  	$result = mysqli_fetch_assoc($sql);
  	$winner = $result['account_id'];
  	//echo($winner);
    
    $winnerdetails = $db->query("SELECT * FROM account WHERE account_id = '$winner'");
    $ans = mysqli_fetch_assoc($winnerdetails);
    if ($ans['winned_bids'] == 0) {
      $db->query("UPDATE account SET winned_bids = '$item_id' WHERE account_id = '$winner'");
    }else{
    	$items = $ans['winned_bids'].','.$item_id;
    	echo $items;
    	$db->query("UPDATE account SET winned_bids = '$items' WHERE account_id = '$winner'");
    }
    header('Location: my_account.php');

  }else{

  //gettng winned bids for the user
    $query = $db->query("SELECT * FROM account WHERE account_id = '$user_id'");
     $result = mysqli_fetch_assoc($query);
    if ($result['winned_bids'] != 0) {
?>

   <div class="row">
   	 <div class="col-md-2"></div>
   	 <div class="col-md-8">
   	 	<h3 class="text-center">WINNED BIDS</h3>
   	 	<?php 
           $string = $result['winned_bids'];
           $items = explode(',', $string);

           foreach ($items as $item):
           	//fetch the amount to be paid
           	$amount = $db->query("SELECT * FROM bidding_history WHERE item_id = '$item'");
           	$pay = mysqli_fetch_assoc($amount);
           	//fetch user details
           	$sql = $db->query("SELECT i.title,i.photograph,i.description,a.username 
           		               FROM Items i
           		               RIGHT JOIN account a
           		               ON i.item_id = '$item'");
           	$request = mysqli_fetch_assoc($sql);
           	//check if user has already left a feedback
           	$feeds = $db->query("SELECT * FROM feedback WHERE item_id = '$item'");
           	$feedback = mysqli_num_rows($feeds);
   	 	?>
   	 	<div class="row">
   	 	<div class="col-md-3">
   	 		<img src="<?=$request['photograph']?>" width="100%" height="150px" class=""><a href=""></a>
   	 	</div>
   	 	<div class="col-md-9">
   	 	<table class="table table-bordered table-striped table-condensed">
   	 		<thead>
   	 			<th>Title</th>
   	 			<th>Description</th>
   	 			<th>Amount Payable</th>
   	 		</thead>
   	 		<tbody>
   	 			<tr>
   	 				<td><?=$request['title'];?></td>
   	 				<td><?=$request['description'];?></td>
   	 				<td>$ <?=$pay['start_price'];?></td>
   	 			</tr>
   	 		</tbody>	
   	 	</table>
   	 <?php if($feedback < 1):?>
   	 	<a href="winned.php?feedback=<?=$item;?>" class="btn btn-xs btn-secondary pull-right">Leave feedback</a>
   	 <?php endif; ?>
   	   </div>
   	 </div>
   	<?php endforeach; ?>
   </div>
   	 <div class="col-md-2">
   	 	<?php
     if (isset($_GET['feedback'])) {
     	$item_id = $_GET['feedback'];
     	$comment = ((isset($_POST['comment']) && !empty($_POST['comment']))?($_POST['comment']):'');
     	$rating = ((isset($_POST['rating']) && !empty($_POST['rating']))?($_POST['rating']):'');
     	if ($_POST) {
     		if (empty($_POST['comment']) || empty($_POST['rating'])) {
				$errors[] = 'Fill all fields';
			}
			//check for errors
			if (!empty($errors)) {
			  
			}else{
				//insert into database
               $db->query("INSERT INTO feedback (winning_bidder,item_id,comment,rating) 
               	VALUES('$user_id','$item_id','$comment','$rating')");
               //header('Location:winned.php');
               ?><script>
               	window.location = 'http://localhost/bidding-system/winned.php';
               </script><?php
			}
     	}
 ?>
      <h3 class="text-center text-primary">Leave Feedback</h3>
      <?=display_errors($errors);?>
      <form class="form-group" action="winned.php?feedback=<?=$item_id;?>" method="post">
      Comment:
       <input type="text" name="comment" class="form-control">
      Rating 1-10:
       <input type="number" name="rating" max="10" min="0" class="form-control">
       <input type="submit" class="btn btn-xs btn-warning pull-right" value="submit" style="margin-top: 10px;">
      </form>
   	 </div>
   </div>

<?php } }else{echo('<p class="text-center bg-danger">You have winned zero bids</p>');} } ?>
