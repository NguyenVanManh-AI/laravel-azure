<div>
    <p style="text-align: center; "></p>
    @if($infors->messsge)
    <h3 style="text-align: center;">
        <span style="font-style: italic; color: rgb(40, 167, 69);">{{ $infors->messsge }} &nbsp;</span>
    </h3>
    @endif
    <p></p>
    <p><span style="font-weight: bold; color: rgb(0, 123, 255);">Thông tin chi tiết dịch vụ :</span></p>
    {{-- @if($user)
        <p><span style="color: rgb(40, 167, 69); font-weight: bold;">Thông tin tài khoản :&nbsp;</span></p>
        <p></p>
        <ol style="list-style: decimal;">
            <li><span>Tên tài khoản : <span style="font-weight: bold;">{{$user->name}}</span></span></li>
            <li><span>Email : <span style="font-weight: bold;">{{$user->email}}&nbsp;</span></span></li>
            <li><span>SĐT : <span style="font-weight: bold;">{{$user->phone}}&nbsp;</span></span></li>
        </ol>
    @endif --}}
    <p></p>
    <p><span style="color: rgb(40, 167, 69); font-weight: bold;">Thông tin khách hàng :&nbsp;</span></p>
    <p></p>
    <ol>
        <li><span>Tên khách hàng : <span style="font-weight: bold;">{{$infors->name_patient}}&nbsp;</span></span></li>
        @if($infors->gender_patient == 1)
        <li><span>Giới tính : <span style="font-weight: bold;">Nam &nbsp;</span></span></li>
        @else 
        <li><span>Giới tính : <span style="font-weight: bold;">Nữ &nbsp;</span></span></li>
        @endif
        <li><span>Ngày sinh : <span style="font-weight: bold;">{{$infors->date_of_birth_patient}}</span></span></li>
        <li><span>Email : <span style="font-weight: bold;">{{$infors->email_patient}}</span></span></li>
        <li><span>SĐT : <span style="font-weight: bold;">{{$infors->phone_patient}}</span></span></li>
        <li><span>Địa chỉ : <span style="font-weight: bold;">{{ $infors->address_patient !== null ? $infors->address_patient : 'Trống' }}&nbsp;</span></span></li>
        <li><span>Bệnh trạng : <span style="font-weight: bold;">{!! $infors->health_condition !!}</span></span></li>
    </ol>
    <p></p>
    <p><span style="font-weight: bold; color: rgb(40, 167, 69);">Thông tin dịch vụ :&nbsp;</span></p>
    <p></p>
    <ol>
        <li><span>Tên dịch vụ : <span style="font-weight: bold;">{{$infors->name_service}}</span></span></li>
        <li><span>Chuyên khoa : <span style="font-weight: bold;">{{$infors->name_department}}</span></span></li>
        <li><span>Bệnh viện : <span style="font-weight: bold;">{{$infors->name_hospital}}</span></span></li>
        <li>SĐT bệnh viện : <span style="font-weight: bold;">{{$infors->phone_hospital}}&nbsp; &nbsp;</span></li>
        <li>Địa chỉ bệnh viện : <span style="font-weight: bold;">{{$infors->address_hospital}}&nbsp;</span></li>
    </ol>
    <div><span style="font-weight: 700;"><span style="color: rgb(40, 167, 69); font-weight: 700; ">Các thông tin khác :</span></span></div>
    <p></p>
    <ol>
        <li><span>Phí dịch vụ : <span style="color: rgb(255, 0, 0); font-weight: bold;">{{number_format($infors->price, 0, ',', '.')}} VNĐ</span></span></li>
        <li><span>Khoảng thời gian : <span style="font-weight: bold; color: rgb(0, 123, 255);">
            {{$infors->time->interval[0]}} - {{$infors->time->interval[1]}} Ngày {{$infors->time->date}}
        </span></span></li>
        <li><span><span style="color: rgb(0, 0, 0);">Địa chỉ : <span style="font-weight: bold; color: rgb(0, 123, 255);">{{$infors->address_hospital}}&nbsp;</span></span></span>
        </li>
    </ol>
</div>
