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

        <script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
        <script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
        <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>
        <script src="https://cdn.socket.io/4.1.3/socket.io.min.js"></script>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2" >
                    <div id="messages" ><p>HI:</p></div>
                    <ul id="messages2"></ul>
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