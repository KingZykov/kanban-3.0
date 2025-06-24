
<?php
if (isset($_SESSION['user'])) {
} else {
	header('Location: login.php');
	die();
}
?>
<!-- --------------------------------------- EDIT PROJECT MODAL ------------------------------------------------------ -->
<div id="project-edit-<?php echo $i; ?>" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-edit" >Изменить проект</h3>
                <a class="close text-white btn" data-dismiss="modal">×</a>
            </div>
            <form name="project" class="edit-project-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" role="form">
                <div class="modal-body">				
                    <div class="form-group">
                        <label class="text-dark" for="edit_name">Название<span class="text-danger pl-1">*</span></label>
                        <input class="form-control" type="text" name="edit_project_name" value="<?php echo $p['project_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="text-dark" for="edit_description">Описание</label>
                        <textarea class="form-control" type="text" name="edit_project_description"><?php echo $p['project_description']; ?></textarea>
                    </div>
					       

                <div class="form-group d-flex justify-content-between mt-2">
                    <div class="col-6 mt-0 p-1">                        
                        <label class="text-dark">Начало<span class="text-danger pl-1">*</span></label>
                        <input type="date" class="form-control" runat="server" id="startAdd1" name="edit_start_date" value="<?php echo $p['start_date']; ?>" required data-date-format="yyyy-mm-dd"/>
                    </div>
                    <div class="col-6 m-0 p-1">  
                        <label class="text-dark">Конец<span class="text-danger pl-1">*</span></label>
                        <input type="date" class="form-control" runat="server" id="endAdd1" name="edit_end_date" value="<?php echo $p['end_date']; ?>"required data-date-format="yyyy-mm-dd"/>
                    </div>
                </div>

                    <div class="form-group">
                        <input hidden id="edit_id_project" name="edit_id_project" value="<?php echo $p['id_project']; ?>" >
                    </div>					
                </div>
                <div class="modal-footer">					
                    <button type="submit" class="btn btn-primary">Обновить</button>
                </div>
            </form>
        </div>
    </div>
</div>
