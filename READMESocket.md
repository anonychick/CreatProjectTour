socketsJoin: làm cho các phiên bản socket phù hợp tham gia vào các phòng được chỉ định
̀socketsLeave : làm cho các phiên bản socket phù hợp rời khỏi các phòng được chỉ định
disconnectSockets: làm cho các phiên bản ổ cắm phù hợp bị ngắt kết nối
fetchSockets: trả về các phiên bản socket phù hợp


io.of("/admin").in("room1").except("room2").local.disconnectSockets();
Điều này tạo ra tất cả các phiên bản Ổ cắm của không gian tên "quản trị viên"

trong phòng "room1" ( in("room1")hoặc to("room1"))
ngoại trừ những cái trong "room2" ( except("room2"))
và chỉ trên máy chủ Socket.IO hiện tại ( local)


// make all Socket instances join the "room1" room
io.socketsJoin("room1");

// make all Socket instances in the "room1" room join the "room2" and "room3" rooms
io.in("room1").socketsJoin(["room2", "room3"]);

// make all Socket instances in the "room1" room of the "admin" namespace join the "room2" room
io.of("/admin").in("room1").socketsJoin("room2");

// this also works with a single socket ID
io.in(theSocketId).socketsJoin("room1");

// server A
io.on("connection", (socket) => {
  socket.data.username = "alice";
});

// server B
const sockets = await io.fetchSockets();
console.log(sockets[0].data.username); // "alice"




socket.on("connect", () => {
  const engine = socket.io.engine;
  console.log(engine.transport.name); // in most cases, prints "polling"

  engine.once("upgrade", () => {
    // called when the transport is upgraded (i.e. from HTTP long-polling to WebSocket)
    console.log(engine.transport.name); // in most cases, prints "websocket"
  });

  engine.on("packet", ({ type, data }) => {
    // called for each packet received
  });

  engine.on("packetCreate", ({ type, data }) => {
    // called for each packet sent
  });

  engine.on("drain", () => {
    // called when the write buffer is drained
  });

  engine.on("close", (reason) => {
    // called when the underlying connection is closed
  });
});