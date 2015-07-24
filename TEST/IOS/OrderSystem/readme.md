http://kodeinfo.com/post/secure-login-system-phplaravel-1
http://blog.fagai.net/2014/05/27/laravel4-sentry-auth-tutorial/
Database
Intro

今回はLaravelでDBをいじって簡単な掲示板を作る。
Lessonで使われるDBはMySQLである。
Step 0. 事前準備

LaravelでDBを操作する前にDatabaseとTableを作っておこう。
Databaseを作る。
sql
CREATE DATABASE `mylaravel`;
Tableを作る。
sql
CREATE TABLE `mylaravel`.`posts` (
`id` int(11) unsigned NOT NULL AUTO_INCREMENT,
`title` varchar(30),
`body` text,
PRIMARY KEY (`id`)
);
Step 1. DB setting

設定はかなり簡単である。
Laravelのapp/config/database.phpを開いて修正する。
...
        'mysql' => array(
            'driver'    => 'mysql',
            'host'      => 'localhost',
            'database'  => 'mylaravel',
            'username'  => 'root',
            'password'  => '',
            'charset'   => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'prefix'    => '',
        ),
...
'database'には先作ったdatabaseである'mylaravel'をいれて、
username、passwordは自分のDB Serverの設定にあわせて登録する。
Step 2. 投稿機能の具現(INSERT)

まず、二つのRouteが必要である。
- 投稿Formを見せるRoute
- もらったデータを保存するRoute
では、routes.phpに次のRouteを追加しよう。
app/routes.php

// 投稿formを表示する
Route::get('posts/create', 'PostController@create');

// 実際にDBにデータを入れる
Route::post('posts', 'PostController@store');
では、二つのRouteが使っているPostControllerを作成する。
まずは、create()Methodを先に作る。
app/controllers/PostController.php

<?php

class PostController extends BaseController{
    function create(){
        return View::make('posts.create');
    }
}
見てわかるお湯に'posts.create'というViewを呼んでいる。
では、今から'posts.create'Viewも作る。
app/views/posts/create.php

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <form method="post" action="../posts">
            <label for="title">title</label><br>
            <input type="text" name="title"><br>
            <label for="body">body</label><br>
            <textarea name="body"></textarea><br>
            <button type="submit">submit</button>
        </form>
    </body>
</html>
やっと投稿Formが完成された。
まだSubmit buttonを押してもちゃんと動かないが、Formがちゃんとできているのかだけ確認して進もう。
http://localhost/mylaravel/posts/create.php
結果
lesson4-1.png
確認ができたら、またPostController.phpに戻ってstore()Methodを完成しよう。
app/controllers/PostController.php

<?php

class PostController extends BaseController{
    function create(){
        return View::make('posts.create');
    }
    function store(){
        DB::table('posts')->insert([
            'title'=>Input::get('title'),
            'body'=>Input::get('body')
        ]);
        return 'Successfully done!';
    }
}
DBにQueryを送るのはDBClassのMethodで簡単にできる。
そして、Input::get()はParameterとして入ったデータを扱うとき使うClass methodである。
これで、http://localhost/mylaravel/posts/create.php
が起動するようになった。
適当に内容を入れてSubmitしてみる。
lesson4-2.png
Step 3. 閲覧機能の具現(SELECT)

今回はすべて投稿と一つの投稿を閲覧する機能をいれる。
まず、Routeを追加する。
app/route.php

// 投稿formを表示する
Route::get('posts/create', 'PostController@create');

// 実際にDBにデータを入れる
Route::post('posts', 'PostController@store');

// すべてのPostを表示する
Route::get('posts', 'PostController@index');

// 一つのPostを表示する。
Route::get('posts/{postid}','PostController@show');
少し注目してほしいのは'posts/{postid}'という部分だ。
{postid}に何あの値が入った場合の処理で、{postid}の値をControllerから読み取ることもできる。
では、Controllerにもindex(),show()Methodを実装させる。
app/controllers/PostController.php

<?php

class PostController extends BaseController{
    function create(){
        return View::make('posts.create');
    }
    function store(){
        DB::table('posts')->insert([
            'title'=>Input::get('title'),
            'body'=>Input::get('body')
        ]);
        return 'Successfully done!';
    }
    function index(){
        $posts = DB::table('posts')->get();
        return View::make('posts.index')->with('posts', $posts);
    }
    function show($postid){
        $post = DB::table('posts')->where('id',$postid)->first();
        return View::make('posts.show')->with('post', $post);
    }
}
DBですべての目録を出すときはget()Methodで、
SQLのWHERE文が必要な場合は、先にwhere()Methodを使ってfirst()Methodを呼ぶ。
get()はすべての目録を配列で出すが、first()は一番目の項目を出す。
次はindex(),show()Methodに使われるViewを作る。
app/views/posts/index.php

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1>Posts list <a href="./posts/create">create a new post</a></h1>
        <ul>
            <?php foreach($posts as $post){ ?>
            <h2><a href="./posts/<?php echo $post->id?>">
                <?php echo $post->title ?></a></h2>
            <p><?php echo $post->body ?></p>
            <?php } ?>
        </ul>
    </body>
</html>
app/views/posts/show.php

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <h1><?php echo $post->title ?></h1>
        <p><?php echo $post->body ?></p>
        <a href="./">back to list</a>
    </body>
</html>
では、結果を確認する。
http://localhost/mylaravel/public/posts

lesson4-3.png
http://localhost/mylaravel/public/posts/1

lesson4-4.png
(追加) Redirecting

store()Methodが呼ばれたとき、最初はindex()Methodがなかったので、適当に文字列を返すようにしておいた。
このままじゃ、使うのが不便なので、store()が成功的に行われたらindex()にRedirectするようにしよう。
store()Methodが返す値を次のように変える。
app/controllers/PostController.php

...
    function store(){
        DB::table('posts')->insert([
            'title'=>Input::get('title'),
            'body'=>Input::get('body')
        ]);
        return Redirect::to('posts');
    }
...
これで、投稿ができたら自動的に投稿ListにRedirectするようになる。


Blade & Helper
今回はLaravelのBladeとHelper機能を使ってみる。
Lesson4の結果と続くので、
DBのsettingである。*Step 0*と*Step 1*を参考してSettingする。
意外のコードは次のリンクにあるので、確認していれること。
http://fluke8259.hatenablog.com/entry/2014/03/30/022046
Intro

まず、Bladeの説明からする。BladeはLaravelのTemplating systemである。
Bladeを使うことによって繰り返される部分を略して書くことができる。
そして、Helperもよく使われる部分をPHPの方で簡単に呼んで、開発がより楽にしてくれる。
今回のLessonはLesson4で使ったViewをBladeとHelperで書き直してみる。
個人的には生産性がすごくあがるので必ず知っておくべきだと思う。
Step 1. Blade Viewを作る。

ViewにBladeを使う方法はFileの名前と拡張名の間に.bladeを入れたらいい。
つまり、こうなる。

index.php => index.blade.php
では、Layoutから作ってみる。

毎回HTML pageを作るときいつもか<meta charset='utf-8'>などを書かなきゃならない。
こういうこともBladeを使ったら、１回だけ書いておくことでいつでも使えるようになる。
では、すべての基盤になるMaster layoutを作る。
app/views/layouts/master.blade.php

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>
            @yield('title')
        </title>
    </head>
    <body>
        @yield('body')
    </body>
</html>
@yield()はここに何かがくるという意味になる。
つまり、master.blade.phpを利用しているViewがこの部分を埋めるようになっている。
では、既存のViewにこのmaster.blade.phpを適用してみる。
まずはFile名から変えよう。

app/views/posts/create.php => create.blade.php

app/views/posts/index.php => index.blade.php

app/views/posts/show.php => show.blade.php
次は本格的に内容を修正する。
app/views/posts/create.blade.php

@extends('layouts.master')

@section('title')
Create a new post - mylaravel
@stop

@section('body')
<form method="post" action="../posts">
    <label for="title">title</label><br>
    <input type="text" name="title"><br>
    <label for="body">body</label><br>
    <textarea name="body"></textarea><br>
    <button type="submit">submit</button>
</form>
@stop
見ているようにかなりコードがすっきりなった。
@section()は先の@yield()で要請した部分である。
そして、@stopを書いてSectionがどこで終わるのかを決める。
続いてindex.blade.phpとshow.blade.phpもする。
app/views/posts/index.blade.php

@extends('layouts.master')

@section('title')
Posts list - mylaravel
@stop

@section('body')
<h1>Posts list <a href="./posts/create">create a new post</a></h1>
<ul>
    @foreach($posts as $post)
    <h2><a href="./posts/{{$post->id}}">
        {{$post->title}}</a></h2>
    <p>{{$post->body}}</p>
    @endforeach
</ul>
@stop
app/views/posts/show.blade.php

@extends('layouts.master')

@section('title')
{{$post->title}} - mylaravel
@stop

@section('body')
<h1>{{$post->title}}</h1>
<p>{{$post->body}}</p>
<a href="./">back to list</a>
@stop
PHPのforeachも@foreachの形で簡単に使うことができる。
そして、<?php echo ~?>でPHPからの値を呼んできたのも{{~}}で簡単に呼ぶことができるようになった。
次からのLessonもBladeで書くつもりなので、
無理して勉強する必要はない。自然にLessonをそって勉強したら自然に上手になると思う。
Step 2. Helperから助けを求める。

Bladeを使ってコードがだいぶすっきりなったが、もっと効率を挙げて見よう。
Laravelはよく使われるHTMLコードをPHPで簡単に書くことができる。
なので、私はBladeとHelperの組み合わせが最強だと思う。
愛の素晴らしさを知らないあなたがかわいそう...
BladeとHelperの素晴らしさを知らないあなたはかわいそう...
では、またapp/views/posts/create.blade.phpからいじってみる。
app/views/posts/create.blade.php

@extends('layouts.master')

@section('title')
Create a new post - mylaravel
@stop

@section('body')
{{Form::open(['url'=>'posts','method'=>'post'])}}
    {{Form::label('title', 'Title')}}<br>
    {{Form::text('title')}}<br>
    {{Form::label('body', 'Body')}}<br>
    {{Form::textarea('body')}}<br>
    {{Form::submit('Submit')}}
{{Form::close()}}
@stop
コードがFormのClass関数から作られている。
このFormは簡単なので微力があまり見えないけど、今も既に拡張された機能が入っている。
http://localhost/mylaravel/public/posts/createで結果を確認うると、
lesson5-2.png
見ているように_tokenというinputTagが追加されている。
これはCSRF攻撃(Hacking Technique)を防ぐために必要な部分である。
今は自分のLaravelをやり始めた段階なのでそれほど気にする必要がないが、
これ以外にもHelperを使った方がいいところがいろいろとでるので、
ぜひなれた方がいいと思う。
続いてindex.blade.phpとshow.blade.phpを修正する。
app/views/posts/index.blade.php

@extends('layouts.master')

@section('title')
Posts list - mylaravel
@stop

@section('body')
<h1>Posts list {{link_to('posts/create','create a new post')}}</h1>
<ul>
    @foreach($posts as $post)
    <h2>{{link_to('posts/'.$post->id,$post->title)}}
        </h2>
    <p>{{$post->body}}</p>
    @endforeach
</ul>
@stop
app/views/posts/show.blade.php

@extends('layouts.master')

@section('title')
{{$post->title}} - mylaravel
@stop

@section('body')
<h1>{{$post->title}}</h1>
<p>{{$post->body}}</p>
{{link_to('posts', 'Back to list')}}
@stop
今回は<a>TagをHelperで作った。
ここもまだそんなに目立つところはないけど、link_to_routeでRouteをまっすぐ呼んだり、
link_to_actionでControllerのMethodをまっすぐ呼ぶこともできる。
私たちが書いたコードにはRouteに名前を付けていないので、link_to_routeはまだ使えない。
Helperも次回のLessonからずっと使うので見慣れておこう。
でも、書き方を無理して覚える必要はない。
LaravelのAPI Documentで全部書かれているし、
私のLessonで勉強してたら自然になれると思う。
今回の結果はLesson4の結果と差がないのでのせない。

RESTful
今回はRESTfulについて学んでみる。
内容が前回と続くので、前のLessonを全部見る余裕がなかったらDBのSettingはLesson4の*Step 0*と*Step 1*に見ておいて、
http://fluke8259.hatenablog.com/entry/2014/03/30/045724
残りの部分はこのリンクを見ればいい。
Intro

RESTfulの説明はかなり難しいので、今回の講義で利用される部分だけを簡略に説明する。
WebでのRESTfulな構造はResource重心の構造であって、あるResourceに接近するURIは同じであるが、
CRUD(Create:作成, Read:読み取り, Update:編集, Delete:削除)などの動作はHTTP Request Methodで認識するという意味である。
つまり、各機能は次のようにまとまる。
Create => POST
Read => GET
Update => PUT
Delete => DELETE
実は今まで作ってきたものもRESTfulに作ってきたので、多分すぐ理解できると思う。
Step 0. php artisan routes

Laravelは開発がもっと簡単にできるようにArtisanというCommand-line interfaeを提供している。
自分のProject directoryを開くと、Artisanが見える。
Artisanの位置
ここで私はProject directoryの名前としてforstudyを使っているけど、
Lessonで作ってもらったProjectの位置はmylaravelなので注意すること。
では、Artisanだけ実行してみる。
php artisan
Artisanで利用かのである命令

実行するとArtisanのすべてのCommandが出てくる。
今回使う命令はroutesであるので、次の命令を実行してみよう。
bash
php artisan routes

Route list
見ているように、今まで作ってきたRouteが全部見える。
URIの方は親切にHTTP request methodも出てくれるし、各RouteがどのControllerのActionを使っているのかもでる。
では、早速本番に入る。
Step 1. Route::resource()

Laravelは楽しくREST Architectureが作れるようにRoute::resource()というMethodを提供している。
では、app/routes.phpを修正してみよう。
app/routes.php

<?php

Route::get('/', function()
{
    return View::make('hello');
});

Route::resource('posts', 'PostController');
既存のコードを全部削除して、Route::resource()コード一本だけで入れればいい。
では、再びphp artisan routesでRouteがどうなっているのかを確認する。

修正後のRoute list

四つのコードが一本でまとまっただけじゃなくて、CRUDに必要であるコードが既に出来上がっている。
では、今回は編集と削除機能をRESTfulな構造で構築してみる。
Step 2. Update(PUT)

もう既にRouteは構築されているのでまっすぐControllerの方を修正する。
app/controllers/PostController.php

...
    function edit($postid){
        $post = DB::table('posts')->where('id', $postid)->first();
        return View::make('posts.edit')->with('post', $post);
    }
    function update($postid){
        DB::table('posts')->where('id', $postid)
            ->update(['title'=>Input::get('title'), 'body'=>Input::get('body')]);
        return Redirect::route('posts.show',[$postid]);
    }
今回説明が必要だと思われる部分はRedirect::route()ぐらいだと思う。
前のstore()に使われているRedirect::to()とは違ってRoute名で呼ぶようになっている。
そして、二番目の引数にはParameterが入る。
では、Viewも早速作る。
app/views/posts/edit.blade.php

@extends('layouts.master')

@section('title')
Edit {{$post->title}} - mylaravel
@stop

@section('body')
{{Form::open(['route'=>['posts.update',$post->id],'method'=>'PUT'])}}
    {{Form::label('title', 'Title')}}<br>
    {{Form::text('title', $post->title)}}<br>
    {{Form::label('body', 'Body')}}<br>
    {{Form::textarea('body', $post->body)}}<br>
    {{Form::submit('Submit')}}
{{Form::close()}}
@stop
修正にはPUTMethodを使うだけ注意すればいい。
Form::text()とForm::textarea()の二番目の引数はInputの基本値である。
では、完成されたので開いてみよう。
http://localhost/mylaravel/public/posts/1/edit
アドレスを見たらわかるようにidが1であるPostを呼んでいるので、必ずPostを一個以上作っておく。
PUT methodを動使うのか
開いてみたら普通の編集画面に出るが、Codeを開いてみたらFormのMethodがまだPUTになっていない。
実はBrowserではまだGETかPOSTしか使えないからである。
この問題を押さえるため_methodというParameterを追加して送る。
幸いにHelperを使ったら自動的に作ってくれるので、こうなるんだぐらいのレベルで理解しておけばいいと思う。
Step 3. Delete(DELETE)

削除はForm pageがいらないので、ControllerにActionを追加することだけで終わる。
app/controllers/PostController.php

    function destroy($postid){
        DB::table('posts')->where('id', $postid)->delete();
        return Redirect::route('posts.index');
    }
だいたいずっと使われてきたコードなので、もう直観的にわかると思う。
ただし、Actionの名前がdestroy()であることに注意する。
ここまでやったらPostController.phpのコードは次のようになる。
app/controllers/PostController.php

<?php
class PostController extends BaseController{
    function create(){
        return View::make('posts.create');
    }
    function store(){
        DB::table('posts')->insert([
            'title'=>Input::get('title'),
            'body'=>Input::get('body')
        ]);
        return Redirect::to('posts');
    }
    function index(){
        $posts = DB::table('posts')->get();
        return View::make('posts.index')->with('posts', $posts);
    }
    function show($postid){
        $post = DB::table('posts')->where('id', $postid)->first();
        return View::make('posts.show')->with('post', $post);
    }
    function edit($postid){
        $post = DB::table('posts')->where('id', $postid)->first();
        return View::make('posts.edit')->with('post', $post);
    }
    function update($postid){
        DB::table('posts')->where('id', $postid)
            ->update(['title'=>Input::get('title'), 'body'=>Input::get('body')]);
        return Redirect::route('posts.show',[$postid]);
    }
    function destroy($postid){
        DB::table('posts')->where('id', $postid)->delete();
        return Redirect::route('posts.index');
    }
}
Step 4. 編集と削除ボータンを追加する

最終的にapp/views/posts/show.blade.phpに編集・削除ボータンを作る。
しかし、HTMLの<a>TagではHTTP request methodを入れることができないので、削除ボータンを作るのができない。
なので、DELETEmethodのFormを作って処理する。
app/views/posts/show.blade.php

@extends('layouts.master')

@section('title')
{{$post->title}} - mylaravel
@stop

@section('body')
<h1>{{$post->title}}</h1>
<p>{{$post->body}}</p>
{{Form::open(['route'=>['posts.destroy',$post->id],'method'=>'DELETE'])}}
    {{link_to_route('posts.edit','Edit', $post->id)}}
    {{Form::submit('Delete')}}
    {{link_to('posts', 'Back to list')}}
{{Form::close()}}
@stop
見ているようにFormを使って、DELETEMethodが使えるようになった。
これの短所は他のTagは<a>なのにDeleteだけ<button>になっちゃうことである。

これは悲しい

実際にBootstrapなどのCSSを適用したら問題ないが、
一応、全部<a>Tagにしたい人のため次のコードも書いておく

<a onclick="this.parentNode.submit();return false;" href="#">Delete</a>

Javascriptで<a>TagをSubmitボータンにした。
