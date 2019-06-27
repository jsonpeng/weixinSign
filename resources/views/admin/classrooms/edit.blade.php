@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            编辑
        </h1>
   </section>
   <div class="content">
       @include('adminlte-templates::common.errors')
       <div class="box box-primary">
           <div class="box-body">
               <div class="row">
                   {!! Form::model($classroom, ['route' => ['classrooms.update', $classroom->id], 'method' => 'patch']) !!}

                        @include('admin.classrooms.fields')

                   {!! Form::close() !!}
               </div>
           </div>
       </div>
   </div>
@endsection