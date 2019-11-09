<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    /**
     * Class steps
     * @package App
     */
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

        /**
         * @return steps[]|\Illuminate\Database\Eloquent\Collection
         */
        public function getAllSteps()
        {
            return steps::all();
        }

        /**
         * @param $stepList
         */
        public static function updateSteps($stepList)
        {
            if (isset($stepList['id']) && $stepList['id'] != null) {
                $step = steps::find($stepList['id']);
            } else {
                $step = new steps;
            }
            $step->minuteInterval = $stepList['minuteInterval'];
            $step->startTime = $stepList['startTime'];
            $step->endTime = $stepList['endTime'];
            $step->startFloor = $stepList['startFloor'];
            $step->endFloor = $stepList['endFloor'];
            $step->save();
        }

        /**
         * @param $stepId
         */
        public static function deleteStep($stepId): void
        {
            steps::destroy($stepId);
        }
    }
