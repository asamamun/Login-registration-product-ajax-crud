<?php
session_start();
require "../database.php";
$pagesize = 10;
$recordstart = (isset($_GET['recordstart'])) ? $_GET['recordstart'] : 0;
// Create the query
if(isset($_GET['searchdata'])){
	$sd = $_GET['searchdata'];
$selectQuery = "SELECT * FROM products where uid='".$_SESSION['userId']."' and sku like '%".$sd."%' or name like '%".$sd."%' ORDER by id desc limit $recordstart,$pagesize";
	}
else {
$selectQuery = "SELECT * FROM products where uid='".$_SESSION['userId']."' ORDER by id desc limit $recordstart,$pagesize";
}
// echo $selectQuery;
// exit;
//total record start
if(isset($_GET['searchdata'])){
	$sd = $_GET['searchdata'];
$totalrecord = "select count(*) from products where uid='".$_SESSION['userId']."' and  sku like '%".$sd."%' or name like '%".$sd."%'";}
else {
	$totalrecord = "select count(*) from products where uid='".$_SESSION['userId']."'";
	}
$totalrecordQuery = $conn->query($totalrecord);
$totalrecordQueryRow = $totalrecordQuery->fetch_row(); 
$totalrecord = $totalrecordQueryRow[0];
//total record end
$numberofpages = ceil($totalrecord/$pagesize);
// Send the query to MySQL
$selectQueryResult = $conn->query($selectQuery);
$totalRows = $selectQueryResult->num_rows;
$table = "<table class='table table-hover'> <caption></caption> <tr><th>Sl</th><th>Name</th><th>Image</th><th>SKU</th><th>Price</th><th>Action</th></tr>";
$sl =($recordstart+1) ;
while($row = $selectQueryResult->fetch_array()){
	//echo $row['price']."<br>";
	$table .= "<tr><td>".$sl++."</td><td class='clspn'>".$row['name']."</td><td><img src='assets/products/".$row['id'].".jpg' width='120px'/></td><td class='clssku'>".$row['sku']."</td><td class='clsprice'>".$row['price']."</td><td><a href='#productform' class='editbtn' data-editid='".$row['id']."'><img src='assets/images/edit.png' width='32px'/></a><a class='delbtn' data-pid='".$row['id']."'><img src='assets/images/delete.png' width='32px'/></a></td></tr>";
	}
$table .= "</table>";
if($totalRows > 0 ){
$table .= "<h4>Total ".$totalRows." records</h4>";}
else {
$table .= "<h4 class='text-danger'>No Records found</h4>";	
	}

echo $table;	
?>
<ul class="pagination">
<?php
for($i = 0; $i <$numberofpages;$i++){
	$pagestartvalue = $i*$pagesize;
	$pageendvalue = $pagestartvalue + $pagesize;
	//if($pagestartvalue == $recordstart){
	if(	$recordstart >=$pagestartvalue && $recordstart <$pageendvalue){
	echo "<li class='active page-item'><a class='pageanchor page-link' data-recid='".$pagestartvalue."'>".($i+1)."</a></li>";
	}
	else {
		echo "<li class='page-item'><a class='pageanchor page-link' data-recid='".$pagestartvalue."'>".($i+1)."</a></li>";
		}
	} 
$selectQueryResult->free();
$conn->close();
?>
</ul>