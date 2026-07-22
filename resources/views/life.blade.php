@extends('layouts.master')
@section('page_title', 'Cuộc sống | ' . data_get($infor, 'name', 'Personal Brand'))
@section('og_title', 'Cuộc sống | ' . data_get($infor, 'name', 'Personal Brand'))
@section('content')
        <!-- Project Section Start -->
        <section class="project-section tp-project-5-2-area fix section-padding">
            <div class="container">
                <div class="section-title tp-project-5-2-title">
                    <h6>
                        my life
                    </h6>
                    <h2 class="">tuanh</h2>
                </div>
                <div class="design-choose-item-wrap">
                    <div class="row">
                        @foreach ($images as $key => $row)
                        <div class="col-xl-6 col-lg-6 col-md-6">
                            <div class="project-box-items design-choose-item-{{++$key}}">
                                <div class="thumb">
                                    <img src="{{$row->image}}" alt="img">
                                    <div class="content">
                                        <h3>{{$row->title}}</h3>
                                    </div>
                               
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            

            </div>
        </section>
@endsection