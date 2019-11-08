<?php

    namespace App\Http\Controllers;

    use App\steps;
    use Illuminate\Contracts\View\View;

    class configController extends Controller
    {
        /**
         * Vista que genera el código para ver la configuración
         * @return View
         */
        public function index(): View
        {
            $config = (new \App\config)->getConfigs();
            $steps = steps::all();
            return View('config/index', ['config' => $config, 'steps' => $steps]);
        }

        /**
         * Función que actualiza la base de datos de steps y config
         * @return array
         */
        public function update(): array
        {
            return [];
        }
    }
