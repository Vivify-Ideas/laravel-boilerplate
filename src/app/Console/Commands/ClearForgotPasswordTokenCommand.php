<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User\User;
use Carbon\Carbon;

class ClearForgotPasswordTokenCommand extends Command {
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'forgot-password:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the expired forgot password token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $expiredTokenDate = Carbon::now()->subHours(24);
        User::whereDate('forgot_password_date', '>=', $expiredTokenDate)
            ->update([
                'forgot_password_token' => null,
                'forgot_password_date' => null
            ]);
    }
}
