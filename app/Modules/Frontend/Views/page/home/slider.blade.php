<section class="hero-slider" style="min-height: 350px">
    @php
        $slider = get_option('home_slider');       
        $galleries = ["	https://ibookings.org/storage/2021/01/30/slider03-1612013205-1920x768.JPG",
                        "https://ibookings.org/storage/2021/01/30/slider04-1612013206-1920x768.JPG",
                        "https://ibookings.org/storage/2021/01/30/slider02-1612013205-1920x768.JPG",
                    ];
        // if(!empty($slider)){
        //     $slider = explode(',', $slider);
        //     if(!empty($slider)){
        //         foreach($slider as $item){
        //             $url = get_attachment_url($item, [1920, 768]);
        //             dd($url);
        //             if(!empty($url)){
        //                 array_push($galleries, $url);
        //             }
        //         }
        //     }
        // }
        $text_slider = get_translate(get_option('home_slider_text'));
        // dd($galleries);
    @endphp
    <div class="container-fluid no-gutters p-0">
        @if(!empty($galleries))
            <div class="slider" data-plugin="slick">
                @foreach($galleries as $item)
                    <div class="item">
                        <img src="{{$item}}" alt="home slider">
                    </div>
                @endforeach
            </div>
        @endif

        <div class="search-center">
            <div class="container">
                @if(!empty($text_slider))
                    <p class="search-center__title">{{$text_slider}}</p>
                @endif
                @include('Frontend::services.search-form')
            </div>
        </div>
    </div>
</section>