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

   INFO  Model [\especializati_LARAVEL---BANCO_DE_DADOS_RELACIONAL\dbrelacional1\app/Models/Preference.php] created successfully.

   INFO  Migration [\especializati_LARAVEL---BANCO_DE_DADOS_RELACIONAL\dbrelacional1\database\migrations/2022_12_21_174839_create_preferences_table.php] created successfully.


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



[Voltar ao Índice](#indice)

---


## <a name="parte9">9 - 07 - Laravel Relacionamentos Many to Many</a>



[Voltar ao Índice](#indice)

---


## <a name="parte10">10 - 08 - Laravel Relacionamentos Many to Many - Pivô</a>



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

