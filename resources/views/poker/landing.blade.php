<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Simple Poker Service</title>
</head>
<body>
<h1>Simple Poker Service</h1>
<p>Deck ID: {{$deckId}}</p>
<p>{{link_to_action('PokerClientController@show', $title = 'Shuffle Deck', $parameters = ['action' => 'shuffle'], $attributes = [])}}</p>
<p>{{link_to_action('PokerClientController@show', $title = 'Deal Hands', $parameters = ['action' => 'deal'], $attributes = [])}}</p>

@isset($firstHand)
    <div>
        <h2>First Hand</h2>
        <p>{{$firstHand}}</p>
        <p>Hand type: {{$firstHandScore['hand']}}</p>
        <p>Hand score: {{$firstHandScore['score']}}</p>
        <p>Cards Index: {{$firstHandScore['index']['score']}}</p>
        @if($winner == 'firstHand') <b>WINNER</b> @else <b>LOOSER</b> @endif
    </div>
@endisset

@isset($secondHand)
    <div>
        <h2>Second Hand</h2>
        <p>{{$secondHand}}</p>
        <p>Hand type: {{$secondHandScore['hand']}}</p>
        <p>Hand score: {{$secondHandScore['score']}}</p>
        <p>Cards Index: {{$secondHandScore['index']['score']}}</p>
        @if($winner == 'secondHand') <b>WINNER</b> @else <b>LOOSER</b> @endif
    </div>
@endisset
</body>
</html>