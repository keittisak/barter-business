@extends('front.layouts.main')
@section('title','Profile') 
@section('css')
<style>
  .btn-edit-profile{
    border-top-right-radius: .25rem;
    border-top-left-radius:0;
    border-bottom-right-radius:0;
  }
</style>
@endsection

@section('nav_header')
    <a href="javascript:history.back()" class="btn-header"><i class="fa fa-arrow-left"></i></a>
    <h5 class="title-header">โปรไฟล์</h5>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-img-overlay justify-content-end badge-sale">
                <a href="{{ route('front.users.profile.edit') }}" class="btn btn-default float-right text-r14 btn-edit-profile">แก้ไขโปรไฟล์</a>
            </div>
            <div class="card-body box-profile row">
              <div class="col-md-6 offset-md-3 col-12">

                <div class="avatars rounded-circle avatars-xl d-block my-0 mx-auto">
                  <div class="avatars-one" style="background-image: url({{ empty($user->image) ? asset('assets/images/img_profile_default.jpg') : $user->image }}"></div>
                </div>
  
                  <h3 class="profile-username text-center">{{ $user->full_name() }}</h3>
                <p class="text-muted text-center text-r14">
                  <span class="text-black">รหัส #{{ $user->code }}</span> <br> {{ $user->email }} <br>{{ $user->phone }}
                  <br/>
                  สมัครสมาชิกวันที่:&nbsp;{{date('d/m/Y', strtotime('+543 years', strtotime($user->created_at)))}}
                  <br/>
                  วันหมดอายุวันที่:&nbsp;&nbsp;{{date('d/m/Y', strtotime('+543 years', strtotime($user->expired_at)))}}
                </p>
  
                <ul class="list-group list-group-unbordered mb-4">
                  <li class="list-group-item py-3">
                    <i class="fab fa-google text-gold mr-2"></i> เทรดบาทคงเหลือ <span class="float-right">{{ number_format($point_balance) }} </span>
                  </li>
                  @if( $user->type == 2 )
                    <li class="list-group-item py-3">
                      <i class="fas fa-circle text-success mr-2"></i> วงเงินเครดิต <span class="float-right">{{ number_format($user->credit_total_amount) }} </span>
                    </li>
                    <li class="list-group-item py-3">
                      <i class="fas fa-circle text-gold mr-2"></i> วงเงินเครดิตคงเหลือ <span class="float-right">{{ number_format($credit_balance) }} </span>
                    </li>
                  @endif
                  <li class="list-group-item py-3">
                    <i class="fas fa-sync-alt text-muted mr-2"></i> โอนเทรดบาท <a class="float-right"><i class="fas fa-angle-right text-muted"></i></a>
                      <a href="{{ route('front.users.trade.form') }}" class="stretched-link"></a>
                  </li>
                  {{-- <li class="list-group-item">
                    <i class="far fa-list-alt text-muted mr-2"></i> รายการ <a class="float-right"><i class="fas fa-angle-right text-muted"></i></a>
                    <a href="{{ route('front.users.point.transfer.lists') }}" class="stretched-link"></a>
                  </li> --}}
                  <li class="list-group-item py-3">
                    <i class="fas fa-store text-muted mr-2"></i> ข้อมูลร้านค้า <a class="float-right"><i class="fas fa-angle-right text-muted"></i></a>
                    <a href="{{ route('front.users.shops.index') }}" class="stretched-link"></a>
                  </li>
                  {{-- @if($user->shop)
                  <li class="list-group-item py-3">
                      <i class="fas fa-shapes text-muted mr-2"></i> เพิ่มสินค้า <a class="float-right"><i class="fas fa-angle-right text-muted"></i></a>
                      <a href="{{ route('front.users.products.index') }}" class="stretched-link"></a>
                  </li>
                  @endif --}}

                  <li class="list-group-item py-3">
                    <i class="fas fa-user-check text-muted mr-2"></i> ผู้รับผลประโยชน์ <a class="float-right"><i class="fas fa-angle-right text-muted"></i></a>
                    <a class="stretched-link cursor-pointer" id="btn-show-beneficairy-modal"></a>
                  </li>
                </ul>

              </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>

<div class="modal fade beneficairy-modal" id="beneficairy-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"><i class="fas fa-user-check text-muted mr-2"></i> ผู้รับผลประโยชน์</h5>
      </div>
      <div class="modal-body">
        <div class="row mb-1">
          <div class="col-4 text-right">ชื่อ-นามสกุล :</div>
          <div class="col-8">xxxxx</div>
        </div>
        <div class="row mb-1">
          <div class="col-4 text-right">ความสัมพันธ์ :</div>
          <div class="col-8">xxxxx</div>
        </div>
        <div class="row mb-1">
          <div class="col-4 text-right">ที่อยู่ :</div>
          <div class="col-8">xxxxx</div>
        </div>
        <div class="row mb-1">
          <div class="col-4 text-right">เบอร์โทรศัพท์ :</div>
          <div class="col-8">xxxxx</div>
        </div>
        <div class="row mb-1">
          <div class="col-4 text-right">รหัสบัตรประชาชน/Passport :</div>
          <div class="col-8">xxxxx</div>
        </div>
      </div>
      <div class="modal-footer justify-content-center">
        <a href="{{ route('front.users.beneficiary.edit') }}" class="btn btn-success">แก้ไขข้อมูล</a>
        <button type="button" class="btn btn-default" data-dismiss="modal" aria-label="Close">ปิด</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('javascript')
<script>
  $('#btn-show-beneficairy-modal').on('click',function(e){
    $.ajax({
            url: "{{ route('front.users.beneficiary.show') }}",
            method: "GET",
            beforeSend: function( xhr ) {
                loader.init();
            }
        }).done(function(data){
          loader.close();
          var element = ``;
          if(data == "") {
            $('#beneficairy-modal').find('.modal-body').hide();
            $('#beneficairy-modal').find('.btn-success').attr('href', "{{ route('front.users.beneficiary.create') }}");
            $('#beneficairy-modal').find('.btn-success').html('เพิ่มข้อมูลผู้รับผลประโยชน์');
          } else {
            element = `
            <div class="row mb-1">
              <div class="col-4 text-right">ชื่อ-นามสกุล :</div>
              <div class="col-8">${data.name}</div>
            </div>
            <div class="row mb-1">
              <div class="col-4 text-right">ความสัมพันธ์ :</div>
              <div class="col-8">${data.relationship}</div>
            </div>
            <div class="row mb-1">
              <div class="col-4 text-right">ที่อยู่ :</div>
              <div class="col-8">${data.address}</div>
            </div>
            <div class="row mb-1">
              <div class="col-4 text-right">เบอร์โทรศัพท์ :</div>
              <div class="col-8">${data.phone}</div>
            </div>
            <div class="row mb-1">
            <div class="col-4 text-right">รหัสบัตรประชาชน/Passport :</div>
              <div class="col-8">${(data.id_card_number != null) ? data.id_card_number :''}</div>
            </div>
            `;
            $('#beneficairy-modal').find('.modal-body').html(element);
            $('#beneficairy-modal').find('.btn-success').attr('href', "{{ route('front.users.beneficiary.edit') }}");
            $('#beneficairy-modal').find('.btn-success').html('แก้ไขข้อมูล');
            $('#beneficairy-modal').find('.modal-body').show();
          }

          $('#beneficairy-modal').modal('show');
        }).fail(function( jqxhr, textStatus ) {
          var message = jqxhr.responseJSON.message
          var errors = jqxhr.responseJSON.errors
          loader.close();
        });
  });
  $('.copyboard-url').on('click', function(e) {
    e.preventDefault();
    var copyText = $(this).data('url');
    var textarea = document.createElement("textarea");
    textarea.textContent = copyText;
    textarea.style.position = "fixed";
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand("copy"); 

    document.body.removeChild(textarea);
    Swal.fire({
        icon: 'success',
        title: 'คัดลอกลิ้งค์',
    })
  })
</script>
@endsection