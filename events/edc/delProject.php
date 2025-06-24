
<?php
if (isset($_SESSION['user'])) {
} else {
	header('Location: login.php');
	die();
}
 ?>
<!-- --------------------------------------- DELETE PROJECT MODAL ------------------------------------------------------ -->
<div id="project-delete-<?php echo $i; ?>" class="col-sm modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-edit" >Вы уверены?</h3>
                <a class="close text-white btn" data-dismiss="modal">×</a>
            </div>
            <form name="project" class="delete-project-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" role="form">
                <div class="modal-body">
                    <p class="text-dark">Вы хотите безвозвратно удалить <i class="text-primary"><?php echo $p['project_name']; ?> </i> ?</p>
                    
               
                    <div class="form-group">
                        <input hidden type="int" name="delete_project_id" value="<?php echo $p['id_project']; ?>">
                    </div>					
                </div>
                <div class="modal-footer">					
                    <button type="submit" class="btn btn-danger">Удалить</button>
                </div>
            </form>
        </div>
    </div>
</div>
