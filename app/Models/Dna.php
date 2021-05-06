<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dna extends Model
{
    use HasFactory;

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
                $rightRange = ($x+3) <= 5;
                $bottomRange = ($y+3) <= 5;
                $leftRange = $x >= 3;

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

    protected function checkSequence($codeArray, $x, $y, $xSum = 1, $ySum = 0)
    {
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

            if ($equals == 4) {
                $mutations++;
                break;
            }
        }while($tempX <= 5 && $tempX >= 0 && $tempY >= 0 && $tempY <= 5);

        return $mutations;
    }
}
