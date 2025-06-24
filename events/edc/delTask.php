
<?php
if (isset($_SESSION['user'])) {
} else {
	header('Location: login.php');
	die();
}
 ?>
<!-- --------------------------------------- DELETE TASK MODAL ------------------------------------------------------ -->
<div id="task-delete-<?php echo $i; ?>" class="col-sm modal fade" role="dialog" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-edit" >Вы уверены?</h3>
                <a class="close text-white btn" data-dismiss="modal">×</a>
            </div>
            <form class="delete-task-form" method="POST" role="form" data-task-id="<?php echo $s['id_task']; ?>" data-project-id="<?php echo $s['id_project']; ?>">
                <div class="modal-body">
                    <p class="text-dark">Вы хотите безвозвратно удалить <i class="text-primary"><?php echo $s['task_name']; ?> </i> ?</p>
                    
               
                    <div class="form-group">
                        <input hidden type="int" name="id_task" value="<?php echo $s['id_task']; ?>">
                    </div>	
                    <div class="form-group">
                        <input hidden type="int" name="id_project" value="<?php echo $s['id_project']; ?>">
                    </div>				
                </div>
                <div class="modal-footer">					
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>
