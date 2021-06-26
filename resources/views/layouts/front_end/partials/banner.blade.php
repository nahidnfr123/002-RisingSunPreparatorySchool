@php
    if(!isset($pageName) || !isset($pageUrl)){
        $pageName = '';
        $pageUrl = '';
        $Link1 = '';
    }else{
        $Link1 = ' / <a href="'.$pageUrl.'"> '.$pageName.'</a>';
    }

    if(!isset($subPageName) || !isset($subPageUrl)){
        $subPageName = '';
        $subPageUrl = '';
        $Link2 = '';
    }else{
        $Link2 = ' / ' . $subPageName;
    }
    if (!isset($Banner)){
        $Banner = '';
    }
@endphp

<!--================ Start Home Banner Area =================-->
<!--<section class="home_banner_area">
    <div class="banner_inner">
        <div class="container">
            &lt;!&ndash;<div class="row">
                <div class="col-lg-12">
                    <div class="banner_content text-center">
                        <p class="text-uppercase">
                            Best online education service In the world
                        </p>
                        <h2 class="text-uppercase mt-4 mb-5">
                            One Step Ahead This Season
                        </h2>
                        <div>
                            <a href="#" class="primary-btn2 mb-3 mb-sm-0">learn more</a>
                            <a href="#" class="primary-btn ml-sm-3 ml-0">see course</a>
                        </div>
                    </div>
                </div>
            </div>&ndash;&gt;
        </div>
    </div>
</section>-->
<div class="customBanner bg-dark" style="height: 360px; background: url('/@if($Banner != null){{ $Banner->image }}@endif'); object-fit: cover; object-position: center; background-attachment: fixed; background-position: center center; background-repeat: no-repeat; background-size: cover;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="banner_content text-center" style="margin-top: 180px;">
                    <h2 class="text-uppercase mt-4 mb-2 text-white">
                        {{ $pageName }}
                    </h2>
                    <p class="text-uppercase">
                        <a href="{{ route('index') }}">Home</a> {!! $Link1 !!}{!! $Link2 !!}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!--================ End Home Banner Area =================-->
