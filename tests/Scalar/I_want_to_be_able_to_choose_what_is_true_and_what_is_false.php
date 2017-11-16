<?php

namespace Stratadox\Hydration\Test\Unit\Mapping;

use PHPUnit\Framework\TestCase;
use Stratadox\Hydration\Mapping\Property\Scalar\BooleanValue;
use Stratadox\Hydration\UnmappableInput;

/**
 * Don't we all?
 */
class I_want_to_be_able_to_choose_what_is_true_and_what_is_false extends TestCase
{
    /**
     * @param mixed $true
     * @param array $truths
     * @param array $falsehoods
     * @scenario
     * @dataProvider trueValues
     */
    function this_is_now_truth($true, array $truths, array $falsehoods)
    {
        $source = ['boolean' => $true];

        $map = BooleanValue::withCustomTruth('boolean', $truths, $falsehoods);

        $this->assertSame(true, $map->value($source));
    }

    /**
     * @param mixed $itsFakeBelieveMe
     * @param array $truths
     * @param array $falsehoods
     * @scenario
     * @dataProvider falseValues
     */
    function fake_news($itsFakeBelieveMe, array $truths, array $falsehoods)
    {
        $source = ['boolean' => $itsFakeBelieveMe];

        $map = BooleanValue::withCustomTruth('boolean', $truths, $falsehoods);

        $this->assertSame(false, $map->value($source));
    }

    /**
     * @param mixed $itsNotBoolean
     * @param array $truths
     * @param array $falsehoods
     * @scenario
     * @dataProvider unacceptableValues
     */
    function unacceptable_input_throws_an_exception($itsNotBoolean, array $truths, array $falsehoods)
    {
        $source = ['boolean' => $itsNotBoolean];

        $map = BooleanValue::withCustomTruth('boolean', $truths, $falsehoods);

        $this->expectException(UnmappableInput::class);
        $map->value($source);
    }

    public function trueValues()
    {
        $truths = ['great', 'ok!', 'yeah', 'why not?'];
        $falsehoods = ['nah', 'no way', 'nope'];
        return [
            '"great"' => ['great', $truths, $falsehoods],
            '"ok!"' => ['ok!', $truths, $falsehoods],
            '"OK!"' => ['OK!', $truths, $falsehoods],
            '"yeah"' => ['yeah', $truths, $falsehoods],
            '"why not?"' => ['why not?', $truths, $falsehoods],
            '"1"' => ['1', $truths, $falsehoods],
        ];
    }

    public function falseValues()
    {
        $truths = ['what', 'is', 'truth', 'anyway', '?'];
        $falsehoods = ['cnn', 'russia', 'nah'];
        return [
            'CNN' => ['CNN', $truths, $falsehoods],
            'Russia' => ['Russia', $truths, $falsehoods],
            'nah' => ['nah', $truths, $falsehoods],
            '"0"' => ['0', $truths, $falsehoods],
        ];
    }

    public function unacceptableValues()
    {
        $truths = ['great', 'ok!', 'yeah', 'why not?'];
        $falsehoods = ['nah', 'no way', 'nope'];
        return [
            '"true"' => ['true', $truths, $falsehoods],
            '"false"' => ['false', $truths, $falsehoods],
            '"maybe"' => ['maybe', $truths, $falsehoods],
        ];
    }
}
