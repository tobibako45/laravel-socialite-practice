@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2>編集</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="/blogs/{{ $blog->id }}" method="post">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="put">
                    <div class="mb-2">
                        <label>Title</label><br>
                        <input type="text" name="title" class="form-control" value="{{ $blog->title }}"/>
                    </div>
                    <div class="mb-2">
                        <label>Body</label><br>
                        <textarea name="content" class="form-control">
                            {{ $blog->content }}
                        </textarea>
                    </div>
                    <div>
                        <input type="submit" class="btn btn-primary mb-5" value="Create"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
