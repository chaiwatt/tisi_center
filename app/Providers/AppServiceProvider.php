<?php

namespace App\Providers;
use App\Tag;
use Carbon\Carbon;
use App\BlogCategory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*view()->composer('layouts.partials.sidebar', function($view)
        {
            $categories = BlogCategory::all();
            $tags = Tag::all();
            $view->with(['tags' => $tags, 'categories' => $categories]);
        });*/

        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/menus.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menus.json')));
            view()->share('laravelAdminMenus', $menus);
        }

        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/menu-tis.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-tis.json')));
            view()->share('laravelMenuTis', $menus);
        }

	      $menus = [];
	      if (File::exists(base_path('resources/laravel-admin/menu-certify.json'))) {
		      $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-certify.json')));
		      view()->share('laravelMenuCertify', $menus);
	      }

        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/menu-bcertify.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-bcertify.json')));
            view()->share('laravelMenuBcertify', $menus);
        }

        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/menu-besurv.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-besurv.json')));
            view()->share('laravelMenuBesurv', $menus);
        }

        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/menu-esurv.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-esurv.json')));
            view()->share('laravelMenuEsurv', $menus);
        }

        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/menu-rsurv.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-rsurv.json')));
            view()->share('laravelMenuRsurv', $menus);
        }

        if (File::exists(base_path('resources/laravel-admin/menu-ssurv.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-ssurv.json')));
            view()->share('laravelMenuSsurv', $menus);
        }
        if (File::exists(base_path('resources/laravel-admin/menu-asurv.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-asurv.json')));
            view()->share('laravelMenuAsurv', $menus);
        }
        if (File::exists(base_path('resources/laravel-admin/menu-csurv.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-csurv.json')));
            view()->share('laravelMenuCsurv', $menus);
        }
        if (File::exists(base_path('resources/laravel-admin/menu-user.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-user.json')));
            view()->share('laravelMenuUser', $menus);
        }
        if (File::exists(base_path('resources/laravel-admin/menu-config.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-config.json')));
            view()->share('laravelMenuConfig', $menus);
        }
        if (File::exists(base_path('resources/laravel-admin/menu-bsection5.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-bsection5.json')));
            view()->share('laravelMenuBsection5', $menus);
        }
        if (File::exists(base_path('resources/laravel-admin/menu-cerreport.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-cerreport.json')));
            view()->share('laravelMenuCerreport', $menus);
        }
        if (File::exists(base_path('resources/laravel-admin/menu-report.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-report.json')));
            view()->share('laravelMenuReport', $menus);
        }
        if (File::exists(base_path('resources/laravel-admin/menu-section5.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-section5.json')));
            view()->share('laravelMenuSection5', $menus);
        }
        if (File::exists(base_path('resources/laravel-admin/menu-blog.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-blog.json')));
            view()->share('laravelMenuBlog', $menus);
        }
        if (File::exists(base_path('resources/laravel-admin/menu-iindustry.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-iindustry.json')));
            view()->share('laravelMenuiIndustry', $menus);
        }

        if (File::exists(base_path('resources/laravel-admin/menu-ws.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-ws.json')));
            view()->share('laravelMenuWS', $menus);
        }

        if (File::exists(base_path('resources/laravel-admin/menu-standards.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-standards.json')));
            view()->share('laravelMenuStandards', $menus);
        }

        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/menu-certificate.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-certificate.json')));
            view()->share('laravelMenuCertificate', $menus);
        }

        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/menu-sign-certify.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-sign-certify.json')));
            view()->share('laravelMenuSignCertify', $menus);
        }

        $menus = [];
        if (File::exists(base_path('resources/laravel-admin/menu-law.json'))) {
            $menus = json_decode(File::get(base_path('resources/laravel-admin/menu-law.json')));
            view()->share('laravelMenuLaw', $menus);
        }

        Blade::directive('spaces', function ($count) {
            return "<?php echo str_repeat('&nbsp;', $count); ?>";
        });

        Schema::defaultStringLength(191);

        Carbon::setLocale('th');

    }


    /**
     * Register any application services.
     * 
     * @return void
     */
    public function register()
    {
        //
    }
}
