// === chat.modal.js ===
let ws;
let chatId = 0;
let senderId = document.body.dataset.userId || 0;
let senderName = document.body.dataset.userName || "Гость";

function openChat(userId, userName) {
  document.getElementById('chatModal').style.display = 'none';
  document.getElementById('chatBoxModal').style.display = 'block';
  document.getElementById('chatTitle').innerText = "Чат с " + userName;
  document.getElementById('chat').innerHTML = "";

  fetch(`../chat/chat.logic.php?user_id=${userId}`)
    .then(res => res.json())
    .then(data => {
      if (data.error) return alert(data.error);
      chatId = data.chatId;
      if (!chatId) return alert("chatId не получен");
      loadMessages(chatId);
      initSocket();
    })
    .catch(err => {
      console.error("Ошибка при открытии чата:", err);
      alert("Не удалось открыть чат.");
    });
}

function closeChat() {
  document.getElementById('chatBoxModal').style.display = 'none';
  if (ws) ws.close();
}

function loadMessages(chatId) {
  fetch(`../chat/loadMessages.php?chatId=${chatId}`)
    .then(res => res.json())
    .then(messages => {
      const chatBox = document.getElementById('chat');
      messages.forEach(m => {
        chatBox.innerHTML += `
          <div data-id="${m.id}" class="chat-line">
            <span><b>${m.sender_name}</b>: <span class="msg-text">${m.message}</span></span>
            <div>
              <button onclick="editMessage(${m.id})" class="edit-btn"><i class="fas fa-pencil-alt"></i></button>
              <button onclick="deleteMessage(${m.id})" class="delete-btn"><i class="fas fa-trash-alt"></i></button>
            </div>
          </div>`;
      });
      chatBox.scrollTop = chatBox.scrollHeight;
    })
    .catch(err => {
      console.error("Ошибка при загрузке сообщений:", err);
    });
}

function initSocket() {
  ws = new WebSocket('ws://localhost:8080');
  ws.onmessage = async e => {
    try {
      const data = JSON.parse(e.data);
      if (data.chatId && data.chatId !== chatId) return;

      const chatBox = document.getElementById('chat');

      if (data.type === 'delete') {
        const el = document.querySelector(`[data-id="${data.id}"]`);
        if (el) el.remove();
        return;
      }

      if (data.type === 'edit') {
        const msgDiv = document.querySelector(`[data-id="${data.id}"] .msg-text`);
        if (msgDiv) msgDiv.textContent = data.message;
        return;
      }

      chatBox.innerHTML += `
        <div data-id="${data.id}" class="chat-line">
          <span><b>${data.sender_name}</b>: <span class="msg-text">${data.message}</span></span>
          <div>
            <button onclick="editMessage(${data.id})" class="edit-btn"><i class="fas fa-pencil-alt"></i></button>
            <button onclick="deleteMessage(${data.id})" class="delete-btn"><i class="fas fa-trash-alt"></i></button>
          </div>
        </div>`;
      chatBox.scrollTop = chatBox.scrollHeight;
    } catch (err) {
      console.error("Ошибка парсинга WebSocket-сообщения:", err);
    }
  };
}

function send() {
  const input = document.getElementById('msg');
  const msg = input.value.trim();
  if (!msg) return;

  fetch('../chat/saveMessage.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `chatId=${chatId}&message=${encodeURIComponent(msg)}`
  })
    .then(res => res.json())
    .then(data => {
      if (!data.status || data.status !== 'success') {
        console.error("Ошибка при сохранении:", data.message);
        return;
      }

      //Отправляем сообщение в WebSocket уже с id
      if (ws && ws.readyState === WebSocket.OPEN) {
        ws.send(JSON.stringify({
          type: 'new',
          chatId,
          id: data.id,                  // ID от saveMessage.php
          senderId,
          sender_name: senderName,
          message: msg
        }));
      }
    });

  input.value = '';
}

function deleteMessage(id) {
  console.log("Удаляем сообщение с ID:", id);
  if (!confirm("Удалить сообщение?")) return;

  fetch('../chat/deleteMessage.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}`
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        const msg = document.querySelector(`[data-id="${id}"]`);
        if (msg) msg.remove();
      } else {
        alert("Не удалось удалить сообщение");
      }
    });
}

function editMessage(id) {
  const msgDiv = document.querySelector(`[data-id="${id}"] .msg-text`);
  const current = msgDiv.textContent;
  const updated = prompt("Изменить сообщение:", current);
  if (!updated || updated === current) return;

  fetch('../chat/editMessage.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id=${id}&message=${encodeURIComponent(updated)}`
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        msgDiv.textContent = updated;
      } else {
        alert("Не удалось обновить сообщение");
      }
    });
}
