<?php

namespace Tests\Feature;

use App\Models\MockPrice;
use App\Models\User;
use App\Models\UserSubscription;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginFreePlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_assigns_free_plan_when_user_has_no_active_plan(): void
    {
        $user = User::create([
            'first_name' => 'Ada',
            'last_name' => 'Lovelace',
            'email' => 'ada@example.com',
            'password' => Hash::make('password'),
        ]);

        $freePlan = MockPrice::create([
            'name' => 'Free',
            'price' => 0,
            'currency' => 'NGN',
            'duration' => null,
            'status' => 'active',
            'order' => 1,
        ]);

        $response = $this->post('/login', [
            'email' => 'ada@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $user->id,
            'plan_id' => $freePlan->id,
            'status' => 'active',
        ]);
    }

    public function test_login_does_not_replace_existing_active_plan(): void
    {
        $user = User::create([
            'first_name' => 'Grace',
            'last_name' => 'Hopper',
            'email' => 'grace@example.com',
            'password' => Hash::make('password'),
        ]);

        $freePlan = MockPrice::create([
            'name' => 'Free',
            'price' => 0,
            'currency' => 'NGN',
            'duration' => null,
            'status' => 'active',
            'order' => 1,
        ]);

        $paidPlan = MockPrice::create([
            'name' => 'Premium',
            'price' => 5000,
            'currency' => 'NGN',
            'duration' => 30,
            'status' => 'active',
            'order' => 2,
        ]);

        UserSubscription::create([
            'user_id' => $user->id,
            'plan_id' => $paidPlan->id,
            'start_date' => now(),
            'expiry_date' => now()->addDays(30),
            'status' => 'active',
        ]);

        $response = $this->post('/login', [
            'email' => 'grace@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticatedAs($user);
        $this->assertDatabaseMissing('user_subscriptions', [
            'user_id' => $user->id,
            'plan_id' => $freePlan->id,
        ]);
        $this->assertDatabaseHas('user_subscriptions', [
            'user_id' => $user->id,
            'plan_id' => $paidPlan->id,
            'status' => 'active',
        ]);
    }
}
