@php
$pageTitle = ucfirst($type).' Photos';
@endphp
@extends('layouts.user')
<style type="text/css">
.padding-0 {
    padding: 0px !important;
}
.border-0 {
    border: 0px !important;
}
.page-link:hover {
    background-color: #e8a93f !important;
}
</style>
@section('content')

<!-- start search area section -->
<div class="container mt20">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="ui-block responsive-flex">
                <div class="ui-block-title">
                    <div class="h6 title">{{ucfirst($type)}} Photos</div>
                    <div class="pull-right">
                        <a href="{{url('user/photos?type=profile')}}" class="btn btn-orange">Profile</a>
                        <a href="{{url('user/photos?type=cover')}}" class="btn btn-orange">Cover</a>
                        <a href="{{url('user/photos?type=post')}}" class="btn btn-orange">Post</a>
                        <a href="{{url('user/photos?type=location')}}" class="btn btn-orange">Location</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end search area section -->

<!-- start search results area section -->
<div class="container">
    <div class="row photo-blocks">        
        @if(count($photos) > 0)
            @foreach($photos as $photo)
                <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-6">
                    <div class="ui-block">
                        <!-- Friend Item -->
                        <div class="friend-item">
                            <div class="friend-header-thumb photo-item padding-0" data-src="{{$photo->url}}">
                                <img src="{{ $photo->url }}" style="width: 245px;height: 260px;">
                            </div>
                        </div>  
                        <!-- ... end Friend Item -->
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-xl-12">
                <p class="text-center">No {{$type}} photos found</p>
            </div>
        @endif
    </div>
    @if(count($photos) > 0)
        <p>{{ $photos->appends(Request::except('page'))->links() }}</p>
    @endif
</div>
<!-- end search results area section -->
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        @if(count($photos) > 0)
            $('.photo-blocks').lightGallery({
                selector : '.photo-item'
            });
        @endif
    });
</script>
@endsection