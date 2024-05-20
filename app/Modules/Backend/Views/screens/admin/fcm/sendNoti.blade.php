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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <center>
                        <button id="btn-nft-enable" onclick="initFirebaseMessagingRegistration()" class="btn btn-danger btn-xs btn-flat">Allow for Notification</button>
                    </center>
                    <div class="card">
                        <div class="card-header">{{ __('Dashboard') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form action="{{ route('send.notification') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title">
                                </div>
                                <div class="form-group">
                                    <label>Body</label>
                                    <textarea class="form-control" name="body"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Notification</button>
                            </form>
                        </div>
                    </div>

                    <div class="card mt-5">
                        <div class="card-header">{{ __('Dashboard') }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form action="{{ route('send.notificationJob') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label>Title</label>
                                    <input type="text" class="form-control" name="title">
                                </div>
                                <div class="form-group">
                                    <label>Body</label>
                                    <textarea class="form-control" name="body"></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Send Notification</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
            <script src="https://www.gstatic.com/firebasejs/7.23.0/firebase.js"></script>
            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>
        <script>
            var firebaseConfig = {
                apiKey : "AIzaSyDX5Caocy0GXBBsYL8GKWkrxIutZ8Px85E" , 
                authDomain : "check-notification-91cdc.firebaseapp.com" , 
                databaseURL : "https://check-notification-91cdc-default-rtdb.asia-southeast1.firebasedatabase.app" , 
                projectId : "check-notification-91cdc" , 
                storageBucket : "check-notification-91cdc.appspot.com" , 
                messagingSenderId : "460856376071" , 
                appId : "1:460856376071:web:6db32da6c5f2a7878923a2" , 
                measurementId : "G-N9C2XD4WS3" 
            };
    
            firebase.initializeApp(firebaseConfig);
            const messaging = firebase.messaging();
    
            function initFirebaseMessagingRegistration() {
                messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(function(token) {
                    console.log(token);

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    $.ajax({
                        url: '{{ route("save-token") }}',
                        type: 'POST',
                        data: {
                            token: token
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            alert('Token saved successfully.');
                        },
                        error: function (err) {
                            console.log('User Chat Token Error'+ err);
                        },
                    });

                }).catch(function (err) {
                    console.log('User Chat Token Error'+ err);
                });
            }
    
            messaging.onMessage(function(payload) {
                const noteTitle = payload.notification.title;
                const noteOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };
                new Notification(noteTitle, noteOptions);
            });               
        </script>
    </div>
@stop