@extends('layout.layout')
@section('contents')
    <? $s = $settings[0] ?>
    {{Form::open(['route'=>'settings.update','method'=>'put'])}}
    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr class="row text-center">
            <td class="mark">
                {{Form::label('azmode', '預かり金',['class'=>'control-label'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('azmode', '通常モード',['class'=>'control-label col-md-9'])}}
                {{Form::radio('azmode', 0, 0 == $s->azmode, ['id' => 'azmode', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('azmode', '自動預かり金モード',['class'=>'control-label col-md-9'])}}
                {{Form::radio('azmode', 1, 1 == $s->azmode, ['id' => 'azmode', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
    </table>
    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr class="row text-center">
            <td class="mark">
                {{Form::label('bumode', '部門管理',['class'=>'control-label'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('bumode', '通常モード',['class'=>'control-label col-md-9'])}}
                {{Form::radio('bumode', 0, 0 == $s->bumode, ['id' => 'bumode', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('bumode', '部門管理モード',['class'=>'control-label col-md-9'])}}
                {{Form::radio('bumode', 1, 1 == $s->bumode, ['id' => 'bumode', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
    </table>
    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr class="row text-center">
            <td class="mark">
                {{Form::label('picmode', '画像管理',['class'=>'control-label'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('picmode', '通常モード',['class'=>'control-label col-md-9'])}}
                {{Form::radio('picmode', 0, 0 == $s->picmode, ['id' => 'picmode', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('picmode', '画像管理モード',['class'=>'control-label col-md-9'])}}
                {{Form::radio('picmode', 1, 1 == $s->picmode, ['id' => 'picmode', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
    </table>
    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr class="row text-center">
            <td class="mark">
                {{Form::label('nimode', '日計表',['class'=>'control-label'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('nimode', '単品表示',['class'=>'control-label col-md-9'])}}
                {{Form::radio('nimode', 0, 0 == $s->nimode, ['id' => 'nimode', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('nimode', '部門表示',['class'=>'control-label col-md-9'])}}
                {{Form::radio('nimode', 1, 1 == $s->nimode, ['id' => 'nimode', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
    </table>
    {{Form::text('id', $s->id, ['style'=>'display:none'])}}
    {{Form::submit('更新', ['name' => 'modeSetting', 'class'=>'form-control btn-primary btn-md btn-block'])}}
    {{Form::close()}}
@stop
@section('contents2')
    <?
        $shop = $shopsettings[0];
            foreach (Shop::all() as $e)
                {
                    $shop_pairs[$e->id] = $e->Tenpo;
                }
    ?>
    {{Form::open(['route'=>'shopsettings.update','method'=>'put'])}}
    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr class="row text-center">
            <td class="mark">
                {{Form::label('tempo', '店舗ID',['class'=>'control-label'])}}
                {{Form::select('tempo', $shop_pairs, $shop->tempo, ['class'=>'form-control'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('reji', 'レジ番号',['class'=>'control-label'])}}
                {{Form::text('reji', $shop->reji, ['class' => 'form-control'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('receipt', 'レシート初期値',['class'=>'control-label'])}}
                {{Form::text('receipt', $shop->receipt, ['class' => 'form-control'])}}
            </td>
        </tr>
   </table>
   {{Form::text('shopid', $shop->id, ['style'=>'display:none'])}}
    {{Form::submit('更新', ['name' => 'shopUpdate', 'class'=>'form-control btn-primary btn-md btn-block'])}}
    {{Form::close()}}
@stop
@section('contents3')
    <? $r = $receiptsettings[0]; ?>
    {{Form::open(['route'=>'receiptsettings.update','method'=>'put'])}}
    <table class="table table-striped table-bordered table-condensed table-hover">
        <tr class="row text-center">
            <td class="mark">
                {{Form::label('tax', '消費税率',['class'=>'control-label'])}}
                {{Form::text('tax', $r->tax, ['class' => 'form-control'])}}
            </td>
        </tr>
        <tr class="row text-center">
            <td class="mark">
                {{Form::label('haveReceipt', 'レシート',['class'=>'control-label'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('haveReceipt', 'レシート無し',['class'=>'control-label col-md-9'])}}
                {{Form::radio('haveReceipt', 0, 0 == $r->haveReceipt, ['id' => 'haveReceipt', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('haveReceipt', 'レシート有り',['class'=>'control-label col-md-9'])}}
                {{Form::radio('haveReceipt', 1, 1 == $r->haveReceipt, ['id' => 'haveReceipt', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
        <tr class="row text-center">
            <td class="mark">
                {{Form::label('haveStamp', 'スタンプ',['class'=>'control-label'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('haveStamp', 'スタンプ無し',['class'=>'control-label col-md-9'])}}
                {{Form::radio('haveStamp', 0, 0 == $r->haveStamp, ['id' => 'haveStamp', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('haveStamp', 'スタンプ有り',['class'=>'control-label col-md-9'])}}
                {{Form::radio('haveStamp', 1, 1 == $r->haveStamp, ['id' => 'haveStamp', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
        <tr class="row text-center">
            <td class="mark">
                {{Form::label('haveComment', 'レシート印字',['class'=>'control-label'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('haveComment', 'レシート印字無し',['class'=>'control-label col-md-9'])}}
                {{Form::radio('haveComment', 0, 0 == $r->haveComment, ['id' => 'haveComment', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>
        <tr class="row">
            <td>
                {{Form::label('haveComment', 'レシート印字有り',['class'=>'control-label col-md-9'])}}
                {{Form::radio('haveComment', 1, 1 == $r->haveComment, ['id' => 'haveComment', 'class'=>'input-sm col-md-1'])}}
            </td>
        </tr>

   </table>
   {{Form::text('receiptsettingid', $r->id, ['style'=>'display:none'])}}
    {{Form::submit('更新', ['name' => 'receiptUpdate', 'class'=>'form-control btn-primary btn-md btn-block'])}}
    {{Form::close()}}
@stop
