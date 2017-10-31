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
class PokerClientController extends Controller
{
    private $deckId;
    private $dealer;

    public function __construct()
    {
        $this->dealer = new DealerService();
        $this->deckId = is_null($this->deckId) ? $this->dealer->shuffle() : $this->deckId;
    }

    public function show()
    {
        $dealResults = $this->dealer->dealHand($this->deckId, 5);

        if($dealResults['success']){
//            $hand = array(
//                0 => array("number" => '10', "suit" => "spades"),
//                1 => array("number" => 'J', "suit" => "spades"),
//                2 => array("number" => 'Q', "suit" => "spades"),
//                3 => array("number" => 'K', "suit" => "spades"),
//                4 => array("number" => 'A', "suit" => 'spades')
//            );

//            $hand = array(
//                0 => array("number" => '6', "suit" => "spades"),
//                1 => array("number" => '7', "suit" => "spades"),
//                2 => array("number" => '8', "suit" => "spades"),
//                3 => array("number" => '9', "suit" => "spades"),
//                4 => array("number" => '10', "suit" => 'spades')
//            );

//            $hand = array(
//                0 => array("number" => '9', "suit" => "spades"),
//                1 => array("number" => '9', "suit" => "diamonds"),
//                2 => array("number" => '9', "suit" => "hearts"),
//                3 => array("number" => 'K', "suit" => "clubs"),
//                4 => array("number" => 'K', "suit" => 'spades')
//            );

//            $hand = array(
//                0 => array("number" => '9', "suit" => "spades"),
//                1 => array("number" => '3', "suit" => "spades"),
//                2 => array("number" => '6', "suit" => "spades"),
//                3 => array("number" => 'K', "suit" => "spades"),
//                4 => array("number" => '2', "suit" => 'spades')
//            );

//            $hand = array(
//                0 => array("number" => '3', "suit" => "hearts"),
//                1 => array("number" => '4', "suit" => "diamonds"),
//                2 => array("number" => '5', "suit" => "clubs"),
//                3 => array("number" => '6', "suit" => "spades"),
//                4 => array("number" => '7', "suit" => 'diamonds')
//            );

//            $hand = array(
//                0 => array("number" => '2', "suit" => "hearts"),
//                1 => array("number" => '5', "suit" => "diamonds"),
//                2 => array("number" => '6', "suit" => "clubs"),
//                3 => array("number" => 'J', "suit" => "spades"),
//                4 => array("number" => 'A', "suit" => 'diamonds')
//            );
//            $handScoreService = new PokerHandScore($hand);

            $handScoreService = new PokerHandScore($dealResults['data']);
            $handScore = $handScoreService::handScore();
        }
    }
}