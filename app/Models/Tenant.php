<?php

namespace App\Models;

use App\Enums\TenantStatus;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
        use HasDatabase, HasDomains;

        /**
         * The attributes that are mass assignable.
         *
         * @var array<int, string>
         */
        protected $fillable = [
                'id',
                'name',
                'status',
        ];

        /**
         * The attributes that should be cast.
         *
         * @var array<string, string>
         */
        protected $casts = [
                'status' => TenantStatus::class,
        ];

        /**
         * Mark the tenant as active after payment confirmation.
         */
        public function activate(): void
        {
                $this->update(['status' => TenantStatus::ACTIVE]);
        }
}
