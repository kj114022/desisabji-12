<?php namespace App\Http\Middleware;

use App\Repositories\Eloquent\UploadRepository;
use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Log;

class App
{

    /**
     * The availables languages.
     *
     * @array $languages
     */
    protected $languages = ['en', 'fr']; // en, fr
    protected $appLogo;
    protected $uploadModel;
    protected $uploadRepository;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('inside App\Http\Middleware\App@handle');

        if (auth()->check()) {
            app()->setLocale(setting('language', app()->getLocale()));
        } else {
            app()->setLocale($request->getPreferredLanguage($this->languages));
        }
        try {
            Carbon::setLocale(app()->getLocale());
            config(['app.timezone' => setting('timezone')]);
            config(['app.version' => setting('version', '12')]);
            $uploadModel = new \App\Models\Upload();
            $this->uploadRepository = new UploadRepository($uploadModel, app());
            $upload = $this->uploadRepository->findByField('uuid', setting('app_logo', ''))->first();
            $appLogo = asset('images/logo_default.png');
                
           if ($upload && $upload->hasMedia('app_logo')) {
                $appLogo = $upload->getFirstMediaUrl('app_logo');
            }
            view()->share('app_logo', $appLogo);
           
        } catch (\Exception $exception) { 
            Log::error('Error setting locale or app logo: ' . $exception->getMessage());
            // Fallback to default locale and logo if there's an error
            app()->setLocale('en');
            view()->share('app_logo', asset('images/logo_default.png'));
        }
        Log::info('inside App\Http\Middleware\App@handle:: '.app()->getLocale());
        
        return $next($request);
    }

}