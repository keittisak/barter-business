@extends('admin.layouts.main')
@section('title','About Barter Advance') 
@section('css')
<style>
</style>
@endsection
@section('content')
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>About Barter Advance</h1>
            </div>
        </div>
    </div>
</section>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col">
                <div id="accordion">
                    @if( !empty($about) )
                    @foreach(json_decode($about) as $key => $item)
                    <div class="card">
                        <div class="card-header" id="heading{{$key}}">
                            <button class="btn btn-block text-left text-r20 px-0" data-toggle="collapse" data-target="#collapse{{$key}}" aria-expanded="true" aria-controls="collapse{{$key}}">
                                {{$item->title}}
                            </button>
                        </div>
                        <div id="collapse{{$key}}" class="collapse" aria-labelledby="heading{{$key}}" data-parent="#accordion">
                            <div class="card-body">
                                {!! $item->description !!}
                            </div>
                            <div class="card-footer text-right">
                                <button type="button" class="btn btn-primary btn-sm edit-about mr-2" data-key="{{$key}}" data-title="{{$item->title}}"><i class="far fa-edit"></i> แก้ไข</button>
                                <button type="button" class="btn btn-danger btn-sm delete-about" data-key="{{$key}}"><i class="far fa-trash-alt"></i> ลบ</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
        <hr>

        <div class="row">
            <div class="col-md-6 offset-md-3 col-12 mt-4">
                <button type="button" class="btn btn-primary btn-block" id="about-form-modal-btn"><i class="fas fa-plus"></i> เพิ่มข้อมูล</button>
            </div>
        </div>
    </div>


  <!-- Modal -->
<div class="modal fade" id="about-form-modal" tabindex="-1" role="dialog" aria-labelledby="about-form-modal-label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="about-form-modal-label">เพิ่มข้อมูล</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <form id="form" action="{{ route('branches.about.store', $id) }}" method="post">
                <input type="hidden" name="_method" value="post">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="">หัวข้อ</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <textarea class="description" name="description" placeholder="Place some text here"></textarea>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
            <button type="submit" class="btn btn-success" id="btn-submit">บันทึกข้อมูล</button>
        </div>
        </div>
    </div>
</div>
</section>
@endsection
@section('javascript')
<script>
    $('#btn-submit').on('click',function(e){
        $('#form').submit();
    });
    $('#form').ajaxForm({
        dataType: 'json',
        beforeSubmit: function (arr, $form, options) {
            $('.invalid-feedback').remove();
            loader.init();
        },
        success: function (res) {
            Swal.fire({
                icon:'success',
                title:'บันทึกข้อมูลเรียบร้อย'
            }).then(function(){
                location.reload();
            });
        },
        error: function (jqXHR, status, options, $form) {
            var message = jqXHR.responseJSON.message
            var errors =  jqXHR.responseJSON.errors
            $.each(errors, function(key,v) {
                $(`#${key}`).addClass('is-invalid');
                for( i=0; i < v.length; i++ ) {
                    $(`#${key}`).parent('.form-group').append(`<div class="invalid-feedback">${v[i]}</div>`);
                }
            });
            loader.close();
        }
    });

    $(function () {
        $('.description').summernote({
            tabsize: 2,
            height: 400,
            callbacks: {
            onImageUpload: function(files,editor, welEditable) {
                console.log(files[0], editor, welEditable);
                sendFile(files[0], editor, welEditable)
            }
    }
        })
    });

    $('.update-description').on('click',function(e){
        var markup = $('.description').summernote('code');
        $.ajax({
            url: "{{ route('branchs.description.update') }}",
            method: "POST",
            data:{
                    _token:'{{ csrf_token() }}',
                    _method:'PUT',
                    description: markup
                },
            beforeSend: function( xhr ) {
                loader.init();
            }
        }).done(function(data){
            Swal.fire({
                icon:'success',
                title:'บันทึกข้อมูลเรียบร้อย'
            }).then(function(){
                location.reload();
            });
        }).fail(function( jqxhr, textStatus ) {
            var message = jqXHR.responseJSON.message
            Swal.fire(`คำเตือน`, message, `error`);
            loader.close();
        });
    });

    $('#about-form-modal-btn').click(function(e){
        $('input[name="title"]').val('');
        $('.description').summernote('code', '');
        var __url = "{{ route('branches.about.store', $id) }}";
        $('#form').attr('action',__url);
        $('#form').find('input[name="_method"]').val('post');
        $('#about-form-modal').modal('show');
    });
    $('.edit-about').on('click',function(e){
        var key = $(this).data('key');
        var title = $(this).data('title');
        var text = $('#collapse'+key).find('.card-body').html();
        var __url = "{{ route('branches.about.update', ['__id', '__key']) }}";
        __url = __url.replace('__id', {{$id}});
        __url = __url.replace('__key', key);
        $('input[name="title"]').val(title);
        $('.description').summernote('code', text);
        $('#form').attr('action',__url);
        $('#form').find('input[name="_method"]').val('put');
        $('#about-form-modal').modal('show');
        
    });
    $('.delete-about').on('click',function(e){
        var key = $(this).data('key');
        Swal.fire({
            icon: 'warning',
            title: 'คุณต้องการลบข้อมูล?',
            showCancelButton: true,
            confirmButtonText: `ยืนยัน`,
            cancelButtonText:`ปิด`,
        }).then(function(result){
            if (result.isConfirmed) {
                var __url = `{{ route('branches.about.delete',[$id,'__key']) }}`;
                __url = __url.replace('__key', key);
                $.ajax({
                    url: __url,
                    method: "POST",
                    dataType:'json',
                    data:{
                        _method:'DELETE',
                        _token:'{{ csrf_token() }}'
                    },
                    beforeSend: function( xhr ) {
                        loader.init();
                    }
                }).done(function(data){
                    Swal.fire({
                        icon:'success',
                        title:'ลบข้อมูลเรียบร้อย'
                    }).then(function(){
                        location.reload();
                    });
                }).fail(function( jqxhr, textStatus ) {
                    var message = jqXHR.responseJSON.message
                    Swal.fire(`คำเตือน`, message, `error`);
                    loader.close();
                });
            }
        });
    });

    function sendFile(file, editor, welEditable) {
        data = new FormData()
        data.append("file", file)
        data.append('_token', '{{ csrf_token() }}')
        $.ajax({
            data: data,
            type: "POST",
            url: "{{route('images.upload')}}",
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function( xhr ) {
                loader.init();
            },
            success: function(url) {
                var image = $('<img>').attr('src', url);
                $('.description').summernote("insertNode", image[0]);
                loader.close();
            }
        });
    }
</script>
@endsection
