const express = require('express');
const http = require('http');
const { Server } = require('socket.io');
const { createAdapter } = require('@socket.io/redis-adapter');
const { Emitter } = require('@socket.io/redis-emitter');
const { createClient } = require('redis');
const redis = require('ioredis');
const app = express();
const server = http.createServer(app);
const uuid = require('uuid');
const Joi = require("joi");  // https://joi.dev/api/?v=17.9.1
const { log } = require('console');
require('events').EventEmitter.defaultMaxListeners = Infinity;

const io = new Server(server, {
  reconnectionDelay: 10000,
  reconnectionDelayMax: 10000,
  maxHttpBufferSize: 1e8,
  pingTimeout: 60000,
  allowedHeaders: ["my-custom-header"],
  // serveClient: false,
  connectionStateRecovery: {
    maxDisconnectionDuration: 2 * 60 * 1000,
    skipMiddlewares: true,
  },
  cors: {
    origin: 'http://127.0.0.1:8001',
    methods: ['GET', 'POST'],
    credentials: true,
  },
});

io.use((socket, next) => {
  // Xác thực token ở đây
  const token = socket.handshake.auth.token;

  if (!token) {
    return next(new Error('Authentication error'));
  }

  if (token === 'abc') {
    next();
  } else {
    const err = new Error("not authorized");
    err.data = { content: "Please retry later" };
    next(err);
  }
});

const userSchema = Joi.object({
  username: Joi.string().max(30).required(),
  email: Joi.string().email().required()
});

// Khởi tạo kết nối Redis
const redisClient = createClient({ url: 'redis://localhost:6379' });
const subscriber = redis.createClient({ url: 'redis://localhost:6379' }); 

const pubClient = createClient({ url: 'redis://localhost:6379' });
const subClient = pubClient.duplicate();

Promise.all([pubClient.connect(), subClient.connect()]).then(() => {
  io.adapter(createAdapter(pubClient, subClient));
  io.listen(8890);

  subscriber.subscribe('ibooking_database_message');

  io.of("/").adapter.on("create-room", (room) => {
    console.log(`room ${room} was created`);
  });
  
  io.of("/").adapter.on("join-room", (room, id) => {
    console.log(`socket ${id} has joined room ${room}`);
  });

  // // to all clients
  // emitter.emit(/* ... */);

  // // to all clients in "room1"
  // emitter.to("room1").emit(/* ... */);

  // // to all clients in "room1" except those in "room2"
  // emitter.to("room1").except("room2").emit(/* ... */);

  // const adminEmitter = emitter.of("/admin");

  // // to all clients in the "admin" namespace
  // adminEmitter.emit(/* ... */);

  // // to all clients in the "admin" namespace and in the "room1" room
  // adminEmitter.to("room1").emit(/* ... */);

  // // make all Socket instances join the "room1" room
  // emitter.socketsJoin("room1");

  // // make all Socket instances of the "admin" namespace in the "room1" room join the "room2" room
  // emitter.of("/admin").in("room1").socketsJoin("room2");

  // make all Socket instances leave the "room1" room
  // emitter.socketsLeave("room1");

  // // make all Socket instances in the "room1" room leave the "room2" and "room3" rooms
  // emitter.in("room1").socketsLeave(["room2", "room3"]);

  // // make all Socket instances in the "room1" room of the "admin" namespace leave the "room2" room
  // emitter.of("/admin").in("room1").socketsLeave("room2");

  // make all Socket instances disconnect
  // emitter.disconnectSockets();

  // // make all Socket instances in the "room1" room disconnect (and discard the low-level connection)
  // emitter.in("room1").disconnectSockets(true);

  // // make all Socket instances in the "room1" room of the "admin" namespace disconnect
  // emitter.of("/admin").in("room1").disconnectSockets();

  // // this also works with a single socket ID
  // emitter.of("/admin").in(theSocketId).disconnectSockets();

  // emit an event to all the Socket.IO servers of the cluster
// emitter.serverSideEmit("hello", "world");

// // Socket.IO server (server-side)
// io.on("hello", (arg) => {
//   console.log(arg); // prints "world"
// });

  subscriber.on('message', function (channel, message) {
    console.log(`Received message from channel ${channel}: ${message}`);
    io.emit('ibooking_database_message', message);
  });

  subscriber.on('error', function (err) {
    console.error('Redis Subscriber Error', err);
  });
});

io.on('connection', (socket) => {
  socket.join("some room");
  socket.join("room1");
  socket.join("room2");
  socket.join("room3");
  io.to("room1").to("room2").to("room3").emit("hello", "worldline90");
  io.to("some room").emit("hello", "worldline80", (err, responses) => {
    // ...
    console.log('hello3');
  });


  console.log('A user connected');

  // Kieemr tra tinh trang recover
  if (socket.recovered) {
    console.log('Recovered');
  } else {
    console.log('Cannot Recover');
  }

  // Kiem tra so luong khach ket noi
  const count = io.engine.clientsCount;
  const count2 = io.of("/").sockets.size;
  console.log(`Hien co ${count} user dang online`);
  console.log(`Hien co ${count2} user dang online`);

  // Kiem tra ten ket noi va cong
  const transport = socket.conn.transport.name; // in most cases, "polling"
  console.log(transport);
  socket.conn.on("upgrade", () => {
    const upgradedTransport = socket.conn.transport.name; // in most cases, "websocket"
    console.log(upgradedTransport);
  });

  // Tao uuid tuy chinh
  io.engine.generateId = (req) => {
    return uuid.v4(); // must be unique across all Socket.IO servers
  }
  console.log(io.engine.generateId);

  // Se duoc phat ra ngay truoc khi viet tieu de phan hoi cua yeu cau HTTP dau tien cua phien (bat tay), cho phep ban tuy chinh chung.
  io.engine.on("initial_headers", (headers, req) => {
    headers["test"] = "123";
    headers["set-cookie"] = "mycookie=456";
  });

  // Se duoc phat ra ngay truoc khi ghi tieu de phan hoi cua tung yeu cau HTTP cua phien (bao gom ca nang cap WebSocket), cho phep ban tuy chinh chung.
  io.engine.on("headers", (headers, req) => {
    headers["test"] = "789";
  });

  // Se duoc phat ra khi ket noi bi dong bat thuong
  io.engine.on("connection_error", (err) => {
    console.log(err.req);      // the request object
    console.log(err.code);     // the error code, for example 1
    console.log(err.message);  // the error message, for example "Session ID unknown"
    console.log(err.context);  // some additional error context
  });

  // emitter.emit("ibooking_database_message", new Date);

  console.log(socket.id);

  // io.serverSideEmit("hello", "world");

  // Handle custom event from the client
  socket.on('ibooking_database_message', (data) => {
    console.log('Received data from client:', data);
    emitter.emit('ibooking_database_message', data);
  });

  socket.emit("hello", 1, "2", { 3: '4', 5: Buffer.from([6]) }, { name: "John" });

  socket.on("update item", (arg1, arg2, callback) => {
    console.log(arg1); // 1
    console.log(arg2); // { name: "updated" }
    callback({
      status: "ok"
    });
  });

  socket.on("create user", (payload, callback) => {
    if (typeof callback !== "function") {
      // not an acknowledgement
      return socket.disconnect();
    }
    const { error, value } = userSchema.validate(payload);
    if (error) {
      return callback({
        status: "Bad Request",
        error
      });
    }
    // do something with the value, and then
    console.log('163');
    console.log(value);
    callback({
      status: "OK"
    });
  });

  socket.on('joinRoom', (room) => {
    socket.join(room);
    console.log(`Client đã tham gia vào room ${room}`);
  });

  // Xử lý khi client leave room
  socket.on('leaveRoom', (room) => {
      socket.leave(room);
      console.log(`Client đã rời khỏi room ${room}`);
  });

  // Xử lý khi nhận được tin nhắn từ client
  socket.on('message', (data) => {
    console.log(data);
      console.log(`Client nói trong room ${data.room}:`, data.message);
      // Gửi lại tin nhắn đã nhận được từ client đến tất cả các client trong cùng một room
      io.to(data.room).emit('message', data.message);
  });

  // socket.on("list items", async (callback) => {
  //   try {
  //     const items = await findItems();
  //     callback({
  //       status: "OK",
  //       items
  //     });
  //   } catch (e) {
  //     callback({
  //       status: "NOK"
  //     });
  //   }
  // });

  // io.timeout(5000).emit("hello", "world", (err, responses) => {
  //   if (err) {
  //     // some clients did not acknowledge the event in the given delay
  //     console.log('hello1');
  //     console.log(err);
  //   } else {
  //     console.log(responses); // one response per client
  //   }
  // });

  // io.to("room123").timeout(5000).emit("hello", "world", (err, responses) => {
  //   // ...
  //   console.log('hello2');
  //   console
  // });

  socket.broadcast.timeout(5000).emit("hello", "world", (err, responses) => {
    // ...
    console.log('hello3');
  });

  // io.of("/the-namespace").timeout(5000).emit("hello", "world", (err, responses) => {
  //   // ...
  //   console.log('hello4');
  // });

  // io.local.emit("hello", "world");

  socket.on("ping", (count) => {
    console.log(count);
  });

  // io.except("some room").emit("some event");
  // io.to("room1").to("room2").to("room3").emit("some event");

  // Disconnect event
  socket.on('disconnect', () => {
    console.log('User disconnected');
  });
});

//////// Không gian order
const orderNamespace = io.of("/orders");
orderNamespace.use((socket, next) => {
  // ensure the socket has access to the "orders" namespace, and then
  next();
});
orderNamespace.on("connection", (socket) => {
  socket.join("room1");
  orderNamespace.to("room1").emit("hello");
});

///////// Không gian user
const userNamespace = io.of("/users");
userNamespace.use((socket, next) => {
  // ensure the socket has access to the "users" namespace, and then
  next();
});

userNamespace.on("connection", (socket) => {
  socket.join("room1"); // distinct from the room in the "orders" namespace
  userNamespace.to("room1").emit("holà");
});

////Không gian tên tự tạo
const workspaces = io.of(/^\/\w+$/);

workspaces.on("connection", socket => {
  const workspace = socket.nsp;

  workspace.emit("hello");
});

io.on("connection", (socket) => {});
io.use((socket, next) => { next() });
io.emit("hello");
// are actually equivalent to
io.of("/").on("connection", (socket) => {});
io.of("/").use((socket, next) => { next() });
io.of("/").emit("hello");


const nsp = io.of("/my-namespace");

nsp.on("connection", socket => {
  console.log("someone connected");
});

nsp.emit("hi", "everyone!");


redisClient.connect().then(() => {
  console.log('Connected redis client');
  const emitter = new Emitter(redisClient); 
});
