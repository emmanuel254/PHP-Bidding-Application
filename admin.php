<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/bidding-system/init.php';
  include 'heading/header.php';
  include 'navigation.php';
  
  $dateQuery = $db->query("SELECT * FROM Items");

  while ($expdate = mysqli_fetch_assoc($dateQuery)) {
     $expirydate = $expdate['end_date'];
    $date = (date("Y-m-d"));

  if ($expirydate == $date && $expdate['maximum'] != 10) {
    $item_id = $expdate['item_id'];
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
  }
    
  }

?>
<h1 class="text-center text-danger">COMPLETED BIDS</h1>
<div class="container-fluid">
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<div class="row">
		  <?php
		  $sqlQuery = $db->query("SELECT * FROM Items WHERE maximum = 10");
		   while($product = mysqli_fetch_assoc($sqlQuery)):
           //check if user has already left a feedback
            $item_id = $product['item_id'];  
            $feeds = $db->query("SELECT * FROM bidding_history WHERE item_id = '$item_id'");
            $feed = mysqli_num_rows($feeds);
            $price = mysqli_fetch_assoc($feeds);
            //get winner
            $winner = $price['account_id'];
            $win = $db->query("SELECT * FROM account WHERE account_id = '$winner'");
            $winner_details = mysqli_fetch_assoc($win);
            //get seller
            $seller = $product['owner'];
            $sell = $db->query("SELECT * FROM account WHERE account_id = '$seller'");
            $seller_details = mysqli_fetch_assoc($sell);

            ?>
			<div class="col-md-4">
			<div class="product">
				<h3 class="text-center"><?=$product['title'];?></h3>
				<img src="<?=$product['photograph']; ?>" width="100%" height="150px" class=""><a href=""></a>
			    </div>
        
          <div class="feedback"><hr>
          	<b><i>SELLER :</i></b>  <?=$seller_details['username'];?><br>
          	<b><i>START PRICE:</i></b> $ <?=$product['starting_price'];?><br>
          	<b><i>PRICE REACHED:</i></b> $ <?=$price['start_price'];?><br>
            <b><i>RESERVE PRICE:</i></b> $ <?=$product['reserve_price'];?><hr>
            <i>Winner: </i><b><?=$winner_details['username'];?></b><br>
            <i>Winning Price: </i><b><?=$price['entered_price'];?></b><br>
            <i>Sell Price: </i><b><?=$price['start_price'];?></b><br>
            <i>Contribution: </i><b><?=$price['start_price'] * 0.1;?></b><hr>
          </div>
			</div>
		<?php endwhile;?>
			</div>
		 
		</div>
	</div>
	<div class="col-md-1"></div>
</div>
<h1 class="text-center">CONTINUING BIDS</h1>
<div class="container-fluid">
<div class="row">
	<div class="col-md-1"></div>
	<div class="col-md-10">
		<div class="row">
		  <?php
		  $sqlQuery2 = $db->query("SELECT * FROM Items WHERE maximum != 10");
		   while($products = mysqli_fetch_assoc($sqlQuery2)):
           //check if user has already left a feedback
            $item_id = $products['item_id'];  
            $feeds = $db->query("SELECT * FROM bidding_history WHERE item_id = '$item_id'");
            $feed = mysqli_num_rows($feeds);
            $price = mysqli_fetch_assoc($feeds);
            //get seller
            $seller = $products['owner'];
            $sell = $db->query("SELECT * FROM account WHERE account_id = '$seller'");
            $seller_details = mysqli_fetch_assoc($sell);
            ?>
			<div class="col-md-4">
			<div class="product">
				<h3 class="text-center"><?=$products['title'];?></h3>
				<img src="<?=$products['photograph']; ?>" width="100%" height="150px" class=""><a href=""></a>
			    </div>
        
          <div class="feedback"><hr>
          	<b><i>SELLER :</i></b>  <?=$seller_details['username'];?><br>
          	 <b><i>START PRICE:</i></b> $ <?=$products['starting_price'];?><br>
          	<b><i>PRICE REACHED:</i></b> $ <?=$price['start_price'];?><br>
            <b><i>RESERVE PRICE:</i></b> $ <?=$products['reserve_price'];?><hr>
          </div>
			</div>
		<?php endwhile;?>
			</div>
		 
		</div>
	</div>
	<div class="col-md-1"></div>
</div>
</div>
