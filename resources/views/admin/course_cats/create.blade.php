@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            添加{!! $type !!}分类
        </h1>
    </section>
    <div class="content">
        @include('adminlte-templates::common.errors')
        <div class="box box-primary">

            <div class="box-body">
                <div class="row">
                    {!! Form::open(['route' => ['courseCats.store',$type]]) !!}

                        @include('admin.course_cats.fields')

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    @include('admin.partials.imagemodel')
@endsection

@include('admin.course_cats.js')
