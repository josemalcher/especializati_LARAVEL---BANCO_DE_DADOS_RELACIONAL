# CURSO LARAVEL - BANCO DE DADOS RELACIONAL

https://academy.especializati.com.br/curso/laravel-banco-de-dados-relacional



## <a name="indice">Índice</a>

1. [01 - Intro BDR](#parte1)     
2. [01 - Ferramentas Trabalhar com o Laravel 8.x](#parte2)     
3. [02 - Instalar o Laravel 8 com o Docker](#parte3)     
4. [03 - Configurações no Laravel 8 (Ambiente Docker)](#parte4)     
5. [04 - Versionar Projeto Laravel e Armazenar no GitHub](#parte5)     
6. [02 - Relacionamentos de Tabelas](#parte6)     
7. [05 - Laravel Relacionamento One to One](#parte7)     
8. [06 - Laravel Relacionamento One to Many (e inverso)](#parte8)     
9. [07 - Laravel Relacionamentos Many to Many](#parte9)     
10. [08 - Laravel Relacionamentos Many to Many - Pivô](#parte10)     
11. [03 - Polymorphic Relationships](#parte11)     
12. [09 - Laravel Relacionamento Polimórfico - One to One](#parte12)     
13. [10 Laravel Relacionamento Polimórfico One to Many](#parte13)     
14. [11 - Laravel Relacionamento Polimórfico - Many to Many](#parte14)     
---


## <a name="parte1">1 - 01 - Intro BDR</a>



[Voltar ao Índice](#indice)

---


## <a name="parte2">2 - 01 - Ferramentas Trabalhar com o Laravel 8.x</a>



[Voltar ao Índice](#indice)

---


## <a name="parte3">3 - 02 - Instalar o Laravel 8 com o Docker</a>



[Voltar ao Índice](#indice)

---


## <a name="parte4">4 - 03 - Configurações no Laravel 8 (Ambiente Docker)</a>



[Voltar ao Índice](#indice)

---


## <a name="parte5">5 - 04 - Versionar Projeto Laravel e Armazenar no GitHub</a>



[Voltar ao Índice](#indice)

---


## <a name="parte6">6 - 02 - Relacionamentos de Tabelas</a>



[Voltar ao Índice](#indice)

---


## <a name="parte7">7 - 05 - Laravel Relacionamento One to One</a>

```
$ php artisan make:model Preference -m

   INFO  Model [\dbrelacional1\app/Models/Preference.php] created successfully.

   INFO  Migration [\dbrelacional1\database\migrations/2022_12_21_174839_create_preferences_table.php] created successfully.


```

```php
    public function up()
    {
        Schema::create('preferences', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained('users');
            $table->boolean('notify_emails')->default(true);
            $table->boolean('notify')->default(true);
            $table->string('background_color');

            $table->timestamps();
        });
    }
```

```php
class Preference extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
```

```php
class User extends Authenticatable
{
    public function preference()
    {
        return $this->hasOne(Preference::class);
    }
```

```
$ php artisan migrate

   INFO  Running migrations.

  2022_12_21_174839_create_preferences_table ................................... 61ms DONE

```

```
$ php artisan tinker
Psy Shell v0.11.9 (PHP 8.0.16 — cli) by Justin Hileman
> \App\Models\User::factory()->count(10)->create();              
```

```php
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

```

[Voltar ao Índice](#indice)

---


## <a name="parte8">8 - 06 - Laravel Relacionamento One to Many (e inverso)</a>

```
$ php artisan make:model Course -m

   INFO  Model [\dbrelacional1\app/Models/Course.php] created successfully.

   INFO  Migration [\dbrelacional1\database\migrations/2022_12_27_183501_create_courses_table.php] created successfully.

$ php artisan make:model Module -m

   INFO  Model [\dbrelacional1\app/Models/Module.php] created successfully.

   INFO  Migration [\dbrelacional1\database\migrations/2022_12_27_183607_create_modules_table.php] created successfully.

$ php artisan make:model Lesson -m

   INFO  Model [\dbrelacional1\app/Models/Lesson.php] created successfully.

   INFO  Migration [\dbrelacional1\database\migrations/2022_12_27_183634_create_lessons_table.php] created successfully.

```

```php
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->boolean('available')->default(true);

            $table->timestamps();
        });
    }
```

```php
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')->constrained('courses');
            $table->string('name');

            $table->timestamps();
        });
    }
```

```php
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();

            $table->foreignId('module_id')->constrained('modules');
            $table->string('name');
            $table->string('video');

            $table->timestamps();
        });
    }
```

```
$ php artisan migrate

   INFO  Running migrations.

  2022_12_27_183501_create_courses_table ........ 24ms DONE
  2022_12_27_183607_create_modules_table ........ 49ms DONE
  2022_12_27_183634_create_lessons_table ........ 43ms DONE

```

```php
class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'available'];
    public function modules()
    {
        return $this->hasMany(Module::class);
    }
```

```php
class Lesson extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'video'];
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
```

```php
class Module extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
```

```php
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
```


[Voltar ao Índice](#indice)

---


## <a name="parte9">9 - 07 - Laravel Relacionamentos Many to Many</a>

```
$ php artisan make:model Permission -m

```

```php
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('permission_user', function (Blueprint $table) {
            $table->id();

            $table->foreignId('permission_id')->constrained('permissions');
            $table->foreignId('user_id')->constrained('users');
            $table->boolean('active')->default(true );
            $table->timestamps();
        });
    }
```

```
$ php artisan migrate

   INFO  Running migrations.

  2022_12_29_135712_create_permissions_table ........ 176ms DONE

```

```php
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
```


[Voltar ao Índice](#indice)

---


## <a name="parte10">10 - 08 - Laravel Relacionamentos Many to Many - Pivô</a>

```php
class User extends Authenticatable
{
    public function permissions()
    {
        // return $this->belongsToMany(Permission::class);
        return $this->belongsToMany(Permission::class)
            ->withPivot(['active', 'created_at']);
    }
```

```php
Route::get('/many-to-many-pivot',
    function () {
        $user = User::with('permissions')->find(1);

//        $user->permissions()->attach([
//            1 => ['active' => false],
//            3 => ['active' => false]
//        ]);

        echo "<b>{$user->name}</b><br>";
        foreach ($user->permissions as $permission) {
            echo "{$permission->name} - {$permission->pivot->active}<br>";
        }

        $user->refresh();
        // dd($user->permissions);
    }
);
```

[Voltar ao Índice](#indice)

---


## <a name="parte11">11 - 03 - Polymorphic Relationships</a>



[Voltar ao Índice](#indice)

---


## <a name="parte12">12 - 09 - Laravel Relacionamento Polimórfico - One to One</a>



[Voltar ao Índice](#indice)

---


## <a name="parte13">13 - 10 Laravel Relacionamento Polimórfico One to Many</a>



[Voltar ao Índice](#indice)

---


## <a name="parte14">14 - 11 - Laravel Relacionamento Polimórfico - Many to Many</a>



[Voltar ao Índice](#indice)

---

