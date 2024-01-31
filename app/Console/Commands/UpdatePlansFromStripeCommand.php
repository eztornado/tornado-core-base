<?php

namespace App\Console\Commands;

use App\Models\Payments\CreditsPlan;
use Illuminate\Console\Command;
use Laravel\Cashier\Cashier;
class UpdatePlansFromStripeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-plans-from-stripe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $products = Cashier::stripe()->products->all();
        foreach($products as $pr) {
            $product = json_decode($pr->toJSON());
            $product_price = json_decode(Cashier::stripe()->prices->retrieve($product->default_price)->toJSON());

            //Actualizar Planes de Créditos
            $plan = CreditsPlan::where('stripe_product',$product->default_price)->first();
            if(!is_null($plan)) {
                $plan->price = doubleval($product_price->unit_amount/100);
                $plan->name = $product->name;
                $plan->save();
            }

            //TODO Actualziar cualquier otro tipo de plan futuro

        }
    }

}
