@php
$pageTitle = 'Privacy Policy';
@endphp
@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">Privacy Policy</h4>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xl-12 order-xl-2 col-lg-12 order-lg-2 col-md-12 order-md-1 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <h6 class="title">Privacy Policy</h6>
                </div>
                <div class="ui-block-content">
                    @include('errors.form_errors')
                    <!-- Change Password Form -->
                    <form action="{{url('privacy/save')}}" method="post">
                        {{csrf_field()}}
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group is-empty">
                                <label for="title" class="control-label">Title</label>
                                <input type="text" name="title" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" value="{{ old('title') ?: (!empty($privacy) ? $privacy->title : '') }}">
                                @if ($errors->has('title'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('title') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group is-empty">
                                <label for="content" class="control-label">Content</label>
                                <textarea name="content" class="content form-control{{ $errors->has('content') ? ' is-invalid' : '' }}" rows="5">{{ old('content') ?: (!empty($privacy) ? $privacy->content : '') }}</textarea>
                                @if ($errors->has('content'))
                                <span class="invalid-feedback">
                                    <strong>{{ $errors->first('content') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <input type="hidden" name="id" value="">
                            <button class="btn btn-orange btn-sm" type="submit">Save</button>
                        </div>

                    </div>
                </form>

                <!-- ... end Change Password Form -->
            </div>
        </div>
    </div>
</div>
</div>
@endsection
