<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Services\User\ForgotPasswordService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;

class ForgotPasswordServiceTest extends TestCase {

    use DatabaseTransactions;

    /**
     * @var ForgotPasswordService
     */
    protected $forgotPasswordService;

    protected function setUp() : void
    {
        parent::setUp();

        $this->forgotPasswordService = $this->app->make(ForgotPasswordService::class);
    }

    public function testResetPasswordShouldFailIfUserDoesntExists()
    {
        $this->expectException(ModelNotFoundException::Class);
        $this->forgotPasswordService->resetPassword('token', 'newPassword');
    }

    public function testResetPasswordShouldChangeUsersPassword()
    {
        factory(User::class)->create([
            'forgot_password_token' => 'token',
            'forgot_password_date' => Carbon::now()
        ]);
        $user = $this->forgotPasswordService->resetPassword('token', 'newPassword');

        $this->assertTrue(\Hash::check('newPassword', $user->password));
        $this->assertNull($user->forgot_password_token);
        $this->assertNull($user->forgot_password_date);
    }
}
