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
        <main class="container">
            <div class="d-flex align-items-center p-3 my-3 text-white bg-purple rounded shadow-sm">
                <a href="https://manhdandev.com" target="_blank">
                    <img class="me-3" src="https://manhdandev.com/web/img/logo.webp" alt="" width="80" height="80">
                </a>
            </div>
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <h6 class="border-bottom pb-2 mb-0">Message OpenAI</h6>
                <div class="text-muted pt-3">
                    <form action="{{ route('openai_write') }}" method="post">
                        @csrf
                        <input type="text" class="form-control input-full-width" name="title" placeholder="Type your article title..." value="{{ $title ?? ''}}">
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-primary mt-2">Generate</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="my-3 p-3 bg-body rounded shadow-sm">
                <div class="d-flex text-muted pt-3">
                    <div class="pb-3 mb-0 small lh-sm border-bottom w-100">
                        <div class="d-flex justify-content-between">
                            <textarea class="form-control" id="exampleFormControlTextarea1" rows="18">{{ $content ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
@stop