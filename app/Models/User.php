<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * الخصائص القابلة للملء في قاعدة البيانات
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username', // اسم المستخدم الفريد
        'email',
        'password',
        'role', // دور المستخدم: admin أو user
    ];

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
     * علاقة المستخدم مع طلباته
     * المستخدم يمكن أن يكون له عدة طلبات
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
