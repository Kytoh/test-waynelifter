<?php

    namespace App\Http\Controllers;

    use Illuminate\Contracts\View\View;
    use Illuminate\Http\Request;

    class configController extends Controller
    {
        /**
         * Vista que genera el código para ver la configuración
         * @return View
         */
        public function index(): View
        {
            $config = (new \App\config)->getConfigs();
            $steps = \App\steps::all();
            return View('config/index', ['config' => $config, 'steps' => $steps]);
        }

        /**
         * Función que actualiza la base de datos de steps y config
         * @param Request $request
         * @return string
         */
        public function update(Request $request): string
        {
            // Here would be great to do some verification, ya?
            (new \App\config)->updateSomething('liftCount', $request->input('liftCount'));
            (new \App\config)->updateSomething('floorCount', $request->input('floorCount'));
            $stepsList = $request->input('steps');

            foreach ($stepsList as $step) {
                \App\steps::updateSteps($step);
            }
            return 'ok';
        }

        /**
         * @param Request $request
         * @return string
         */
        public function delete(Request $request): string
        {
            \App\steps::deleteStep($request->input('id'));
            return 'ok';
        }
    }
