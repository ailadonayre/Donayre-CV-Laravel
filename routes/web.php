use Illuminate\Support\Facades\Auth;

Route::get('/who', function () {
    return dd(Auth::user());
});
