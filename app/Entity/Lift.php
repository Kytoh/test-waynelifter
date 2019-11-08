<?php

    namespace App\Entity;

    class Lift
    {
        public $location;
        public $count;

        function __construct()
        {
            $this->location = 0;
            $this->lastPosition = 0;
            $this->count = 0;
            $this->lastMoveTime = -1;
            $this->lastMoveCount = -1;
        }

        /**
         * Calculate how many floors a lift moves between its current position, the call and the finish line
         * @param int $call
         * @param int $end
         * @param string $time
         * @return void
         */
        public function moveLift(int $call, int $end, string $time): void
        {
            $this->count += abs($this->location - $call) + abs($call - $end);
            $this->lastPosition = $this->location;
            $this->lastMoveCount = abs($this->location - $call) + abs($call - $end);;
            $this->location = $end;
            $this->lastMoveTime = $time;
        }

    }
