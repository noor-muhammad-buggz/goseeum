<!DOCTYPE html>
<html lang="en">
<head>

    <title>{{$pageTitle}} - {{ config('app.name', 'Goseeum') }}</title>

    <!-- Required meta tags always come first -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('Bootstrap/dist/css/bootstrap-reboot.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Bootstrap/dist/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('Bootstrap/dist/css/bootstrap-grid.css') }}">

    <!-- Main Styles CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/main.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/fonts.min.css') }}">

    <!-- Main Font -->
    <script src="{{ asset('js/webfontloader.min.js') }}"></script>
    <script>
        WebFont.load({
            google: {
                families: ['Roboto:300,400,500,700:latin']
            }
        });
    </script>
    <style>
.sidebar {
  height: 100%;
  width: 0;
  position: fixed;
  z-index: 1;
  top: 0;
  left: 0;
  background-color: #111;
  overflow-x: hidden;
  transition: 0.5s;
  padding-top: 60px;
}

.sidebar a {
  padding: 8px 8px 8px 32px;
  text-decoration: none;
  font-size: 25px;
  color: #818181;
  display: block;
  transition: 0.3s;
}

.sidebar a:hover {
  color: #f1f1f1;
}

.sidebar .closebtn {
  position: absolute;
  top: 0;
  right: 25px;
  font-size: 36px;
  margin-left: 50px;
}

.openbtn {
  font-size: 20px;
  cursor: pointer;
  background-color: #111;
  color: white;
  padding: 10px 15px;
  border: none;
}

.openbtn:hover {
  background-color: #444;
}

#main {
  transition: margin-left .5s;
  padding: 16px;
}

@media screen and (max-height: 450px) {
  .sidebar {padding-top: 15px;}
  .sidebar a {font-size: 18px;}
}
@media  screen and (max-width: 768px){
    #mySidebars{
        display: block !important;
    }
    .openbtn{
        display: block !important;
    }
    .main-header-has-header-standard{
        display: none;
    }
    button.navbar-toggle {
    background: transparent;
    border: 0;
    margin-top: 13px;
    float: right;
}
a.logo.navbar-brand.pull-left {
    float: left;
}
.categories-section .container-fluid .full-images img{
    height: auto !important;
}
.categories-section .container-fluid .half-images, .categories-section .container-fluid .full-images{
    height: auto;
}
.col-xl-4.col-lg-4.col-md-6.col-sm-12.col-xs-12 {
    margin-bottom: 10px;
}
.post-content.no-padding .author-thumb {
    vertical-align: bottom;
    margin-bottom: 30px;
}
.post-content.no-padding {
    margin-bottom: 30px;
}
.col-xl-7.col-lg-7.m-auto.col-md-12.col-sm-12.col-xs-12{
    margin-bottom: 30px !important;
}
.sub-footer-copyright{
    margin-top: 0px;
}
.post-additional-info{
    display: inherit !important;
}
}
</style>

</head>
<body class="body-bg-white">
    <nav class="navbar" >
      <div class="container-fluid">
        <div class="navbar-header">
            <a href="{{url('/')}}" class="logo navbar-brand pull-left">
                    <div class="img-wrap">
                        <img src="img/logo.png" alt="Gosseum"  width="170">
                    </div>
                    <div class="title-block">
                        <h6 class="logo-title"></h6>
                        <div class="sub-title"></div>
                    </div>
                </a>
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>                        
          </button>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
          <ul class="nav navbar-nav">
            <li class="active"><a href="#home" class="nav-link">Home</a></li>
            <li class="nav-item"><a href="#howitworks" class="nav-link">How It Works</a></li>
            <li class="nav-item"><a href="#features" class="nav-link">Features</a></li>
            <li class="nav-item"><a href="#blog" class="nav-link">Blog</a></li>
            <li class="nav-item"><a href="#contact" class="nav-link">Contact</a></li>
            <li class="nav-item"><a href="{{url('login')}}" class="nav-link">Login</a></li>
            <li class="nav-item"><a href="{{url('register')}}" class="nav-link orange-rounded">Sign up</a></li>
          </ul>
        </div>
      </div>
    </nav>
<div class="main-header-fullwidth main-header-has-header-standard">

    <!-- Header Standard Landing  -->
    <div class="header--standard home-header header--standard-landing" id="header--standard">
        <div class="container">
            

            <div class="header--standard-wrap">
                <a href="{{url('/')}}" class="logo">
                    <div class="img-wrap">
                        <img src="img/logo.png" alt="Gosseum">
                    </div>
                    <div class="title-block">
                        <h6 class="logo-title"></h6>
                        <div class="sub-title"></div>
                    </div>
                </a>
    
                <div class="nav nav-pills nav1 header-menu expanded-menu">
                    <div class="mCustomScrollbar">
                        <ul class="main-menu">
                            <li class="nav-item">
                                <a href="#home" class="nav-link">Home</a>
                            </li>
                            <li class="nav-item">
                                <a href="#howitworks" class="nav-link">How It Works</a>
                            </li>
                            <li class="nav-item">
                                <a href="#features" class="nav-link">Features</a>
                            </li>
                            <li class="nav-item">
                                <a href="#blog" class="nav-link">Blog</a>
                            </li>
                            <li class="nav-item">
                                <a href="#contact" class="nav-link">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('login')}}" class="nav-link">Login</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{url('register')}}" class="nav-link orange-rounded">Sign up</a>
                            </li>
                        </ul>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    <!-- ... end Header Standard Landing  -->
    {{-- <div class="header-spacer--standard"></div> --}}
</div>

@yield('content')

<!-- Footer Full Width -->
<div class="footer footer-full-width" id="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <!-- Widget List -->
                <div class="widget w-list">
                    <h5 class="title">Product</h5>
                    <ul>
                        <li>
                            <a href="#">About us</a>
                        </li>
                        <li>
                            <a href="#">Careers</a>
                        </li>
                        <li>
                            <a href="#">Press</a>
                        </li>
                    </ul>
                </div>
                
                <!-- ... end Widget List -->
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div class="widget w-list">
                    <h5 class="title">Company</h5>
                    <ul>
                        <li>
                            <a href="#">Overview</a>
                        </li>
                        <li>
                            <a href="#">Features</a>
                        </li>
                        <li>
                            <a href="#">Pricing</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">   
                <div class="widget w-list">
                    <h5 class="title">Resource</h5>
                    <ul>
                        <li>
                            <a href="#">News</a>
                        </li>
                        <li>
                            <a href="#">Dcumentation</a>
                        </li>
                        <li>
                            <a href="#">Faq</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12"> 
                <div class="widget w-list">
                    <h5 class="title">Contact</h5>
                    <ul>
                        <li>
                            <a href="#">Email us</a>
                        </li>
                        <li>
                            <a href="#">Tweet Faulkner</a>
                        </li>
                        <li>
                            <a href="#">Visit the office</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <!-- Widget About -->
                <div class="widget w-about">
                    <a href="{{url('/')}}" class="logo">
                        <div class="img-wrap">
                            <img src="img/logo.png" alt="Goseeum" width="200">
                        </div>
                        <div class="title-block">
                            <h6 class="logo-title"></h6>
                            <div class="sub-title"></div>
                        </div>
                    </a>
                </div>
                <!-- ... end Widget About -->
            </div>            
        </div>
    </div>

</div>
<div class="clearfix">
<!-- SUB Footer -->
<div class="sub-footer-copyright">
    <span>
        Copyright <a href="{{url('/')}}">Goseeum</a> All Rights Reserved 2018
    </span>
</div>
<!-- ... end SUB Footer -->
</div>
<!-- ... end Footer Full Width -->

<a class="back-to-top" href="#">
    <img src="{{ asset('svg-icons/back-to-top.svg') }}" alt="arrow" class="back-icon">
</a>

<!-- JS Scripts -->
<script src="{{ asset('js/jquery-3.2.1.js') }}"></script>
<script src="{{ asset('js/jquery.appear.js') }}"></script>
<script src="{{ asset('js/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('js/jquery.matchHeight.js') }}"></script>
<script src="{{ asset('js/svgxuse.js') }}"></script>
<script src="{{ asset('js/imagesloaded.pkgd.js') }}"></script>
<script src="{{ asset('js/Headroom.js') }}"></script>
<script src="{{ asset('js/velocity.js') }}"></script>
<script src="{{ asset('js/ScrollMagic.js') }}"></script>
<script src="{{ asset('js/jquery.waypoints.js') }}"></script>
<script src="{{ asset('js/jquery.countTo.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/material.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-select.js') }}"></script>
<script src="{{ asset('js/smooth-scroll.js') }}"></script>
<script src="{{ asset('js/selectize.js') }}"></script>
<script src="{{ asset('js/swiper.jquery.js') }}"></script>
<script src="{{ asset('js/moment.js') }}"></script>
<script src="{{ asset('js/daterangepicker.js') }}"></script>
<script src="{{ asset('js/simplecalendar.js') }}"></script>
<script src="{{ asset('js/fullcalendar.js') }}"></script>
<script src="{{ asset('js/isotope.pkgd.js') }}"></script>
<script src="{{ asset('js/ajax-pagination.js') }}"></script>
<script src="{{ asset('js/Chart.js') }}"></script>
<script src="{{ asset('js/chartjs-plugin-deferred.js') }}"></script>
<script src="{{ asset('js/circle-progress.js') }}"></script>
<script src="{{ asset('js/loader.js') }}"></script>
<script src="{{ asset('js/run-chart.js') }}"></script>
<script src="{{ asset('js/jquery.magnific-popup.js') }}"></script>
<script src="{{ asset('js/jquery.gifplayer.js') }}"></script>
<script src="{{ asset('js/mediaelement-and-player.js') }}"></script>
<script src="{{ asset('js/mediaelement-playlist-plugin.min.js') }}"></script>

<script src="{{ asset('js/base-init.js') }}"></script>
<script defer src="{{ asset('fonts/fontawesome-all.js') }}"></script>

<script src="{{ asset('Bootstrap/dist/js/bootstrap.bundle.js') }}"></script>

<script type="text/javascript">
    //Scroll to top.
    jQuery('ul.main-menu li.nav-item a').on('click', function () {
        var scroll = 0;
        var elem = $(this).attr('href');
        if(elem !== '#home') {
            var position = $(elem).position();
            scroll = position.top;
        }

        $('html,body').animate({
            scrollTop: scroll
        }, 1200);
        return false;
    });   
</script>

</body>
</html>