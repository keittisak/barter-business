<style>
    .icon-transfer-down {
        display: block;
        width: 56px;
        text-align: center;
        margin: 16px 0 14px 0;
    }
    .icon-transfer-down i {
        font-size: 30px;
        color: #28a745;
    }
    .signature {
        background-image: linear-gradient(to bottom, rgba(255,255,255,.96) 0%,rgba(255,255,255,.9) 100%), url("{{ asset('assets/images/'.env('LOGO_SLIP')) }}");
        background-size: contain;
        /* background-repeat: no-repeat; */
    }
    .user-block {
        float: inherit !important;
    }
    .user-block .description {
        color: black;
        font-size: 1rem;

    }
    .bbg-logo {
        width: 56px !important;
        height: auto !important;
    }
</style>
<div class="modal fade slip-modal" id="slip-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content signature">
            <div class="modal-body">
                <h5 class="text-center text-green">Barter Advance</h5>
                <p class="text-center text-muted text-r14">รหัสอ้างอิง #{{ strtoupper('xxx') }}</p>
                <div class="mt-5 d-block">
                    <div class="profile-user-img float-left mr-4">
                        <img class="img-fluid img-circle" src="{{ asset('assets/images/img_profile_default.jpg')  }}" alt="User profile picture">
                    </div>
                    <h6>xxxxx</h6>
                    <h6>รหัสผู้ซื้อ  #</h6>
                </div>
                <span class="icon-transfer-down">
                    <i class="fas fa-long-arrow-alt-down"></i>
                </span>
                <div class="d-block">
                    <div class="profile-user-img float-left mr-4">
                        <img class="img-fluid img-circle" src="{{ asset('assets/images/img_profile_default.jpg') }}" alt="User profile picture">
                    </div>
                    <h6>xxxxx</h6>
                    <h6>รหัสผู้ขาย  #</h6>
                </div>
                <ul class="list-unstyled list-inline mt-4 mb-1">
                    <li class="d-inline">จำนวนเทรดบาท</li>
                    <li class="d-inline float-right"><span class="h5">3,000</span> เทรดบาท</li>
                </ul>
                <ul class="list-unstyled list-inline">
                    <li class="d-inline">วันที่ทำรายการ</li>
                    <li class="d-inline float-right">05/10/2563 11:11</li>
                </ul>
                <ul class="list-unstyled list-inline">
                    <li class="">บันทึกช่วยจำ</li>
                    <li class="">ค่ากาแฟ</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="btn-colose-modal" data-dismiss="modal">
        <button type="button" class="btn btn-success btn-circle btn-lg"><i class="fas fa-times text-r24"></i></button>
        <p class="text-white">ปิด</p>
    </div>
</div>