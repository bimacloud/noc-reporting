<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceType;
use App\Models\Customer;
use App\Models\Reseller;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Ensure 'Reseller' ServiceType exists
        $resellerType = ServiceType::firstOrCreate(['name' => 'Reseller']);

        // 2. Move Resellers to Customers
        $resellers = DB::table('resellers')->get();

        foreach ($resellers as $reseller) {
            DB::table('customers')->insert([
                'name' => $reseller->name,
                'service_type_id' => $resellerType->id,
                'bandwidth' => $reseller->bandwidth,
                'address' => $reseller->address, // From previous change
                'status' => $reseller->status,
                'created_at' => $reseller->created_at,
                'updated_at' => $reseller->updated_at,
            ]);
        }

        // 3. Drop Resellers table
        Schema::dropIfExists('resellers');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate Resellers table
        Schema::create('resellers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('bandwidth');
            $table->text('address')->nullable();
            $table->enum('status', ['active', 'inactive', 'suspended'])->default('active');
            $table->timestamps();
        });

        // Get Reseller Service Type ID
        $resellerType = ServiceType::where('name', 'Reseller')->first();

        if ($resellerType) {
            // Move back customers with Reseller type to Resellers table
            $customers = DB::table('customers')->where('service_type_id', $resellerType->id)->get();

            foreach ($customers as $customer) {
                DB::table('resellers')->insert([
                    'name' => $customer->name,
                    'bandwidth' => $customer->bandwidth,
                    'address' => $customer->address,
                    'status' => $customer->status,
                    'created_at' => $customer->created_at,
                    'updated_at' => $customer->updated_at,
                ]);

                // Delete from customers
                DB::table('customers')->where('id', $customer->id)->delete();
            }
        }
    }
};
