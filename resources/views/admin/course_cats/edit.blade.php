@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            编辑课程分类
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($courseCat, ['route' => ['courseCats.update', $courseCat->id,$type], 'method' => 'patch']) !!}

                        @include('admin.course_cats.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
   @include('admin.partials.imagemodel')
@endsection

@include('admin.course_cats.js')