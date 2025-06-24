/* const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });

console.log("✅ WebSocket-сервер запущен на порту 8080");

wss.on('connection', function connection(ws) {
    console.log('🟢 Клиент подключился');

    ws.on('message', function incoming(message) {
        const text = message.toString(); // Преобразуем в строку
        console.log('📩 Получено сообщение:', text);

        // Рассылка всем клиентам
        wss.clients.forEach(client => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(text);
            }
        });
    });

    ws.on('close', () => {
        console.log('🔴 Клиент отключился');
    });
});
 */
const WebSocket = require('ws');
const wss = new WebSocket.Server({ port: 8080 });

console.log('WebSocket сервер запущен на порту 8080');

wss.on('connection', ws => {
    ws.on('message', msg => {
        let data;
        try {
            data = JSON.parse(msg);
        } catch {
            return;
        }

/*      // Логика получения имени по ID
        data.sender_name = `Пользователь ${data.senderId}`; */

        // Рассылка всем
        wss.clients.forEach(client => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(JSON.stringify(data));
            }
        });
    });
});

// Также слушаем обычные TCP-сообщения от PHP
const net = require('net');
net.createServer(socket => {
    socket.on('data', msg => {
        let data;
        try {
            data = JSON.parse(msg);
        } catch {
            return;
        }

        wss.clients.forEach(client => {
            if (client.readyState === WebSocket.OPEN) {
                client.send(JSON.stringify(data));
            }
        });
    });
}).listen(8081);
