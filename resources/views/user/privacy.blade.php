@php
$pageTitle = $pageTitle;
@endphp
@extends('layouts.user')
<style type="text/css">
.padding-0 {
    padding: 0px !important;
}
.border-0 {
    border: 0px !important;
}
</style>
@section('content')
<!-- start search results area section -->
<div class="container mt20">
    <div class="row">        
        @if(!empty($content))
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="ui-block">
                    <div class="ui-block-title">
                        <h6 class="title">{{$content->title}}</h6>
                    </div>
                    <div class="ui-block-content">
                        <p class="">{{$content->content}}</p>
                    </div>
                </div>
            </div>
        @else
            <div class="col-xl-12">
                <p class="text-center"></p>
            </div>
        @endif
    </div>
</div>
<!-- end search results area section -->
@endsection
@section('scripts')
<script type="text/javascript">
    $(document).ready(function(){
    });
</script>
@endsection