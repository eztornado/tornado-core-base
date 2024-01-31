<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Core\Controller;
use App\Models\Core\Setting;
use App\Models\Core\User;
use App\Models\Payments\CreditsPlan;
use App\Models\Payments\UserBillable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Exception;

class PaymentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['checkoutOk', 'checkoutKo']);
    }

    public function getPlans() {
        return CreditsPlan::all();
    }

    public function billingPortal(Request $request) {
        if(!is_null(\Illuminate\Support\Facades\Auth::user())) {
            $user = Auth::user();
            $user = UserBillable::find($user->id);
            return $user->billingPortalUrl();
        } else {
            return 'User not logged';
        }
    }

    public function buy(Request $request) {
        if(!is_null(\Illuminate\Support\Facades\Auth::user())) {
            try {
                $user = Auth::user();
                $user = UserBillable::find($user->id);
                $stripeCustomer = $user->createOrGetStripeCustomer();
                $stripeCustomer = $user->updateStripeCustomer(['email' => $user->email]);
                $user->syncStripeCustomerDetails();
            }catch (Exception $e) {
            }
            if(!is_null($request->query->get('product'))) {
                return response($user->checkout($request->query->get('product'),
                    [ 'success_url' => route('checkout-success')."?session_id={CHECKOUT_SESSION_ID}&user_id=".$user->id,
                        'cancel_url' => route('checkout-cancel')."?session_id={CHECKOUT_SESSION_ID}&user_id=".$user->id,
                        'invoice_creation' => ['enabled' => true],
                        'allow_promotion_codes' => true])
                    ->toJson());
            }
        } else {
            return 'User not logged';
        }
    }

    public function checkoutOk(Request $request) {
        $redirect_url = $this->getRedirectUrl();
        // IMPLEMENTAR LÓGICA CONCRETA PROYECTO EXTENDIENDO ESTE MÉTODO PARA VALIDAR PAGO
        // Y EFECUTAR LOS CAMBIOS NECESARIOS AL USUARIO
        return redirect($redirect_url.'?status=checkout_ok');
    }

    public function checkoutKo(Request $request) {
        $redirect_url = $this->getRedirectUrl();
        return redirect($redirect_url.'?status=checkout_ko');
    }

    protected function getRedirectUrl() {
        $redirect_url = Setting::where('key','PAYMENT_SYSTEM_RETURN_URL')->first();
        if(!is_null($redirect_url)) {
            $redirect_url = $redirect_url->value;
        } else {
            $redirect_url = "";
        }
        return $redirect_url;
    }
}
