
<?php
if (isset($_SESSION['user'])) {
} else {
	header('Location: login.php');
	die();
}
?>
<!-- --------------------------------------- NEW TASK  ------------------------------------------------------ -->
<div id="new-task-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-edit" >Создать Задачу</h3>
                <a class="close text-white btn" data-dismiss="modal">×</a>
            </div>
           <form name="task_todo" class="create-task-form" method="POST" role="form">
                <div class="modal-body">
                    <label class="text-dark">Статус<span class="text-danger pl-1">*</span></label>
                    <div class="form-group d-flex justify-content-around">                    
                        <div class="form-check">                        
                            <input class="form-check-input btn" type="radio" name="task_status" id="task_status_1" value="1" checked>
                            <label class="form-check-label" for="task_status_1">
                                Не Начато
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input btn" type="radio" name="task_status" id="task_status_2" value="2">
                            <label class="form-check-label" for="task_status_2">
                                В Процессе
                            </label>
                        </div>
                        <div class="form-check" >
                            <input class="form-check-input btn" type="radio" name="task_status" id="task_status_3" value="3">
                            <label class="form-check-label" for="task_status_3">
                                Завершено
                            </label>
                        </div>
                    </div>				
                    <div class="form-group">
                        <label class="text-dark" for="task_name">Название<span class="text-danger pl-1">*</span></label>
                        <input class="form-control" type="text" name="task_name" required>
                    </div>
                    <div class="form-group">
                        <label class="text-dark" for="task_description">Описание</label>
                        <textarea class="form-control" type="text" name="task_description"></textarea>
                    </div>
					<div class="form-group">
						<label for="task_color" class="text-dark">Приоритет</label>
						<select name="task_color" class="form-control">
							<option value=""></option>
							<option style="color:#5cb85c" value="#5cb85c">&#9724; Низкий</option>						  
							<option style="color:#f0ad4e" value="#f0ad4e">&#9724; Средний</option>
							<option style="color:#d9534f" value="#d9534f">&#9724; Высокий</option>													  
						</select>
				    </div>         
                    <div class="form-group d-flex justify-content-between mt-2">
                        <div class="col-12 m-0 p-1">  
                            <label class="text-dark">Срок</label>
                            <input type="date" class="form-control" runat="server" name="deadline" min="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd"/>
                        </div>                   
                    </div>    
                    <div class="form-group">
                        <label for="user_name">Назначить пользователя:</label>
                        <select name="user_name" id="user_name" class="form-control">
                            <?php foreach ($result1 as $output) {?>
                                <option><?php echo $output["user"];?></option>  
                            <?php }?>

                        </select>
                    </div>            
                    <div class="form-group">
                        <input hidden id="id_task_project" name="id_task_project" value=<?php echo $id_project_for_task; ?> >
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
