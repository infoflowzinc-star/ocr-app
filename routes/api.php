use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SubAccountController;
use App\Http\Controllers\JournalTemplateController;

Route::apiResource('clients', ClientController::class);
Route::apiResource('accounts', AccountController::class);
Route::apiResource('sub-accounts', SubAccountController::class);
Route::apiResource('journal-templates', JournalTemplateController::class);
