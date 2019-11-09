<?php

    namespace App\Http\Controllers;

    use App\Entity\Lift;
    use Mockery\Exception;

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
            $this->steps = (new \App\steps)->getReportable();

            $this->liftCount = $config['liftCount'];
            $this->floorCount = $config['floorCount'];

            $this->currentTime = strtotime('1 January 1970 00:00:00');
        }

        /**
         * Build a basicReport with database data
         * Basic Report, every step is individually and have no relation with others.
         * @deprecated
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
            // Variable Setters
            $stepsCount = count($this->steps) - 1; // Index 0
            $this->currentTime = strtotime('1 January 1970 00:00:00');
            $this->lifts = [];

            // Initialize Elevators
            for ($i = 0; $i < $this->liftCount; $i++) {
                $this->lifts[$i] = new Lift();
            }
            // Initialize next Time on Steps to prepare to loop
            for ($i = 0; $i <= $stepsCount; $i++) {
                $this->steps[$i]['next'] = $this->nextStepExecution($this->steps[$i]);
            }

            // Welcome to the Loop
            while ($this->currentTime < 86400) {
                // Iterate on time
                $this->OneMore();

                // Check every stepcount. To reduce number of loops, a mid-array could be created to manage the "next" time instead of doing loops
                for ($i = 0; $i <= $stepsCount; $i++) {
                    // Every Iteration, we check if someone should have pressed the call button to the elevator. On real world, this would be an API...
                    if ($this->currentTime >= $this->steps[$i]['next'] && $this->currentTime < $this->steps[$i]['endTime']) {
                        // We can have three request
                        if (count($this->steps[$i]['startFloor']) > 1 && count($this->steps[$i]['endFloor']) == 1) {
                            // n floors to 1 floor
                            foreach ($this->steps[$i]['startFloor'] as $stFl) {
                                ($this->lifts[$this->getBestLift($stFl)])
                                    ->moveLift($stFl, $this->steps[$i]['endFloor'][0], $this->currentTime);
                            }
                        } elseif (count($this->steps[$i]['startFloor']) == 1 && count($this->steps[$i]['endFloor']) > 1) {
                            // 1 floor to n floors
                            foreach ($this->steps[$i]['endFloor'] as $enFl) {
                                ($this->lifts[$this->getBestLift($this->steps[$i]['startFloor'][0])])
                                    ->moveLift($this->steps[$i]['startFloor'][0], $enFl, $this->currentTime);
                            }
                        } elseif (count($this->steps[$i]['startFloor']) == 1 && count($this->steps[$i]['endFloor']) == 1) {
                            // or 1 floor to 1 floor
                            ($this->lifts[$this->getBestLift($this->steps[$i]['startFloor'][0])])
                                ->moveLift($this->steps[$i]['startFloor'][0], $this->steps[$i]['endFloor'][0], $this->currentTime);
                        } else {
                            // And of course, an error else that would never happen
                            throw new Exception('Kaboom');
                        }
                        // Lets log the movement.
                        $this->steps[$i]['last'] = $this->steps[$i]['next'];
                        $this->report[] = $this->logReport();

                        // And calculate when the next execution will be done
                        $this->steps[$i]['next'] = $this->nextStepExecution($this->steps[$i]);
                    }
                }
            }
            // Send to view
            return View('report/full', ['report' => $this->report]);
        }

        /**
         *  Calculate next execution time. If there is no execution done, set the Start Time
         * @param array $step
         * @return int
         */
        private function nextStepExecution($step): int
        {
            return ($step['next'] == -1) ? $step['startTime'] : $step['last'] + $step['interval'];
        }

        /**
         * Lets Log any movement.
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
         * Move the currentTime one minute more or whatever user sends. :D
         * @param string $data
         */
        private function OneMore($data = '+1 minutes'): void
        {
            $this->currentTime = strtotime($data, $this->currentTime);
        }

        /**
         * Search the best lift to use that have the lowest distance with our current lift.
         * Would try to use the less used lift too.
         * FE. If two lifts have the same distance to the start point, will use the elevator that has been used less until that moment
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

            // Cause we are doing a nested call, lets filter the first call
            if ($bestCurrently['liftId'] == -1 && $startFloor != 999) {
                $this->checkCollision();
                $bestCurrently['liftId'] = $this->getBestLift($startFloor);
            }
            return $bestCurrently['liftId'];
        }

        /**
         * Check if currently all the lifts are occupied for the current time.
         * If all have been used that moment, people will a 30 secs compulsary delay until at least one elevator is free (well, all elevators will be free)
         */
        private function checkCollision(): void
        {
            if ($this->getBestLift(999) == -1) {
                $this->OneMore('+30 seconds');
            }
        }
    }
