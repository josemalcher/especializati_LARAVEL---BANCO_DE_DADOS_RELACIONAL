<?php

use App\Models\{Course, Permission, User, Preference};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/one-to-one', function () {

    // $user = User::first();
    // $user = User::find(2);
    $user = User::with('preference')->find(2); // melhor forma


    // dd($user->preference);
    // dd($user->preference()->get());
    // dd($user->preference()->first());

    $data = [
        'background_color' => '#000'
    ];

    if ($user->preference) {
        $user->preference()->update($data);
    }else{
        // $user->preference()->create($data);
        $preference = new Preference($data);
        $user->preference()->save($preference);

    }

    $user->refresh();

    var_dump($user->preference);

    $user->preference->delete();
    $user->refresh();

    dd($user->preference);

});

Route::get('/one-to-many',
    function () {
        // $course = Course::create(['name' => 'Curso de Laravel']);
        // $course = Course::first();
        $course = Course::with('modules.lessons')->first();

        // dd($course);

        echo $course->name;
        echo "<br>";
        foreach ($course->modules as $module) {
            echo "Módulo: {$module->name} <br>";
            foreach ($module->lessons as $lesson) {
                echo "AULA: {$lesson->name} <br>";
            }
        }

        $data = [
            'name' => 'Módulo x2'
        ];
        // $course->modules()->create($data);

        // $course->modules()->get();
        // $modules = $course->modules;
        // dd($modules);
    });

Route::get('/many-to-many',
    function () {
        // dd(Permission::create(['name'=> 'menu_04']));
        $user = User::with('permissions')->find(1);

        // $permission = Permission::find(1);
        //$user->permissions()->save($permission);
        /*
        $user->permissions()->saveMany([
            Permission::find(1),
            Permission::find(3),
            Permission::find(6)
        ]);
        */
        // $user->permissions()->sync([4]);

        // $user->permissions()->attach([3,6]);

        $user->permissions()->detach([3,6]);

        $user->refresh();

        dd($user->permissions);

    });
