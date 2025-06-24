
<?php
if (isset($_SESSION['user'])) {
} else {
	header('Location: login.php');
	die();
}
?>
<!-- --------------------------------------- EDIT TASK  ------------------------------------------------------ -->
<div id="task-edit-<?php echo $i; ?>" class="modal fade" role="dialog" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="lead text-edit" >Редактирование задачи</h3>
                <a class="close text-white btn" data-dismiss="modal">×</a>
            </div>
            <form name="task" class="edit-task-form" data-task-id="<?php echo $s['id_task']; ?>" method="POST" role="form">
                <div class="modal-body">				
                    <div class="form-group">
                        <label class="text-dark" for="edit_name">Название<span class="text-danger pl-1">*</span></label>
                        <input class="form-control" type="text" name="edit_task_name" id="edit_task_name_<?php echo $s['id_task']; ?>" value="<?php echo $s['task_name']; ?>" required>
                    </div>
                    <div class="form-group">
                        <label class="text-dark" for="edit_description">Описание</label>
                        <textarea class="form-control" type="text" name="edit_task_description" id="edit_task_description_<?php echo $s['id_task']; ?>"><?php echo $s['task_description']; ?></textarea>
                    </div>
					<div class="form-group">
						<label for="edit_colour" class="text-dark">Приоритет</label>
						<select name="edit_task_color" id="edit_task_color_<?php echo $s['id_task']; ?>" class="form-control" style="color:<?php echo $s['task_color']; ?>" value="<?php echo $s['task_color'];?>">
							<option style="color:<?php echo $s['task_color']; ?>" value="<?php echo $s['task_color'];?>">&#9724; 
                        <?php 
                         if ($s['task_color'] == '#5cb85c') {echo "Низкий";} 
                         elseif ($s['task_color'] == '#f0ad4e') {echo "Средний";} 
                         elseif ($s['task_color'] == '#d9534f') {echo "Высокий";} 
                         else { echo ""; } ?>                      
                        
                        </option>
							<option style="color:#5cb85c" value="#5cb85c">&#9724; Низкий</option>						  
							<option style="color:#f0ad4e" value="#f0ad4e">&#9724; Средний</option>
							<option style="color:#d9534f" value="#d9534f">&#9724; Высокий</option>
						</select>
				    </div> 
                    
                    <div class="form-group d-flex justify-content-between mt-2">
                        <div class="col-12 m-0 p-1">  
                            <label class="text-dark">Срок</label>
                            <input type="date" class="form-control" runat="server" name="deadline" id="deadline_<?php echo $s['id_task']; ?>" value="<?php if( $s['deadline'] !== '1970-01-01'){echo $s['deadline'];} ?>" min="<?php echo date('Y-m-d'); ?>" data-date-format="yyyy-mm-dd"/>
                        </div>                   
                    </div>    
                    <div class="form-group">
                        <label for="edit_user_name">Назначить пользователя:</label>
                        <select name="edit_user_name" id="edit_user_name_<?php echo $s['id_task']; ?>" class="form-control">
                            <?php foreach ($result1 as $output) { ?>
                                <option value="<?php echo htmlspecialchars($output['user']); ?>"
                                    <?php echo $output['user'] == $s['user_name'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($output['user']); ?>
                                </option>  
                            <?php } ?>
                        </select>
                    </div>         
               
                    <div class="form-group">
                        <input hidden id="id_user" name="id_user" value=<?php echo $_SESSION['id_user']; ?> >
                    </div>	

                    <div class="form-group">
                        <input hidden id="id_task_project" name="id_task_project" value="<?php echo $s['id_project']; ?>" >
                    </div>	  

                    <div class="form-group">
                        <input hidden id="edit_id_task" name="edit_id_task" value="<?php echo $s['id_task']; ?>" >
                    </div>					
                </div>
                <div class="modal-footer">					
                    <button type="submit" class="btn btn-primary">Обновить</button>
                </div>
            </form>
        </div>
    </div>
</div>
