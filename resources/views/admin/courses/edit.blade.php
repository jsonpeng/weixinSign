@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            编辑课程
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($course, ['route' => ['courses.update',$cat->id,$course->id], 'method' => 'patch']) !!}

                        @include('admin.courses.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection

@include('admin.courses.js')