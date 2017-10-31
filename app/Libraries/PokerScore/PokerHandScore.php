<?php
/**
 * Created by PhpStorm.
 * User: juane
 * Date: 10/31/17
 * Time: 12:30 AM
 */

namespace App\Libraries\PokerScore;

use Illuminate\Support\Facades\Config;

class PokerHandScore
{

    private static $handindex;
    private static $hand;

    public function __construct($hand)
    {
        self::$hand = $hand;
        self::$handindex = self::getHandIndex($hand);
    }

    public static function handScore()
    {
        if (self::checkRoyalFlush(self::$handindex)) {
            return array(
                'hand' => 'Royal Flush',
                'score' => Config::get('handScore.royal_flush')
            );
        }
        if (self::checkStraightFlush(self::$handindex)) {
            return array(
                'hand' => 'Straight Flush',
                'score' => Config::get('handScore.straight_flush')
            );
        }
        if (self::checkFourOfAKind(self::$hand)) {
            return array(
                'hand' => 'Four of a Kind',
                'score' => Config::get('handScore.four_of_a_kind')
            );
        }
        if (self::checkFullHouse(self::$hand)) {
            return array(
                'hand' => 'Full House',
                'score' => Config::get('handScore.full_house')
            );
        }
        if (self::checkFlush(self::$hand)) {
            return array(
                'hand' => 'Flush',
                'score' => Config::get('handScore.flush')
            );
        }
        if (self::checkStraight(self::$handindex)) {
            return array(
                'hand' => 'Straight',
                'score' => Config::get('handScore.straight')
            );
        }
        if (self::checkThreeOfAKind(self::$hand)) {
            return array(
                'hand' => 'Three of a Kind',
                'score' => Config::get('handScore.three_of_a_kind')
            );
        }
        if (self::checkTwoPairs(self::$hand)) {
            return array(
                'hand' => 'Two Pairs',
                'score' => Config::get('handScore.two_pairs')
            );
        }
        if (self::checkOnePair(self::$hand)) {
            return array(
                'hand' => 'One Pair',
                'score' => Config::get('handScore.one_pair')
            );
        }
        if (self::checkHighCard(self::$hand)) {
            return array(
                'hand' => 'High Card',
                'score' => Config::get('handScore.high_card')
            );
        } else {
            return array(
                'hand' => 'Can\'t handle this card combination',
                'score' => 0
            );
        }
    }

    private static function checkRoyalFlush($handIndex)
    {
        $royalFlushHand = array(
            array('number' => 'A'),
            array('number' => 'K'),
            array('number' => 'Q'),
            array('number' => 'J'),
            array('number' => '10')
        );
        $suit = self::$hand[0]['suit'];
        $royalFlushIndex = self::getHandIndex($royalFlushHand);
        if ($handIndex['score'] === $royalFlushIndex['score']) {
            foreach (self::$hand as $card) {
                if ($card['suit'] !== $suit) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    private static function checkStraightFlush($handIndex)
    {
        $handValues = array_values($handIndex['handIndex']);
        if (sizeof($handValues) === 5 && ($handValues[0] - $handValues[4] === 4)) { //consecutive cards
            $suit = self::$hand[0]['suit'];
            foreach (self::$hand as $card) {
                if ($card['suit'] !== $suit) {
                    return false;
                }
            }
            return true;
        } else {
            return false;
        }
    }

    private static function checkFourOfAKind($handCards)
    {
        $occurrences = self::getOccurrencesBy($handCards, 'number');
        foreach ($occurrences as $key => $count) {
            if ($count === 4) {
                return true;
            }
        }
        return false;
    }

    private static function checkFullHouse($handCards)
    {
        $occurrences = self::getOccurrencesBy($handCards, 'number');
        if (sizeof($occurrences) === 2 &&
            (($occurrences[0] === 3 && $occurrences[1] === 2) || ($occurrences[0] === 2 && $occurrences[1] === 3))) {
            return true;
        }
        return false;
    }

    private static function checkFlush($handCards)
    {
        $occurrences = self::getOccurrencesBy($handCards, 'suit');
        if ($occurrences[0] === 5) {
            return true;
        }
        return false;
    }

    private static function checkStraight($handIndex)
    {
        $handValues = array_values($handIndex['handIndex']);
        if (sizeof($handValues) === 5 && ($handValues[0] - $handValues[4] === 4)) { //consecutive cards
            return true;
        }
        return false;
    }

    private static function checkThreeOfAKind($handCards)
    {
        $occurrences = self::getOccurrencesBy($handCards, 'number');
        if (sizeof($occurrences) === 3 && $occurrences[0] === 3 && $occurrences[1] === 1 && $occurrences[2] === 1) {
            return true;
        }
        return false;
    }

    private static function checkTwoPairs($handCards)
    {
        $occurrences = self::getOccurrencesBy($handCards, 'number');
        if (sizeof($occurrences) === 3 && $occurrences[0] === 2 && $occurrences[1] === 2 && $occurrences[2] === 1) {
            return true;
        }
        return false;
    }

    private static function checkOnePair($handCards)
    {
        $occurrences = self::getOccurrencesBy($handCards, 'number');
        if (sizeof($occurrences) === 4 && $occurrences[0] === 2) {
            return true;
        }
        return false;
    }

    private static function checkHighCard($handCards)
    {
        $occurrences = self::getOccurrencesBy($handCards, 'number');
        if (sizeof($occurrences) === 5) {
            return true;
        }
        return false;
    }


    private static function getHandIndex($handCards)
    {
        $handIndex = array();
        foreach ($handCards as $index => $card) {
            $handIndex[$card['number']] = Config::get('cardScore.' . $card['number']);
        }
        arsort($handIndex);
        return $handScore = [
            'handIndex' => $handIndex,
            'score' => self::handNumberScore($handIndex)
        ];
    }

    private static function handNumberScore($handCards)
    {
        $score = 0;
        if (!empty($handCards)) {
            foreach ($handCards as $card => $cardValue) {
                $score += $cardValue;
            }
        }
        return $score;
    }

    private static function getOccurrencesBy($handCards, $filter)
    {
        $occurrences = array_values(array_count_values(
            array_column($handCards, $filter)
        ));
        rsort($occurrences);
        return $occurrences;
    }
}