@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            为{!! a_link($cat->name,route('courseCats.index',$cat->type),'cancle_target') !!}添加课程
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => ['courses.store',$cat->id]]) !!}

                        @include('admin.courses.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@include('admin.courses.js')
