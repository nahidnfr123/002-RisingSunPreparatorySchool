@extends('layouts.front_end.app_front')
@php $pageName = 'About'; $pageUrl = route('about'); @endphp

@section('main_content')
    @include('layouts.front_end.partials.banner')

    <!--================Contact Area =================-->
    <section class="contact_area section_gap">
        <div class="container">

            @if($About != null)
                {!! $About->about !!}
            @endif
        </div>
    </section>
    <!--================Contact Area =================-->


@endsection




@section('page_level_script')

@endsection
