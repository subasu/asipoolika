
<div align="center" style="width: 500px; height: 500px; margin-left: 34%; margin-top: 5%;">
    @foreach($cards as $card)
        <img src="{{$card->card}}" style="width: 100%; height: 600px;"/>
    @endforeach
</div>