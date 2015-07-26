@extends('layout.goods')
@section('contents4')
    @include('message.form')
    <? foreach ($categories as $c) { $category_pairs[$c->id] = $c->Bumon;} ?>
    {{Form::open(['route'=>'items.store', 'method'=>'post', 'files'=>true])}}
    <div>
        {{Form::label('title', '商品名', ['class' => 'control-label'])}}
    </div>
    <div>
        {{Form::text('title', $item->title, ['class' => 'form-control'])}}
    </div>
    <div>
        {{Form::label('price', '価格',['class'=>'control-label'])}}
    </div>
    <div>
        {{Form::text('price', $item->price, ['class' => 'form-control'])}}
    </div>
    <div>
        {{Form::label('genka', '原価',['class'=>'control-label'])}}
    </div>
    <div>
        {{Form::text('genka', $item->genka, ['class' => 'form-control'])}}
    </div>
    <div>
        {{Form::label('Bumon', '部門',['class'=>'control-label'])}}
    </div>
    <div>
        {{Form::select('Bumon', $category_pairs, $item->Bumon, ['class'=>'form-control'])}}
    </div>
    <div>
        {{Form::label('Kosu', '個数',['class'=>'control-label'])}}
    </div>
    <div>
        {{Form::text('Kosu', $item->Kosu, ['class' => 'form-control'])}}
    </div>
    <div>
        {{Form::label('contents', '写真',['class'=>'control-label'])}}
    </div>
    <div>
        {{Form::file('upload',['class'=>'form-control'])}}
    </div>
    <div class="well-sm">
        {{Form::submit('登録', ['name' => 'createItem', 'class'=>'form-control btn-primary btn-md btn-block'])}}
    </div>
    <div class="well-sm">
        {{Form::submit('更新', ['name' => 'updateItem', 'class'=>'form-control btn-primary btn-md btn-block'])}}
    </div>
    <div class="well-sm">
        {{Form::submit('削除', ['name' => 'deleteItem', 'class'=>'form-control btn-primary btn-md btn-block'])}}
        {{Form::text('idItem', $item->id, ['style'=>'display:none'])}}
    </div>
    {{Form::close()}}
@stop

@section('contents5')
    <? $items = Item::all(); ?>
    {{Form::open(['route'=>'items.store', 'method'=>'post', 'id'=>"item_form"])}}
    <table class="table table-striped table-bordered table-hover table-condensed" data-toggle="table"
           data-locale="ja-JP">
        <thead>
        <tr class="row text-center mark">
            <td>選択</td>
            <td>商品名</td>
            <td>価格</td>
            <td>原価</td>
            <td>部門</td>
            <td>在庫</td>
            <td>写真</td>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr class="row">
                <td class="col-md-1 text-center">{{Form::radio('selectedItem',$item->id, false, ['id'=>'selectedItem'])}}</td>
                <td>{{$item->title}}</td>
                <td>{{$item->price}}</td>
                <td>{{$item->genka}}</td>
                <td>{{$item->Bumon}}</td>
                <td>{{$item->Kosu}}</td>
                <td class="col-md-1"><a href="#" class="pop"><img style="width:48px;height:42px;" id="imageresource" src='./item/{{$item->id}}' } class="img-responsive"/></a></td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{Form::close()}}
@stop
