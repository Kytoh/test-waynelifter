<?php

    namespace App;

    use Illuminate\Database\Eloquent\Model;

    class config extends Model
    {
        private $defaultData = ['floorCount', 'liftCount'];

        public function getConfigs(): array
        {
            $curr = config::whereIn('name', $this->defaultData)->get();
            $ret = [];
            for ($i = 0; $i < count($this->defaultData); $i++) {
                $ret[$curr[$i]->attributes['name']] = $curr[$i]->attributes['value'];
            }
            return $ret;
        }
    }
