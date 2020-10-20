<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css">
</head>
<style>
    .card-info {
        border: 1px solid #0000001a;
        border-radius: 10px;
        padding: 21px;

    }

    .content-input input {
        border: 1px solid #ced4da;
        border-radius: 5px;
        padding-left: 10px;
        font-size: 15px;
        height: 33px;
    }

    .content-input {
        margin-bottom: 30px;
    }

    .btn_enviar {
        border: 0;
        padding: 5px 18px;
        border-radius: 10px;
        font-size: 14px;
        background: #FFC107;
        color: #fff;
    }

    .card_content-title p {
        font-size: 12px;
        text-transform: uppercase;
        color: #b9b9b9;
        font-weight: bolder;
        letter-spacing: 2px;
        margin: 0;
        margin-bottom: 5px;
    }

    .card_content-title span {
        color: #000;
        font-size: 21px;
        font-weight: bold;
        margin-left: 20px;
    }

    .card_content-title .subtitle {
        margin-left: 20px;
        font-size: 15px;
        color: #8a8a8a;
        text-transform: none;
        letter-spacing: 0;
        font-weight: initial;
    }

    .card_content-body {

        margin-top: 25px;

    }

    .card_content ul {
        list-style: none;
        padding: 0;
        padding-left: 14px;
        margin-top: 12px;
    }

    .card_content li {
        font-size: 15px;
        color: #8a8a8a;
    }

    .nl-process {
        padding-top: 50px;
        position: relative;
    }

    .process-list {
        display: table;
        table-layout: fixed;
        width: 100%;
        counter-reset: process-count;
        height: 54px;
    }

    .complete-hint {
        display: none;
        width: 54px;
        height: 54px;
        background: #69be38;
        border-radius: 50%;
        text-align: center;
        position: absolute;
        left: calc(50% - 24px);
        top: 23px;
        z-index: 3;
    }

    .complete-hint i {
        line-height: 54px;
        color: #fff;
        font-size: 28px;
    }

    .complete-hint.is-show {
        display: block;
        -webkit-animation: scale0to1 160ms forwards;
        animation: scale0to1 160ms forwards;
    }

    .complete-hint.is-hide {
        display: none;
    }

    .process-item {
        display: table-cell;
        text-align: center;
    }

    .process-item:first-child {
        pointer-events: none;
    }

    .process-item:first-child .process-content:before {
        display: none;
    }

    .process-item:last-child .process-content:after {
        display: none;
    }

    .process-item:last-child .process-active-bar {
        display: none !important;
    }

    .process-content {
        position: relative;
    }

    .process-content .process-active-bar,
    .process-content:before,
    .process-content:after {
        content: '';
        position: absolute;
        background: #e5e5e6;
        border-radius: 2px;
        height: 8px;
        top: 10px;
        -webkit-transition: all 120ms ease-in;
        transition: all 120ms ease-in;
    }

    .process-content .process-active-bar {
        position: absolute;
        background: #ff9c23;
        z-index: 2;
        left: calc(44% + 24px);
        right: calc(100% - 20px);
        -webkit-transition: all 300ms ease-out;
        transition: all 300ms ease-out;
    }

    .process-content:before {
        right: calc(0% + 100px);
        left: -2px;
        margin-left: -100px;
        z-index: -1;
    }

    .process-content:after {
        left: calc(50% + 20px);
        right: 0;
        z-index: 1;
    }

    .process-content .circle {
        position: relative;
        background: #e5e5e6;
        display: inline-block;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        line-height: 28px;
        text-align: center;
        -webkit-transition: all 300ms ease-in;
        transition: all 300ms ease-in;
    }

    .process-content .circle span {
        color: #999;
    }

    .process-content .circle span:before {
        counter-increment: process-count;
        content: counter(process-count);
    }

    .process-content .des {
        padding-top: 5px;
        padding: 5px 10px;
        box-sizing: border-box;
    }

    .process-content .des span {
        color: #bbb;
        -webkit-transition: color 120ms ease-in;
        transition: color 120ms ease-in;
    }

    .process-item.is-active .process-content .process-active-bar {
        display: block;
        right: calc(-52% + 20px);
    }

    .process-item.is-active .process-content .circle {
        background: #ff9c23;
    }

    .process-item.is-active .process-content .circle span {
        color: #fff;
        display: inline-block;
        -webkit-animation: scale0to1 300ms forwards;
        animation: scale0to1 300ms forwards;
    }

    .process-item.is-active .process-content .circle span:before {
        content: "\f00c";
        font: normal normal normal 14px/1 FontAwesome;
    }

    .process-item.is-active .process-content .des span {
        color: #6f6f6f;
    }

    .process-item.is-current .process-content .circle {
        background: #ff9c23;
    }

    .process-item.is-current .process-content .circle span {
        color: #fff;
    }

    .process-item.is-current .process-content .des span {
        color: #6f6f6f;
    }

    .process-item.all-complete .process-content .process-active-bar,
    .process-item.all-complete .process-content:before,
    .process-item.all-complete .process-content:after,
    .process-item.all-complete .process-content .des {
        width: 0;
        opacity: 0;
    }

    .process-item.all-complete .process-content .circle {
        -webkit-transform: scale(0);
        transform: scale(0);
    }

    .footer-step {
        text-align: center;
    }

    .footer-step .footer-step-btn {
        font-size: 17px;
        color: #fff;
        padding: 1px 26px;
        background: #ff9c23;
        border: 0;
        border-radius: 4px;
        -webkit-transition: background 120ms ease-in;
        transition: background 120ms ease-in;
        cursor: pointer;
        display: inline-block;
    }

    .footer-step .footer-step-btn.is-ghost {
        background: transparent;
        color: #ff9c23;
        box-shadow: inset 0 0 0 2px #ff9c23;
    }

    .footer-step .footer-step-btn:hover {
        background: #ffa73d;
        color: #fff;
    }

    .footer-step .footer-step-btn:active {
        background: #ff910a;
    }

    .footer-step .footer-step-btn.is-show {
        display: inline-block;
    }

    .footer-step .footer-step-btn.is-hide {
        display: none;
    }

    .footer-step .footer-step-btn.is-slidedown {
        -webkit-animation: slideDown 120ms forwards;
        animation: slideDown 120ms forwards;
    }

    .footer-step .footer-step-btn.is-slideup {
        -webkit-animation: slideUp 120ms forwards;
        animation: slideUp 120ms forwards;
    }

    @-webkit-keyframes scale0to1 {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
        }

        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
        }
    }

    @keyframes scale0to1 {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
        }

        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
        }
    }

    @-webkit-keyframes slideDown {
        0% {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }

        100% {
            -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
            opacity: 0;
        }
    }

    @keyframes slideDown {
        0% {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }

        100% {
            -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
            opacity: 0;
        }
    }

    @-webkit-keyframes slideUp {
        0% {
            -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
            opacity: 0;
        }

        100% {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }
    }

    @keyframes slideUp {
        0% {
            -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
            opacity: 0;
        }

        100% {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }
    }


    @-webkit-keyframes scale0to1 {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
        }

        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
        }
    }

    @keyframes scale0to1 {
        0% {
            -webkit-transform: scale(0);
            transform: scale(0);
        }

        100% {
            -webkit-transform: scale(1);
            transform: scale(1);
        }
    }

    @-webkit-keyframes slideDown {
        0% {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }

        100% {
            -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
            opacity: 0;
        }
    }

    @keyframes slideDown {
        0% {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }

        100% {
            -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
            opacity: 0;
        }
    }

    @-webkit-keyframes slideUp {
        0% {
            -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
            opacity: 0;
        }

        100% {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }
    }

    @keyframes slideUp {
        0% {
            -webkit-transform: translate3d(0, 100%, 0);
            transform: translate3d(0, 100%, 0);
            opacity: 0;
        }

        100% {
            -webkit-transform: translate3d(0, 0, 0);
            transform: translate3d(0, 0, 0);
            opacity: 1;
        }
    }



    /*section2*/
    .border_content {
        border: 1px solid #0000001a;
        border-radius: 10px;
        padding: 21px;
        background: #ffff;
    }

    .card_content-title--info .subtitle {
        margin-left: 0px;
        font-size: 16px;
        color: #8a8a8a;
        font-weight: 600;
    }

    .card_content-title--info span {
        color: #8a8a8a;
        font-size: 16px;
        font-weight: inherit;
        margin-left: 0;
    }

    .title-order {
        font-size: 12px;
        text-transform: uppercase;
        color: #6b6b6b;
        font-weight: bolder;
        letter-spacing: 1px;
        margin: 0;
        margin-bottom: 21px;
        margin-left: 10px;
    }

    @media (max-width: 767px) {
        .process-content .process-active-bar {

            left: calc(40% + 20px);
        }

        .process-list {

            padding: 0;
        }

        .process-content:before {
            right: calc(-115% + 126px);
            margin-left: -36px;

        }

        .process-item.is-active .process-content .process-active-bar {

            right: calc(-60% + 21px);
        }
    }

    @media (min-width: 768px) and (max-width: 991px) {

        .process-content:before {
            right: calc(-5% + 130px);
            margin-left: -115px;
        }
    }
</style>

<body>

    <div id="tracking-dev">

        <section class="container mt-5">
            <div class="card-info">
                <div class="content-input">
                    <input type="text" placeholder="Csx567" id="tracking">
                    <button class="btn_enviar" onclick="search()">Buscar</button>
                </div>
                @if(isset($shipping))
                <div class="row">
                    <div class="col-md-6">
                        <div class="card_content">
                            <div class="card_content-title">
                                <p><i class="fa fa-align-left"></i> Tracking</p>
                                <span>{{ $shipping->tracking }}</span>
                            </div>

                            <div class="card_content-body">
                                <div class="card_content-title">
                                    <p><i class="fa fa-map-marker" aria-hidden="true"></i>
                                        Desde</p>
                                    <ul>
                                        <li>LyCCouurier </li>
                                        <li>8301 NW 66th St, Miami, FL 33166, EE. UU.</li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card_content">
                            <div class="card_content-title">
                                <p><i class="fa fa-calendar-o" aria-hidden="true"></i>
                                    Fecha de creación</p>
                                <p class="subtitle">{{ $shipping->created_at->format('d-m-Y') }}</p>
                            </div>

                            <div class="card_content-body">
                                <div class="card_content-title">
                                    <p>Hasta</p>
                                    <ul class="p-0">
                                        <li>{{ $shipping->recipient->name }}</li>
                                        <li>{{ $shipping->recipient->email }}</li>
                                        <li>{{ $shipping->recipient->phone }}</li>
                                        <li>{{ $shipping->recipient->address }}</li>
                                    </ul>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
                <!-----step--------->
                <div class="nl-process">
                    <div class="complete-hint"><i class="fa fa-check"></i></div>
                    <ul class="process-list">

                        <li class="process-item  is-current">
                            <!---is-active--->
                            <div class="process-content">
                                <div class="process-active-bar"></div>
                                <div class="circle">
                                    <span></span>
                                </div>
                                <div class="des">
                                    <span>En Miami</span>
                                </div>
                            </div>
                        </li>
                        <li class="process-item ">
                            <div class="process-content">
                                <div class="process-active-bar"></div>
                                <div class="circle">
                                    <span></span>
                                </div>
                                <div class="des">
                                    <span>Camino a Lima</span>
                                </div>
                            </div>
                        </li>
                        <li class="process-item ">
                            <div class="process-content">
                                <div class="process-active-bar"></div>
                                <div class="circle">
                                    <span></span>
                                </div>
                                <div class="des">
                                    <span>Lima (en transito)</span>
                                </div>
                            </div>
                        </li>
                        <li class="process-item ">
                            <div class="process-content">
                                <div class="process-active-bar"></div>
                                <div class="circle">
                                    <span></span>
                                </div>
                                <div class="des">
                                    <span>Delivery</span>
                                </div>
                            </div>
                        </li>
                        <li class="process-item ">
                            <div class="process-content">
                                <div class="process-active-bar"></div>
                                <div class="circle">
                                    <span></span>
                                </div>
                                <div class="des">
                                    <span>Entregado</span>
                                </div>
                            </div>
                        </li>

                    </ul>
                </div>
                <div class="footer-step">
                    <button class="footer-step-btn  prev is-show" style="visibility: hidden;">Prev</button>
                    <button class="footer-step-btn next is-show" style="visibility: hidden;">Next</button>
                    {{--<button class="footer-step-btn finished is-hide">Complete</button>--}}
                </div>
                @endif
            </div>
        </section>

        @if(isset($shipping))
        <section class="container mt-5 mb-5">
            <p class="title-order">Información adicional</p>
            <div class="border_content">

                <div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card_content-title card_content-title--info">
                                <p>Numero de piezas</p>
                                <p class="subtitle">{{ $shipping->pieces }}</p>
                            </div>
                            <div class="card_content-title card_content-title--info mt-5">
                                <p>Largo</p>
                                <p class="subtitle">{{ $shipping->length }}cm</p>
                            </div>
                            <div class="card_content-title card_content-title--info mt-5">
                                <p>Tipo de empaque</p>
                                <p class="subtitle">{{ $shipping->box->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card_content-title card_content-title--info">
                                <p>Alto</p>
                                <p class="subtitle">{{ $shipping->height }}cm</p>
                            </div>
                            <div class="card_content-title card_content-title--info mt-5">
                                <p>Ancho</p>
                                <p class="subtitle">{{ $shipping->width }}cm</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card_content-title card_content-title--info">
                                <p>Descripción</p>
                                <span>{{ $shipping->description }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

    </div>



    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>

        function search(){

        let tracking = $("#tracking").val()
        window.location.href="{{ url('/') }}"+"/tracking?tracking="+tracking

        }

    </script>

    @if(isset($shipping))
    <script>

        $(document).ready(function(){

            var shippingIndex = "{{ $shipping->shipping_status_id }}"


            if(shippingIndex > 1){
                var y = 0;
                var interval = setInterval(() => {
                    
                    console.log("index", parseInt(shippingIndex))
                    if(y == shippingIndex - 2){
                        clearInterval(interval)
                    }
                    $(".next").click()

                    y++;
                }, 500);
            }

        })

        $(document).on('click', '.next', function () {
            StepToNext();

        });
        $(document).on('click', '.prev', function () {
            StepToPrev();
        });
        /*$(document).on('click', '.finished', function () {
            StepToInitial();
        });*/

        function StepToNext() {
            if (($('.process-item.is-current').next('.process-item')).length) {
                $('.process-item.is-current').addClass("is-changing");


                $('.process-item.is-current.is-changing').removeClass('is-current').addClass('is-active');

                setTimeout(function () {
                    $('.process-item.is-changing').next('.process-item').addClass("is-current");
                    $('.process-item.is-current').prev('.process-item.is-changing').removeClass('is-changing');
                }, 250)


            } else {
                var itemCount;
                itemCount = $('.process-item').length
                console.log(itemCount);
                $('.process-item.is-current').addClass('is-active').removeClass('is-current');
                $('.process-item').addClass('all-complete');

                $('.next').addClass('is-slidedown').removeClass('is-slideup');
                $('.prev').addClass('is-slidedown').removeClass('is-slideup');


                setTimeout(function () {
                    $('.next').addClass('is-hide').removeClass('is-show');
                    $('.prev').addClass('is-hide').removeClass('is-show');
                    $('.finished').addClass('is-show').removeClass('is-hide');
                    $('.finished').addClass('is-slideup').removeClass('is-slidedown');
                    $('.complete-hint').addClass('is-show').removeClass('is-hide');
                }, 120)

                setTimeout(function () {
                    $('.next').removeClass('is-slidedown').removeClass('is-slideup');
                    $('.prev').removeClass('is-slidedown').removeClass('is-slideup');

                    $('.finished').removeClass('is-slidedown').removeClass('is-slideup');

                }, 240);

            }
            $('.star .radiance').addClass('is-active');
        }

        /*function StepToPrev() {

            if (($('.process-item.is-current').prev('.process-item')).length) {

                $('.process-item.is-current').prev('.process-item').addClass("is-changing")

                $('.process-item.is-current').removeClass("is-current");

                $('.process-item.is-changing').addClass('is-current').removeClass('is-active').removeClass(
                    'is-changing');
            } else {
                return;
            }

        }*/

        /*function StepToInitial() {

            $('.process-item').removeClass("is-current").removeClass("is-active").removeClass("all-complete");
            $('.process-item:first-child').addClass("is-current");

            $('.complete-hint').addClass('is-hide').removeClass('is-show');

            $('.finished').removeClass('is-slideup').addClass('is-slidedown');


            setTimeout(function () {
                $('.next').addClass('is-show').removeClass('is-hide');
                $('.prev').addClass('is-show').removeClass('is-hide');

                $('.next').removeClass('is-slidedown').addClass('is-slideup');
                $('.prev').removeClass('is-slidedown').addClass('is-slideup');

                $('.finished').addClass('is-hide').removeClass('is-show');

            }, 120)

            setTimeout(function () {
                $('.next').removeClass('is-slidedown').removeClass('is-slideup');
                $('.prev').removeClass('is-slidedown').removeClass('is-slideup');

                $('.finished').removeClass('is-slidedown').removeClass('is-slideup');
            }, 240);

        }*/
    </script>
    @endif
    <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>-->
</body>

</html>