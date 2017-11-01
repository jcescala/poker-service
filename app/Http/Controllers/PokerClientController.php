<?php
/**
 * Created by PhpStorm.
 * User: juane
 * Date: 10/30/17
 * Time: 11:08 PM
 */

namespace App\Http\Controllers;

use App\Services\DealerService;
use App\Libraries\PokerScore\PokerHandScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PokerClientController extends Controller
{
    private $deckId;
    private $dealer;

    public function __construct()
    {
        $this->dealer = new DealerService();
    }

    public function show(Request $request)
    {
        $action = $request->get('action', false);
        if ($action && $action == 'shuffle') {
            $this->deckId = $this->dealer->shuffle();
            if (!Cache::has('deckId')) {
                Cache::put('deckId', $this->deckId, 5);
            } else {
                Cache::add('deckId', $this->deckId, 5);
            }
            return view('poker.landing', ['deckId' => Cache::get('deckId')]);
        }
        if ($action && $action == 'deal') {
            $firstHand = $this->dealer->dealHand(Cache::get('deckId'), 5);
            $secondHand = $this->dealer->dealHand(Cache::get('deckId'), 5);
            if ($firstHand['success'] && $secondHand['success']) {
                $handScoreService = new PokerHandScore($firstHand['data']);
                $firstHandScore = $handScoreService::handScore();
                $handScoreService = new PokerHandScore($secondHand['data']);
                $secondHandScore = $handScoreService::handScore();

                $winner = '';
                if ($firstHandScore['score'] > $secondHandScore['score']) {
                    $winner = 'firstHand';
                }
                if ($firstHandScore['score'] < $secondHandScore['score']) {
                    $winner = 'secondHand';
                }
                if ($firstHandScore['score'] == $secondHandScore['score']) {
                    if ($firstHandScore['index']['score'] > $secondHandScore['index']['score']) {
                        $winner = 'firstHand';
                    } else {
                        $winner = 'secondHand';
                    }
                }

                return view('poker.landing', [
                    'deckId' => Cache::get('deckId'),
                    'firstHand' => $this->printableHand($firstHand),
                    'firstHandScore' => $firstHandScore,
                    'secondHand' => $this->printableHand($secondHand),
                    'secondHandScore' => $secondHandScore,
                    'winner' => $winner
                ]);
            } else {
                $this->deckId = $this->dealer->shuffle();
                Cache::put('deckId', $this->deckId, 5);
                return view('poker.landing', ['deckId' => Cache::get('deckId')]);
            }
        } else {
            $this->deckId = $this->dealer->shuffle();
            Cache::put('deckId', $this->deckId, 5);
            return view('poker.landing', ['deckId' => Cache::get('deckId')]);
        }
    }

    private function printableHand($hand)
    {
        $handValue = '';
        foreach ($hand['data'] as $h) {
            if (!empty($handValue)) {
                $handValue .= ', ' . $h['number'] . ' of ' . $h['suit'];
            } else {
                $handValue .= $h['number'] . ' of ' . $h['suit'];
            }
        }
        return $handValue;
    }
}