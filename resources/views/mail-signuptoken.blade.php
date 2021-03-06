@extends("layout.email-token-layout")
@section("section-body")
    <script src="js/count-down-to-home.js"></script>
    <section>
        <div class="body-email-token">
            @if(isset($is_token))
                <p>Token không khả dụng</p>
            @else
            <h4>Bạn đã xác nhận đăng kí tài khoản thành công.</h4>
            <p>Bây giờ bạn đã là thành viên và có thể đăng nhập vào trang web</p>
            @endif
            <p>Trang sẽ chuyển hướng trong <span class="second">10</span> giây ...</p>
        </div>
    </section>
@endsection