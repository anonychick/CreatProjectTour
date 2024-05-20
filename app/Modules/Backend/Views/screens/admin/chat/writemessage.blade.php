@extends('Backend::layouts.master')

@section('title', __('Chat'))

@php
    admin_enqueue_styles([
        'apexcharts',
        'flatpickr',
        'modules-widgets',
    ]);
    admin_enqueue_scripts([
        'apexcharts',
        'flatpickr',
        'gmz-widget'
    ]);
    $user_id = get_current_user_id();
@endphp

@section('content')

    <h5 class="mt-4 mb-4">{{__('Dashboard')}}</h5>

    <div class="row layout-top-spacing sales">

        <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
        <script src="https://cdn.socket.io/4.1.3/socket.io.min.js"></script>
        <div class="container">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel panel-default">
                        <div class="panel-heading">Send message</div>
                        <form action="sendmessage" method="POST">
                            @csrf
                            <input type="text" name="message" >
                            <input type="submit" value="send">

                            <input type="text" id="roomInput" placeholder="Nhập tên room">
                            <button class="joinroom">Tham gia Room</button>
                            <button class="leaveroom">Rời khỏi Room</button>
                            <input type="text" id="messageInput" placeholder="Nhập tin nhắn">
                            <button class="sendtoserver">Gửi</button>
                            <ul id="messages"></ul>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            const token = "abc"; // Thay bằng token thực tế của bạn
             const socket = io.connect('http://localhost:8890', {
                 withCredentials: true,
                 extraHeaders: {
                     "my-custom-header": "abcd"
                 },
                 auth: {
                     token: token
                 }
             });
             socket.on("connect", () => {
                 const engine = socket.io.engine;
                 //kiem tra cong ket noi
                 const transport = socket.io.engine.transport.name; // in most cases, "polling"
    
                 socket.io.engine.on("upgrade", () => {
                     const upgradedTransport = socket.io.engine.transport.name; // in most cases, "websocket"
                 });
    
                 //Nhan tin nhan gui den
                 console.log(transport);
                 var test = socket.on('ibooking_database_message', function (data) {
                     console.log(data);
                     $( "#messages" ).append( "<p>"+data+"</p>" );
                 });
    
                 socket.on("hello", (arg1) => {
                     console.log(arg1); // prints "world"
                     $( "#messages" ).append( "<p>"+arg1+"</p>" );
                 });
    
                 socket.emit("update item", "1", { name: "updated" }, (response) => {
                     console.log(response.status); // ok
                 });
                 
                 window.onbeforeunload = function() {
                    return "Bạn có chắc chắn muốn rời khỏi trang này?";
                };
                // Hàm tham gia room
                $('.joinroom').on('click', function (e) {
                    event.preventDefault();
                    const roomInput = document.getElementById('roomInput');
                    const room = roomInput.value;
                    console.log(room);
                    // socket.emit('joinRoom', room);
                    socket.emit("create-room", room, (response) => {
                        console.log(response.status); // ok
                    })

                    socket.emit("create-room", room, (response) => {
                        console.log(response.status); // ok
                    })

                    socket.emit("joinRoom", room, (response) => {
                        console.log(response.status); // ok
                    })
                    roomInput.value = '';
                })
        
                // Hàm rời khỏi room
                $('.leaveroom').on('click', function (e) {
                    event.preventDefault();
                    const roomInput = document.getElementById('roomInput');
                    const room = roomInput.value;
                    socket.emit("leaveRoom", room, (response) => {
                        console.log(response.status); // ok
                    })
                    roomInput.value = '';
                })
        
                // Xử lý khi nhận được tin nhắn từ server
                socket.on('message', (message) => {
                    const messages = document.getElementById('messages');
                    const li = document.createElement('li');
                    li.textContent = message;
                    messages.appendChild(li);
                });
        
                // Hàm gửi tin nhắn tới server
                $('.sendtoserver').on('click', function (e) {
                    event.preventDefault();
                    const messageInput = document.getElementById('messageInput');
                    const message = messageInput.value;
                    const roomInput = document.getElementById('roomInput');
                    const room = roomInput.value;
                    console.log(room);
                    // socket.emit('message', { message, room });
                    socket.emit("message", { message, room }, (response) => {
                        console.log(response.status); // ok
                    })
                    messageInput.value = '';
                })
    
                 // if (socket.connected) {
                 //      socket.emit( /* ... */ );
                 // } else {
                 // // ...
                 // }
    
                 // socket.volatile.emit( /* ... */ );
    
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
    
                 // let count = 0;
                 // setInterval(() => {
                 //     socket.volatile.emit("ping", ++count);
                 // }, 1000);
    
                 // socket.once("details", (...args) => {
                 // // ...
                 // });
    
                 // // and then later...
                 // socket.off("details", listener);
    
                 // });
    
                 // socket.on("data", () => { 
                 //     console.log(data);
                 // });
    
                 // for a specific event
    
                 // socket.removeAllListeners("details");
                 // // for all events
                 // socket.removeAllListeners();
    
                 // socket.onAny((eventName, ...args) => {
                 // // ...
                 // });
    
                 // socket.onAnyOutgoing(() => {
                 // // triggered when the event is sent
                 // });
    
                 // socket.prependAny((eventName, ...args) => {
                 // // ...
                 // });
    
                 // and then later...
                 // socket.offAny(listener);
    
                 // // or all listeners
                 // socket.offAny();
    
                 // socket.onAnyOutgoing(() => {
                 // // not triggered when the acknowledgement is sent
                 // });
    
                 // socket.prependAnyOutgoing((event, ...args) => {
                 // // ...
                 // });
                 socket.emit("create user", {username: "cuongđoan", email: "cuong@gmail"}, (response) => {
                     console.log(response.status); // ok
                 })
             });

            //  const socket = io(); // or io("/"), the main namespace
            // const orderSocket = io("/orders"); // the "orders" namespace
            // const userSocket = io("/users"); // the "users" namespace
            // const socket1 = io();
            // const socket2 = io("/admin", { forceNew: true });
            // io.of(/^\/dynamic-\d+$/);

            // io.of((name, auth, next) => {
            // next(null, true); // or false, when the creation is denied
            // });

            // io.of(/^\/dynamic-\d+$/).on("connection", (socket) => {
            //     const namespace = socket.nsp;
            // });

            // const parentNamespace = io.of(/^\/dynamic-\d+$/);

            // parentNamespace.use((socket, next) => { next() });
    
             socket.on("disconnect", (reason) => {
                 if (reason === "io server disconnect") {
                     console.log('io server disconnect hang on');
                     // the disconnection was initiated by the server, you need to reconnect manually
                     socket.connect();
                 }
                 console.log('io server disconnect hang on');
                 socket.connect();
             });
    
             socket.io.on("reconnect_attempt", () => {
                 // ...
                 console.log('ket noi lai  1');
             });
    
             socket.io.on("reconnect", () => {
                 // ...
                 console.log('ket noi lai  2');
             });
    
             socket.on("connect_error", (err) => {
                 console.log(err instanceof Error); // true
                 console.log(err.message); // not authorized
                 console.log(err.data); // { content: "Please retry later" }
                 socket.auth.token = "abcd";
                 socket.connect();
             });
         </script>
    </div>
@stop