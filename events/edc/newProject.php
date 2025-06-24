
<?php
if (isset($_SESSION['user'])) {
} else {
	header('Location: administration/main.php');
	die();
}
?>

<!-- --------------------------------------- NEW PROJECT MODAL ------------------------------------------------------ -->
<div id="project-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-edit" >Создать Проект</h3>
                <a class="close text-white btn" data-dismiss="modal">×</a>
            </div>
            <form name="project" class="new-project-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" role="form">
                <div class="modal-body">				
                    <div class="form-group">
                        <label class="text-dark" for="project_name">Название<span class="text-danger pl-1">*</span></label>
                        <input class="form-control" type="text" name="project_name" required>
                    </div>
                    <div class="form-group">
                        <label class="text-dark" for="project_description">Описание</label>
                        <textarea class="form-control" type="text" name="project_description"></textarea>
                    </div>      

                <div class="form-group d-flex justify-content-between mt-2">
                    <div class="col-6 mt-0 p-1">                        
                        <label class="text-dark">Начало<span class="text-danger pl-1">*</span></label>
                        <input type="date" class="form-control" runat="server" id="startAdd" name="start_date" required data-date-format="yyyy-mm-dd"/>
                    </div>
                    <div class="col-6 m-0 p-1">  
                        <label class="text-dark">Конец<span class="text-danger pl-1">*</span></label>
                        <input type="date" class="form-control" runat="server" id="endAdd" name="end_date" required data-date-format="yyyy-mm-dd"/>
                    </div>
                </div>

                    <div class="form-group">
                        <input hidden id="id_user" name="id_user" value=<?php echo $_SESSION['id_user']; ?> >
                    </div>					
                </div>
                <div class="modal-footer">					
                    <button type="submit" class="btn btn-primary">Создать</button>
                </div>
            </form>
        </div>
    </div>
</div>
