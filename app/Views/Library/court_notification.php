
<?php
 
/*******USER ROLES ***************

role_id: 4
role_name: COURT ASSISTANT
*************************** 5. row ***************************
  role_id: 5
role_name: LIBRARIAN
*************************** 6. row ***************************
  role_id: 6
role_name: ADMIN
*************************** 7. row ***************************
  role_id: 7
role_name: ADVOCATE

*/

 
$requisition = $RequisitionModel;


$adminuser = $AdminusersModel;
$roleid=$_SESSION['role_id'];
if($roleid==4)
{
  $dashboardUrl="court_dashboard.php"; $adv_dashboardUrl="#";
}else if($roleid==7)
{
  $dashboardUrl="advocate_dashboard.php"; $adv_dashboardUrl="#";


}else{
  $dashboardUrl="view_court_requisition.php";
  $adv_dashboardUrl="view_advocate_requistion.php";
}

//$requisition->role_id=$roleid;
$unread_AOR_interaction=$requisition->unread_AOR_pending_interaction($roleid);
?>
<style>
  .main-header {
  padding: 17px 0;
    align-items: center;
  }
  .main-header .navbar-nav {
        display: flex;
    flex-direction: row;
    justify-content: right;
    margin-right: 21px;
  }
  .main-header .left {
    text-align: right;
  }
  @media screen and (max-width:768px){
    .main-header .left{
      text-align: center;
    margin: 10px auto;
    }
    .main-header .navbar-nav {
          flex-direction: row;
    display: flex;
    justify-content: center;
    margin: 10px auto;
    }
    .main-header .navbar-nav .nav-item {
          margin-bottom: 20px;

    }
  }

</style>

 <nav class="row main-header " style="">

     <!-- <div class="col-sm-3"></div> -->
     <div class="col-md-6 col-sm-12 left">
 <span style="margin-top: -1%;color: #800000;">   
   <?php 
      if($roleid==5 || $roleid==6)
      {

      ?>

           Welcome &nbsp;&nbsp;<?php echo $_SESSION['username']; ?>
      &nbsp;<div style="margin-top: -1%;color: green;" id="Cnt_Requistion"> </div>

      <?php }else if($roleid==7)
           {
            //$adminuser->UserName= $_SESSION['username'];
            $result_usr=$adminuser->existingUsername($_SESSION['username']);
            //$result_usr=$stmt_usr->fetch(PDO::FETCH_OBJ);

            ?>

              Welcome &nbsp;&nbsp;<?php echo  $result_usr['fullname']; ?>
          <?php }else{?>
            Welcome 
      <?php echo ucwords($_SESSION['username']) ?>
      <?php  }?> 
</span>
 </div>
     <div class="col-md-6 col-sm-12 right">
      <ul class="row navbar-nav ml-auto">
      <!-- Navbar Search -->

      <li class="nav-item"> &nbsp; &nbsp;&nbsp;</li>
       <?php if($roleid ==4 ){?>
          <li class="nav-item">  <a  class="btn btn-success pull-right" href="#" id="btnrequistion"> ADD REQUISITION</a></li>
       <?php }else{?>
         <!-- <li class="nav-item">  <a  class="btn btn-success pull-right" href="<?php echo $dashboardUrl;?>" id="">HOME</a></li> -->
       <?php }?>

        &nbsp; &nbsp;&nbsp;
      <?php if($roleid == 5 || $roleid == 6 ){
      ?>
     
      <li class="nav-item">  
        <!-- <a  class="btn btn-success pull-right" href="<?php echo $adv_dashboardUrl;?>"> AOR</a> -->
        <?php if($unread_AOR_interaction!=0)
        {?>
        <a class="nav-link" data-toggle="dropdown" href="#" onclick="call_AOR_interaction('<?php echo $adv_dashboardUrl;?>')" title="Pending AOR Interactions">
            <i class="far fa-bell"></i> 
            <span class="badge badge-warning navbar-badge"><?php echo $unread_AOR_interaction;?></span>
        </a>
      <?php }?>
      </li>
        &nbsp; &nbsp;&nbsp;
      <?php echo '<li class="nav-item"> 
    <a  class="btn btn-success pull-right" href="javascript:void(0)" onclick="getreqHome();">Home</a></li> &nbsp;<li class="nav-item"> 
    <a  class="btn btn-success pull-right" id="showForm" href="javascript:void(0)" onclick="getreqForm();">Add Requisition</a></li> &nbsp;<li class="nav-item">
      <a  class="btn btn-success pull-right" href="report-requisition.php">UPLOAD</a>
      </li>';
      } 

      if($roleid==4)
      {
      ?> <li class="nav-item"> &nbsp; &nbsp;&nbsp; </li> <li class="nav-item"> &nbsp; &nbsp;&nbsp; </li>

      <!-- <li class="nav-item"> 
      <a  class="btn btn-success pull-right" href="<?php //echo $dashboardUrl;?>">HOME</a></li>    -->   
      <?php } if($roleid ==7) {?>
<li class="nav-item"> 
    <a  class="btn btn-success pull-right" id="showForm" href="javascript:void(0)" onclick="getadvForm();">Add Requisition</a></li>
      <?php } ?>

      <li class="nav-item"> &nbsp; &nbsp;&nbsp;</li>


      <li class="nav-item">
      <div class="right-div">
      <!-- <a href="logout.php" class="btn btn-danger pull-right">LOGOUT</a> -->
      </div>
      </li>


      </ul>
     </div>
   


     </nav>
