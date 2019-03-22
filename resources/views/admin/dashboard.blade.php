@php
$pageTitle = 'Dashboard';
@endphp
@extends('layouts.admin')
@section('content')
<div class="container">
    <div class="row">
        <div class="ui-block-title">
            <h4 class="title">Dashboard</h4>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">

        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="ui-block">
                <div class="ui-block-content">
                    <div class="monthly-indicator">
                        <a href="{{url('cities')}}" class="btn btn-control bg-orange negative">
                            <svg class="olymp-stats-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-icon') }}"></use></svg>
                        </a>
                        <div class="monthly-count">
                            Cities
                            <span class="indicator orange"> {{$cities}}</span>
                            <span class="period">Total</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="ui-block">
                <div class="ui-block-content">
                    <div class="monthly-indicator">
                        <a href="{{url('users')}}" class="btn btn-control bg-orange negative">
                            <svg class="olymp-happy-face-icon"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-happy-face-icon') }}"></use></svg>
                        </a>
                        <div class="monthly-count">
                            Users
                            <span class="indicator orange"> {{$users}}</span>
                            <span class="period">Total</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12">
            <div class="ui-block">
                <div class="ui-block-content">
                    <div class="monthly-indicator">
                        <a href="{{url('locations')}}" class="btn btn-control bg-orange negative">
                            <svg class="olymp-stats-arrow"><use xlink:href="{{ asset('svg-icons/sprites/icons.svg#olymp-stats-arrow') }}"></use></svg>
                        </a>
                        <div class="monthly-count">
                            Locations
                            <span class="indicator orange"> {{$locations}}</span>
                            <span class="period">Total</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- <div class="container">
    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-xs-12">
            <div class="ui-block">
                <div class="ui-block-title">
                    <div class="h6 title">Single Two Bar Graphic</div>
                    <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg></a>
                </div>

                <div class="ui-block-content">

                    <div class="points">

                        <span>
                            <span class="statistics-point bg-primary"></span>
                            Statistic 01
                        </span>

                        <span>
                            <span class="statistics-point bg-yellow"></span>
                            Statistic 02
                        </span>

                    </div>

                    <div class="chart-js chart-js-two-bars">
                        <canvas id="two-bars-chart" width="400" height="300"></canvas>
                    </div>

                </div>

            </div>
        </div>
        <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-xs-12">
            <div class="ui-block responsive-flex">
                <div class="ui-block-title">
                    <div class="h6 title">Lines Graphic</div>

                    <select class="selectpicker form-control without-border">
                        <option value="LY">LAST YEAR (2016)</option>
                        <option value="2">CURRENT YEAR (2017)</option>
                    </select>

                    <div class="points align-right">

                        <span>
                            <span class="statistics-point bg-blue"></span>
                            FAVOURITES
                        </span>

                        <span>
                            <span class="statistics-point bg-breez"></span>
                            VISITORS
                        </span>

                    </div>

                    <a href="#" class="more"><svg class="olymp-three-dots-icon"><use xlink:href="svg-icons/sprites/icons.svg#olymp-three-dots-icon"></use></svg></a>

                </div>

                <div class="ui-block-content">
                    <div class="chart-js chart-js-line-stacked">
                        <canvas id="line-stacked-chart" width="730" height="300"></canvas>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div> -->

@endsection
