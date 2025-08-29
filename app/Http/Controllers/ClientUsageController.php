use Illuminate\Support\Facades\Auth;
use App\Models\OfficeUsage;
use Inertia\Inertia;

public function index()
{
    $clientId = Auth::guard('client')->id();

    $usages = OfficeUsage::with('office')
        ->where('client_user_id', $clientId)
        ->where('billing_status', '支払済')
        ->orderByDesc('yyyymm')
        ->get();

    return Inertia::render('Client/Dashboard', [
        'usages' => $usages,
    ]);
}
