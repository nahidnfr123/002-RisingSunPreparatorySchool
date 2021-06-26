<link rel="stylesheet" href="{{ asset('commonAsset/popupMsg.css') }}">
<link rel="stylesheet" href="{{ asset('commonAsset/animate.css') }}">
<script src="{{ asset('commonAsset/wow.min.js') }}"></script>

<div class="popupMsgContainer">
    {{-- Error Messages --}}
    @if($errors->any())
        <div class="popupMsg ErrorMessage animated fadeInDown m-b-10">
            <div class="title"><strong> Error </strong>
                <div class="PopCloseBtn float-right"><i class="fa fa-times"></i></div>
                <div class="clearfix"></div>
            </div>
            <div class="body">
                <ul>
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    @if(session()->has('Error'))
        <div class="popupMsg ErrorMessage animated fadeInDown m-b-10">
            <div class="title"><strong> Error </strong>
                <div class="PopCloseBtn float-right"><i class="fa fa-times"></i></div>
                <div class="clearfix"></div>
            </div>
            <div class="body"> {{ session('Error') }} </div>
        </div>
    @endif

    @if(session()->has('ErrorMsg'))
        <div class="popupMsg ErrorMessage animated fadeInDown m-b-10">
            <div class="title"><strong> Error </strong>
                <div class="PopCloseBtn float-right"><i class="fa fa-times"></i></div>
                <div class="clearfix"></div>$id
            </div>
            <div class="body"> {{ session('ErrorMsg') }} </div>
        </div>
    @endif


    {{-- Success Message --}}
    @if(session()->has('Success'))
        <div class="popupMsg SuccessMessage animated fadeInDownBig">
            <div class="title">
                <strong> Success </strong>
                <div class="PopCloseBtn float-right"><i class="fa fa-times"></i></div>
                <div class="clearfix"></div>
            </div>
            <div class="body"> {{ session('Success') }} </div>
        </div>
    @endif


    {{-- Password reset send --}}
    @if(session()->has('status'))
        <div class="popupMsg SuccessMessage animated fadeInDownBig">
            <div class="title">
                <strong> Success </strong>
                <div class="PopCloseBtn float-right"><i class="fa fa-times"></i></div>
                <div class="clearfix"></div>
            </div>
            <div class="body"> {{ session('status') }} </div>
        </div>
    @endif

    @if(session()->has('Info'))
        <div class="popupMsg text-info animated fadeInDownBig">
            <div class="title">
                <strong class="text-info"> Information </strong>
                <div class="PopCloseBtn float-right"><i class="fa fa-times"></i></div>
                <div class="clearfix"></div>
            </div>
            <div class="body text-info"> {{ session('Info') }} </div>
        </div>
    @endif

</div>



<script>
    $(document).ready(function () {
        //Close popup menus ....
        //$('.popupMsg').each(function () {
            $('.PopCloseBtn').click(function () {
                $(this).parent( ".title" ).parent( ".popupMsg" ).hide();
            });
        //});

    });
</script>



