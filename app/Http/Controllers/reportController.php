<?php

    namespace App\Http\Controllers;

    use App\Entity\Lift;

    class reportController extends Controller
    {
        public $report = [];
        private $liftCount = 0;
        private $floorCount = 0;
        private $steps = [];
        private $lifts;
        private $currentTime;

        /**
         * reportController constructor.
         */
        function __construct()
        {
            $config = (new \App\config)->getConfigs();
            $steps = (new \App\steps)->getReportable();

            $this->liftCount = $config['liftCount'];
            $this->floorCount = $config['floorCount'];

            $this->steps = $steps;
            $this->currentTime = strtotime('1 January 1970 00:00:00');
            return;
        }

        /**
         * Build a full report with database data
         * Basic Report, every step is individually and have no relation with others.
         *
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
         */
        public function fullReportBasic()
        {
            foreach ($this->steps as $step) {
                $this->currentTime = strtotime('1 January 1970 00:00:00');
                // Initialize Steps
                $report['step'] = $step;
                $this->lifts = [];
                for ($i = 0; $i < $this->liftCount; $i++) {
                    $this->lifts[$i] = new Lift();
                }
                // Flux
                $report['step']['next'] = $this->nextStepExecution($report['step']);
                $report['log'][] = $this->logReport($report);
                while ($this->currentTime <= 86400) {
                    $this->OneMinuteMore();
                    if ($this->currentTime >= $report['step']['next'] && $this->currentTime < $report['step']['endTime']) {
                        if (count($report['step']['startFloor']) > 1 && count($report['step']['endFloor']) == 1) {
                            foreach ($report['step']['startFloor'] as $stFl) {
                                ($this->lifts[$this->getBestLift($stFl)])->moveLift($stFl, $report['step']['endFloor'][0], $this->currentTime);
                            }
                        } elseif (count($report['step']['startFloor']) == 1 && count($report['step']['endFloor']) > 1) {
                            foreach ($report['step']['endFloor'] as $enFl) {
                                ($this->lifts[$this->getBestLift($report['step']['startFloor'][0])])->moveLift($report['step']['startFloor'][0], $enFl, $this->currentTime);
                            }
                        } elseif (count($report['step']['startFloor']) == 1 && count($report['step']['endFloor']) == 1) {
                            ($this->lifts[$this->getBestLift($report['step']['startFloor'][0])])->moveLift($report['step']['startFloor'][0], $report['step']['endFloor'][0], $this->currentTime);
                        } else {
                            echo 'Error';
                        }
                        $report['step']['last'] = $report['step']['next'];

                        // Send Report
                        $report['log'][] = $this->logReport($report);

                        $report['step']['next'] = $this->nextStepExecution($report['step']);
                    }
                };

                // End Flux
                $report['lifts'] = $this->lifts;
                $report['timeEnd'] = $this->currentTime;
                $this->report[] = $report;
            }
            return View('report/fullbasic', ['report' => $this->report]);
        }

        /**
         * @param array $step
         * @return int
         */
        private function nextStepExecution($step): int
        {
            return ($step['next'] == -1) ? $step['startTime'] : $step['last'] + $step['interval'];
        }

        /**
         * @param array $report
         * @return array
         */
        private function logReport(array $report): array
        {
            $elevators = [];

            foreach ($this->lifts as $liftId => $lift) {
                $elevators[$liftId] = [
                    'lastMove' => $lift->lastMoveTime,
                    'initPosition' => ($lift->lastMoveTime == $this->currentTime) ? $lift->lastPosition : $lift->location,
                    'endPosition' => $lift->location,
                    'MoveNow' => ($lift->lastMoveTime == $this->currentTime) ? $lift->lastMoveCount : 0,
                    'MoveCount' => $lift->count];
            }

            return ['currentTime' => $this->currentTime, 'lifts' => $elevators];
        }

        /**
         * Move the currentTime one minute more :D
         */
        private function OneMinuteMore(): void
        {
            $this->currentTime = strtotime('+1 minutes', $this->currentTime);
        }

        /**
         * Search the best lift to use that have the lowest distance with our current lift
         * @param int $startFloor
         * @return int
         */
        private function getBestLift(int $startFloor): int
        {
            $bestCurrently = ['liftId' => -1, 'distance' => 999, 'count' => 999];
            foreach ($this->lifts as $singleId => $singleLift) {
                $distance = abs($singleLift->location - $startFloor);
                if ($distance < $bestCurrently['distance'] &&
                    $singleLift->count < $bestCurrently['count'] &&
                    $this->currentTime != $singleLift->lastMoveTime) {
                    $bestCurrently['liftId'] = $singleId;
                    $bestCurrently['distance'] = $distance;
                    $bestCurrently['count'] = $singleLift->count;
                }
            }
            return $bestCurrently['liftId'];
        }
    }
