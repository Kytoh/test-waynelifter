<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Support\Facades\DB;

    class PopulateFields extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            DB::table('configs')->insert(
                [
                    [
                        'name' => 'floorCount',
                        'value' => 3
                    ], [
                    'name' => 'liftCount',
                    'value' => 3
                ]
                ]
            );

            DB::table('steps')->insert([
                [
                    'minuteInterval' => 5,
                    'startTime' => '090000',
                    'endtime' => '110000',
                    'startFloor' => '[0]',
                    'endFloor' => '[2]',
                    'created_at' => DB::raw('CURRENT_TIMESTAMP')
                ],
                [
                    'minuteInterval' => 5,
                    'startTime' => '090000',
                    'endtime' => '110000',
                    'startFloor' => '[0]',
                    'endFloor' => '[3]',
                    'created_at' => DB::raw('CURRENT_TIMESTAMP')
                ],
                [
                    'minuteInterval' => 10,
                    'startTime' => '090000',
                    'endtime' => '100000',
                    'startFloor' => '[0]',
                    'endFloor' => '[1]',
                    'created_at' => DB::raw('CURRENT_TIMESTAMP')
                ],
                [
                    'minuteInterval' => 20,
                    'startTime' => '110000',
                    'endtime' => '182000',
                    'startFloor' => '[0]',
                    'endFloor' => '[1,2,3]',
                    'created_at' => DB::raw('CURRENT_TIMESTAMP')
                ],
                [
                    'minuteInterval' => 4,
                    'startTime' => '140000',
                    'endtime' => '150000',
                    'startFloor' => '[1,2,3]',
                    'endFloor' => '[0]',
                    'created_at' => DB::raw('CURRENT_TIMESTAMP')
                ],
                [
                    'minuteInterval' => 7,
                    'startTime' => '150000',
                    'endtime' => '160000',
                    'startFloor' => '[2,3]',
                    'endFloor' => '[0]',
                    'created_at' => DB::raw('CURRENT_TIMESTAMP')
                ],
                [
                    'minuteInterval' => 7,
                    'startTime' => '150000',
                    'endtime' => '160000',
                    'startFloor' => '[0]',
                    'endFloor' => '[1,3]',
                    'created_at' => DB::raw('CURRENT_TIMESTAMP')
                ],
                [
                    'minuteInterval' => 3,
                    'startTime' => '180000',
                    'endtime' => '200000',
                    'startFloor' => '[1,2,3]',
                    'endFloor' => '[0]',
                    'created_at' => DB::raw('CURRENT_TIMESTAMP')
                ],
            ]);
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            //
        }
    }
