<?= view('header') ?>

<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/menu_assign.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/style.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('Ajaxcalls/menu_assign/all.css'); ?>">
<link rel="stylesheet" href="<?php echo base_url('assets/vendor/fontawesome-free/css/all.min.css'); ?>">
 <style>
    /* .radio-inline{
        display: list-item;
    padding: inherit;
    position: absolute;
} */

 </style>

<div data-ng-app="postModule" data-ng-controller="PostController" data-ng-init="init()">
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header heading">
                <div class="row">
                    <div class="col-sm-10">
                        <h3 class="card-title">Master Management >> Master</h3>
                    </div>
                    <div class="col-sm-2"> </div>
                </div>
            </div>
            <br /><br />
                 
                    <!--start menu programming-->
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12"> <!-- Right Part -->
                            <div class="form-div">
                                <div class="d-block text-center">

                                     <!-- Main content -->                                                
                                     <div class="container">
                                     <input type="hidden" name="<?= csrf_token(); ?>" value="<?= csrf_hash(); ?>" id="csrf_token">
                                            <div class="row mt80">
                                                <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 animated fadeInDown">
                                                    <div class="alert alert-danger text-center alert-failure-div" role="alert" style="display: none">
                                                        <p></p>
                                                    </div>
                                                    <div class="alert alert-success text-center alert-success-div" role="alert" style="display: none">
                                                        <p></p>
                                                    </div>
                                                    <form novalidate name="userForm" >
                                                        <div class="form-group">

                                                            <label for="exampleInputEmail1">Keyword</label>
                                                            <input data-ng-minlength="3" required type="text" class="form-control" id="keyword_description" name="keyword_description" placeholder="Keyword" data-ng-model='tempUser.keyword_description'>
                                                            <span class="help-block error" data-ng-show="userForm.keyword_description.$invalid && userForm.keyword_description.$dirty">
                                                                {{getError(userForm.keyword_description.$error, 'keyword_description')}}
                                                            </span>
                                                        </div>
                                                        <div class="text-center">
                                                            <button ng-disabled="userForm.$invalid" data-loading-text="Saving ..." ng-hide="tempUser.id" type="submit" class="btn btn-save" data-ng-click="addUser()">Save </button>
                                                            <button ng-disabled="userForm.$invalid" data-loading-text="Updating ..." ng-hide="!tempUser.id" type="submit" class="btn btn-save" data-ng-click="updateUser()">Update </button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 animated fadeInUp">
                                                    <div class="table-responsive">
                                                        <input type="text" ng-model="projectList.search" class="form-control pull-right" id="projects_search" placeholder="Search">
                                                        <table class="table table-bordered table-hover table-striped">
                                                            <thead>
                                                                <tr>
                                                                    <th width="5%">#</th>
                                                                    <th width="20%">Keyword</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                            <tr data-ng-repeat="user in post.users | filter:projectList.search | orderBy : '-id'">
                                                                    <th scope="row">{{user.id}}</th>
                                                                    <td> {{user.keyword_description}} </td>
                                                                    <td>  <button class="btn btn-primary"><span data-ng-click="editUser(user)"> Edit</span></button> | <button class="btn btn-primary"><span data-ng-click="deleteUser(user)">Delete</span></button> </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>

                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>


<!-- <script src="<?= base_url('/master/angular.min.js') ?>"></script> -->
<script src="<?= base_url('/Ajaxcalls/menu_assign/menu_assign.js') ?>"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/pdfmake.min.js"></script>
<!-- <script src="<?=base_url()?>/assets/plugins/datepicker/bootstrap-datepicker.js"></script> -->
<script src="<?=base_url()?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/dataTables.buttons.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.print.min.js"></script>
<script src="<?=base_url()?>/assets/plugins/datatables/buttons.html5.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
 
<script type="text/javascript">
     
 
$postModule = angular.module('postModule', []);
$postModule.controller('PostController',function($scope, $http){
	$scope.post = {};
	$scope.post.users = [];
	$scope.tempUser = {};
	$scope.editMode = false;
	$scope.index = '';
	
	
$scope.saveUser = function() {
    updateCSRFToken()
    var csrfToken = $('#csrf_token').val();
    $http({
        method: 'POST',
        url: "<?=base_url();?>/MasterManagement/MasterController/save_User",
        data: $.param({'user': $scope.tempUser, 'type': 'save_user','csrfToken': csrfToken}),
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': csrfToken  
        }
    }).then(function(response) {
        updateCSRFToken()
        if (response.data.success) {
            if ($scope.editMode) {
                $scope.post.users[$scope.index].id = response.data.id;
                $scope.post.users[$scope.index].keyword = $scope.tempUser.keyword_description;
            } else {
                $scope.post.users.push({
                    id: response.data.id,
                    keyword: $scope.tempUser.keyword_description
                });
            }
            $scope.messageSuccess(response.data.message);
            $scope.userForm.$setPristine();
            $scope.tempUser = {};
        } else {
            $scope.messageFailure(response.data.message);
        }
    }, function(error) {
        // Handle error response
        updateCSRFToken()
        $scope.messageFailure("An error occurred.");
    });


     $timeout(function() {
        jQuery('.btn-save').button('loading'); // Ensure button is initialized
    }, 0);
 
}


    $scope.search = function()
    {
        $scope.filteredList  = $scope.filter($scope.post.users,
            function(item){
                return searchUtil(item,$scope.post.users);
            });

        if($scope.searchText == '')
        {
            $scope.filteredList = $scope.allItems ;
        }
    }

    $scope.addUser = function(){
		
		jQuery('.btn-save').button('loading');
		$scope.saveUser();
		$scope.editMode = false;
		$scope.index = '';
               // $scope.init();
	}
	
	$scope.updateUser = function(){
		// $('.btn-save').button('loading');
		$scope.saveUser();
	}
	
	$scope.editUser = function(user){
		$scope.tempUser = {
			id: user.id,
			keyword_description : user.keyword_description
		};
        //alert(user.keyword_description);
		$scope.editMode = true;
		$scope.index = $scope.post.users.indexOf(user);
	}
	
	$scope.deleteUser = function(user)
    {
		var r = confirm("Are you sure want to delete this user!");
		if (r == true) {
			$http({
		      method: 'post',
              url: "<?=base_url();?>/MasterManagement/MasterController/deleteUser",
		      data: $.param({ 'id' : user.id, 'type' : 'delete_user' }),
		      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
		    }).
		    then(function(data, status, headers, config) {
		    	if(data.success){
		    		var index = $scope.post.users.indexOf(user);
		    		$scope.post.users.splice(index, 1);
		    	}else{
		    		$scope.messageFailure(data.message);
		    	}
		    },function(data, status, headers, config) {
		    	//$scope.messageFailure(data.message);
		    });
		}
	}
	
	$scope.init = function(){
        
	    $http({
	      method: 'GET',
          url: "<?=base_url();?>/MasterManagement/MasterController/getUsers",
	      data: $.param({ 'type' : 'getUsers' }),
	      headers: {'Content-Type': 'application/x-www-form-urlencoded'}
	    }).
	    then(function(response, status, headers, config) {
            
            let data = response.data;
            console.log(data);
	    	if(data.success && !angular.isUndefined(data.data) ){
	    		$scope.post.users = data.data;
	    	}else{
	    		$scope.messageFailure(data.message);
	    	}
	    }, function(data, status, headers, config) {
	    	//$scope.messageFailure(data.message);
	    });
	}
	
	$scope.messageFailure = function (msg){
		jQuery('.alert-failure-div > p').html(msg);
		jQuery('.alert-failure-div').show();
		jQuery('.alert-failure-div').delay(5000).slideUp(function(){
			jQuery('.alert-failure-div > p').html('');
		});
	}
	
	$scope.messageSuccess = function (msg){
		jQuery('.alert-success-div > p').html(msg);
		jQuery('.alert-success-div').show();
		jQuery('.alert-success-div').delay(5000).slideUp(function(){
			jQuery('.alert-success-div > p').html('');
		});
	}
	
	
	$scope.getError = function(error, name){
		if(angular.isDefined(error)){
			if(error.required && name == 'keyword_description'){
				return "Please enter name";
			}else if(error.minlength && name == 'keyword_description'){
				return "Name must be 3 characters long";
			}
		}
	}
	
});


 

</script>
