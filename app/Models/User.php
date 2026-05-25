<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'telephone',
        'email',
        'checkbox',
        'password',
        'google_id',
        'avatar',
    ];


    
    // In App\Models\User.php
public function badges()
{
    return $this->belongsToMany(Badge::class, 'user_badges')
                ->withPivot('earned_at', 'metadata')
                ->withTimestamps();
}

public function hasBadge($badgeId)
{
    return $this->badges()->where('badges.id', $badgeId)->exists();
}

public function awardBadge($badgeId, $metadata = null)
{
    if (!$this->hasBadge($badgeId)) {
        $this->badges()->attach($badgeId, [
            'metadata' => $metadata ? json_encode($metadata) : null
        ]);
        return true;
    }
    return false;
}
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    // In User.php model


    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    
    public function userinformation(){
        return $this->belongsTo(UserInformation::class,'user_id');
    }
    public function courses()
{
    return $this->belongsToMany(Course::class, 'programme_id');
}
public function tests()
{
    return $this->hasMany(Test::class, 'user_id');
}

public function purchasedSummaryPdfs()
{
    return $this->belongsToMany(SummaryPdf::class, 'summary_pdf_users')
                ->using(SummaryPdfUser::class)
                ->withTimestamps();
}

public function userpayment() {
    return $this->hasMany(UserPayment::class);
}



public function pdfPayments() {
    return $this->hasMany(UserPdfPayment::class, 'user_id');
}
 
public function info() {
    return $this->hasOne(UserInformation::class, 'user_id');
}



public function tmaCarts() {
    return $this->hasMany(TmaCart::class, 'user_id');
}

public function test() {
    return $this->hasMany(Test::class, 'user_id');
}

public function summaryPdfs() {
    return $this->hasMany(SummaryPdfUser::class, 'user_id');
}
 

public function plan() {
    return $this->belongsTo(Plan::class, 'plan_id');
}

public function messagesSent() {
    return $this->hasMany(Message::class, 'sender_id');
}

public function messagesReceived() {
    return $this->hasMany(Message::class, 'receiver_id');
}

// ✅ Correct — user_id is on user_subscriptions, not users
public function userSub() {
    return $this->hasOne(UserSubscription::class, 'user_id');
}





public function wallet()
{
    return $this->hasOne(StudentWallet::class, 'user_id')->withDefault([
        'balance' => 0
    ]);
}
}