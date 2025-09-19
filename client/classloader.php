<?php  
require_once 'classes/Database.php';
require_once 'classes/Offer.php';
require_once 'classes/Proposal.php';
require_once 'classes/Category.php';
require_once 'classes/Subcategory.php';
require_once 'classes/User.php';

$databaseObj= new Database();
$offerObj = new Offer();
$proposalObj = new Proposal();
$categoryObj = new Category();
$subcategoryObj = new Subcategory();
$userObj = new User();

$userObj->startSession();
?>