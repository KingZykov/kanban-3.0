<!DOCTYPE html>
<html lang="en">
	
<head>
	<?php $title= "projects"; ?>
    <?php require 'head.php'; ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title><?php $title ?></title>
    <?php require '../events/edc/newProject.php'; ?> 
    <link rel="icon" type="image/png" href="../img/favicon.png">
</head>

<body class="bg"  data-user-id="<?= $_SESSION['id_user'] ?>" data-user-name="<?= $_SESSION['user'] ?>">

<header class="m-0 p-0">
	<nav class="navbar navbar-expand-lg pt-3 text-dark">
            <!--Боковая панель -->
    <input type="checkbox" id="side-checkbox" />
<div class="side-panel">
    <label class="side-button-2" for="side-checkbox">+</label>   
    <div class="side-title">
    <h4 class="title font-weight-normal"><i class="fas fa-briefcase pr-3"></i>Мои проекты</h4>
    </div>
        <!-- --------------------------------- SHOWING LIST OF PROJECTS --------------------------------- -->
        <div class="col-3 p-0 pl-3 pr-1">
        <div class="proj proj text-dark">
            <div class="card-header-tab card-header d-flex flex-nowrap justify-content-between">
                
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#project-modal" <?php if ($_SESSION['role'] == "user") echo "disabled"; ?>>Создать</button>
            </div>
            <div class="scroll-area">
                <perfect-scrollbar class="ps-show-limits">
                    <div style="position: static;" class="ps ps--active-y">
                        <div class="ps-content">
                            <ul class=" list-group list-group-flush">
                                
                                <?php if (isset($projects)) {	
                                    $i = 1;
                                    foreach ($projects as $p): 
                                    ?>                         
                                    <li class="accordion list-group-item pe-auto" id="project-p-<?php echo $i; ?>" data-project-id="<?php echo $p['id_project']; ?>" draggable=false>        
                                        <div class="widget-content p-0">
                                            <div class="widget-content-wrapper">                                            
                                                <form name="id_project_task" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="GET" role="form">
                                                    <input hidden name="idProject" value=<?php echo $p['id_project']; ?> >                                                    
                                                    <button class="btn" type="submit">
                                                        <div class="widget-content-left">
                                                            <div class="text-center widget-heading text-primary project-title">
                                                                <?php echo $p['project_name'];?>                                                                
                                                            </div>                                                            
                                                            <div class="widget-subheading text-muted project-start"><i>Start: <?php echo $p['start_date'];?></i></div>
                                                            <div class="widget-subheading text-muted project-end"><i>End: <?php echo $p['end_date'];?></i></div>                                                       
                                                           
                                                        </div>
                                                    </button>
                                                    <?php if($p['project_description'] !== ''){ ?>
                                                    <a class="d-flex justify-content-center nav-link text-primary p-0" data-toggle="collapse" data-target="#collapse-p-<?php echo $i; ?>" aria-expanded="true">
                                                        <span class="accicon"><i class="fa fa-angle-down rotate-icon pl-2 pr-2"></i></span>
                                                        <div  id="collapse-p-<?php echo $i; ?>" class="collapse" data-parent="#project-p-<?php echo $i; ?>">                                                    
                                                            <p class="font-small text-dark pt-1 project-description"><?php echo $p['project_description'];?></p> 
                                                        </div>
                                                    </a>
                                                    <?php  }; ?>
                                                </form>                                               
                                             
                                                <div class="widget-content-right ml-auto d-flex flex-nowrap"> 
                                                    <button type="button" class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#project-edit-<?php echo $i; ?>" <?php if ($_SESSION['role'] == "user") echo "disabled"; ?> > <i class="fas fa-pencil-alt"></i></button> 
                                                <?php require '../events/edc/editProject.php'; ?>
                                                    <button type="button" class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target="#project-delete-<?php echo $i; ?>" <?php if ($_SESSION['role'] == "user") echo "disabled"; ?>> <i class="fas fa-trash-alt"></i> </button> 
                                                <?php require '../events/edc/delProject.php'; ?>

                                                </div>                                                
                                            </div>

                                        </div>                                    
                                    </li>

                                    
                                
                                <?php $i++;
                                endforeach; }  ?>

                            </ul>
                        </div>
                    </div>
                </perfect-scrollbar>
            </div>
        </div>
    </div>
</div>
<div class="side-button-1-wr">
    <label class="side-button-1" for="side-checkbox">
        <div class="side-b side-open">></div>
        
        
    </label>
</div>
    <!--Боковая панелб -->
		<div class="menu container">




			<button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler"><span class="navbar-toggler-icon"></span></button>
			<div id="navbarSupportedContent" class="collapse navbar-collapse">
				<ul class="navbar-nav  ml-auto">
                    <li class="mynav"><span class="hbtn "><i class="far fa-address-card pr-2"></i>Здравствуйте, <?php echo strtoupper($_SESSION['user']);?>!</li>
                    <li class="mynav2"><span class="hbtn "><i class="fab fa-shirtsinbulk pr-2"></i>Роль -  <?php echo ($_SESSION['role']);?></li>
                    <li class="mynav3"><span class="hbtn"><i class="far fa-calendar-check pr-2"></i>Дата:<span class="pl-2 date"></span></li>	
                    <li class="mynav4"><span class="hbtn "><i class="far fa-clock pr-2"></i>Время:<span class="pl-2 clock"></span></li>	
					<li class="mynav-chat">
                        <button onclick="openModal()" class="chat-top-btn" title="Открыть чат"><i class="fas fa-comments pr-2"></i>Чат</button>
                    </li>
                    <li class="mynav5"><a href="/login/logout.php" class="hbtn "><i class="fas fa-door-closed"></i> Выйти</a></li>				
				</ul>
			</div>
            <i class="fas fa-circle-minus"></i>

	</nav>
</header>

<div class="row d-flex m-0 p-0 mt-4">

  
   
    <!-- --------------------------------- SHOWING LIST OF TASKS --------------------------------- -->
    <div class="col-9 p-0 pr-3 pl-1">
        <div class="card-hover-shadow-2x mb-3 card text-dark">
            <div class="card-header-tab card-header d-flex justify-content-between">
                <h4 class="card-header-title font-weight-normal"><i class="fas fa-business-time"></i> Задачи</h4>
                <?php if (isset($show_tasks)) { 
                    echo"<button type='button' class='btn btn-primary' data-toggle='modal' data-target='#new-task-modal'>Добавить</button>";
                    require '../events/edc/newTask.php';} ?>
            </div>
            <div class="scroll-area">
                <perfect-scrollbar class="ps-show-limits">
                    <div style="position: static;" class="ps ps--active-y">
                        <div class="ps-content">
                            <div class="row m-2 mt-4">
                                <div class="col-4">
                                    <div class="card-hover-shadow-2x mb-3 card text-dark">
                                        <div class="card-header-tab card-header">
                                            <h5 class="card-header-title font-weight-normal"><i class="fas fa-play mr-3"></i>Не Начато</h5>                                            
                                        </div>
                                        <div class="scroll-area-sm">
                                            <perfect-scrollbar class="ps-show-limits">
                                                <div style="position: static;" class="ps ps--active-y">
                                                    <div class="ps-content">
                                                        <ul class=" list-group list-group-flush drop-zone" style="min-height: 125px; max-height: 1250px; background: #FFFFFF;" id="col1"  task-status="1">                                                          

                                                        <?php if (isset($show_tasks)) {	
                                                            $i = 1;
                                                            foreach ($show_tasks as $s): 
                                                                if($s['task_status'] == '1'){
                                                        ?>  
                                                            <li class="accordion list-group-item pe-auto" id="task-todo-<?php echo $i; ?>" data-task-id="<?php echo $s['id_task']; ?>" draggable="true">                                                                      
                                                                <div class="todo-indicator task-color" style="background-color:<?php echo $s['task_color'];?>;">
                                                                </div>
                                                                <div class="widget-content p-0">
                                                                    <div class="widget-content-wrapper">
                                                                        <a class="col-8 nav-link text-primary p-0" data-toggle="collapse" data-target="#collapse-todo-<?php echo $i; ?>" aria-expanded="true">
                                                                            <div class="widget-content-left p-2 pl-3">
                                                                                <div class="widget-heading d-flex task-title">                                                                                   
                                                                                <?php echo $s['task_name'];?>                                                                                                                                                             
                                                                                    <span class="accicon"><i class="fa fa-angle-down rotate-icon pl-2"></i></span>
                                                                                </div>                                                                                  
                                                                                <div  id="collapse-todo-<?php echo $i; ?>" class="collapse" data-parent="#task-todo-<?php echo $i; ?>">  
                                                                                    <div class="widget-subheading text-muted"><i><?php          echo "id:";
                                                                                                                                                echo $s['id_task']; ?></i></div> 
                                                                                    <div class="widget-subheading text-muted task-deadline"><i> <?php if( $s['deadline'] !== '1970-01-01'){
                                                                                                                                                echo "Deadline: ";
                                                                                                                                                echo $s['deadline'];} ?></i></div>  
                                                                                    <p class="font-small text-dark pt-1 task-description"><?php echo $s['task_description'];?></p>                                                                                                                                                                                                                   
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                        <div class="widget-content-right ml-auto"> 
                                                                            <button type="button" class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#task-edit-<?php echo $i; ?>" <?php if ($_SESSION['role'] == "user") echo "disabled"; ?>> <i class="fas fa-pencil-alt"></i></button> 
                                                                        <?php  require '../events/edc/editTask.php' ?>    
                                                                            <button type="button" class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target="#task-delete-<?php echo $i; ?>" <?php if ($_SESSION['role'] == "user") echo "disabled"; ?>> <i class="fas fa-trash-alt"></i> </button> 
                                                                        <?php  require '../events/edc/delTask.php' ?>
                                                                        </div>
                                                                    </div>
                                                                </div> 
                                                                <div class="d-flex justify-content-center">                                                                    
                                                                    <button type="submit" class="border-0 btn-transition btn btn-outline-secondary" disabled> <i class="fa fa-arrow-left"></i></button>
                                                                    <form name="id_task_right-<?php echo $i; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" role="form">
                                                                        <input hidden name="id_task_right" value=<?php echo $s['id_task']; ?> >
                                                                        <input hidden name="task_status" value=<?php echo $s['task_status']; ?> >           
                                                                        <input hidden name="id_project_right" value=<?php echo $s['id_project']; ?> >                                          
                                                                        <button type="submit" class="border-0 btn-transition btn btn-outline-primary"> <i class="fa fa-arrow-right"></i></button>
                                                                    </form>     
                                                                </div>                                    
                                                            </li>

                                                            <?php $i++; }
                                                            endforeach; }  ?>                                                            
                                                        </ul>
                                                    </div>
                                                </div>
                                            </perfect-scrollbar>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="card-hover-shadow-2x mb-3 card text-dark">
                                        <div class="card-header-tab card-header d-flex justify-content-between">
                                            <h5 class="card-header-title font-weight-normal"><i class="fab fa-whmcs mr-3"></i>В Процессе</h5>                                            
                                        </div>
                                        <div class="scroll-area-sm">
                                            <perfect-scrollbar class="ps-show-limits">
                                                <div style="position: static;" class="ps ps--active-y">
                                                    <div class="ps-content">
                                                        <ul class=" list-group list-group-flush drop-zone" style="min-height: 125px; max-height: 1250px; background: #FFFFFF;" id="col2"  task-status="2">
                                                        
                                                        <?php if (isset($show_tasks)) {	
                                                            $i = 1000000;
                                                            foreach ($show_tasks as $s): 
                                                                if($s['task_status'] == '2'){
                                                        ?>  
                                                        
                                                        <li class="accordion list-group-item pe-auto" id="task-ip-<?php echo $i; ?>" data-task-id="<?php echo $s['id_task']; ?>" draggable="true">        
                                                                <div class="todo-indicator" style="background-color:<?php echo $s['task_color'];?>;">
                                                                </div>
                                                                <div class="widget-content p-0">
                                                                    <div class="widget-content-wrapper">
                                                                        <a class="col-8 nav-link text-primary p-0" data-toggle="collapse" data-target="#collapse-ip-<?php echo $i; ?>" aria-expanded="true">
                                                                            <div class="widget-content-left p-2 pl-3">
                                                                                <div class="widget-heading d-flex task-title">                                                                                   
                                                                                <?php echo $s['task_name'];?>                                                                                                                                                             
                                                                                    <span class="accicon"><i class="fa fa-angle-down rotate-icon pl-2"></i></span>
                                                                                </div>                                                                                  
                                                                                <div  id="collapse-ip-<?php echo $i; ?>" class="collapse" data-parent="#task-ip-<?php echo $i; ?>"> 
                                                                                    <div class="widget-subheading text-muted"><i><?php          echo "id: ";
                                                                                                                                                echo $s['id_task']; ?></i></div> 
                                                                                    <div class="widget-subheading text-muted task-deadline"><i><?php if( $s['deadline'] !== '1970-01-01'){
                                                                                                                                                echo "Deadline: ";
                                                                                                                                                echo $s['deadline'];} ?></i></div>  
                                                                                    <p class="font-small text-dark pt-1 task-description"><?php echo $s['task_description'];?></p>                                                                                                                                                                                                                   
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                        <div class="widget-content-right ml-auto"> 
                                                                            <button type="button" class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#task-edit-<?php echo $i; ?>" <?php if ($_SESSION['role'] == "user") echo "disabled"; ?>> <i class="fas fa-pencil-alt"></i></button> 
                                                                        <?php  require '../events/edc/editTask.php' ?>    
                                                                            <button type="button" class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target="#task-delete-<?php echo $i; ?>" <?php if ($_SESSION['role'] == "user") echo "disabled"; ?>> <i class="fas fa-trash-alt"></i> </button> 
                                                                        <?php  require '../events/edc/delTask.php' ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-center">   
                                                                    <form name="id_task_left-<?php echo $i; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" role="form">
                                                                        <input hidden name="id_task_left" value=<?php echo $s['id_task']; ?> >
                                                                        <input hidden name="task_status" value=<?php echo $s['task_status']; ?> >           
                                                                        <input hidden name="id_project_left" value=<?php echo $s['id_project']; ?> >                                          
                                                                        <button type="submit" class="border-0 btn-transition btn btn-outline-primary"> <i class="fa fa-arrow-left"></i></button>
                                                                    </form>
                                                                    <form name="id_task_right-<?php echo $i; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" role="form">
                                                                        <input hidden name="id_task_right" value=<?php echo $s['id_task']; ?> >
                                                                        <input hidden name="task_status" value=<?php echo $s['task_status']; ?> >           
                                                                        <input hidden name="id_project_right" value=<?php echo $s['id_project']; ?> >                                          
                                                                        <button type="submit" class="border-0 btn-transition btn btn-outline-primary"> <i class="fa fa-arrow-right"></i></button>
                                                                    </form>       
                                                                </div>                                     
                                                            </li>

                                                            <?php $i++; }
                                                            endforeach; }  ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </perfect-scrollbar>
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-4">
                                    <div class="card-hover-shadow-2x mb-3 card text-dark">
                                        <div class="card-header-tab card-header d-flex justify-content-between">
                                            <h5 class="card-header-title font-weight-normal"><i class="fas fa-power-off mr-3"></i>Завершено</h5>                                            
                                        </div>
                                        <div class="scroll-area-sm">
                                            <perfect-scrollbar class="ps-show-limits">
                                                <div style="position: static;" class="ps ps--active-y">
                                                    <div class="ps-content">
                                                        <ul class=" list-group list-group-flush drop-zone" style="min-height: 125px; max-height: 1250px; background: #FFFFFF;" id="col3"  task-status="3"> 
                                                        <?php if (isset($show_tasks)) {	
                                                            $i = 2000000;
                                                            foreach ($show_tasks as $s): 
                                                                if($s['task_status'] == '3'){
                                                        ?>  
                                                        
                                                            <li class="accordion list-group-item pe-auto" id="task-c-<?php echo $i; ?>" data-task-id="<?php echo $s['id_task']; ?>" draggable="true">        
                                                                <div class="todo-indicator" style="background-color:<?php echo $s['task_color'];?>;">
                                                                </div>
                                                                <div class="widget-content p-0">
                                                                    <div class="widget-content-wrapper">
                                                                        <a class="col-8 nav-link text-primary p-0" data-toggle="collapse" data-target="#collapse-c-<?php echo $i; ?>" aria-expanded="true">
                                                                            <div class="widget-content-left p-2 pl-3">
                                                                                <div class="widget-heading d-flex task-title">                                                                                   
                                                                                <?php echo $s['task_name'];?>                                                                                                                                                             
                                                                                    <span class="accicon"><i class="fa fa-angle-down rotate-icon pl-2"></i></span>
                                                                                </div>                                                                                  
                                                                                <div  id="collapse-c-<?php echo $i; ?>" class="collapse" data-parent="#task-c-<?php echo $i; ?>"> 
                                                                                    <div class="widget-subheading text-muted"><i><?php          echo "id:";
                                                                                                                                                echo $s['id_task']; ?></i></div> 
                                                                                    <div class="widget-subheading text-muted task-deadline"><i><?php if( $s['deadline'] !== '1970-01-01'){
                                                                                                                                                echo "Deadline: ";
                                                                                                                                                echo $s['deadline'];} ?></i></div>                                                               
                                                                                    <p class="font-small text-dark pt-1 task-description"><?php echo $s['task_description'];?></p>                                                                                                                                                                                                                   
                                                                                </div>
                                                                            </div>
                                                                        </a>
                                                                        <div class="widget-content-right ml-auto"> 
                                                                            <button type="button" class="border-0 btn-transition btn btn-outline-success" data-toggle="modal" data-target="#task-edit-<?php echo $i; ?>" <?php if ($_SESSION['role'] == "user") echo "disabled"; ?>> <i class="fas fa-pencil-alt"></i></button> 
                                                                        <?php  require '../events/edc/editTask.php' ?>    
                                                                            <button type="button" class="border-0 btn-transition btn btn-outline-danger" data-toggle="modal" data-target="#task-delete-<?php echo $i; ?>" <?php if ($_SESSION['role'] == "user") echo "disabled"; ?>> <i class="fas fa-trash-alt"></i> </button> 
                                                                        <?php  require '../events/edc/delTask.php' ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="d-flex justify-content-center">   
                                                                    <form name="id_task_left-<?php echo $i; ?>" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" role="form">
                                                                        <input hidden name="id_task_left" value=<?php echo $s['id_task']; ?> >
                                                                        <input hidden name="task_status" value=<?php echo $s['task_status']; ?> >           
                                                                        <input hidden name="id_project_left" value=<?php echo $s['id_project']; ?> >                                          
                                                                        <button type="submit" class="border-0 btn-transition btn btn-outline-primary"> <i class="fa fa-arrow-left"></i></button>
                                                                    </form>
                                                                    <button type="submit" class="border-0 btn-transition btn btn-outline-secondary" disabled> <i class="fa fa-arrow-right"></i></button>       
                                                                </div>                                                                                                    
                                                            </li>

                                                            <?php $i++; }
                                                            endforeach; }  ?>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </perfect-scrollbar>
                                        </div>
                                    </div>
                                </div>                                 
                            </div>                            
                        </div>
                    </div>
                </perfect-scrollbar>
            </div>
        </div>
    </div>
</div>

        <!--Чат -->

        <div id="chatBoxModal" style="display: none;">
  <div id="chatModal2">
    <span onclick="closeChat()" class="close-btn" style="position:absolute;top:10px;right:15px;font-size:24px;color:white;cursor:pointer;">×</span>
    <div id="chatContainer">
      <h2 id="chatTitle" style="color:white;"></h2>
      <div id="chat" style="height:300px;background:white;border:1px solid #ccc;overflow:auto;margin-bottom:10px;padding:10px;"></div>
      <input type="text" id="msg" placeholder="Введите сообщение" class="form-control mb-2">
      <button onclick="send()" class="btn btn-primary">Отправить</button>
    </div>
  </div>
</div>


                                <!-- Модальное окно -->
                                <div id="chatModal">
                                 <div id="chatModal2"> 
                                    <span onclick="closeModal()" style="font-size: 25px; color:white; position:absolute; top:10px; right:15px; cursor:pointer; font-weight:bold;">×</span>
                                    <h3 id="chatText">Выберите собеседника</h3>
                                    <ul>
                                    <?php include '../chat/chat.users.php'; ?>
                                    </ul>
                               </div> 
                                </div>

 <script>
                                function openModal() {
                                document.getElementById('chatModal').style.display = 'block';
                                }
                                function closeModal() {
                                document.getElementById('chatModal').style.display = 'none';
                                }
</script>

                                <?php
                                if (isset($_GET['user_id'])) {
                                    include '../chat/chat.logic.php';
                                }
                                ?>

<!-- <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script> -->
<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.10/dist/sweetalert2.all.min.js"></script>

<!-- <script>
  const senderId = <?= json_encode($_SESSION['id_user'] ?? 0) ?>;
  const senderId = <?= json_encode($_SESSION['user'] ?? 0) ?>;
</script> -->
<!-- <script>
    document.querySelectorAll('button[data-toggle="modal"]').forEach(btn => {
        btn.addEventListener('click', () => {
            setTimeout(() => btn.blur(), 100); // убрать фокус с кнопки
        });
        // Убирает фокус с кнопок, когда модальное окно закрывается
        $('.modal').on('hidden.bs.modal', function () {
        document.activeElement.blur();
        });
    });
</script>  -->
<script src="../js/task.modal.js"></script>
<script src="../js/project.modal.js"></script>
<script src="../js/chat.modal.js"></script>
<script type="text/javascript" src=/js/clock.js></script> 
<script src=/js/drag_&_drop.js></script> 
<script src="../js/button.focus.js"></script> 


</body>
</html>
<!--/*  $connection = new mysqli('localhost', 'root', 'root', 'kanban-board';
                      $query = "SELECT role FROM users WHERE id = $user_id";
                      $result = mysqli_query($connection, $query);
                      if ($row = mysqli_fetch_assoc($result)) {
                        $_SESSION['role'] = $row['role'];
                        echo ($_SESSION['role']);
                    }  */ -->

            