@extends('front.layouts.main')
@section('title','Recommended Members') 
@section('css')
<style>
    #DataTables_Table_0_filter {
        display: none;
    }
    .btn-express-date {
        /* margin-right: .5rem!important; */
        margin-bottom: 1rem!important;
    }
    .table tr {
        cursor: pointer;
    }
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">{{ __('สมาชิกที่ได้แนะนำ') }}</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body p-0 pb-5">
                <div class="row">
                    <div class="col-md-8 offset-md-2 col-12">
                        <div class="px-3 pt-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control text-search" placeholder="ค้นหา ...">
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-success btn-search"><i class="fas fa-search mr-2"></i> ค้นหา</button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <h5 class="text-center text-muted"><i class="fas fa-exchange-alt"></i></h5>
                <div class="table-responsive p-0">
                    <table class="table table-vcenter table-striped">
                        <thead>
                            <tr>
                                <th class="text-nowrap">รหัสสมาชิก</th>
                                <th>ชื่อ-นามสกุล</th>
                                <th class="text-nowrap">วันที่เป็นสมาชิก</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $item)
                            <tr>
                                <td>{{ $item->code }}</td>
                                <td>{{ $item->first_name.' '.$item->last_name }}</td>
                                <td>{{ date('d/m/Y H:i', strtotime('+543 years', strtotime($item->created_at) ) ) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@section('javascript')
<script>
    var $dt = $('.table');
    table = $dt.DataTable({
        order:[[ 0, "desc" ]],
        paging: false,
        info: false,
        drawCallback: function (settings) {
            if (!$dt.parent().hasClass("table-responsive")) {
                $dt.wrap("<div class='table-responsive'></div>");
            }
            loader.close();
        },
    });

    $('.btn-search').on('click',function(e){
        loader.init();
        var text = $('.text-search').val();
        table.search(text).draw();
    });
</script>
@endsection