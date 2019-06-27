<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Schema;
use Log;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();
         if(Schema::hasTable('settings'))
         {
                $send_email_time = getSettingValueByKey('send_email_time');
                if(!empty($send_email_time))
                {
                    $schedule->call(function () {
                        #邮箱提醒
                        app('zcjy')->CourseJoinRepo()->sendEmailAttach();
                    })->daily()->at($send_email_time);
                }
                #发送上课通知任务
                // $send_course_time = getSettingValueByKey('send_course_time');
                // if(!empty($send_course_time))
                // {
                //     $schedule->call(function () {
                //         #提前一天的上课通知
                //         app('zcjy')->willStartCourseInform();
                //     })->daily()->at($send_course_time);
                // }
         }

         #处理一下课程的开放状态
         $schedule->call(function () {
            app('zcjy')->CourseRepo()->autoCloseOpenStatus();
         })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
