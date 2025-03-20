<?=view('header'); ?>
	<?php 	 
            $db = \Config\Database::connect();
            $action="Create"; 
            $umidInput='';
		 
			$userId=1;
			 
            $userId =    $_SESSION['login']['usercode'];;
			if(is_numeric($userId) && $userId != 0) {
				$sql=$db->query("select * from master.users where usercode='".$userId."'");
				//$rs=$dbo->prepare($sql);
				//$rs->bindParam(1, $userId, PDO::PARAM_INT);
				//$rs->execute();
				//$data=$rs->fetch();
                $data = $sql->getRowArray();
				$action="Update";
				$umidInput='<input type="hidden" value="'.$userId.'" name="UiD" />';
			}

	?>
	<div class="container-fluid">
		<div class="row">
			<div class="col-md-12"> <!-- Right Part -->
				<div class="form-div">

					<div class="d-block text-center">

						 

				        <div id="loginbox" style="margin-top:20px;" class="mainbox">

				            <div class="panel panel-info text-left" id="addUsersDiv">
			                    <div class="panel-heading">
			                        <div class="panel-title d-block">
			                        	User <span class="text-danger">[ Profile ]</span>
			                        </div>
			                    </div>

			                    <div style="margin-top: 10px" class="panel-body">
			                    	<div class="table-responsive">
			                    		<table class="table table-hover" border="1" width="100%">
			                    			<tbody>
			                    				<tr>
			                    					<td class="alert alert-success">Name :</td>
			                    					<td colspan="4"><?php echo $data['name'] ?></td>
			                    					<td rowspan="5" style="padding: 0; width: 220px;"><div class="imgWrapper"><?php echo '<img src="/userImage/'.$data['uphoto'].'" class="img-responsive img-thumbnail">'; ?></div></td>
			                    				</tr>
			                    				<tr>
			                    					<td class="alert alert-success">User type :</td>
			                    					<td colspan="5">
		                    						<?php
					                        			$query=$db->query("SELECT type_name FROM master.usertype where display ='Y' AND id='".$data['usertype']."'");
					                        			//$rs=$dbo->prepare($query);
					                        			//$rs->bindParam(1, $data['usertype'], PDO::PARAM_INT);
					                        			//$rs->execute();
					                        			//$utype=$rs->fetchColumn();
                                                        $rs = $query->getRowArray();
					                        			echo $utype = $rs['type_name'] ?? '';
					                        		?>
			                    					</td>
			                    				</tr>
			                    				<tr>
			                    					<td class="alert alert-success">User Section :</td>
			                    					<td colspan="5">
		                    						<?php
					                        			$query=$db->query("SELECT section_name FROM master.usersection where display ='Y' AND id='".$data['section']."'");
					                        			//$rs=$dbo->prepare($query);
					                        			//$rs->bindParam(1, $data['section'], PDO::PARAM_INT);
					                        			//$rs->execute();
					                        			//$section=$rs->fetchColumn();
                                                        $rs = $query->getRowArray();
					                        			echo $section = $rs['section_name'] ?? '';
					                        		?>
			                    					</td>
			                    				</tr>
			                    				<tr>
			                    					<td class="alert alert-success">User Department :</td>
			                    					<td colspan="5">
		                    						<?php
					                        			$query=$db->query("SELECT dept_name FROM master.userdept where display ='Y' AND id='".$data['udept']."'");
					                        			//$rs=$dbo->prepare($query);
					                        			//$rs->bindParam(1, $data['udept'], PDO::PARAM_INT);
					                        			//$rs->execute();
					                        			//$dept=$rs->fetchColumn();
                                                        $dept = $query->getRowArray();
					                        			echo $dept['dept_name'] ?? '';
					                        		?>
			                    					</td>
			                    				</tr>
			                    				<tr>
			                    					<td class="alert alert-success">Judge Name :</td>
			                    					<td colspan="5">
		                    						<?php
					                        			//$query=$db->query("SELECT distinct jname,CASE jtype when 'R' then first_name else '' end as rname FROM judge where display ='Y' AND (jtype='J' OR jtype='R') AND is_retired='N' AND jcode='".$data['jcode']."'");

					                        			//$rs=$dbo->prepare($query);
					                        			//$rs->bindParam(1, $data['jcode'], PDO::PARAM_INT);
					                        			//$rs->execute();
					                        			//$judge=$rs->fetch(PDO::FETCH_NUM);

                                                        

                                                        $judge = $Menu_model->getJudgeDetail($data['jcode']);
                                                        //pr($judge);
					                        			$jname=$judge['jname'] ?? '';
					                        			$rname=$judge['rname'] ?? '';
					                        			if($rname=='') echo $jname;
					                        			else echo $rname.' &nbsp;['.$jname.']';
					                        		?>
			                    					</td>
			                    				</tr>
			                    				<tr>
			                    					<td class="alert alert-success">Emp. Id :</td>
			                    					<td><?php echo $data['empid'];?></td>
			                    					<td class="alert alert-success">Mobile No. :</td>
			                    					<td><?php echo $data['mobile'];?></td>
			                    					<td class="alert alert-success">Court master :</td>
			                    					<td><?php echo $data['is_CourtMaster'] ?? '';?></td>
			                    				</tr>
			                    				<tr>
			                    					<td class="alert alert-success">Email :</td>
			                    					<td colspan="5"><?php echo $data['email_id'];?></td>
			                    				</tr>
			                    				<tr>
			                    					<td class="alert alert-success">Permitted IP(s) :</td>
			                    					<td colspan="5" style="border-bottom: 1px solid #ddd;"><?php echo $data['ip_address'];?></td>
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
	<style>
		table tr td:first-child {
			padding: 14px 12px !important;
		    color: #000;
		    font-size: 16px;
		}
		table tr td {
			padding: 14px 12px !important;
		}
		.alert-success {
		    color: #000000;
		    background-color: #f7f7f7;
		    border-color: #dddddd;
		}
		.panel-title {
			text-align: center;
		    font-weight: bold;
		    text-transform: uppercase;
		}
		.imgWrapper {
		    background: #fff;
		    float: right;
		    padding: 8px 0 8px 18px;
		    margin-right: -12px;
		}
		.imgWrapper img {
			max-width: 200px;
    		box-shadow: -5px 5px 8px rgba(0, 0, 0, 0.09)
		}
		.alert {
		    padding: 14px 12px !important;
		    font-size: 13px !important;
    		font-weight: bold;
    		text-transform: uppercase;
		}
	</style>

<?= $dbo=null; ?>