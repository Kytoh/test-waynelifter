<?php

    namespace App\Http\Controllers;

    use App\Entity\Lift;

    /**
     * Controlador de Reportes. Muahahaha
     * Class reportController
     * @package App\Http\Controllers
     */
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
         * Build a basicReport with database data
         * Basic Report, every step is individually and have no relation with others.
         *
         * @return \Illuminate\View\View
         */
        public function basicReport(): \Illuminate\View\View
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
                $report['log'][] = $this->logReport();
                while ($this->currentTime <= 86400) {
                    $this->OneMore();
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
                        $report['log'][] = $this->logReport();

                        $report['step']['next'] = $this->nextStepExecution($report['step']);
                    }
                };

                // End Flux
                $report['lifts'] = $this->lifts;
                $report['timeEnd'] = $this->currentTime;
                $this->report[] = $report;
            }
            return View('report/basic', ['report' => $this->report]);
        }

        /**
         * @return \Illuminate\View\View
         */
        public function fullReport(): \Illuminate\View\View
        {
            $stepsCount = count($this->steps) - 1; // Index 0
            $this->currentTime = strtotime('1 January 1970 00:00:00');
            // Initialize Elevators
            $this->lifts = [];
            for ($i = 0; $i < $this->liftCount; $i++) {
                $this->lifts[$i] = new Lift();
            }

            // Report the elevators at 00:00, to know where they are
            //$this->report[] = $this->logReport();

            // Initialize next Time to prepare to loop
            for ($i = 0; $i <= $stepsCount; $i++) {
                $this->steps[$i]['next'] = $this->nextStepExecution($this->steps[$i]);
            }

            while ($this->currentTime < 86400) {
                $this->OneMore();

                for ($i = 0; $i <= $stepsCount; $i++) {

                    if ($this->currentTime >= $this->steps[$i]['next'] && $this->currentTime < $this->steps[$i]['endTime']) {
                        if (count($this->steps[$i]['startFloor']) > 1 && count($this->steps[$i]['endFloor']) == 1) {
                            foreach ($this->steps[$i]['startFloor'] as $stFl) {
                                ($this->lifts[$this->getBestLift($stFl)])
                                    ->moveLift($stFl, $this->steps[$i]['endFloor'][0], $this->currentTime);
                            }
                        } elseif (count($this->steps[$i]['startFloor']) == 1 && count($this->steps[$i]['endFloor']) > 1) {
                            foreach ($this->steps[$i]['endFloor'] as $enFl) {
                                ($this->lifts[$this->getBestLift($this->steps[$i]['startFloor'][0])])
                                    ->moveLift($this->steps[$i]['startFloor'][0], $enFl, $this->currentTime);
                            }
                        } elseif (count($this->steps[$i]['startFloor']) == 1 && count($this->steps[$i]['endFloor']) == 1) {
                            ($this->lifts[$this->getBestLift($this->steps[$i]['startFloor'][0])])
                                ->moveLift($this->steps[$i]['startFloor'][0], $this->steps[$i]['endFloor'][0], $this->currentTime);
                        } else {
                            echo 'Error';
                        }

                        $this->steps[$i]['last'] = $this->steps[$i]['next'];

                        // Send Report
                        $this->report[] = $this->logReport();

                        $this->steps[$i]['next'] = $this->nextStepExecution($this->steps[$i]);
                    }
                }
            }
            return View('report/full', ['report' => $this->report]);
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
         * @return array
         */
        private function logReport(): array
        {
            $elevators = [];

            foreach ($this->lifts as $liftId => $lift) {
                $elevators[$liftId] = [
                    'lastMove' => $lift->lastMoveTime,
                    'initPosition' => ($lift->lastMoveTime == $this->currentTime) ? $lift->lastPosition : $lift->location,
                    'callPosition' => $lift->lastCall,
                    'endPosition' => $lift->location,
                    'MoveNow' => ($lift->lastMoveTime == $this->currentTime) ? $lift->lastMoveCount : 0,
                    'MoveCount' => $lift->count];
            }
            return ['currentTime' => $this->currentTime, 'lifts' => $elevators];
        }

        /**
         * Move the currentTime one minute more :D
         */
        private function OneMore($data = '+1 minutes'): void
        {
            $this->currentTime = strtotime($data, $this->currentTime);
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
                if ($distance <= $bestCurrently['distance'] &&
                    $singleLift->count < $bestCurrently['count'] &&
                    $this->currentTime != $singleLift->lastMoveTime) {
                    $bestCurrently['liftId'] = $singleId;
                    $bestCurrently['distance'] = $distance;
                    $bestCurrently['count'] = $singleLift->count;
                }
            }
            if($bestCurrently['liftId'] == -1 && $startFloor != 999){
                $this->checkCollision();
                $bestCurrently['liftId'] = $this->getBestLift($startFloor);
            }
            return $bestCurrently['liftId'];
        }

        /**
         * Check if currently all the lifts are occuped for the current time. If are occuped, people will must wait 30 secs till at least one elevator is free
         */
        private function checkCollision() : void
        {
            if ($this->getBestLift(999) == -1) {
                $this->OneMore('+30 seconds');
            }
        }
    }
