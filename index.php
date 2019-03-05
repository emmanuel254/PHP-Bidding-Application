<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/bidding-system/init.php';
  include 'heading/header.php';
  include 'navigation.php';
  
  if (!is_logged_in()) {
    header('Location: login.php');
  }

  $date = date('Y:m:d');
  $sqlQuery = $db->query("SELECT * FROM Items");
  //adding a bid to the system
     if (isset($_GET['add-bid'])) {
        $user = 1;
        $price = $_POST['price'];
        $item_id = $_POST['item_id'];
        
        $sql = $db->query("SELECT * FROM bidding_history WHERE item_id = '$item_id'");
        $details = mysqli_fetch_assoc($sql);
        //query to select the specific item
        $sql2 = $db->query("SELECT * FROM Items WHERE item_id = '$item_id'");
        $result = mysqli_fetch_assoc($sql2);
        $start_price = $result['starting_price'];
        $reserve_price = $result['reserve_price'];
        $count = mysqli_num_rows($sql);
        if ($count < 1) {
          $insert = "INSERT INTO bidding_history(start_price, entered_price,item_id) 
          VALUES('$start_price','$price','$item_id')";
          $db->query($insert) or die(mysqli_error($db));
        }else{
          //increase start price by 0.5 dollars;
          $start_price = $details['start_price'] + 0.5;
          $insert ="UPDATE bidding_history SET start_price = '$start_price'
          WHERE item_id = '$item_id'";
          $db->query($insert) or die(mysqli_error($db));
        }
        if ($price >= $reserve_price && $result['maximum'] == 0) {
          $db->query("UPDATE Items SET maximum = 1 WHERE item_id = '$item_id'") or die(mysqli_error($db));
          $db->query("UPDATE bidding_history SET start_price = '$reserve_price' 
            WHERE item_id = '$item_id'");
        }
        //if this biddder sets the maximum price let him to be the pre-winner of the whole bid
        if ($price > $details['entered_price']) {
          $db->query("UPDATE bidding_history SET entered_price = '$price',account_id = '$user_id' 
            WHERE item_id = '$item_id'");
        }
        $db->query("INSERT INTO bids (username,price,item_id) VALUES('$user_id','$price','$item_id')");
        $id = $db->insert_id;
        //check if reserve price is already met
        if ($result['maximum'] == 1) {
          $db->query("UPDATE bids SET reserve_price_met = 1 WHERE bid_number = '$id'") 
          or die(mysqli_error($db));
        }
        header('Location: index.php?add=1&id='.$item_id);
      }

 
  if (isset($_GET['add'])) {
  	$id = $_GET['id'];
    $sql2 = $db->query("SELECT * FROM Items WHERE item_id = '$id'");
    $result = mysqli_fetch_assoc($sql2);

  	?>
      <div class="row">
        <div class="col-md-2"></div>
      	<div class="col-md-2">
      		<div class="product">
				<h3 class="text-center"><?=$result['title'];?></h3>
				<img src="<?=$result['photograph']?>" width="100%" height="150px" class=""><a href=""></a>
				<p class="list-price text-danger"><?=$result['description'];?></p>
				<p>Start Price: $ <?=$result['starting_price'];?></p>
			    </div>
			</div>
      	<div class="col-md-6">
      		<h3 class="text-center text-primary">ALL BIDDERS</h3>
      		<table class="table table-bordered table-striped table-condensed" style="margin-top: 20px;">
      			<thead>
      				<th>Bidder</th>
      				<th>Price</th>
      				<th>Status</th>
      			</thead>
      			<tbody>
              <?php
                 $bidders = $db->query("SELECT * FROM bids WHERE item_id = '$id'");
                 while($bidder = mysqli_fetch_assoc($bidders)):
                  $id = $bidder['username'];
                  $query = $db->query("SELECT * FROM account WHERE account_id = '$id'");
                  $r = mysqli_fetch_assoc($query);
              ?>
      				<tr>
      					<td><?=$r['username'];?></td>
      					<td>$ <?=$bidder['price'];?></td>
      					<td><?php if($bidder['reserve_price_met'] == 0){
                   echo('Reserve not met');
                }else{
                  echo('Reserve Met');
                } ?></td>
      				</tr>
            <?php endwhile; ?>
      			</tbody>
      		</table><hr>
      		<h4 class="text-center text-primary">BID ALSO</h4>
      		<form method="post" action="index.php?add-bid=<?=$id;?>">
      	       <div class="row">
	      		<div class="col-md-6">
	      		Price:
	      		<input type="text" name="price" class="form-control">
	      		<input type="hidden" name="item_id" value="<?=$result['item_id']; ?>">
            <a href="index.php" class="btn btn-xs btn-danger" style="margin-top: 20px">cancel</a>
	      		</div>
	      		<div class="col-md-6">
	      		Confirm:
	      		<input type="submit" class="add-button pull-right" >	
	      		</div>
      	   </div>
      	</form>
      	</div>
      	<div class="col-md-2"></div>
      </div>
  	<?php
  } else {   ?>
<h1 class="text-center"><i>ALL PRODUCTS</i></h1>
<div class="container-fluid">
<div class="row">
	<div class="col-md-2"></div>
	<div class="col-md-8">
		<div class="row">
		  <?php while($product = mysqli_fetch_assoc($sqlQuery)):
           //check if user has already left a feedback
            $item_id = $product['item_id'];  
            $feeds = $db->query("SELECT * FROM feedback WHERE item_id = '$item_id'");
            $feed = mysqli_num_rows($feeds);
            $feedback = mysqli_fetch_assoc($feeds);
            ?>
			<div class="col-md-3">
			<div class="product">
				<h3 class="text-center"><?=$product['title'];?></h3>
				<img src="<?=$product['photograph']; ?>" width="100%" height="150px" class=""><a href=""></a>
				DESCRIPTION:<p class="list-price text-danger"><?=$product['description'];?></p>
				<p>Start Price: $ <?=$product['starting_price'];?></p>
			    </div>
        <?php if($product['maximum'] != 10):?>
				<a href="index.php?add=1&id=<?=$product['item_id']?>" class="add-button" >
				 <span class="glyphicon glyphicon-shopping-cart"></span> bid item!</a>
        <?php else: ?>
          <div class="feedback">
            <strong><i>FEEDBACK:</i></strong> <?=$feedback['comment'];?><hr>
            <strong><i>RATINGS:</i></strong> <?=$feedback['rating'];?><hr>
          </div>
        <?php endif; ?>
			</div>
		<?php endwhile;?>
			</div>
		 
		</div>
	</div>
	<div class="col-md-2"></div>
</div>
</div>

<?php } include 'footer.php'; ?>
