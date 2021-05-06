<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dna extends Model
{
    use HasFactory;

    /**
     * The name of the table for the model
     *
     * @var string
     */
    protected $table = 'dna_codes';

    /**
     * Fields that could be filled in bulk update in the model
     *
     * @var array
     */
    protected $fillable = [
        'code',
    ];

    /**
     * Method to convert the dna code and save in the database
     *
     * @param  array  $dnaCode
     * @return \App\Models\Dna
     */
    public static function saveByCode(array $dnaCode)
    {
        $dnaCode = implode(',', $dnaCode);

        return self::firstOrCreate([
            'code' => $dnaCode,
        ]);
    }

    /**
     * Accessor Method to return if the dna code has mutations
     *
     * @return bool
     */
    public function getHasMutationsAttribute()
    {
        // If the current code already has a captured number
        // of mutations in the dabatase returns true
        if (! is_null($this->mutations))
            return true;

        // Define and save in the database if the code has mutations
        $this->mutations = $this->checkForMutations();
        $this->save();

        return !! $this->mutations;
    }

    /**
     * Method to check the dna code for mutations
     *
     * @return int
     */
    public function checkForMutations()
    {
        // Convert the saved string code into a multidimensions array
        $codeArray = (collect(explode(',', $this->code))
            ->map(function ($value, $key) {
                return str_split($value);
            }))->toArray();

        $mutations = 0;

        foreach ($codeArray as $y => $row) {
            foreach ($row as $x => $char) {
                $rightRange = ($x + $this->mutationLength - 1) <= $this->sequenceIndexXLimit;
                $bottomRange = ($y + $this->mutationLength - 1) <= $this->sequenceIndexYLimit;
                $leftRange = $x >= $this->mutationLength - 1;

                // Check for Right horizontal
                if ($rightRange) {
                    $mutations += $this->checkSequence($codeArray, $x, $y, 1, 0);
                }

                // Check for Right oblique
                if ($rightRange && $bottomRange) {
                    $mutations += $this->checkSequence($codeArray, $x, $y, 1, 1);
                }

                // Check for bottom
                if ($bottomRange) {
                    $mutations += $this->checkSequence($codeArray, $x, $y, 0, 1);
                }

                // Check for left oblique
                if ($leftRange && $bottomRange) {
                    $mutations += $this->checkSequence($codeArray, $x, $y, -1, 1);
                }
            }
        }

        return $mutations;
    }

    /**
     * Check a single sequence for the given parameters
     *
     * @param  array  $codeArray
     * @param  int  $x
     * @param  int  $y
     * @param  int  $xSum
     * @param  int  $ySum
     * @return int
     */
    protected function checkSequence($codeArray, $x, $y, $xSum = 1, $ySum = 0): int
    {
        // Prevent for stuck on the method
        if ($xSum == 0 && $ySum == 0) {
            return 0;
        }

        $char = $codeArray[$y][$x];
        $equals = 1;
        $tempX = $x + $xSum;
        $tempY = $y + $ySum;
        $mutations = 0;

        do {
            if ($char != $codeArray[$tempY][$tempX]) break;

            $equals++;
            $tempX = $tempX + $xSum;
            $tempY = $tempY + $ySum;

            if ($equals == $this->mutationLength) {
                $mutations++;
                break;
            }
        }while(
            $tempX <= $this->sequenceIndexXLimit &&
            $tempY <= $this->sequenceIndexYLimit &&
            $tempX >= 0 && $tempY >= 0
        );

        return $mutations;
    }

    /**
     * Return the last permitted index for the X value
     *
     * @return int
     */
    public function getSequenceIndexXLimitAttribute()
    {
        return config('dna.sequence_length') - 1;
    }

    /**
     * Return the last permitted index for the Y value
     *
     * @return int
     */
    public function getSequenceIndexYLimitAttribute()
    {
        return config('dna.sequence_height') - 1;
    }

    /**
     * Return the expected length to consider a mutation sequence
     *
     * @return int
     */
    public function getMutationLengthAttribute()
    {
        return config('dna.mutation_length');
    }
}
