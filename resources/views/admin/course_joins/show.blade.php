@extends('layouts.app')

@section('content')
    <section class="content-header">
        <h1>
            Course Join
        </h1>
    </section>
    <div class="content">
        <div class="box box-primary">
            <div class="box-body">
                <div class="row" style="padding-left: 20px">
                    @include('course_joins.show_fields')
                    <a href="{!! route('courseJoins.index') !!}" class="btn btn-default">Back</a>
                </div>
            </div>
        </div>
    </div>
@endsection
