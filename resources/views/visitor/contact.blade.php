@extends('layouts.front_end.app_front')
@php $pageName = 'Contact Us'; $pageUrl = route('contact'); @endphp

@section('main_content')
    @include('layouts.front_end.partials.banner')

    <!--================Contact Area =================-->
    <section class="contact_area section_gap">
        <div class="container">
            <div class="m-b-30">
                <iframe src="//www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3651.7054736079713!2d90.36529473227534!3d23.757880067438126!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755bf82045d47dd%3A0x1ef424171c3a0772!2sRising%20Sun%20Preparatory%20School!5e0!3m2!1sen!2sbd!4v1580022337930!5m2!1sen!2sbd" class="col-12" height="400" frameborder="0" style="border:0;" allowfullscreen=""></iframe>
            </div>
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact_info">
                        <br>
                        @if($ContactDetails !== null)
                            <div class="info_item">
                                <i class="ti-home"></i>
                                <h6>{{ $ContactDetails->address }}, {{ $ContactDetails->city }}</h6>
                                <p></p>
                            </div>
                            <div class="info_item">
                                <i class="ti-headphone"></i>
                                <h6><a href="tel:{{ $ContactDetails->phone }}">{{ $ContactDetails->phone }}</a></h6>
                                <p>Mon to Fri 9am to 6 pm</p>
                            </div>
                            <div class="info_item">
                                <i class="ti-email"></i>
                                <h6><a href="mailto:{{ $ContactDetails->email }}">{{ $ContactDetails->email }}</a></h6>
                                <p>Send us your query anytime!</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-8">
                    <h3 class="col-12 text-center m-b-20">Get in touch</h3>
                    <form class="row contact_form" action="{{ route('contact.send') }}" method="post" id="contactForm" novalidate="novalidate">
                        @csrf
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="name">Name:</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" required="" />
                            </div>
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'" required="" />
                            </div>
                            <div class="form-group">
                                <label for="subject">Subject:</label>
                                <input type="text" class="form-control" id="subject" name="subject" value="{{ old('subject') }}" placeholder="Enter Subject" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Subject'" required="" />
                            </div>
                        </div>
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="message">Message:</label>
                                <textarea class="form-control" name="message" id="message" rows="1" placeholder="Enter Message" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" required="">{{ old('message') }}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12 text-right">
                            <button type="submit" value="submit" class="btn primary-btn">
                                Send Message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!--================Contact Area =================-->

    <!--================Contact Success and Error message Area =================-->
    <div id="success" class="modal modal-message fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                    <h2>Thank you</h2>
                    <p>Your message is successfully sent...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals error -->

    <div id="error" class="modal modal-message fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <i class="ti-close"></i>
                    </button>
                    <h2>Sorry !</h2>
                    <p>Something went wrong</p>
                </div>
            </div>
        </div>
    </div>
    <!--================End Contact Success and Error message Area =================-->

@endsection




@section('page_level_script')
    <script src="{{ asset('asset_front/js/contact.js') }}"></script>
@endsection
