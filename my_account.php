<?php
  require_once $_SERVER['DOCUMENT_ROOT'].'/bidding-system/init.php';
  include 'heading/header.php';
  include 'navigation.php';
  $saved_image = '';

  //$sqlQuery = $db->query("SELECT * FROM accesories WHERE featured = 1");
  
  //fired up when wanting to view items and add them
  if (isset($_GET['my_account'])) {
  
  if (isset($_GET['add'])) {
  	$title = ((isset($_POST['title']) && !empty($_POST['title']))?($_POST['title']):'');
  	$stp = ((isset($_POST['stp']) && !empty($_POST['stp']))?($_POST['stp']):'');
  	$reprice = ((isset($_POST['reprice']) && !empty($_POST['reprice']))?($_POST['reprice']):'');
  	$photo = ((isset($_POST['photo']) && !empty($_POST['photo']))?($_POST['photo']):'');
  	$description = ((isset($_POST['description']) && !empty($_POST['description']))?($_POST['description']):'');
  	$date = (date("Y:m:d"));
	$expdate = explode(':', $date);
	$d = $expdate[2] + 7;
	$newdate = $expdate[0].'-'.$expdate[1].'-'.$d;
	echo($photo);

	if ($_POST) {
		 $errors = [];
   
     $required = array(
      'title'    => 'Title',
      'stp'    => 'Price',
      'reprice' => 'Reserve Price',
      'description'=> 'Description',
     );
		 $allowed = array('jpg','png','jpeg','gif');
     foreach ($required as $field => $d) {
     	if ($_POST[$field] == '') {
     	   $errors[] = 'Please fill out the '.$d.' field';
     	}
     }
     $tmpLoc = '';
     $uploadPath = '';
    if($saved_image == '' && $_FILES['photo']['name'] != ''){

  	 $photo_name = $_FILES['photo']['name'];
  	 $nameArray = explode('.', $photo_name);
  	 $file_name = $nameArray[0];
  	 $file_ext = $nameArray[1];
  	 $type = $_FILES['photo']['type'];
  	 $mime = explode('/', $type);
  	 $mimeType = $mime[0];
  	 $mimeExt = $mime[1];
  	 $tmpLoc = $_FILES['photo']['tmp_name'];
  	 $fileSize = $_FILES['photo']['size'];
  	 $uploadName = md5(microtime()).'.'.$file_ext;
  	 $uploadPath = BASEURL.'/products/'.$uploadName;
  	 $dbPath = '/bidding-system/products/'.$uploadName;
  	 echo($dbPath);

  	 //filtering errors out
  	 if ($mimeType != 'image') {
  	 	$errors[] = 'File must be an image';
  	 }
  	 if (!in_array($file_ext, $allowed)) {
  	   $errors[] = 'The file must be a png,jpeg,jpg or a gif';
  	 }
  	 if ($fileSize > 1500000) {
  	 	$errors[] = 'Only files bellow 15MB is allowed';
  	 }
  	 }
  	 if (!empty($errors)) {
  	 	echo display_errors($errors);
  	 }else{
  	 	//move file to the specified folder
  	 	move_uploaded_file($tmpLoc, $uploadPath);
  	 	//insert accessory into database
      $sql = ("
        INSERT INTO  Items (title,starting_price,reserve_price,end_date,photograph,description,maximum,owner)
        VALUES ('$title','$stp','$reprice','$newdate','$dbPath','$description','0','$user_id')");

      $db->query($sql)or die(mysqli_error($db));
      header('Location: my_account.php');
    }
	}
  }

 ?>
 <div class="row">
 	<div class="col-md-2"></div>
 	<div class="col-md-8">
 		<form class="form-group" action="my_account.php?my_account&add=1" method="post" enctype="multipart/form-data">
 		<div class="row">
      				<div class="col-md-6">
      				<label for="email">Title: </label>
      			      <input type="title" name="title" class="form-control" 
      			      value="">	
      				</div>
      				<div class="col-md-6">
      				<label for="stp">Start Price: </label>
      			      <input type="text" name="stp" class="form-control" 
      			      value="">	
      				</div>
      			</div>
      			<div class="row">
      				<div class="col-md-6">
      				<label for="reprice">Reserve Price : </label>
      			      <input type="number" name="reprice" class="form-control" 
      			      value="">	
      				</div>
      				<div class="col-md-6">
      				<label for="photo">Photo: </label>
      			      <input type="file" name="photo" class="form-control" 
      			      value="">	
      				</div>
      			</div>
      			<div class="row"> 
      				<div class="col-md-6">
      				<label for="description">Description: </label>
      			      <textarea type="text" id="description" name="description" class="form-control" rows="4"></textarea>	
      				</div>
      				<div class="col-md-6" style="margin-top: 25px; margin-bottom: 50px;">
      					<input type="submit" class="btn btn-xs btn-primary pull-right" value="ADD">
      					<a type="submit" style="margin-right: 5px;" class="btn btn-xs btn-warning pull-right" href="my_account.php" >Cancel</a>
      				</div>
      			</div>
      		</form>
 	</div>
 	<div class="col-md-2"></div>
 </div>

 <?php } else { 
   $sql = $db->query("SELECT * FROM Items WHERE owner = '$user_id'");
   ?>
   <div class="row">
   <div class="col-md-2"></div>
   <div class="col-md-8">
     <h2 class="text-center text-primary">YOUR ITEMS</h2>
     <?php while($result = mysqli_fetch_assoc($sql)):
          $id = $result['item_id'];
          $sql2 = $db->query("SELECT * FROM bids WHERE item_id = '$id'");
      ?>
     <div class="row">
       <div class="col-md-4">
         <h3 class="text-center"><?=$result['title'];?></h3>
        <img src="<?=$result['photograph']?>" width="100%" height="150px" class=""><a href=""></a>
       </div>
       <div class="col-md-8">
        <table class="table table-bordered table-striped table-condensed">
          <thead>
            <th>Bidder</th>
            <th>Set Price</th>
          </thead>
          <tbody>
            <?php while($bidder = mysqli_fetch_assoc($sql2)):
                 $id = $bidder['username'];
                 $query = $db->query("SELECT * FROM account WHERE account_id = '$id'");
                 $r = mysqli_fetch_assoc($query);
              ?>
            <tr>
              <td><?=$r['username'];?></td>
              <td>$ <?=$bidder['price'];?></td>
            </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
        <?php if($result['maximum'] != 10):?>
        <a href="winned.php?complete=<?=$result['item_id']; ?>" class="btn btn-xs btn-primary pull-right">Complete Bid</a>
      <?php endif; ?>
       </div>
     </div><hr>
   <?php endwhile; ?>
   <a href="my_account.php?my_account=1" class="btn btn-danger pull-right">add items</a>
   </div>
   <div class="col-md-2"></div>
 </div>

 <?php } ?>