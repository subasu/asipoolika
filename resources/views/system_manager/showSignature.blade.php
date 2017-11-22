
    <div align="center" style="width: 500px; height: 500px; margin-left: 34%; margin-top: 16%;">
    @foreach($signatures as $signature)
        <img src="{{$signature->signature}}" style=" width: 300px;"/>
    @endforeach
    </div>