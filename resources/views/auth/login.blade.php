@extends('layouts.auth')

@section('auth')
<div class="col-md-8">
    <div class="card-group">
        <div class="card">
            <div class="card-body p-5">
                <div class="text-center d-lg-none">
                    <img src="svg/modulr.svg" class="mb-5" width="150" alt="Modulr Logo">
                </div>
                <h1>{{ __('Login') }}</h1>
                <p class="text-muted">Sign In to your account</p>
                <form method="POST" action="{{ route('login') }}">
                    @csrf
                            @if($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger">{{ $error }}</div>
                                @endforeach
                            @endif
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="icon-phone"></i></span>
                        </div>
                        <input id="Mobile" type="text" class="form-control{{ $errors->has('mobile_number') ? ' is-invalid' : '' }}" name="mobile_number" value="{{ old('mobile_number') }}" placeholder="{{ __('Mobile Number') }}" required autofocus>
                        <span class="btn btn-warning btn-sm" id="getOtp">Send OTP</span>
                        @if ($errors->has('mobile_number'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('mobile_number') }}</strong>
                        </span>
                        @endif
                       </div>
                       <label id="otpsent" style="display: none;" class="col col-xs-12">Otp Sent</label>
                    
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="icon-lock"></i>
                            </span>
                        </div>
                        <input id="code" {{old("code")??"disabled"}} type="number" value="{{old('code')}}" class="form-control{{ $errors->has('code') ? ' is-invalid' : '' }}" name="code" placeholder="OTP " required>

                        @if ($errors->has('code'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('code') }}</strong>
                        </span>
                        @endif
                    </div>
                 
                    <div class="row">
                        <div class="col-4">
                            <button type="submit" class="btn btn-primary px4">
                                {{ __('Login') }}
                            </button>
                        </div>
                     
                    </div>
                </form>
            </div>
            <div class="card-footer p-4 d-lg-none">
                <div class="col-12 text-right">
                    <a class="btn btn-outline-primary btn-block mt-3" href="{{ route('register') }}">{{ __('Register') }}</a>
                </div>
            </div>
        </div>
        <div class="card text-white bg-primary py-5 d-md-down-none">
            <div class="card-body text-center">
                <div>
                   <h1>PaperStory</h1>
                    {{-- <h2>{{ __('Sign up') }}</h2> --}}
                    {{-- <p>If you don't have account create one.</p> --}}
                    {{-- <a class="btn btn-primary active mt-2" href="{{ route('register') }}">{{ __('Register Now!') }}</a> --}}
                    {{-- <p>Or for registration as agent call +59712123123</p> --}}
                </div>
            </div>
        </div>
    </div>
</div>
<script>

    document.getElementById("getOtp").addEventListener("click",function(){
        // alert(document.querySelector("meta[name='csrf-token']").getAttribute("content"));
        var mobile_number=document.getElementById("Mobile").value;
        if(mobile_number==""){
            alert("Mobile Number is Required");
            return;
        }
        sendOtp(mobile_number);
    });
    function sendOtp(mobile_number) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
        document.getElementById("code").disabled=false;
        document.getElementById("otpsent").style.display="block";
    }
  };
  xhttp.open("POST", "/send-otp", true);
  xhttp.setRequestHeader("X-CSRF-TOKEN", document.querySelector("meta[name='csrf-token']").getAttribute("content"));
  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xhttp.send("mobile="+mobile_number);
}
</script>    
@endsection
