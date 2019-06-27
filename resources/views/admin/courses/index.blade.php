@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{!! a_link($cat->name,route('courseCats.index',$cat->type),'cancle_target') !!}的课程列表</h1>
        {{-- <a class="btn btn-primary " href="javascripts:;"><i class="glyphicon glyphicon-download"></i>回收站</a> --}}
        <h1 class="pull-right">
           <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('courses.create',$cat->id) !!}">添加</a>
        </h1>
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.courses.table')
            </div>
        </div>
        <div class="text-center">
        
        </div>
    </div>
@endsection

@include('admin.courses.js')

