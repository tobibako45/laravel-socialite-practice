@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="mb-3">投稿一覧</h2>

                <a href="/blogs/create" class="btn btn-outline-primary mb-2">新規登録</a>

                <div>
                    <table>
                        <thead>
                        <tr>
                            <th>title</th>
                            <th>body</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($blogs as $blog)
                            <tr>
                                <td>
                                    {{--                                    <a href="/blogs/{{ $blog->id }}">{{ $blog->title }}</a>--}}
                                    <a href="/blogs/{{ $blog->id }}/edit">{{ $blog->title }}</a>
                                </td>
                                <td>
                                    {!! nl2br(e($blog->content)) !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
