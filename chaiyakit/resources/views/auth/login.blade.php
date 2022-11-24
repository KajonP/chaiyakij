@extends('layouts.main')
<style>
 input {
    width: 400px;
    padding: 0 20px;
}

input,
input::-webkit-input-placeholder {
    font-size: 20px;
    line-height: 3;
}
</style>
@section('content')

<div class="container" style="display: grid;align-items: center; font-family: 'Sarabun', sans-serif;">
    <div class="row justify-content-center">
        <div class="col-6">
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="row justify-content-center">
                    <div class="col-12 form-group text-center text-white">
                        <h1 style="font-size:50px">ไชยกิจคอนกรีต</h1>
                    </div>
                    <div class="col-10 input-group mb-3 input-group-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-user"></i></span>
                        </div>
                        <input  id="email" type="email" placeholder="บัญชีผู้ใช้งาน" class="form-control  @error('email') is-invalid @enderror  @if (session('status')) is-invalid @endif"
                            name="email" value="" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        @if (session('status'))
                        <span class="invalid-feedback" role="alert">
                            <strong> {{ session('status') }}</strong>
                        </span>
                         @endif
                    </div>
                    <div class="col-10 input-group mb-3 input-group-lg">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-lock"></i></span>
                        </div>
                        <input id="password" placeholder="รหัสผ่าน" type="password"
                            class="form-control @error('password') is-invalid @enderror" name="password"
                            value="" required autocomplete="current-password">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <div class="col-10 form-group">
                        <button type="submit"  class="btn btn-danger btn-lg col-12">
                            เข้าสู่ระบบ
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
