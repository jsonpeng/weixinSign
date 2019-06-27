@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1 class="pull-left">{!! $type !!}分类</h1>
        @if($type == '课程班')
            <h1 class="pull-right">
               <a class="btn btn-primary pull-right" style="margin-top: -10px;margin-bottom: 5px" href="{!! route('courseCats.create',$type) !!}">添加</a>
            </h1>
        @endif
    </section>
    <div class="content">
        <div class="clearfix"></div>

        @include('flash::message')

        <div class="clearfix"></div>
        <div class="box box-primary">
            <div class="box-body">
                    @include('admin.course_cats.table')
            </div>
        </div>
        <div class="text-center">
            {!! $courseCats->links() !!}
        </div>
    </div>
@endsection

@include('admin.course_cats.js')

