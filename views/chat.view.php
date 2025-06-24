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



