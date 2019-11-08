<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class steps extends Model
    {
        /**
         * @return array
         */
        public function getReportable(): array
        {
            $eval = [];
            $steps = self::getAllSteps();
            foreach ($steps as $step) {

                $eval[] = [
                    'next' => -1,
                    'last' => -1,
                    'interval' => $step->minuteInterval * 60,
                    'startTime' => strtotime('1 January 1970 ' . $step->startTime),
                    'endTime' => strtotime('1 January 1970 ' . $step->endTime),
                    'startFloor' => json_decode($step->startFloor),
                    'endFloor' => json_decode($step->endFloor)
                ];
            }
            return $eval;
        }

        public function getAllSteps()
        {
            return steps::all();
        }
    }
